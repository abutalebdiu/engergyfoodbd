<?php

namespace App\Traits;
use Illuminate\Support\Facades\Log;
use App\Models\Mail\Template;

trait SmsTrait{
    public function templateConfig($code, $shortcodes = null)
    {
        try {
            $template = Template::where('status', true)->where('code', $code)->first();
            if ($template) {
                $find = [];
                $replace = [];

                foreach ($shortcodes as $key => $value) {
                    $find[] = '{{' . $key . '}}';
                    $replace[] = $value;
                }

                $details = [
                    'title'             => str_replace($find, $replace, $template->title),
                    'subject'           => str_replace($find, $replace, $template->subject),
                    'message_body'      => str_replace($find, $replace, $template->message_body)
                ];

                return $details;
            }


        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }

}
