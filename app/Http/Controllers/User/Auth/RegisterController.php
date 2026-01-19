<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Constants\Status;
use App\Models\UserLogin;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('registration.status');
    }


    public function showRegistrationForm(){
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('user.auth.register',compact('mobileCode','countries'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        $this->validator($request->all())->validate();

        if(preg_match("/[^a-z0-9_]/", trim($request->username))){
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }


        $exist = User::where('mobile',$request->mobile_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    protected function validator(array $data)
    {
        $general = gs();
        $passwordValidation = Password::min(6);
        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'type' => 'required|in:buyer,supplier',
            'email' => 'required|string|email|unique:users',
            'mobile' => 'required|regex:/^([0-9]*)$/',
            'password' => ['required','confirmed',$passwordValidation],
            'username' => 'required|unique:users|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;

    }

    protected function create(array $data)
    {
        //User Create
        $user = new User();
        $user->email = strtolower($data['email']);
        $user->password = Hash::make($data['password']);
        $user->username = $data['username'];
        $user->country_code = $data['country_code'];
        $user->mobile = $data['mobile_code'].$data['mobile'];
        $user->type = $data['type'];
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $user->ev = gs()->ev ? Status::NO : Status::YES;
        $user->sv = gs()->sv ? Status::NO : Status::YES;
        $user->save();

        if($user->type == 'buyer'){
            $urlPath = 'admin.buyers.detail';
        }else{
            $urlPath = 'admin.suppliers.detail';
        }

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url =  urlPath($urlPath,$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }


    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->exists();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
        }
        return response($exist);
    }
}
