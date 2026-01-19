<?php

namespace App\Models\Product;

use App\Models\Item;
use App\Models\Unit;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    use Searchable;

    protected $guarded = [];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }
    
}
