<?php

namespace App\Traits;
use App\Models\DomainConfig;
use Illuminate\Support\Facades\Log;
trait MailConfigTrait{
    public function mailConfig($domain)
    {
        try {
            $con = DomainConfig::where('id', $domain)->first();
            $email_data = json_decode($con->config, true);

            return [
                'driver'     => $email_data['mail'],
                'host'       => $email_data['host'],
                'port'       => $email_data['port'],
                'encryption' => $email_data['encryption'],
                'username'   => $email_data['user_name'],
                'password'   => $email_data['password'],
                'from'       => [
                    'address' => $email_data['address'],
                    'name'    => $email_data['name'],
                ]
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }
}
