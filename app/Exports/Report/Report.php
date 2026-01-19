<?php

namespace App\Exports\Report;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Report  implements FromView
{
    public $view;
    public $data = [];

    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function view(): View
    {

        return view($this->view, [
            'layout' => false,
            'data' => $this->data
        ]);
    }

}
