<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // Add role check if needed: $this->middleware('role:admin,seller');
    }

    /**
     * Show the sales report dashboard.
     */
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $ordersQuery = Order::with('items.product', 'user')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('created_at', 'desc');

        // KPI aggregates
        $allOrders      = (clone $ordersQuery)->get();
        $totalRevenue   = $allOrders->sum('total_amount');
        $totalOrders    = $allOrders->count();
        $completedOrders= $allOrders->where('status', 'delivered')->count();
        $cancelledOrders= Order::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                               ->where('status', 'cancelled')->count();
        $avgOrderValue  = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;
        $totalItemsSold = $allOrders->sum(fn($o) => $o->items->sum('quantity'));
        $uniqueProductsSold = $allOrders->flatMap(fn($o) => $o->items->pluck('product_id'))->unique()->count();

        // Compare with previous period
        $periodDays    = $startDate->diffInDays($endDate) + 1;
        $prevStart     = $startDate->copy()->subDays($periodDays);
        $prevEnd       = $startDate->copy()->subDay();
        $prevRevenue   = Order::whereBetween('created_at', [$prevStart, $prevEnd->endOfDay()])
                              ->whereNotIn('status', ['cancelled'])
                              ->sum('total_amount');
        $revenueChange = $prevRevenue > 0
            ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
            : null;

        // Top products
        $topProducts = Product::withSum(['orderItems' => function ($q) use ($startDate, $endDate) {
            $q->whereHas('order', function ($oq) use ($startDate, $endDate) {
                $oq->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                   ->whereNotIn('status', ['cancelled']);
            });
        }], 'quantity as total_sold')
        ->withSum(['orderItems' => function ($q) use ($startDate, $endDate) {
            $q->whereHas('order', function ($oq) use ($startDate, $endDate) {
                $oq->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                   ->whereNotIn('status', ['cancelled']);
            });
        }], 'subtotal as total_revenue')
        ->having('total_sold', '>', 0)
        ->orderByDesc('total_sold')
        ->take(10)
        ->get();

        // Paginated orders for the table
        $orders = Order::with('items', 'user')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reports.index', compact(
            'orders', 'topProducts',
            'totalRevenue', 'totalOrders', 'completedOrders', 'cancelledOrders',
            'avgOrderValue', 'totalItemsSold', 'uniqueProductsSold',
            'revenueChange', 'startDate', 'endDate'
        ));
    }

    /**
     * Export to Excel (XLSX) or PDF.
     */
    public function export(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $format   = $request->get('format', 'excel');
        $filename = 'laporan-penjualan-casaforma-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd');

        $orders = Order::with('items.product', 'user')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf', compact('orders', 'startDate', 'endDate'))
                      ->setPaper('a4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }

        // Default: Excel
        return Excel::download(new SalesReportExport($orders, $startDate, $endDate), $filename . '.xlsx');
    }

    // ── Private helpers ──────────────────────────────────────────────────────────

    private function resolveDateRange(Request $request): array
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            return [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ];
        }

        return match ($request->get('period', '30d')) {
            '7d'         => [now()->subDays(6)->startOfDay(), now()],
            'this_month' => [now()->startOfMonth(), now()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year'  => [now()->startOfYear(), now()],
            default      => [now()->subDays(29)->startOfDay(), now()],  // 30d
        };
    }
}
