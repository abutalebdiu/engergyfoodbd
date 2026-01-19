<?php

namespace App\Models\Product;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Admin;
use App\Constants\Status;
use App\Traits\Searchable;
use App\Models\HR\Department;
use App\Models\DailyProduction;
use App\Models\Order\OrderDetail;
use App\Models\Order\OrderReturnDetail;
use App\Models\Order\PurchaseDetail;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commission\ReferenceCommision;

class Product extends Model
{
    use Searchable;

    protected $guarded = [];
    
    
    
    // for testing
        
    public function orderdetail()
    {
        return $this->hasMany(OrderDetail::Class,'product_id');
    }
        
    
    // end testnig
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function productrecipe()
    {
        return $this->hasMany(ProductRecipe::class, 'product_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function ppitem()
    {
        return $this->belongsTo(Item::class, 'pp_item_id');
    }

    public function boxitem()
    {
        return $this->belongsTo(Item::class, 'box_item_id');
    }

    public function strikeritem()
    {
        return $this->belongsTo(Item::class, 'striker_item_id');
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

    public function getstock($id)
    {
        return (self::openingstock($id)
            + self::productionstock($id)
            + self::stocksettlementplus($id)
            + self::orderreturndetail($id))
            - (self::saleproduct($id)
            + self::productdamange($id)
            + self::customerproductdamage($id)
            + self::stocksettlementminus($id));
    }

    public function openingstock($id)
    {
        return Product::where('id', $id)->first()->opening_qty;
    }
    
    // FIXED: Only include 'settlement', NOT 'Opening'
    public static function stocksettlementplus($id)
    {
        return ProductStock::where('product_id', $id)
            ->where('type', 'settlement')  // ← Removed 'Opening' from here
            ->where('status', 'Plus')
            ->sum('qty');
    }
    
    public static function stocksettlementminus($id)
    {
        return ProductStock::where('product_id', $id)
            ->where('type', 'settlement')
            ->where('status', 'Minus')
            ->sum('qty');
    }

    public static function saleproduct($id)
    {
        return OrderDetail::where('product_id', $id)->sum('qty');
    }
    
     public static function orderreturndetail($id)
     {
        return OrderReturnDetail::where('product_id', $id)->sum('qty');
     }

    public static function productdamange($id)
    {
        return ProductDamage::where('product_id', $id)->sum('qty');
    }

    public static function customerproductdamage($id)
    {
        return CustomerProductDamageDetail::where('product_id', $id)->sum('qty');
    }
 

    public static function productionstock($id)
    {
        return DailyProduction::where('product_id', $id)->sum('qty');
    }


    public function productCommission()
    {
        return $this->hasOne(ReferenceCommision::class, 'product_id');
    }

    public function dailyproduction($date, $product_id)
    {
        $count =  DailyProduction::where('date', $date)->where('product_id', $product_id)->count();

        if ($count > 0) {
            return DailyProduction::where('date', $date)->where('product_id', $product_id)->first()->qty;
        } else {
            return 0;
        }
    }

    public function dailyProductions()
    {
        return $this->hasMany(DailyProduction::class);
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }


    public function dailyProductionBetween($startDate, $endDate)
    {
        return $this->dailyProductions()
            ->whereBetween('date', [$startDate, $endDate])
            ->first();
    }

    public function getRecipe($itemId)
    {
        return $this->recipes()->where('item_id', $itemId)->first();
    }


    // Stock Settlements

    public function getopeningstock($product_id)
    {
        $countstock = ProductStock::where('product_id', $product_id)->count();
        if ($countstock > 0) {
            return ProductStock::where('product_id', $product_id)->orderBy('date', 'desc')->first()->physical_stock;
        } else {
            return Product::where('id', $product_id)->first()->opening_qty;
        }
    }

    public function getproductionvalue($product_id)
    {
        $countstock = ProductStock::where('product_id', $product_id)->count();

        if ($countstock > 0) {
            $latestStock = ProductStock::where('product_id', $product_id)
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
            return DailyProduction::where('product_id', $product_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->sum('qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return DailyProduction::where('product_id', $product_id)->sum('qty');
        }
    }


    public function getsalevalue($product_id)
    {
        $countstock = ProductStock::where('product_id', $product_id)->count();

        if ($countstock > 0) {
            $latestStock = ProductStock::where('product_id', $product_id)
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
            return OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('order_details.product_id', $product_id)
                ->whereBetween('orders.date', [$start_date, $end_date])
                ->sum('order_details.qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return OrderDetail::where('product_id', $product_id)->sum('qty');
        }
    }
    
    
    public function getcustomerorderreturn($product_id)
    {
        $countstock = ProductStock::where('product_id', $product_id)->count();

        if ($countstock > 0) {
            $latestStock = ProductStock::where('product_id', $product_id)
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


            return OrderReturnDetail::join('order_returns', 'order_return_details.order_return_id', '=', 'order_returns.id')
                ->where('order_return_details.product_id', $product_id)
                ->whereBetween('order_returns.date', [$start_date, $end_date])
                ->sum('order_return_details.qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return OrderReturnDetail::where('product_id', $product_id)->sum('qty');
        }
    }


    public function getproductdamage($product_id)
    {
        $countstock = ProductStock::where('product_id', $product_id)->count();

        if ($countstock > 0) {
            $latestStock = ProductStock::where('product_id', $product_id)
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
            return ProductDamage::where('product_id', $product_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->sum('qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return ProductDamage::where('product_id', $product_id)->sum('qty');
        }
    }

    public function getcustomerproductdamage($product_id)
    {
        $countstock = ProductStock::where('product_id', $product_id)->count();

        if ($countstock > 0) {
            $latestStock = ProductStock::where('product_id', $product_id)
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


            return CustomerProductDamageDetail::join('customer_product_damages', 'customer_product_damage_details.customer_product_damage_id', '=', 'customer_product_damages.id')
                ->where('customer_product_damage_details.product_id', $product_id)
                ->whereBetween('customer_product_damages.date', [$start_date, $end_date])
                ->sum('customer_product_damage_details.qty');
        } else {
            // If no stock exists, return the total quantity for the product
            return CustomerProductDamageDetail::where('product_id', $product_id)->sum('qty');
        }
    }
    
    
    
}
