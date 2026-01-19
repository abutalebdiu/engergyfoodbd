<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Account\BuyerAccount;
use App\Models\Order\OrderDetail;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function getCities($country_id = null)
    {
        try {
            if ($country_id) {
                $cities = City::where('country_id', $country_id)->get();
                return $this->success($cities);
            } else {
                $cities = City::get();
                return $this->success($cities);
            }
        } catch (\Exception $e) {
            return $this->error();
        }
    }


    public function getbuyeraccount(Request $request)
    {
        $method = $request->payment_method_id;
        $buyer_id = $request->buyer_id;         

        $output = "<option value=''>Select Buyer Account</option>";
        $buyeraccounts = BuyerAccount::where('payment_method_id', $method)->where('buyer_id', $buyer_id)->active()->get();
        foreach ($buyeraccounts as $buyeraccount) {
            $output .= "<option value='" . $buyeraccount->id . "'> " . $buyeraccount->title . " </option>";
        }

        return $output;
    }
    public function getbuyeraccountbybuyer(Request $request)
    {
        $method = $request->payment_method_id;
        $buyer_id = $request->buyer_id;

        $output = "<option value=''>Select Buyer Account</option>";
        $buyeraccounts = BuyerAccount::where('payment_method_id', $method)->where('buyer_id', $buyer_id)->active()->get();
        foreach ($buyeraccounts as $buyeraccount) {
            $output .= "<option value='" . $buyeraccount->id . "'> " . $buyeraccount->title . " </option>";
        }
        return $output;
    }



}
