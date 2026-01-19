<?php

namespace App\Traits;

use App\Models\Account\OrderSupplierPayment;
use App\Models\Order\Purchse;
use App\Models\Order\PurchaseReturn;
use App\Models\User;
use App\Models\Account\SupplierAdvance;

trait SupplierCalculationTrait
{
    use ProcessByDate;

    public $from;
    public $to;

    public function __construct()
    {
        [$this->from, $this->to] = $this->processByDateRange(request());
    }

    public function getSupplierDue($request, $id)
    {
        return $this->calculateTotalDue($id);
    }

    private function getSupplier($id)
    {
        return User::findOrFail($id);
    }

    private function getTransactionHistories($id)
    {
        return \App\Models\Account\TransactionHistory::whereBetween('created_at', [$this->from, $this->to])
            ->where('client_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    private function getTotalPurchses($id)
    {
        return Purchse::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->count();
    }

    private function getTotalPurchseAmount($id)
    {
        return Purchse::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->sum('totalamount');
    }

    private function getPayable($id)
    {
        $totalAmount = $this->getSupplier($id)->opening;
        return $totalAmount ?? 0;
    }

    private function getOrderSupplierPayment($id)
    {
        return OrderSupplierPayment::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->sum('amount');
    }

    private function getTotalPurchesReturns($id)
    {
        return PurchaseReturn::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->sum('amount');
    }

    private function getAdvance($id)
    {
        return SupplierAdvance::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->sum('amount');
    }

    private function usedAdvance($id)
    {
        return SupplierAdvance::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->sum('used_amount');
    }

    private function calculateTotalPurchaseDue($id)
    {
        $totalAmount = $this->getTotalPurchseAmount($id);
        $payable = $this->getPayable($id);

        // Balance is negative to show in zero
        $balance = ($totalAmount + $payable) - $this->payableBalance($id);
        return max($balance, 0);
    }

    private function payableBalance($id)
    {
        $paidAmount = $this->getOrderSupplierPayment($id);
        $returnAmount = $this->getTotalPurchesReturns($id);
        return ($paidAmount + $returnAmount + $this->getLessAmount($id) + $this->getAdvance($id));
    }

    private function getLessAmount($id)
    {
        return OrderSupplierPayment::whereBetween('created_at', [$this->from, $this->to])
            ->where('supplier_id', $id)
            ->sum('less_amount');
    }
}
