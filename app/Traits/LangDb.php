<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;

trait LangDb
{
    public function lang($colum = null)
    {
        if (!$colum) {
            return 'Colum name empty';
        }
        if (Session::get('lang') == 'ar') {
            $langCode = Session::get('lang');
            $columName = $colum . '_' . $langCode;

            if ($this->$columName ?? false) {
                return $this->$columName;
            }
            return $this->$colum;
        }
        return $this->$colum;
    }
}
