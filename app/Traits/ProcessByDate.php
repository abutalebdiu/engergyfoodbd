<?php


namespace App\Traits;

use Carbon\Carbon;

trait ProcessByDate
{
    /**
     * Process date range from request and return in format of array
     *
     * @param $range
     * @return array
     */
    public function processByDate($range)
    {
        $dateRange = explode(',', $range);
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
        return [$startDate, $endDate];
    }

    /**
     * Process date range from request and return in format of array
     *
     * @param  Request $request
     * @return array
     */
    public function processByDateRange($request)
    {
        $from = '';
        $to = '';

        if($request->range) {
            $dateRange = $this->processByDate($request->range);
            $from = $dateRange[0];
            $to = $dateRange[1];
        }

        if($request->filter == 'today') {
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }elseif($request->filter == 'yesterday') {
            $from = date('Y-m-d', strtotime('-1 days'));
            $to = date('Y-m-d', strtotime('-1 days'));
        }elseif ($request->filter == 'this_week') {
            $from = date('Y-m-d', strtotime('last sunday'));
            $to = date('Y-m-d');
        }elseif ($request->filter == 'last_week') {
            $from = date('Y-m-d', strtotime('last sunday -6 days'));
            $to = date('Y-m-d', strtotime('last sunday'));
        }elseif ($request->filter == 'this_month') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
        }elseif ($request->filter == 'last_month') {
            $from = date('Y-m-01', strtotime('-1 months'));
            $to = date('Y-m-t', strtotime('-1 months'));
        }elseif($request->filter == 'custom') {

        }

        $from = $from.' 00:00:00';
        $to = $to.' 23:59:59';

        return [$from, $to];
    }
}
