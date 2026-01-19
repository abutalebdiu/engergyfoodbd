<?php

namespace App\Services;

use App\Models\DailyProduction;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Support\Carbon;
use App\Models\ItemOrderDetail;
use App\Models\ItemStock;
use App\Models\MakeProduction;
use Illuminate\Support\Facades\DB;

class DailyItemReportService
{
    public $start_date;
    public $end_date;
    public $item_category;
    public $item_id;
    public $last_stock_dates;


    public function __construct($start_date = null, $end_date = null, $item_category = null, $item_id = null)
    {
        $this->start_date = $start_date ?? Carbon::now()->format('Y-m-d');
        $this->end_date = $end_date ?? Carbon::now()->subDays(3)->format('Y-m-d');
        $this->item_category = $item_category;
        $this->item_id = $item_id;
        $this->last_stock_dates = $this->getLastStockDates($this->start_date, $this->end_date);
    }

    public function getReports()
    {
        $items = $this->getItems($this->item_category, $this->item_id);
        $itemsIds = $items->pluck('id')->toArray();
        $dates = $this->getDateRange();

        $report = [];
        $totals = [];

        // Initialize report array for all dates
        foreach ($dates as $date) {
            $report[$date] = [];
            foreach ($items as $item) {
                $report[$date][$item->id] = [
                    'id' => $item->id,
                    'item_category_id' => $item->item_category_id,
                    'name' => $item->name,
                    'previous_stock' => 0,
                    'purchase' => 0,
                    'used' => 0,
                    'current_stock' => 0,
                ];
            }
        }

        // Process each stock period
        foreach ($this->last_stock_dates as $last_stock_date) {
            // Calculate from last stock date to end date (including hidden dates)
            $current_last_stocks_dates = $this->getDateRange($last_stock_date, $this->end_date);

            $itemOrderData = $this->getBatchItemOrderData($current_last_stocks_dates, $itemsIds);
            $usedItem = $this->getBatchUsedData($current_last_stocks_dates, $itemsIds);
            $usedItemPP = $this->getBatchUesdPackageItem($current_last_stocks_dates, $itemsIds, 'pp_item_id');
            $usedItemBox = $this->getBatchUesdPackageItem($current_last_stocks_dates, $itemsIds, 'box_item_id');
            $usedItemST = $this->getBatchUesdPackageItem($current_last_stocks_dates, $itemsIds, 'striker_item_id');

            $initialStockData = $this->getInitialStockData($itemsIds);
            $previousStocks = $initialStockData[$last_stock_date];

            foreach ($current_last_stocks_dates as $date) {
                $currentStocks = [];

                foreach ($items as $item) {
                    $itemId = $item->id;

                    $purchase = $itemOrderData[$date][$itemId] ?? 0;
                    $used = $usedItem[$date][$itemId] ?? 0;
                    $previousStock = $previousStocks[$itemId] ?? 0;

                    $qtyUsedItemPP = $usedItemPP[$date][$itemId] ?? 0;
                    $qtyUsedItemBox = $usedItemBox[$date][$itemId] ?? 0;
                    $qtyUsedItemST = $usedItemST[$date][$itemId] ?? 0;

                    $total_used = $used + $qtyUsedItemPP + $qtyUsedItemST + $qtyUsedItemBox;

                    $currentStock = $previousStock + $purchase - $total_used;

                    // Only save to report if date is within our display range
                    if (in_array($date, $dates)) {
                        $report[$date][$itemId] = [
                            'id' => $itemId,
                            'item_category_id' => $item->item_category_id,
                            'name' => $item->name,
                            'previous_stock' => $previousStock,
                            'purchase' => $purchase,
                            'used' => $total_used,
                            'current_stock' => $currentStock,
                        ];
                    }

                    // Always update current stocks for next iteration
                    $currentStocks[$itemId] = $currentStock;
                }

                $previousStocks = $currentStocks;
                
                // Only calculate totals for display dates
                if (in_array($date, $dates)) {
                    $totals[$date] = $this->calculateDailyTotals($report[$date]);
                }
            }
        }

        // Group by item and show category name
        $itemLists = $this->groupByItem($items);

        return [
            'report_data' => $report,
            'totals' => $totals,
            'dates' => $dates,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,
            'categoryItems' => $itemLists
        ];
    }

    private function groupByItem($items)
    {
        $groupByItem = [];

        foreach ($items as $item) {
            $groupByItem[$item->item_category_id]['category_name'] = $item?->category?->name ?? 'Uncategorized';
            $groupByItem[$item->item_category_id]['items'][] = $item;
        }

        return $groupByItem;
    }

    public function getItemCategory()
    {
        return ItemCategory::with(['items:id,name,item_category_id'])->get(['id','name']);
    }

    private function getLastStockDates($start_date = null, $end_date = null)
    {
        $start_date = $start_date ?? $this->start_date;
        $end_date   = $end_date ?? $this->end_date;

        // Check if stock report exists on start_date
        $stockOnStartDate = ItemStock::where('date', $start_date)
            ->exists();

        // 1. Get last stock date before start_date (only if no stock on start_date)
        $beforeStart = collect();
        if (!$stockOnStartDate) {
            $beforeStart = ItemStock::where('date', '<', $start_date)
                ->orderBy('date', 'desc')
                ->limit(1)
                ->pluck('date');
        }

        // 2. Get stock dates from start_date to end_date
        $betweenDates = ItemStock::whereBetween('date', [$start_date, $end_date])
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

    private function getInitialStockData($itemIds)
    {
        $initialStocks = [];

        foreach ($this->last_stock_dates as $date) {
            // Get the latest stock for each item up to and including this date
            $latestStocks = ItemStock::whereIn('item_id', $itemIds)
                ->whereDate('date', '<=', $date)
                ->select('item_id', DB::raw('MAX(date) as max_date'))
                ->groupBy('item_id')
                ->get()
                ->mapWithKeys(function ($stock) {
                    $physicalStock = ItemStock::where('item_id', $stock->item_id)
                        ->whereDate('date', $stock->max_date)
                        ->value('physical_stock');

                    return [$stock->item_id => $physicalStock ?? 0];
                });

            $initialStocks[$date] = [];
            foreach ($itemIds as $itemId) {
                $initialStocks[$date][$itemId] = $latestStocks[$itemId] ?? 0;
            }
        }

        return $initialStocks;
    }

    private function getBatchItemOrderData($dates, $itemsIds)
    {
        $data = ItemOrderDetail::whereIn('item_id', $itemsIds)
            ->select([
                'item_order_id',
                'item_id',
                'qty'
            ])
            ->with(['itemOrderList' => function($q) use ($dates) {
                $q->whereIn(DB::raw('DATE(date)'), $dates)
                  ->select(['id', 'date']);
            }])
            ->get()
            ->filter(function($detail) {
                return $detail->itemOrderList !== null;
            })
            ->groupBy(function ($detail) {
                return $detail->itemOrderList->date;
            })
            ->map(function ($dateGroup) {
                return $dateGroup->groupBy('item_id')->map(function ($itemGroup) {
                    return $itemGroup->sum('qty');
                })->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $itemsIds);
    }

    private function getBatchUsedData($dates, $itemIds)
    {
        $data = MakeProduction::whereIn('item_id', $itemIds)
            ->whereIn(DB::raw('DATE(`date`)'), $dates)
            ->select([
                DB::raw('DATE(`date`) as production_date'),
                'item_id',
                DB::raw('SUM(qty) as total_qty')
            ])
            ->groupBy('production_date', 'item_id')
            ->get()
            ->groupBy('production_date')
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('total_qty', 'item_id')->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $itemIds);
    }

    private function getBatchUesdPackageItem($dates, $itemIds, $field)
    {
        $data = DailyProduction::whereIn($field, $itemIds)
            ->whereIn(DB::raw('DATE(`date`)'), $dates)
            ->select([
                DB::raw('DATE(`date`) as production_date'),
                $field,
                DB::raw('SUM(qty) as total_qty')
            ])
            ->groupBy('production_date', $field)
            ->get()
            ->groupBy('production_date')
            ->map(function ($dateGroup) use ($field) {
                return $dateGroup->pluck('total_qty', $field)->toArray();
            })
            ->toArray();

        return $this->ensureAllDates($dates, $data, $itemIds);
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

    private function getItems($item_category = null, $item_id = null)
    {
        $query = Item::where('status', "Active")
            ->select(['id', 'name', 'item_category_id'])
            ->orderBy('name');

        if (!is_null($item_category)) {
            $query->where('item_category_id', $item_category);
        }

        if (!is_null($item_id)) {
            $query->where('id', $item_id);
        }

        return $query->get();
    }

    private function calculateDailyTotals($dailyReport)
    {
        return [
            'previous_stock' => array_sum(array_column($dailyReport, 'previous_stock')),
            'purchase' => array_sum(array_column($dailyReport, 'purchase')),
            'used' => array_sum(array_column($dailyReport, 'used')),
            'current_stock' => array_sum(array_column($dailyReport, 'current_stock'))
        ];
    }
}