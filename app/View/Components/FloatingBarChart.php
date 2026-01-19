<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FloatingBarChart extends Component
{
    public $title;
    public $labels;
    public $values;

    public function __construct($title, $labels = [], $values = [])
    {
        $this->title = $title;
        $this->labels = $labels;
        $this->values = $values;
    }

    public function render(): View
    {
        return view('components.floating-bar-chart');
    }
}
