<?php

namespace App\Services;

use App\Models\Mail\SmsConfig;
use App\Models\Mail\History;
use App\Models\Mail\ContactPerson;
use Exception;
use http\Message;

class SmsService
{
    public function sendMessage($code, $phones, $message)
    {
        if ($code === "bd_bulk_sms") {
            return $this->bulkBdApi($code, $phones, $message);
        }
    }

    protected function configuration($code)
    {
        try {
            $sms_config = SmsConfig::where('code', $code)->first();
            $sms_decode = json_decode($sms_config->config, true);
            return $sms_decode;
        } catch (Exception $e) {
            return null;
        }
    }

    protected function bulkBdApi($code, $phones, $message)
    {
        $config = $this->configuration($code);
        $url = "http://bulksmsbd.net/api/smsapi";

        $data = [
            "api_key" => $config['api_key'],
            "senderid" => $config['sender_id'],
            "number" => $phones,
            "message" => $message,
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        try {
            $responseArray = json_decode($response, true);

            if ($responseArray && $responseArray['response_code'] === '202') {
                //                $messageLog = new MessageLog();
//                $messageLog->storeHistory($user->id, $message);
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function historySave($request, $phone_lists = null)
    {
        try {
            $history = new History();

            if ($request->input('category_id')) {
                $history->group_id = $request->category_id;
            }

            $history->message = json_encode($request->message);
            $history->phone = json_encode($phone_lists);
            $history->code = $request->provider;
            $history->template_code = $request->template;
            $history->save();

            return $history;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPhones($group_id)
    {
        $phones = [];
        $contacts = ContactPerson::where('category_id', $group_id)->get();

        foreach ($contacts as $contact) {
            $phones[] = $contact->phone;
        }

        return $phones;

    }


}
