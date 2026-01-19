<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\UserLogin;
use Illuminate\Http\Request;
use App\Models\NotificationLog;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function loginHistory(Request $request)
    {
        $title = 'User Login History';
        $loginLogs = UserLogin::orderBy('id','desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('loginLogs','title'));
    }

    public function loginIpHistory($ip)
    {
        $title = 'Login by - ' . @$ip;
        $loginLogs = UserLogin::where('user_ip',$ip)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('loginLogs','ip','title'));
    }

    public function notificationHistory(Request $request){
        $logs = NotificationLog::orderBy('id','desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('logs'));
    }

    public function emailDetails($id){
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('email'));
    }
}
