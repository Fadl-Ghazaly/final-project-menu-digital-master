<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'day'); // day, week, month
        
        $query = Order::query();
        
        $now = Carbon::now();
        $startDate = $now->copy()->startOfDay();
        $endDate = $now->copy()->endOfDay();

        $labels = [];
        $data = [];
        $divisor = 1;

        if ($period == 'day') {
            $startDate = $now->copy()->startOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
            
            $salesData = Order::select(DB::raw('HOUR(created_at) as label'), DB::raw('SUM(total_price) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('label')
                ->get()
                ->keyBy('label');
                
            for ($i = 0; $i <= 23; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $data[] = isset($salesData[$i]) ? (float)$salesData[$i]->total : 0;
            }
            $divisor = 1;

        } elseif ($period == 'week') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
            
            $salesData = Order::select(DB::raw('DATE(created_at) as label'), DB::raw('SUM(total_price) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('label')
                ->get()
                ->keyBy('label');
                
            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $labels[] = $date->translatedFormat('D'); 
                $dateStr = $date->toDateString();
                $data[] = isset($salesData[$dateStr]) ? (float)$salesData[$dateStr]->total : 0;
            }
            $divisor = 7;

        } elseif ($period == 'custom') {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);

            $salesData = Order::select(DB::raw('DATE(created_at) as label'), DB::raw('SUM(total_price) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('label')
                ->get()
                ->keyBy('label');

            $diffInDays = $startDate->diffInDays($endDate);
            for ($i = 0; $i <= $diffInDays; $i++) {
                $date = $startDate->copy()->addDays($i);
                $labels[] = $date->format('d/m');
                $dateStr = $date->toDateString();
                $data[] = isset($salesData[$dateStr]) ? (float)$salesData[$dateStr]->total : 0;
            }
            $divisor = $diffInDays + 1;

        } else { // month
            $startDate = $now->copy()->startOfMonth();
            $query->whereBetween('created_at', [$startDate, $endDate]);
            
            $salesData = Order::select(DB::raw('DAY(created_at) as label'), DB::raw('SUM(total_price) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('label')
                ->get()
                ->keyBy('label');
                
            $daysInMonth = $now->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = $i;
                $data[] = isset($salesData[$i]) ? (float)$salesData[$i]->total : 0;
            }
            $divisor = $now->day; 
        }

        $totalRevenue = $query->sum('total_price') ?? 0;
        $totalOrders = $query->count();
        $averagePerDay = $divisor > 0 ? $totalRevenue / $divisor : 0;

        $topMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->name,
                    'qty' => $item->total_qty,
                    'revenue' => $item->total_revenue
                ];
            });

        $topMenuName = count($topMenus) > 0 ? $topMenus[0]['name'] : '-';

        $reportData = [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averagePerDay' => $averagePerDay,
            'topMenuName' => $topMenuName,
            'chartLabels' => $labels,
            'chartData' => $data,
            'topMenus' => $topMenus
        ];

        if ($request->expectsJson()) {
            return response()->json($reportData);
        }

        return view('admin.reports.index', compact('reportData', 'period'));
    }

    public function exportCsv(Request $request)
    {
        $period = $request->get('period', 'day');
        $now = Carbon::now();
        $startDate = $now->copy();
        $endDate = $now->copy()->endOfDay();

        if ($period == 'day') $startDate = $now->copy()->startOfDay();
        elseif ($period == 'week') $startDate = $now->copy()->subDays(6)->startOfDay();
        elseif ($period == 'custom') {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
        }
        else $startDate = $now->copy()->startOfMonth();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=laporan-penjualan-$period.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Pesanan', 'Tanggal', 'Nama Pelanggan', 'Status', 'Total Pendapatan'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->name ?? 'Pelanggan',
                    $order->status,
                    $order->total_price
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $period = $request->get('period', 'day');
        $now = Carbon::now();
        $startDate = $now->copy();
        $endDate = $now->copy()->endOfDay();

        if ($period == 'day') $startDate = $now->copy()->startOfDay();
        elseif ($period == 'week') $startDate = $now->copy()->subDays(6)->startOfDay();
        elseif ($period == 'custom') {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
        }
        else $startDate = $now->copy()->startOfMonth();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->latest()->get();
        $totalRevenue = $orders->sum('total_price');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', compact('orders', 'totalRevenue', 'period', 'startDate', 'endDate'));
        return $pdf->download("laporan-penjualan-$period.pdf");
    }
}
