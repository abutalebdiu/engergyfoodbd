<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Order\Order;
use App\Models\Account\OrderPayment;
use App\Models\Account\CustomerAdvance;
use App\Models\Order\OrderReturn;

trait CustomerCalculateTrait
{
    use ProcessByDate;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function getCustomerDue($request, $id): float
    {
        return $this->calculateTotalDue($id);
    }

    public function getCustomer(int $id): User
    {
        return User::findOrFail($id);
    }

    public function getTotalAdvance(int $id): float
    {
        $totalAdvance = CustomerAdvance::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->sum('amount');

        $usedAmount = CustomerAdvance::where('customer_id', $id)->sum('used_amount');

        return $totalAdvance - $usedAmount;
    }

    public function getTotalOrders(int $id): int
    {
        return Order::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->count();
    }

    public function getTotalReturns(int $id): float
    {
        return OrderReturn::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->sum('sub_total');
    }

    public function getTotalAmount(int $id): float
    {
        return Order::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->sum('net_amount');
    }

    public function getOpeningDue(int $id): float
    {
        return $this->getCustomer($id)->opening;
    }

    public function lessAmountCalculate(int $id): float
    {
        return OrderPayment::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->sum('less_amount');
    }

    public function getTotalPaidAmount(int $id): float
    {
        return OrderPayment::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->sum('amount');
    }

    public function getTotalOrderPaid(int $id): float
    {
        return OrderPayment::whereBetween('created_at', [$this->from, $this->to])
            ->where('customer_id', $id)
            ->where('order_id', '!=', 0)
            ->groupBy('order_id')
            ->sum('amount');
    }

    public function calculateTotalDue(int $id): float
    {
        $totalAmount = $this->getTotalAmount($id);
        $openingDue = $this->getOpeningDue($id);
        $orderPaid = $this->getTotalOrderPaid($id);
        $return = $this->getTotalReturns($id);
        $lessAmount = $this->lessAmountCalculate($id);

        return ($totalAmount + $openingDue) - ($orderPaid + $lessAmount + $return);
    }
}
