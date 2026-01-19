<?php
namespace App\Http\Controllers\Mail;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Mail\SmsConfig;
use App\Models\Mail\SmsHistory;
use App\Http\Controllers\Controller;

class SMSController extends Controller
{
    public function general()
    {
        $data['providers'] = SmsConfig::Active()->get();
        return view('mail_vendor.sms.general', $data);
    }

    public function sendGeneralMessage(Request $request)
    {
        $request->validate([
            "sms_config_id" => "required",
            "mobile_no" => "required",
            "message" => "required",
        ]);

        if ($request->sms_config_id == 1) {
            $this->bulksmsbd_bulk_sms($request->mobile_no, $request->message);
        } elseif ($request->sms_config_id = 2) {
            $this->techno_bulk_sms($request->mobile_no, $request->message);
        } else {
            return back()->with('error', 'Select SMS Provider');
        }

        $smshistory = new SmsHistory();
        $smshistory->sms_config_id = $request->sms_config_id;
        $smshistory->mobile = $request->mobile_no;
        $smshistory->message = $request->message;
        $smshistory->save();

        return back()->with('success', 'Message send successfully');
    }


    public function suppliersms()
    {
        $data['providers'] = SmsConfig::Active()->get();
        return view('mail_vendor.sms.suppliersms', $data);
    }

    public function sendSupplierMessage(Request $request)
    {
        $request->validate([
            "sms_config_id" => "required",
            "message" => "required",
        ]);

        $supplier = User::where('type', 'supplier')->active()->whereNotNull('mobile')->get()->pluck('mobile');

        if ($request->sms_config_id == 1) {
            $this->bulksmsbd_bulk_sms($supplier, $request->message);
        } elseif ($request->sms_config_id = 2) {
            $this->techno_bulk_sms($supplier, $request->message);
        } else {
            return back()->with('error', 'Select SMS Provider');
        }



        return back()->with('success', 'Message send successfully');
    }

    function techno_bulk_sms($mobile_no, $message)
    {
        $smsconfig = SmsConfig::find(2);
        $url = 'https://24bulksms.com/24bulksms/api/api-sms-send';
        $data = array(
            'api_key' => $smsconfig->appkey,
            'sender_id' => $smsconfig->senderid,
            'message' => $message,
            'mobile_no' => $mobile_no,
            'user_email' => $smsconfig->email
        );

        // use key 'http' even if you send the request to https://...
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    function bulksmsbd_bulk_sms($mobile_no, $message)
    {
        $smsconfig = SmsConfig::find(1);
        $url = "http://bulksmsbd.net/api/smsapi";

        $data = [
            "api_key" => $smsconfig->appkey,
            "senderid" => $smsconfig->senderid,
            "number" => $mobile_no,
            "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function smshistory()
    {
        $data['smshistories'] = SmsHistory::latest()->get();
        return view('mail_vendor.sms.history', $data);
    }


}
