<?php


namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait FileUpload
{
    public function fileUpload($query, $old = null): string
    {
        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'pdf'];
        $ext = strtolower($query->getClientOriginalExtension());

        $ext_name = strtolower($query->getClientOriginalName());

        $explod_name = explode('.',$ext_name);

        if ($query->getSize() > 5100000) {
            abort('406', "max file size:5MB");
            return redirect()->back();
        }

        if (!in_array($ext, $allowExt)) {
            abort('406', "only allow : jpeg, png, jpg, gif, svg, pdf");
        }

        if ($old != null) {
            self::deleteImage($old);
        }

        $image_name = rand(1000,9999);
        $image_full_name = $explod_name[0]. '-'. $image_name . '.' . $ext;

        $upload_path = 'assets/documents/';

        $url = $image_full_name;

        $success = $query->move($upload_path, $image_full_name);

        if($success){
            return $url; // Just return url
        }else{
            return '';
        }


    }

    protected function deleteImage($path)
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
