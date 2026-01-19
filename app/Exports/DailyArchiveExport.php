<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DailyArchiveExport implements FromView
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Create a new view instance.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('admin.reports.dailayarchive_export', [
            'datas' => $this->data
        ]);
    }
}
