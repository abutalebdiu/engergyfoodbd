<?php

namespace App\Models\Account;

use App\Models\Admin;
use App\Models\HR\Loan;
use App\Traits\Searchable;
use App\Models\Account\Deposit;
use App\Models\HR\SalaryAdvance;
use App\Models\HR\OverTimeAllowance;
use App\Models\ItemOrderPayment;
use App\Models\Account\Settlement;
use App\Models\Account\Withdrawal;
use App\Models\Account\OfficialLoan;
use App\Models\Account\OrderPayment;
use App\Models\Account\PaymentMethod;
use App\Models\Account\AccountTransfer;
use App\Models\Account\CustomerAdvance;
use App\Models\Account\SupplierAdvance;
use App\Models\HR\SalaryPaymentHistory;
use App\Models\HR\FestivalBonusPayment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\CustomerDuePayment;
use App\Models\Account\OrderReturnPayment;
use App\Models\Account\SupplierDuePayment;
use App\Models\Account\TransactionHistory;
use App\Models\Account\OfficialLoanPayment;
use App\Models\Expense\AssetExpensePayment;
use App\Models\Account\OrderSupplierPayment;
use App\Models\Account\PurchaseReturnPayment;
use App\Models\Expense\ExpensePaymentHistory;
use App\Models\Expense\MonthlyExpensePayment;
use App\Models\Service\ServiceInvoicePayment;
use App\Models\Expense\TransportExpensePayment;
use App\Models\Report\DailyReport;
use App\Models\Commission\MarketerCommissionPayment;

class Account extends Model
{
    use Searchable;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


    public function entryuser()
    {
        return $this->belongsTo(Admin::class, 'entry_id');
    }

    public function edituser()
    {
        return $this->belongsTo(Admin::class, 'edit_id');
    }

    public function deleteuser()
    {
        return $this->belongsTo(Admin::class, 'deleted_id');
    }



    public function opendingbalance($id)
    {
        $amount = Account::where('id', $id)->where('status', 'Active')->sum('opening_balance');
        return $amount;
    }

    public function orderpayment($id,$start_date=null,$end_date=null)
    {
        $query = OrderPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function orderreturnpayment($id,$start_date=null,$end_date=null)
    {
        $query = OrderReturnPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }


    public function supplierpayment($id,$start_date=null,$end_date=null)
    {
        $query = OrderSupplierPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function purchasereturnpayment($id,$start_date=null,$end_date=null)
    {
        $query = PurchaseReturnPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function deposit($id,$start_date=null,$end_date=null)
    {
        $query = Deposit::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function widthdrawal($id,$start_date=null,$end_date=null)
    {
        $query = Withdrawal::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function expense($id,$start_date=null,$end_date=null)
    {
        $query = ExpensePaymentHistory::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function accounttransferplus($id,$start_date=null,$end_date=null)
    {
        $query = AccountTransfer::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function accounttransferminues($id,$start_date=null,$end_date=null)
    {
        $query = AccountTransfer::where('from_account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function salaryadvance($id,$start_date=null,$end_date=null)
    {
        $query = SalaryAdvance::where('account_id', $id)->where('type', 'Regular');

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->where('type','Regular')->sum('amount');

    }
    
    
    public function overtimeallowance($id,$start_date=null,$end_date=null)
    {
        $query = OverTimeAllowance::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');

    }

    public function salarypaymenthistory($id,$start_date=null,$end_date=null)
    {
        $query = SalaryPaymentHistory::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function loan($id,$start_date=null,$end_date=null)
    {
        $query = Loan::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->where('type','Regular')->sum('amount');
    }

    public function officialloan($id,$start_date=null,$end_date=null)
    {

        $query = OfficialLoan::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function officialloanpayment($id,$start_date=null,$end_date=null)
    {
        $query = OfficialLoanPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');

    }

    public function supplieradvance($id,$start_date=null,$end_date=null)
    {

        $query = SupplierAdvance::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function customeradvance($id,$start_date=null,$end_date=null)
    {

        $query = CustomerAdvance::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');

    }

    public function customerduepayment($id,$start_date=null,$end_date=null)
    {

        $query = CustomerDuePayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }


    public function servicepayment($id)
    {
        return ServiceInvoicePayment::where('account_id', $id)->sum('amount');
    }

    public function supplierduepayment($id,$start_date=null,$end_date=null)
    {

        $query = SupplierDuePayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');

    }


    public function settlementplus($id,$start_date=null,$end_date=null)
    {

        $query = Settlement::where('account_id', $id)->where('type', 'Plus');

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function settlementminus($id,$start_date=null,$end_date=null)
    {
        $query = Settlement::where('account_id', $id)->where('type', 'Minus');

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function itempayment($id,$start_date=null,$end_date=null)
    {
        $query = ItemOrderPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }
    
    
    public function marketercommissionpayment($id,$start_date=null,$end_date=null)
    {
        $query = MarketerCommissionPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }


    public function assetexpensepayment($id,$start_date=null,$end_date=null)
    {
        $query = AssetExpensePayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');


    }

    public function monthlyexpensepayment($id,$start_date=null,$end_date=null)
    {

        $query = MonthlyExpensePayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');

    }

    public function transportexpensepayment($id,$start_date=null,$end_date=null)
    {

        $query = TransportExpensePayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }


    public function accountsettlementplus($id,$start_date=null,$end_date=null)
    {

        $query = AccountSettlement::where('account_id', $id)->where('type', 'Plus');

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }

    public function accountsettlementminus($id,$start_date=null,$end_date=null)
    {
        $query = AccountSettlement::where('account_id', $id)->where('type', 'Minus');

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }

        return $query->sum('amount');
    }
    
    
    public function festivalbonuspayment($id,$start_date=null,$end_date=null)
    {
        $query = FestivalBonusPayment::where('account_id', $id);

        if ($start_date && $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date', $start_date);
        }
        
        return $query->sum('amount');
    }


    public function balance($id,$start_date=null,$end_date=null)
    {
         return  (self::opendingbalance($id)
                  + self::orderpayment($id,$start_date,$end_date)
                  + self::deposit($id,$start_date,$end_date)
                  + self::officialloan($id,$start_date,$end_date)
                  + self::customerduepayment($id,$start_date,$end_date)
                  + self::accountsettlementplus($id,$start_date,$end_date))
                - (
                + self::itempayment($id,$start_date,$end_date)
                + self::supplierduepayment($id,$start_date,$end_date)
                + self::salaryadvance($id,$start_date,$end_date)
                + self::salarypaymenthistory($id,$start_date,$end_date)
                + self::loan($id,$start_date,$end_date)
                + self::overtimeallowance($id,$start_date,$end_date)
                + self::widthdrawal($id,$start_date,$end_date)
                + self::expense($id,$start_date,$end_date)
                + self::officialloanpayment($id,$start_date,$end_date)
                + self::assetexpensepayment($id,$start_date,$end_date)
                + self::monthlyexpensepayment($id,$start_date,$end_date)
                + self::transportexpensepayment($id,$start_date,$end_date)
                + self::marketercommissionpayment($id,$start_date,$end_date)
                + self::festivalbonuspayment($id,$start_date,$end_date)
                + self::accountsettlementminus($id,$start_date,$end_date)
            );
            
    }

    public function transactionhistories()
    {
        return $this->hasMany(TransactionHistory::class, 'account_id');
    }
}
