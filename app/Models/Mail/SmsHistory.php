<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    use HasFactory;

    public function smsconfig()
    {
        return $this->belongsTo(SmsConfig::class, 'sms_config_id');
    }
}
