<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;

use App\Models\Mail\DomainConfig;
use App\Http\Controllers\Controller;

class DomainConfigController extends Controller
{

    public function index()
    {
        $data['domainconfigs'] = DomainConfig::get();
        return view('mail_vendor.domainconfigs.index',$data);
    }

    public function create()
    {
        return view('mail_vendor.domainconfigs.create');
    }

    public function store(Request $request)
    {

        return redirect()->route('admin.domainconfig.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $data['domainconfig'] = DomainConfig::find($id);
        return view('mail_vendor.domainconfigs.edit',$data);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $domainconfig = DomainConfig::find($id);
        $domainconfig->delete();

        return redirect()->route('admin.domainconfig.index');
    }
}
