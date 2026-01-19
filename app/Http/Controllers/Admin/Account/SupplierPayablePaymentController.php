<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account\SupplierAdvance;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Account\Settlement;
use App\Models\Order\Purchse;  // Corrected spelling to 'Purchase'
use App\Models\Account\TransactionHistory;
use App\Models\Account\Account;
use App\Models\Account\OrderSupplierPayment;
use App\Models\Order\PurchaseReturn;

class SupplierPayablePaymentController extends Controller
{
    public function payablePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $getOpeningDue = $this->getOpeningDue($request->supplier_id);

            // Current balance
            $current_balance = $request->amount;

            if ($getOpeningDue > 0) {
                $balance = min($getOpeningDue, $request->amount);
                $current_balance -= $balance;
                $this->createSettlement($request, $balance);
            }

            $getAdvance = $this->getAdvance($request->supplier_id);

            if ($getAdvance > 0) {
                $balance = min($getAdvance, $getAdvance);
                $this->decrementAdvance($request->supplier_id, $balance);
            }


            if ($current_balance > 0) {
                $payable = $this->nowPayableAmount($request->supplier_id);
                $pay = min($current_balance, $payable);


                $this->createSupplierPayment($request, 0, $request->supplier_id, $pay, $request->less_amount);

                $current_balance -= $pay;
            }

            if ($current_balance > 0) {
                $this->storeAdvance($request, $current_balance);
            }

            $this->createTransactionHistory($request, $request->purchase_id, $request->amount, 'debit', 2);

            DB::commit();
            $notify[] = ['success', 'Payable Payment successfully Added'];
            return redirect()->back()->withNotify($notify);
        } catch (Exception $e) {
            DB::rollBack();
            $notify[] = ['error', $e->getMessage()];
            return redirect()->back()->withNotify($notify);
        }
    }

    protected function storeAdvance($request, $amount)
    {
        DB::beginTransaction();
        try {
            $advance = new SupplierAdvance();
            $advance->supplier_id = $request->supplier_id;
            $advance->payment_method_id = $request->payment_method_id;
            $advance->account_id = $request->account_id;
            $advance->amount = $amount;
            $advance->date = date('Y-m-d');
            $advance->save();

            if ($advance) {
                $advanceTrx = SupplierAdvance::findOrFail($advance->id);
                $advanceTrx->update(['trx_no' => 'SA000' . $advanceTrx->id]);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return new Exception($e->getMessage());
        }
    }

    protected function getSupplier($id)
    {
        return User::find($id);  // Shortened to a single line
    }

    protected function getTotalAmount($id)
    {
        return Purchse::where('supplier_id', $id)->sum('totalamount') ?? 0;  // Corrected spelling to 'Purchase'
    }

    protected function getPayable($id)
    {
        $totalAmount = $this->getSupplier($id)->opening;
        return $totalAmount ?? 0;
    }

    protected function getOrderSupplierPayment($id)
    {
        $amount = OrderSupplierPayment::where('supplier_id', $id)->sum('amount');
        return $amount ?? 0;
    }

    protected function getTotalReturns($id)
    {
        return PurchaseReturn::where('supplier_id', $id)->sum('amount') ?? 0;  // Shortened to a single line
    }

    protected function nowPayableAmount($id)
    {
        $totalAmount = $this->getTotalAmount($id);
        $getPayable = $this->getPayable($id);

        // balance is negative to show zero

        $balance = ($totalAmount + $getPayable) - $this->payableBalance($id);
        return $balance > 0 ? $balance : 0;
    }

    protected function payableBalance($id)
    {
        $paidAmount = $this->getOrderSupplierPayment($id);
        $returnAmount = $this->getTotalReturns($id);
        return ($paidAmount + $returnAmount + $this->getLessAmount($id) + $this->getAdvance($id)) ?? 0;
    }

    protected function getLessAmount($id)
    {
        return OrderSupplierPayment::where('supplier_id', $id)->sum('less_amount') ?? 0;  // Shortened to a single line
    }

    protected function createSupplierPayment($request, $purchase_id, $supplier_id, $amount = 0, $less_amount = 0)
    {

        try {
            $supplierPayment = new OrderSupplierPayment();
            $supplierPayment->purchase_id = $purchase_id;
            $supplierPayment->supplier_id = $supplier_id;
            $supplierPayment->amount = $amount;
            $supplierPayment->less_amount = $less_amount;
            $supplierPayment->payment_method_id = $request->payment_method_id;
            $supplierPayment->account_id = $request->account_id;
            $supplierPayment->date = $request->date;
            $supplierPayment->save();

            if ($supplierPayment) {
                $supplierPaymentTrx = OrderSupplierPayment::findOrFail($supplierPayment->id);
                $supplierPaymentTrx->tnx_no = 'SP000' . $supplierPayment->id;
                $supplierPaymentTrx->save();
            }

            return $supplierPayment;
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    protected function getAdvance($supplier_id)
    {
        return SupplierAdvance::where('supplier_id', $supplier_id)->sum('amount') ?? 0;  // Shortened to a single line
    }

    protected function decrementAdvance($supplier_id, $amount)
    {

        try {
            $advances = SupplierAdvance::where('supplier_id', $supplier_id)->get();

            foreach ($advances as $advance) {
                if ($advance->amount == $advance->used_amount) {
                    continue; // Skip fully used advances
                } else {
                    $decrementAdvanceUpdate = SupplierAdvance::find($advance->id);
                    $remainingAmount = $advance->amount - $advance->used_amount;

                    if ($amount <= $remainingAmount) {
                        $decrementAdvanceUpdate->used_amount += $amount;
                        $amount = 0; // Fully decremented
                    } else {
                        $decrementAdvanceUpdate->used_amount += $remainingAmount;
                        $amount -= $remainingAmount; // Continue decrementing
                    }

                    $decrementAdvanceUpdate->save();

                    if ($amount == 0) {
                        break;
                    }
                }
            }

            return $advances;
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    protected function createSettlement($request, $amount)
    {
        try {
            $settlement = new Settlement();
            $settlement->user_type = 'Supplier';
            $settlement->user_id = $request->supplier_id;
            $settlement->payment_method_id = $request->payment_method_id;
            $settlement->account_id = $request->account_id;
            $settlement->amount = $amount;
            $settlement->type = 'Minus';
            $settlement->note = $request->note;
            $settlement->entry_id = auth('admin')->user()->id;
            $settlement->save();

            if ($settlement) {
                $this->payOpeningPayable($request->supplier_id, $amount);

                $trxSettlement = Settlement::find($settlement->id);
                $trxSettlement->tnx_no = 'STM0' . $trxSettlement->id;
                $trxSettlement->save();
            }

            return $trxSettlement;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function getOpeningDue($id)
    {
        return User::findOrFail($id)->opening ?? 0;  // Added a fallback to 0 if opening is null
    }

    protected function payOpeningPayable($id, $amount)
    {
        try {
            $user = User::findOrFail($id);
            $user->opening -= $amount;
            $user->save();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function createTransactionHistory($request, $orderpayment, $amount, $cdf_type = 'debit', $module_id = 2)
    {
        try {
            $transactionHistory = new TransactionHistory();
            $transactionHistory->invoice_no = '';
            $transactionHistory->reference_no = '';
            $transactionHistory->module_id = $module_id;
            $transactionHistory->module_invoice_id = $orderpayment;
            $transactionHistory->amount = $amount;
            $transactionHistory->cdf_type = $cdf_type;
            $transactionHistory->payment_method_id = $request->payment_method_id;
            $transactionHistory->account_id = $request->account_id;
            $transactionHistory->client_id = $request->supplier_id;
            $transactionHistory->note = $request->note;
            $transactionHistory->save();

            $account = Account::find($request->account_id);

            // Transaction before balance
            $transactionHistory->pre_balance = $account->main_balance;
            $transactionHistory->txt_no = 'TNH000' . $transactionHistory->id;
            $transactionHistory->save();

            $account->main_balance -= $request->amount;
            $account->save();

            // Transaction after balance
            $transactionHistory->per_balance = $account->main_balance;
            $transactionHistory->save();

            return $transactionHistory;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
