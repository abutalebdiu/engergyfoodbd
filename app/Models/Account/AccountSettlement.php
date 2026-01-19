<?php

namespace App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class AccountSettlement extends Model
{
        
    protected $table = "account_settlement";
    
    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    
    
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}