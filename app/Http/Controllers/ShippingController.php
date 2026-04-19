<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    public function __construct(protected RajaOngkirService $rajaOngkir) {}

    /**
     * Show tracking page for an order.
     */
    public function track($orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);

        // Only owner or seller/admin can view
        if (Auth::id() !== $order->user_id && !in_array(Auth::user()->role, ['seller', 'admin'])) {
            abort(403);
        }

        $trackingHistory = [];

        // Fetch live tracking from RajaOngkir if resi exists
        if ($order->tracking_number && $order->courier) {
            try {
                $result = $this->rajaOngkir->trackShipment(
                    $order->courier,
                    $order->tracking_number
                );
                if ($result && isset($result['manifest'])) {
                    $trackingHistory = array_map(function ($event) {
                        return [
                            'date'        => $event['manifest_date'] ?? '',
                            'time'        => $event['manifest_time'] ?? '',
                            'status'      => $event['manifest_code'] ?? '',
                            'description' => $event['manifest_description'] ?? '',
                            'location'    => $event['city_name'] ?? null,
                        ];
                    }, $result['manifest']);
                }
            } catch (\Exception $e) {
                // Tracking data unavailable — show status steps instead
                $trackingHistory = [];
            }
        }

        return view('shipping.track', compact('order', 'trackingHistory'));
    }

    /**
     * Update tracking/resi number (seller/admin only).
     */
    public function updateResi(Request $request, $orderId)
    {
        abort_unless(in_array(Auth::user()->role, ['seller', 'admin']), 403);

        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'courier'         => 'required|string|max:30',
        ]);

        $order = Order::findOrFail($orderId);
        $order->update([
            'tracking_number' => $request->tracking_number,
            'courier'         => $request->courier,
            'status'          => 'shipped',
        ]);

        return redirect()->back()->with('success', 'Nomor resi berhasil disimpan.');
    }

    /**
     * API: Get cities by province (for dynamic dropdown in checkout).
     */
    public function apiCities($provinceId)
    {
        $cities = $this->rajaOngkir->getCities($provinceId);
        return response()->json($cities);
    }

    /**
     * API: Get shipping options (cost calculation via RajaOngkir).
     */
    public function apiShippingOptions(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'weight'  => 'required|integer|min:1',
        ]);

        // Origin city configured in config/rajaongkir.php
        $originCity = config('rajaongkir.origin_city_id');
        $couriers   = config('rajaongkir.couriers', ['jne', 'jnt', 'pos']);

        $options = [];
        foreach ($couriers as $courier) {
            $costs = $this->rajaOngkir->calculateCost(
                $originCity,
                $request->city_id,
                $request->weight,
                $courier
            );
            foreach ($costs as $service) {
                $options[] = [
                    'code'    => $courier,
                    'service' => $service['service'],
                    'etd'     => $service['cost'][0]['etd'] ?? '?',
                    'cost'    => $service['cost'][0]['value'] ?? 0,
                ];
            }
        }

        // Sort by cost
        usort($options, fn($a, $b) => $a['cost'] - $b['cost']);

        return response()->json($options);
    }
}
