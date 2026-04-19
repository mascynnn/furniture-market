<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(protected RajaOngkirService $rajaOngkir) {}

    /**
     * Show checkout form.
     */
    public function index()
    {
        $cart = CartController::getCartWithData();

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $provinces = $this->rajaOngkir->getProvinces();

        return view('checkout.index', [
            'cartItems'   => $cart['items'],
            'subtotal'    => $cart['subtotal'],
            'totalWeight' => $cart['weight'],
            'provinces'   => $provinces,
        ]);
    }

    /**
     * Process checkout: validate, create order, clear cart.
     */
    public function process(Request $request)
    {
        $request->validate([
            'recipient_name'  => 'required|string|max:120',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:500',
            'province_id'     => 'required',
            'city_id'         => 'required',
            'postal_code'     => 'required|string|max:10',
            'shipping_service'=> 'required|string',
            'shipping_cost'   => 'nullable|numeric|min:0',
        ]);

        $cart = CartController::getCartWithData();

        if (empty($cart['items'])) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Parse shipping service info
        [$courierCode, $serviceType] = explode('_', $request->shipping_service, 2);
        $shippingCost = (int) $request->shipping_cost;

        // Get province and city names
        $provinces  = $this->rajaOngkir->getProvinces();
        $provinceName = collect($provinces)->firstWhere('province_id', $request->province_id)['province'] ?? '';
        $cities       = $this->rajaOngkir->getCities($request->province_id);
        $cityData     = collect($cities)->firstWhere('city_id', $request->city_id);
        $cityName     = isset($cityData) ? $cityData['type'] . ' ' . $cityData['city_name'] : '';

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'    => 'CF-' . strtoupper(Str::random(8)),
                'user_id'         => Auth::id(),
                'status'          => 'pending',
                'recipient_name'  => $request->recipient_name,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'province'        => $provinceName,
                'province_id'     => $request->province_id,
                'city'            => $cityName,
                'city_id'         => $request->city_id,
                'postal_code'     => $request->postal_code,
                'notes'           => $request->notes,
                'courier'         => $courierCode,
                'shipping_service'=> strtoupper($courierCode) . ' ' . $serviceType,
                'shipping_cost'   => $shippingCost,
                'subtotal'        => $cart['subtotal'],
                'total_amount'    => $cart['subtotal'] + $shippingCost,
                'weight'          => $cart['weight'],
            ]);

            foreach ($cart['items'] as $productId => $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $productId,
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);

                // Decrement stock
                Product::where('id', $productId)->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');

            DB::commit();

            return redirect()->route('checkout.confirm', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show order confirmation page.
     */
    public function confirm($orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);

        // Only the owner can view this
        abort_unless($order->user_id === Auth::id(), 403);

        return view('checkout.confirm', compact('order'));
    }
}
