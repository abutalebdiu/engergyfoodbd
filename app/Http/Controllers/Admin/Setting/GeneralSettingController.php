<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Constants\Status;
use Illuminate\Http\Request;


use App\Rules\FileTypeValidate;
use App\Models\Setting\Frontend;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Models\Setting\GeneralSetting;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Drivers\Gd\Driver;

class GeneralSettingController extends Controller
{
    public function index()
    {
        Gate::authorize('admin.setting.general');
        $timezones = json_decode(file_get_contents(resource_path('views/admin/partials/timezone.json')));
        $currentTimezone = array_search(config('app.timezone'), $timezones);
        return view('admin.setting.general', compact('timezones', 'currentTimezone'));
    }

    public function update(Request $request)
    {
        Gate::authorize('admin.setting.general.edit');

        $request->validate([
            'site_name' => 'required|string|max:40',
            'cur_text' => 'required|string|max:40',
            'cur_sym' => 'required|string|max:40'
        ]);

        $general = gs();
        $general->site_name = $request->site_name;
        $general->address = $request->address;
        $general->phone = $request->phone;
        $general->mobile = $request->mobile;
        $general->cur_text = $request->cur_text;
        $general->cur_sym = $request->cur_sym;
        $general->save();


        $notify[] = ['success', 'General setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function systemConfiguration()
    {
        return view('admin.setting.configuration');
    }

    public function systemConfigurationSubmit(Request $request)
    {
        $general = gs();
        $general->ev = $request->ev ? Status::ENABLE : Status::DISABLE;
        $general->en = $request->en ? Status::ENABLE : Status::DISABLE;
        $general->sv = $request->sv ? Status::ENABLE : Status::DISABLE;
        $general->sn = $request->sn ? Status::ENABLE : Status::DISABLE;
        $general->force_ssl = $request->force_ssl ? Status::ENABLE : Status::DISABLE;
        $general->secure_password = $request->secure_password ? Status::ENABLE : Status::DISABLE;
        $general->registration = $request->registration ? Status::ENABLE : Status::DISABLE;
        $general->agree = $request->agree ? Status::ENABLE : Status::DISABLE;
        $general->multi_language = $request->multi_language ? Status::ENABLE : Status::DISABLE;
        $general->save();
        $notify[] = ['success', 'System configuration updated successfully'];
        return back()->withNotify($notify);
    }

    public function logoIcon()
    {
        Gate::authorize('admin.setting.logo');
        return view('admin.setting.logo_icon');
    }

    public function logoIconUpdate(Request $request)
    {
        Gate::authorize('admin.setting.logo');
        $request->validate([
            'logo' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon' => ['image', new FileTypeValidate(['png'])],
        ]);

        if ($request->hasFile('logo_dark')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $manager = new ImageManager(new Driver());
                $manager->read($request->logo_dark)->save($path . '/logo_dark.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('logo')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $manager = new ImageManager(new Driver());
                $manager->read($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = getFilePath('logoIcon');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', getFileSize('favicon'));
                $manager = new ImageManager(new Driver());
                $manager->read($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the favicon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon updated successfully'];
        return back()->withNotify($notify);
    }

    public function customCss()
    {
        $file = 'assets/web/css/custom.css';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.custom_css', compact('fileContent'));
    }

    public function customCssSubmit(Request $request)
    {
        $file = 'assets/web/css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->css);
        $notify[] = ['success', 'CSS updated successfully'];
        return back()->withNotify($notify);
    }

    public function maintenanceMode()
    {
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        return view('admin.setting.maintenance', compact('maintenance'));
    }

    public function maintenanceModeSubmit(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);
        $general = GeneralSetting::first();
        $general->maintenance_mode = $request->status ? Status::ENABLE : Status::DISABLE;
        $general->save();

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        $maintenance->data_values = [
            'description' => $request->description,
        ];
        $maintenance->save();

        $notify[] = ['success', 'Maintenance mode updated successfully'];
        return back()->withNotify($notify);
    }

    public function cookie()
    {
        $cookie = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        return view('admin.setting.cookie', compact('cookie'));
    }

    public function cookieSubmit(Request $request)
    {
        $request->validate([
            'short_desc' => 'required|string|max:255',
            'description' => 'required',
        ]);
        $cookie = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        $cookie->data_values = [
            'short_desc' => $request->short_desc,
            'description' => $request->description,
            'status' => $request->status ? Status::ENABLE : Status::DISABLE,
        ];
        $cookie->save();
        $notify[] = ['success', 'Cookie policy updated successfully'];
        return back()->withNotify($notify);
    }
}
