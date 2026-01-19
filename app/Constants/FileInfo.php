<?php

namespace App\Constants;

class FileInfo
{
    public function fileInfo(){
        $data['verify'] = [
            'path'      =>'assets/verify'
        ];
        $data['default'] = [
            'path'      => 'assets/images/default.png',
        ];
        $data['logoIcon'] = [
            'path'      => 'assets/images/logoIcon',
        ];
        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['extensions'] = [
            'path'      => 'assets/images/extensions',
            'size'      => '36x36',
        ];
        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];
        $data['userProfile'] = [
            'path'      =>'assets/images/user/profile',
            'size'      =>'300x300',
        ];
        $data['adminProfile'] = [
            'path'      =>'assets/admin/images/profile',
            'size'      =>'300x300',
        ];


        $data['product_image'] = [
            'path'      =>'assets/admin/images/products',
            'size'      =>'400x400',
        ];
        $data['property'] = [
            'path'      =>'assets/admin/images/property',
            'size'      =>'400x400',
        ];
        $data['service'] = [
            'path'      =>'assets/admin/images/service',
            'size'      =>'1440x300',
        ];
        $data['service_content'] = [
            'path'      =>'assets/admin/images/service',
            'size'      =>'400x400',
        ];


        return $data;
	}

}
