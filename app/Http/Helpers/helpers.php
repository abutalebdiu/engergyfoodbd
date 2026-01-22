<?php

use Carbon\Carbon;
use App\Lib\Captcha;
use App\Models\Unit;
use App\Notify\Notify;


use App\Lib\ClientInfo;
use App\Lib\FileManager;
use App\Constants\Status;
use Illuminate\Support\Str;
use App\Models\Setting\Frontend;
use App\Models\Setting\Extension;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting\GeneralSetting;
use Illuminate\Support\Facades\Session;

function getIpInfo()
{
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}

function osBrowser()
{
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}

function getRealIP()
{
    $ip = $_SERVER["REMOTE_ADDR"];
    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}

function gs($key = null)
{
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }

    $general = GeneralSetting::first();

    if ($key) {
        return @$general->$key;
    }

    return $general;
}

function appendQuery($key, $value)
{
    return request()->fullUrlWithQuery([$key => $value]);
}

function siteFavicon()
{
    return getImage(getFilePath('logoIcon') . '/favicon.png');
}

function siteLogo($type = null)
{
    $name = $type ? "/logo_$type.png" : '/logo.png';
    return getImage(getFilePath('logoIcon') . $name);
}

function getImage($image, $size = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    return asset('assets/images/default.png');
}

function getFilePath($key)
{
    return fileManager()->$key()->path;
}

function fileManager()
{
    return new FileManager();
}

function getFileSize($key)
{
    return fileManager()->$key()->size;
}

function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false)
{

    if ($singleQuery) {
        $content = Frontend::where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {

        $article = Frontend::when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}

function verifyCaptcha()
{
    return Captcha::verify();
}

function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true)
{
    $general = gs();
    $globalShortCodes = [
        'site_name' => $general->site_name,
        'site_currency' => $general->cur_text,
        'currency_symbol' => $general->cur_sym,
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);

    $notify = new Notify($sendVia);
    $notify->templateName = $templateName;
    $notify->shortCodes = $shortCodes;
    $notify->user = $user;
    $notify->createLog = $createLog;
    $notify->userColumn = isset($user->id) ? $user->getForeignKey() : 'user_id';
    $notify->send();
}

function verificationCode($length)
{
    if ($length == 0) {
        return 0;
    }

    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function showEmailAddress($email)
{
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);
    return $path;
}

function menuActive($routeName)
{
    if ($routeName) {
        $class = 'mm-active';
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($routeName)) {
                    return $class;
                }
            }
        } else {
            if (request()->routeIs($routeName)) {
                return $class;
            }
        }
    }
}

function getPaginate($paginate = 20)
{
    return $paginate;
}

function paginateLinks($data)
{
    return $data->appends(request()->all())->links();
}

function showDateTime($date, $format = 'Y-m-d h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}
function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
{
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    return $printAmount;
}

function fileUploader($file, $location, $size = null, $old = null, $thumb = null)
{

    $fileManager = new FileManager($file);
    $fileManager->path = $location;
    $fileManager->size = $size;
    $fileManager->old = $old;
    $fileManager->thumb = $thumb;
    $fileManager->upload();
    return $fileManager->filename;
}

function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}

function keyToTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function getPageSections($arr = false)
{
    $jsonUrl = resource_path('views/') . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}
function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}
function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#003')
{
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function loadReCaptcha()
{
    return Captcha::reCaptcha();
}

function showMobileNumber($number)
{
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function loadExtension($key)
{
    $extension = Extension::where('act', $key)->where('status', Status::ENABLE)->first();
    return $extension ? $extension->generateScript() : '';
}


if (!function_exists('statusButton')) {
    function statusButton($status)
    {
        switch ($status) {
            case 'Active':
                $color = 'success';
                break;
            case 'Paid':
                $color = 'success';
                break;
            case 'Approved':
                $color = 'success';
                break;
            case 'Settled':
                $color = 'success';
                break;
            case 'Unpaid':
                $color = 'danger';
                break;
            case 'Inactive':
                $color = 'danger';
                break;
            case 'Pending':
                $color = 'warning';
                break;
            case 'Deleted':
                $color = 'danger';
                break;
            default:
                $color = 'warning';
        }


        return $color;
    }
}

function numberToWord($num = '')
{
    $num    = (string) ((int) $num);

    if ((int) ($num) && ctype_digit($num)) {
        $words  = array();

        $num    = str_replace(array(',', ' '), '', trim($num));

        $list1  = array(
            '',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        );

        $list2  = array(
            '',
            'ten',
            'twenty',
            'thirty',
            'forty',
            'fifty',
            'sixty',
            'seventy',
            'eighty',
            'ninety',
            'hundred'
        );

        $list3  = array(
            '',
            'thousand',
            'million',
            'billion',
            'trillion',
            'quadrillion',
            'quintillion',
            'sextillion',
            'septillion',
            'octillion',
            'nonillion',
            'decillion',
            'undecillion',
            'duodecillion',
            'tredecillion',
            'quattuordecillion',
            'quindecillion',
            'sexdecillion',
            'septendecillion',
            'octodecillion',
            'novemdecillion',
            'vigintillion'
        );

        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num    = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds   = (int) ($num_part / 100);
            $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
            $tens       = (int) ($num_part % 100);
            $singles    = '';

            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int) ($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_part % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
        }
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words  = implode(', ', $words);

        $words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
        if ($commas) {
            $words  = str_replace(',', ' and', $words);
        }

        return $words;
    } else if (!((int) $num)) {
        return 'Zero';
    }
    return '';
}


function numberToBanglaWord($num = '')
{
    // Split the number into the integer and decimal parts
    $parts = explode('.', (string)($num));
    $integerPart = (string)((int)$parts[0]);
    $decimalPart = isset($parts[1]) ? substr($parts[1], 0, 2) : ''; // Limit to two decimal places

    if ((int)($integerPart) && ctype_digit($integerPart)) {
        $words = array();

        $integerPart = str_replace(array(',', ' '), '', trim($integerPart));

        $list1 = array(
            '',
            'এক',      // 1
            'দুই',     // 2
            'তিন',     // 3
            'চার',     // 4
            'পাঁচ',    // 5
            'ছয়',     // 6
            'সাত',     // 7
            'আট',      // 8
            'নয়',     // 9
            'দশ',      // 10
            'এগারো',  // 11
            'বারো',    // 12
            'তেরো',    // 13
            'চৌদ্দ',   // 14
            'পনেরো',   // 15
            'ষোল',     // 16
            'সতেরো',   // 17
            'আঠারো',   // 18
            'উনিশ',    // 19
            'কুড়ি',    // 20
            'একুশ',    // 21
            'বাইশ',    // 22
            'তেইশ',    // 23
            'চব্বিশ',  // 24
            'পঁচিশ',   // 25
            'ছাব্বিশ', // 26
            'সাতাশ',   // 27
            'আটাশ',    // 28
            'ঊনত্রিশ', // 29
            'তিরিশ',   // 30
            'একত্রিশ', // 31
            'বত্রিশ',  // 32
            'তেত্রিশ', // 33
            'চৌত্রিশ', // 34
            'পঁইত্রিশ', // 35
            'ছত্রিশ',  // 36
            'সাইত্রিশ', // 37
            'আটত্রিশ', // 38
            'ঊনচল্লিশ', // 39
            'চল্লিশ',  // 40
            'একচল্লিশ', // 41
            'বিয়াল্লিশ', // 42
            'তেতাল্লিশ', // 43
            'চুয়াল্লিশ', // 44
            'পঁয়তাল্লিশ', // 45
            'ছেচল্লিশ', // 46
            'সাতচল্লিশ', // 47
            'আটচল্লিশ', // 48
            'ঊনপঞ্চাশ', // 49
            'পঞ্চাশ',  // 50
            'একান্ন',  // 51
            'বাহান্ন', // 52
            'তিপ্পান্ন', // 53
            'চুয়ান্ন', // 54
            'পঞ্চান্ন', // 55
            'ছাপ্পান্ন', // 56
            'সাতান্ন', // 57
            'আটান্ন',  // 58
            'ঊনষাট',   // 59
            'ষাট',      // 60
            'একষট্টি',  // 61
            'বাষট্টি',  // 62
            'তেষট্টি',  // 63
            'চৌষট্টি',  // 64
            'পঁয়ষট্টি', // 65
            'ছেষট্টি',  // 66
            'সাতষট্টি', // 67
            'আটষট্টি',  // 68
            'ঊনসত্তর', // 69
            'সত্তর',    // 70
            'একাত্তর',  // 71
            'বাহাত্তর', // 72
            'তিয়াত্তর', // 73
            'চুয়াত্তর', // 74
            'পঁচাত্তর', // 75
            'ছিয়াত্তর', // 76
            'সাতাত্তর', // 77
            'আটাত্তর',  // 78
            'ঊনআশি',   // 79
            'আশি',      // 80
            'একাশি',    // 81
            'বিরাশি',   // 82
            'তিরাশি',   // 83
            'চুরাশি',   // 84
            'পঁচাশি',   // 85
            'ছিয়াশি',  // 86
            'সাতাশি',   // 87
            'আটাশি',    // 88
            'ঊননব্বই',  // 89
            'নব্বই',    // 90
            'একানব্বই', // 91
            'বিরানব্বই', // 92
            'তিরানব্বই', // 93
            'চুরানব্বই', // 94
            'পঁচানব্বই', // 95
            'ছিয়ানব্বই', // 96
            'সাতানব্বই', // 97
            'আটানব্বই', // 98
            'নিরানব্বই', // 99
            'একশ'       // 100
        );

        // Bangla words for tens multiples
        $list2 = array(
            '',
            'দশ', // 10
            'কুড়ি', // 20
            'তিরিশ', // 30
            'চল্লিশ', // 40
            'পঞ্চাশ', // 50
            'ষাট', // 60
            'সত্তর', // 70
            'আশি', // 80
            'নব্বই' // 90
        );

        // Bangla words for large number groups (thousands, millions, etc.)
        $list3 = array(
            '',
            'হাজার', // Thousand
            'লক্ষ', // Lakh
            'কোটি', // Crore
            'শত কোটি', // Billion
            'ট্রিলিয়ন' // Trillion
        );

        $num_length = strlen($integerPart);
        $levels = (int)(($num_length + 2) / 3);
        $max_length = $levels * 3;
        $integerPart = substr('00' . $integerPart, -$max_length);
        $num_levels = str_split($integerPart, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds = (int)($num_part / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' শত ' : ''); // "শত" means hundred
            $tens = (int)($num_part % 100);
            $singles = '';

            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int)($num_part % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int)($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
        }

        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words = implode(', ', $words);

        $words = trim(str_replace(' ,', ',', $words), ', ');
        if ($commas) {
            $words = str_replace(',', ' এবং', $words); // "এবং" means "and"
        }

        // Convert the decimal part (if present)
        $decimalWord = '';
        if ($decimalPart !== '') {
            $decimalWord = numberToBanglaWord($decimalPart); // Recursively convert decimal part
            $decimalWord = ' ' . $decimalWord . ' পয়সা'; // "পয়সা" means cent
        }

        return $words . $decimalWord; // "টাকা" means Taka
    } else if (!((int)$num)) {
        return 'শূন্য'; // "শূন্য" means "zero"
    }
    return '';
}



if (!function_exists('calculateCommission')) {

    function calculateCommission($product_id, $user_id)
    {
        $product = \App\Models\Product\Product::with('productCommission')
            ->find($product_id);

        if (!$product) {

            return throw new \Exception("Product not found");
        }

        if (!$product->productCommission) {
            return throw new \Exception("Commission not found for the product");
        }

        $commission = $product->productCommission
            ->where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->first();

        if ($commission == null) {
            return 0;
        }

        if ($commission->type == 'Percentage') {
            $calculatedCommission = $commission->price * ($commission->amount / 100);
        } else {
            $calculatedCommission = $commission->amount;
        }

        return $calculatedCommission > 0 ? $calculatedCommission : 0;
    }
}


if (!function_exists('calculateProductPrice')) {

    function calculateProductPrice($product_id, $user_id)
    {
        $product = \App\Models\Product\Product::with('productCommission')
            ->find($product_id);

        if (!$product) {

            return throw new \Exception("Product not found");
        }

        if (!$product->productCommission) {
            return  $product->sale_price;
        } else {
            $refprice = $product->productCommission->where('product_id', $product_id)->where('user_id', $user_id)->first();
            return  $refprice->price;
        }
    }
}




if (!function_exists('storeCommission')) {
    function storeCommission($model, $price, $field = 'commission_amount')
    {
        try {
            $model->update([$field => $price]);
        } catch (Exception $e) {
            return $e;
        }
    }
}

function bn2en($number)
{
    $bn = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
    $en = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    $converted = str_replace($bn, $en, $number);

    return $converted;
}


function en2bn($number)
{
    $bn = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
    $en = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    return str_replace($en, $bn, $number);
}


if (!function_exists('convertUnitQty')) {
    function convertUnitQty($unitId, $qty)
    {
        $unit = Unit::where('id', $unitId)
            ->where('status', 'Active')
            ->first();

        if (!$unit || $unit->value <= 0) {
            return $qty;
        }

        if (!$unit->base_unit) {
            return $qty;
        }

        return $qty * $unit->value;
    }
}



if (!function_exists('entry_info')) {
    function entry_info($item, $dateFormat = 'd-m-Y h:i A')
    {
        $lang = app()->getLocale();

        $name = data_get($item, 'entryuser.name', '');

        $date = data_get($item, 'updated_at');

        $formattedDate = $date
            ? ($lang == 'bn'
                ? en2bn(\Carbon\Carbon::parse($date)->format($dateFormat))
                : \Carbon\Carbon::parse($date)->format($dateFormat))
            : '';

        $output = trim($name);

        if ($formattedDate) {
            $output .= '<br>' . $formattedDate;
        }

        return $output;
    }
}