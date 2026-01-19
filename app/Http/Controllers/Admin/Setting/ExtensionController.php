<?php

namespace App\Http\Controllers\Admin\Setting;


use Illuminate\Http\Request;
use App\Models\Setting\Extension;
use App\Http\Controllers\Controller;

class ExtensionController extends Controller
{
    public function index()
    {
        $extensions = Extension::searchable(['name'])->orderBy('name')->get();
        return view('admin.extension.index', compact('extensions'));
    }

    public function update(Request $request, $id)
    {
        $extension = Extension::findOrFail($id);
        $validationRule = [];
        foreach ($extension->shortcode as $key => $val) {
            $validationRule = array_merge($validationRule, [$key => 'required']);
        }
        $request->validate($validationRule);

        $shortcode = json_decode(json_encode($extension->shortcode), true);
        foreach ($shortcode as $key => $value) {
            $shortcode[$key]['value'] = $request->$key;
        }

        $extension->shortcode = $shortcode;
        $extension->save();
        $notify[] = ['success', $extension->name . ' updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Extension::changeStatus($id);
    }
}
