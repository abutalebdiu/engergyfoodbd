<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductionReport implements FromView
{
    private $products;
    private $items;
    private $start_date;
    private $end_date;
    private $department_id;
    private $total_received_qty;
    private $total_received_cost;
    private $total_pp_cost;
    private $total_box_cost;
    private $total_striker_cost;
    private $total_cost;
    private $production_qty;
    private $production_price;
    private $profit_or_loss;
    private $profit_or_loss_percentage;


    public function __construct($products, $items, $start_date, $end_date, $department_id, $total_received_qty, $total_received_cost, $total_pp_cost, $total_box_cost, $total_striker_cost, $total_cost, $production_qty, $production_price, $profit_or_loss, $profit_or_loss_percentage)
    {
        $this->products = $products;
        $this->items = $items;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->department_id = $department_id;
        $this->total_received_qty = $total_received_qty;
        $this->total_received_cost = $total_received_cost;
        $this->total_pp_cost = $total_pp_cost;
        $this->total_box_cost = $total_box_cost;
        $this->total_striker_cost = $total_striker_cost;
        $this->total_cost = $total_cost;
        $this->production_qty = $production_qty;
        $this->production_price = $production_price;
        $this->profit_or_loss = $profit_or_loss;
        $this->profit_or_loss_percentage = $profit_or_loss_percentage;
    }

    public function view(): View
    {
        return view('admin.productions.dailyproductions.daily_production_report_excel', [
            'products' => $this->products,
            'items' => $this->items,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'department_id' => $this->department_id,
            'total_received_qty' => $this->total_received_qty,
            'total_received_cost' => $this->total_received_cost,
            'total_pp_cost' => $this->total_pp_cost,
            'total_box_cost' => $this->total_box_cost,
            'total_striker_cost' => $this->total_striker_cost,
            'total_cost' => $this->total_cost,
            'production_qty' => $this->production_qty,
            'production_price' => $this->production_price,
            'profit_or_loss' => $this->profit_or_loss,
            'profit_or_loss_percentage' => $this->profit_or_loss_percentage,
        ]);
    }
}
