<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;

use App\Models\Mail\DomainConfig;
use App\Models\Mail\SmsConfig;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function sms()
    {
        $data['providers'] = SmsConfig::get();
        return view('mail_vendor.setting.sms.index', $data);
    }

    public function email()
    {
        $data['providers'] = DomainConfig::get();
        return view('mail_vendor.setting.email.index', $data);
    }

    public function createEmail()
    {
        return view('mail_vendor.setting.email.create');
    }

    public function storeEmail(Request $request)
    {
        $request->validate([
            "title"     => "required|string|max:191",
            "domain"    => "required|string|max:191",
            "mail"      => "required|string|max:191",
            "host"      => "required|string|max:191",
            "port"      => "required|string|max:191",
            "user_name"       => "required|string|max:191",
            "password"        => "required|string|max:191",
            "encryption"      => "required|string|max:191",
            "address"         => "required|string|max:191",
            "status"          => "required"
        ]);

        $config = $request->except(['_token', 'title', 'domain', 'status']);
        $domain_config = new DomainConfig();
        $domain_config->title = $request->title;
        $domain_config->domain = $request->domain;
        $domain_config->config = json_encode($config);
        $domain_config->status = $request->status;
        $domain_config->save();

        return redirect()->route('admin.setting.email')->with(['success' => __('Setting update successful')]);
    }


    public function editSms($id){
        $sms = SmsConfig::find($id);
        $data['sms'] = $sms;

        return view('mail_vendor.setting.sms.edit', $data);
    }

    public function smsUpdate(Request $request, $id){

        $request->validate([
            "name"      =>  "required|string|max:191",
            "status"    =>  "required"
        ]);

        $config = [];

        foreach ($request->except(['_token', 'name', 'status']) as $key => $val) {
            $config[$key] = $val;
        }

        $smsConfig = SmsConfig::find($id);
        $smsConfig->name = $request->name;
        $smsConfig->config = json_encode($config);
        $smsConfig->status = $request->status;
        $smsConfig->save();

        return redirect()->route('admin.setting.sms')->with(['success' => __('Setting update successful')]);
    }

    public function editEmail($id){
        $email = DomainConfig::find($id);
        $data['email'] = $email;

        return view('mail_vendor.setting.email.edit', $data);
    }

    public function emailUpdate(Request $request, $id){

        $request->validate([
            "title"   =>  "required|string|max:191",
            "status"  =>  "required"
        ]);

        $config = [];

        foreach ($request->except(['_token', 'title', 'status']) as $key => $val) {
            $config[$key] = $val;
        }

        $emailConfig = DomainConfig::find($id);
        $emailConfig->title = $request->title;
        $emailConfig->config = json_encode($config);
        $emailConfig->status = $request->status;
        $emailConfig->save();

        return redirect()->route('admin.setting.email')->with(['success' => __('Setting update successful')]);
    }
}
