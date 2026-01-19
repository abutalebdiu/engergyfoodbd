<?php

namespace App\Models\Setting;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    use GlobalStatus, Searchable;

    protected $casts = [
        'shortcode' => 'object',
    ];

    protected $hidden = ['script', 'shortcode'];

    public function scopeGenerateScript()
    {
        $script = $this->script;
        foreach ($this->shortcode as $key => $item) {
            $script = str_replace('{{' . $key . '}}', $item->value, $script);
        }
        return $script;
    }
}
