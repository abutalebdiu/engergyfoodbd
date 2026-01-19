<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\MakeProduction;
use App\Models\Product\ProductRecipe;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function brand()
    {
        return $this->belongsTo(ItemBrand::class);
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function entryuser()
    {
        return $this->belongsTo(Admin::class, 'entry_id');
    }

    public function edituser()
    {
        return $this->belongsTo(Admin::class, 'edit_id');
    }

    public function deleteuser()
    {
        return $this->belongsTo(Admin::class, 'deleted_id');
    }


    public function stock($id)
    {
        return  self::opening($id)
            +   self::itemorderdetail($id)
            -   self::itemorderreturndetail($id)
            -   self::makeproduction($id)
            -   self::productionloss($id)
            +   self::stocksettlementplus($id)
            -   self::stocksettlementminus($id)
            -   self::productppstock($id)
            -   self::productboxstock($id)
            -   self::productstrikerstock($id);
    }

    // Opending Stocks
    public function opening($id)
    {
        return Item::where('id', $id)->sum('opening_qty');
    }

    // Purchase Stocks
    public function itemorderdetail($id)
    {
        return ItemOrderDetail::where('item_id', $id)->sum('qty');
    }

    // item order return details
    public function itemorderreturndetail($id)
    {
        return ItemOrderReturnDetail::where('product_id', $id)->sum('qty');
    }

    public function makeproduction($id)
    {
        return MakeProduction::where('item_id', $id)->sum('qty');
    }

    public function productionloss($id)
    {
        return ProductionLoss::where('item_id', $id)->sum('qty');
    }

    public static function stocksettlementplus($id)
    {
        return ItemStock::where('item_id', $id)->whereIn('type', ['settlement'])->where('status', 'Plus')->sum('qty');
    }

    public static function stocksettlementminus($id)
    {
        return ItemStock::where('item_id', $id)->where('type', 'settlement')->where('status', 'Minus')->sum('qty');
    }


    // PP

    public static function productppstock($id)
    {
        return round(DailyProduction::where('pp_item_id', $id)->sum('qty') / 1000, 2);
    }

    public static function productboxstock($id)
    {
        return DailyProduction::where('box_item_id', $id)->sum('qty');
    }

    public static function productstrikerstock($id)
    {
        return DailyProduction::where('striker_item_id', $id)->sum('qty');
    }




    public function makeproductionqty($date, $item_id, $department_id)
    {
        $count =  MakeProduction::where('date', $date)->where('item_id', $item_id)->where('department_id', $department_id)->count();

        if ($count > 0) {
            return MakeProduction::where('date', $date)->where('item_id', $item_id)->where('department_id', $department_id)->first()->qty;
        } else {
            return 0;
        }
    }

    public function makeproductionqtysum($start_date, $end_date, $item_id, $department_id)
    {
        return MakeProduction::whereBetween('date', [$start_date, $end_date])->where('item_id', $item_id)->where('department_id', $department_id)->sum('qty');
    }

    public function receiveTotalQty($start_date, $end_date, $department_id)
    {
        $makeProductions = MakeProduction::with('item')
            ->where('department_id', $department_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->get();

        $qty = 0;

        foreach ($makeProductions as $makeProduction) {
            $qty += $makeProduction->qty;
        }

        return $qty;
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class, 'item_id');
    }


    public function makeProductions()
    {
        return $this->hasMany(MakeProduction::class, 'item_id');
    }

    public function getMakeProduction($departmentId, $startDate, $endDate)
    {
        return $this->makeProductions()
            ->where('department_id', $departmentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->first();
    }



    // Item Stock Settlements

    public function getopeningstock($item_id)
    {
        $countstock = ItemStock::where('item_id', $item_id)->count();
        if ($countstock > 0) {
            return ItemStock::where('item_id', $item_id)->orderBy('date', 'desc')->first()->physical_stock;
        } else {
            return Item::where('id', $item_id)->first()->opening_qty;
        }
    }

    public function getpurchasevalue($item_id)
    {
        $countstock = ItemStock::where('item_id', $item_id)->count();

        if ($countstock > 0) {
            $latestStock = ItemStock::where('item_id', $item_id)
                ->orderBy('year', 'desc')
                ->orderBy('month_id', 'desc')
                ->first();

            $getmonth = $latestStock->month_id;
            $getyear  = $latestStock->year;

            if ($getmonth == 12) {
                $getmonth = 1;
                $getyear++;
            } else {
                $getmonth;
            }

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date   = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            // Return the sum of quantities within the calculated range
            return ItemOrderDetail::join('item_orders', 'item_order_details.item_order_id', '=', 'item_orders.id')
                ->where('item_order_details.item_id', $item_id)
                ->whereBetween('item_orders.date', [$start_date, $end_date])
                ->sum('item_order_details.qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return ItemOrderDetail::where('item_id', $item_id)->sum('qty');
        }
    }

    public function getmakeproductionvalue($item_id)
    {
        $countstock = ItemStock::where('item_id', $item_id)->count();

        if ($countstock > 0) {
            $latestStock = ItemStock::where('item_id', $item_id)
                ->orderBy('year', 'desc')
                ->orderBy('month_id', 'desc')
                ->first();

            $getmonth = $latestStock->month_id;
            $getyear  = $latestStock->year;

            if ($getmonth == 12) {
                $getmonth = 1;
                $getyear++;
            } else {
                $getmonth;
            }

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            // Return the sum of quantities within the calculated range
            return MakeProduction::where('item_id', $item_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->sum('qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return MakeProduction::where('item_id', $item_id)->sum('qty');
        }
    }


    public function getproductionloss($item_id)
    {
        $countstock = ItemStock::where('item_id', $item_id)->count();

        if ($countstock > 0) {
            $latestStock = ItemStock::where('item_id', $item_id)
                ->orderBy('year', 'desc')
                ->orderBy('month_id', 'desc')
                ->first();

            $getmonth = $latestStock->month_id;
            $getyear  = $latestStock->year;

            if ($getmonth == 12) {
                $getmonth = 1;
                $getyear++;
            } else {
                $getmonth;
            }

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date   = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            return ProductionLoss::where('item_id', $item_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->sum('qty');
        } else {
            return ProductionLoss::where('item_id', $item_id)->sum('qty');
        }
    }
    
    
    public function getproductppstock($item_id)
    {
        $countstock = ItemStock::where('item_id', $item_id)->count();

        if ($countstock > 0) {
            $latestStock = ItemStock::where('item_id', $item_id)
                ->orderBy('year', 'desc')
                ->orderBy('month_id', 'desc')
                ->first();

            $getmonth = $latestStock->month_id;
            $getyear  = $latestStock->year;

            if ($getmonth == 12) {
                $getmonth = 1;
                $getyear++;
            } else {
                $getmonth;
            }

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            // Return the sum of quantities within the calculated range
            return  round(DailyProduction::where('pp_item_id', $item_id)
                    ->whereBetween('date', [$start_date, $end_date])
                    ->sum('qty') / 1000, 2);
        } else {
            // If no stock exists, return the total quantity for the product
            return round(DailyProduction::where('pp_item_id', $item_id)->sum('qty') / 1000, 2);
        }
        
    }

    public function getproductboxstock($item_id)
    {
        $countstock = ItemStock::where('item_id', $item_id)->count();

        if ($countstock > 0) {
            $latestStock = ItemStock::where('item_id', $item_id)
                ->orderBy('year', 'desc')
                ->orderBy('month_id', 'desc')
                ->first();

            $getmonth = $latestStock->month_id;
            $getyear  = $latestStock->year;

            if ($getmonth == 12) {
                $getmonth = 1;
                $getyear++;
            } else {
                $getmonth;
            }

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            // Return the sum of quantities within the calculated range
            return  round(DailyProduction::where('box_item_id', $item_id)
                    ->whereBetween('date', [$start_date, $end_date])
                    ->sum('qty'));
        } else {
            // If no stock exists, return the total quantity for the product
            return DailyProduction::where('box_item_id', $item_id)->sum('qty');
        }
        
    }

    public function getproductstrikerstock($item_id)
    { 
        $countstock = ItemStock::where('item_id', $item_id)->count();

        if ($countstock > 0) {
            $latestStock = ItemStock::where('item_id', $item_id)
                ->orderBy('year', 'desc')
                ->orderBy('month_id', 'desc')
                ->first();

            $getmonth = $latestStock->month_id;
            $getyear  = $latestStock->year;

            if ($getmonth == 12) {
                $getmonth = 1;
                $getyear++;
            } else {
                $getmonth;
            }

            $start_date = Carbon::create($getyear, $getmonth, 1)->startOfMonth()->toDateString();
            $end_date = Carbon::create($getyear, $getmonth, 1)->endOfMonth()->toDateString();

            // Return the sum of quantities within the calculated range
            return  round(DailyProduction::where('striker_item_id', $item_id)
                    ->whereBetween('date', [$start_date, $end_date])
                    ->sum('qty'));
        } else {
            // If no stock exists, return the total quantity for the product
             return DailyProduction::where('striker_item_id', $item_id)->sum('qty');
        }
    }



}
