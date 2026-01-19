<?php

namespace App\Models\Setting;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Frontend extends Model
{
    protected $casts = [
        'data_values' => 'object',
    ];

    public static function scopeGetContent($data_keys)
    {
        return Frontend::where('data_keys', $data_keys);
    }

    public function lang($colum = null)
    {
        if (!$colum) {
            return 'Colum name empty';
        }
        if (Session::get('lang') == 'ar') {
            $langCode = Session::get('lang');
            $columName = $colum . '_' . $langCode;
            if ($this->data_values->$columName ?? false) {
                return $this->data_values->$columName;
            }
        }
        return $this->data_values->$colum;
    }
}
