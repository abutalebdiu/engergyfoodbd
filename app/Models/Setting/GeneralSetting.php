<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{

    protected $casts = ['mail_config' => 'object', 'sms_config' => 'object', 'global_shortcodes' => 'object'];

    protected $hidden = ['email_template', 'mail_config', 'sms_config', 'system_info'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('GeneralSetting');
        });
    }
}
