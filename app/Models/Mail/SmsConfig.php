<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    use HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
