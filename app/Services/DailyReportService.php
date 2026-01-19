<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\HR\Department;
use App\Models\DailyProduction;
use App\Models\Product\Product;
use App\Models\Order\OrderDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Product\ProductStock;
use App\Models\Order\OrderReturnDetail;
use App\Models\Product\CustomerProductDamage;
use App\Models\Product\ProductDamage;
 
class DailyReportService
{
    public $start_date;
    public $end_date;
    public $department_id;
    public $product_id;
    public $last_stock_dates;

    public function __construct($start_date = null, $end_date = null, $department_id = null, $product_id = null)
    {
        $this->start_date = $start_date ?? Carbon::now()->format('Y-m-d');
        $this->end_date = $end_date ?? Carbon::now()->subDays(1)->format('Y-m-d');
        $this->department_id = $department_id;
        $this->product_id = $product_id;
        $this->last_stock_dates = $this->getLastStockDates($this->start_date, $this->end_date);
    }

    public function getReports()
    {
        $products = $this->getProducts($this->department_id, $this->product_id);
        $productIds = $products->pluck('id')->toArray();
        $dates = $this->getDateRange();

        $report = [];
        $totals = [];

        // Initialize report array for all dates
        foreach ($dates as $date) {
            $report[$date] = [];
            foreach ($products as $product) {
                $report[$date][$product->id] = [
                    'id' => $product->id,
                    'department_id' => $product->department_id,
                    'name' => $product->name,
                    'previous_stock' => 0,
                    'production' => 0,
                    'returns' => 0,
                    'sales' => 0,
                    'damaged' => 0,
                    'product_damaged' => 0,
                    'current_stock' => 0,
                ];
            }
        }

        // Process each stock period
        foreach ($this->last_stock_dates as $index => $last_stock_date) {
            // Calculate from last stock date to end date (including hidden dates)
            $current_last_stocks_dates = $this->getDateRange($last_stock_date, $this->end_date);

            $productionData = $this->getBatchProductionData($current_last_stocks_dates, $productIds);
            $salesData = $this->getBatchSalesData($current_last_stocks_dates, $productIds);
            $returnsData = $this->getBatchReturnsData($current_last_stocks_dates, $productIds);
            $damagedData = $this->getBatchDamagedData($current_last_stocks_dates, $productIds);
            $productdamagedData = $this->getBatchProductDamagedData($current_last_stocks_dates, $productIds);
            $initialStockData = $this->getInitialStockData($productIds);

            $previousStocks = $initialStockData[$last_stock_date];

            foreach ($current_last_stocks_dates as $date) {
                $currentStocks = [];

                foreach ($products as $product) {
                    $productId = $product->id;

                    $production = $productionData[$date][$productId] ?? 0;
                    $returns = $returnsData[$date][$productId] ?? 0;
                    $sales = $salesData[$date][$productId] ?? 0;
                    $damaged = $damagedData[$date][$productId] ?? 0;
                    $productdamaged = $productdamagedData[$date][$productId] ?? 0;
                    $previousStock = $previousStocks[$productId] ?? 0;

                    $currentStock = $previousStock + $production + $returns - $sales - $damaged - $productdamaged;

                    // Only save to report if date is within our display range
                    if (in_array($date, $dates)) {
                        $report[$date][$productId] = [
                            'id' => $productId,
                            'department_id' => $product->department_id,
                            'name' => $product->name,
                            'previous_stock' => $previousStock,
                            'production' => $production,
                            'returns' => $returns,
                            'sales' => $sales,
                            'damaged' => $damaged,
                            'product_damaged' => $productdamaged,
                            'current_stock' => $currentStock,
                        ];
                    }

                    // Always update current stocks for next iteration
                    $currentStocks[$productId] = $currentStock;
                }

                $previousStocks = $currentStocks;
                
                // Only calculate totals for display dates
                if (in_array($date, $dates)) {
                    $totals[$date] = $this->calculateDailyTotals($report[$date]);
                }
            }
        }

        // Group by product and show department name
        $productLists = $this->groupByProduct($products);

        return [
            'report_data' => $report,
            'totals' => $totals,
            'dates' => $dates,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,
            'departmentProducts' => $productLists
        ];
    }

    public function getCategory()
    {
        return Department::with(['products:id,name,department_id'])
            ->get(['id', 'name']);
    }

    private function groupByProduct($products)
    {
        $groupByProduct = [];

        foreach ($products as $product) {
            $groupByProduct[$product->department_id]['department_name'] = $product->department->name;
            $groupByProduct[$product->department_id]['products'][] = $product;
        }

        return $groupByProduct;
    }

    private function getLastStockDates($start_date = null, $end_date = null)
    {
        $start_date = $start_date ?? $this->start_date;
        $end_date   = $end_date ?? $this->end_date;

        // Check if stock report exists on start_date
        $stockOnStartDate = ProductStock::where('date', $start_date)
            ->exists();

        // 1. Get last stock date before start_date (only if no stock on start_date)
        $beforeStart = collect();
        if (!$stockOnStartDate) {
            $beforeStart = ProductStock::where('date', '<', $start_date)
                ->orderBy('date', 'desc')
                ->limit(1)
                ->pluck('date');
        }

        // 2. Get stock dates from start_date to end_date
        $betweenDates = ProductStock::whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'desc')
            ->pluck('date');

        // 3. Merge and return (duplicate remove)
        $dates = $betweenDates->merge($beforeStart)->unique()->sortDesc();

        return $dates->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->values();
    }

    private function getDateRange($start_date = null, $end_date = null)
    {
        $startDate = ($start_date) ? Carbon::parse($start_date) : Carbon::parse($this->start_date);
        $endDate = ($end_date) ? Carbon::parse($end_date) : Carbon::parse($this->end_date);

        if ($startDate->gt($endDate)) {
            list($startDate, $endDate) = [$endDate, $startDate];
        }

        $dates = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        return $dates;
    }

    private function getInitialStockData($productIds)
    {
        $initialStocks = [];

        foreach ($this->last_stock_dates as $date) {
            // Get the latest stock for each product up to and including this date
            $latestStocks = ProductStock::whereIn('product_id', $productIds)
                ->whereDate('date', '<=', $date)
                ->select('product_id', DB::raw('MAX(date) as max_date'))
                ->groupBy('product_id')
                ->get()
                ->mapWithKeys(function ($stock) {
                    $physicalStock = ProductStock::where('product_id', $stock->product_id)
                        ->whereDate('date', $stock->max_date)
                        ->value('physical_stock');

                    return [$stock->product_id => $physicalStock ?? 0];
                });

            $initialStocks[$date] = [];
            foreach ($productIds as $productId) {
                $initialStocks[$date][$productId] = $latestStocks[$productId] ?? 0;
            }
        }

        return $initialStocks;
    }

    private function getBatchProductionData($dates, $productIds)
    {
        $data = DailyProduction::whereIn('date', $dates)
            ->whereIn('product_id', $productIds)
            ->select([
                'date',
                'product_id',
                DB::raw('SUM(qty) as total_qty')
            ])
            ->groupBy('date', 'product_id')
            ->get()
            ->groupBy('date')
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('total_qty', 'product_id')->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $productIds);
    }

    private function getBatchSalesData($dates, $productIds)
    {
        $data = OrderDetail::whereHas('order', function($q) use ($dates) {
                $q->whereIn(DB::raw('DATE(date)'), $dates);
            })
            ->whereIn('order_details.product_id', $productIds)
            ->select([
                DB::raw('DATE(orders.date) as order_date'),
                'order_details.product_id',
                DB::raw('SUM(order_details.qty) as total_qty')
            ])
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->groupBy('order_date', 'order_details.product_id')
            ->get()
            ->groupBy('order_date')
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('total_qty', 'product_id')->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $productIds);
    }

    private function getBatchReturnsData($dates, $productIds)
    {
        $data = OrderReturnDetail::whereHas('orderreturn', function($q) use ($dates) {
                $q->whereIn(DB::raw('DATE(date)'), $dates);
            })
            ->whereIn('order_return_details.product_id', $productIds)
            ->select([
                DB::raw('DATE(order_returns.date) as return_date'),
                'order_return_details.product_id',
                DB::raw('SUM(order_return_details.qty) as total_qty')
            ])
            ->join('order_returns', 'order_returns.id', '=', 'order_return_details.order_return_id')
            ->groupBy('return_date', 'order_return_details.product_id')
            ->get()
            ->groupBy('return_date')
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('total_qty', 'product_id')->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $productIds);
    }
    
     

    private function getBatchDamagedData($dates, $productIds)
    {
        $data = CustomerProductDamage::whereIn(DB::raw('DATE(date)'), $dates)
            ->join('customer_product_damage_details', 'customer_product_damages.id', '=', 'customer_product_damage_details.customer_product_damage_id')
            ->whereIn('customer_product_damage_details.product_id', $productIds)
            ->select([
                DB::raw('DATE(customer_product_damages.date) as damage_date'),
                'customer_product_damage_details.product_id',
                DB::raw('SUM(customer_product_damage_details.qty) as total_qty')
            ])
            ->groupBy('damage_date', 'customer_product_damage_details.product_id')
            ->get()
            ->groupBy('damage_date')
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('total_qty', 'product_id')->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $productIds);
    }
    
    
    private function getBatchProductDamagedData($dates, $productIds)
    {
        $data = ProductDamage::whereIn('date',$dates)
            ->whereIn('product_id', $productIds)
            ->select([
                'date as damage_date',
                'product_id',
                'qty as total_qty'
            ])
            ->groupBy('damage_date', 'product_id')
            ->get()
            ->groupBy('damage_date')
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('total_qty', 'product_id')->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $productIds);
    }
    
    
     
    

    private function ensureAllDates($dates, $data, $productIds)
    {
        $result = [];
        $emptyProductData = array_fill_keys($productIds, 0);

        foreach ($dates as $date) {
            $dateData = $emptyProductData;

            if (isset($data[$date])) {
                foreach ($data[$date] as $productId => $value) {
                    if (array_key_exists($productId, $dateData)) {
                        $dateData[$productId] = $value;
                    }
                }
            }

            $result[$date] = $dateData;
        }

        return $result;
    }

    private function getProducts($department_id = null, $product_id = null)
    {
        return Product::where('status', "Active")
            ->when($department_id, function ($query) use ($department_id) {
                $query->where('department_id', $department_id);
            })
            ->when($product_id, function ($query) use ($product_id) {
                $query->where('id', $product_id);
            })
            ->select(['id', 'name', 'department_id'])
            ->orderBy('name')
            ->get();
    }

    private function calculateDailyTotals($dailyReport)
    {
        return [
            'previous_stock' => array_sum(array_column($dailyReport, 'previous_stock')),
            'production' => array_sum(array_column($dailyReport, 'production')),
            'returns' => array_sum(array_column($dailyReport, 'returns')),
            'sales' => array_sum(array_column($dailyReport, 'sales')),
            'damaged' => array_sum(array_column($dailyReport, 'damaged')),
            'current_stock' => array_sum(array_column($dailyReport, 'current_stock'))
        ];
    }
}