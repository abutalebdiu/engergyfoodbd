<?php

namespace App\Models\Order;

use App\Models\User;
use App\Models\Admin;
use App\Traits\Searchable;
use App\Models\Order\QuotationDetail;
use Illuminate\Database\Eloquent\Model;
use App\Models\Distribution\Distribution;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use Searchable;

    protected $guarded = [];
 
    /**
     * Get the user who restored this quotation
     *
     * @return \App\Models\Admin
     */
    public function restoreduser()
    {
        return $this->belongsTo(Admin::class, 'restored_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function quotationdetail()
    {
        return $this->hasMany(QuotationDetail::class);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
     
    
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
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


    public function findquotation($quotation_id, $product_id)
    {
        $qtycount = QuotationDetail::where('quotation_id', $quotation_id)->where('product_id', $product_id)->count();

        if ($qtycount > 0) {
            return QuotationDetail::where('quotation_id', $quotation_id)->where('product_id', $product_id)->first()->qty;
        } else {
        }
    }

    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id', 'id');
    }
}
