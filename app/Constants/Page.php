<?php

namespace App\Constants;

class Page
{
    const PAGES = [
        "marketing"    => "Marketing",
        "finance"      => "Finance",
        "evaluation"   => "Evaluation",
        "investment"   => "Investment",
        "auctions"     => "Auctions",
    ];

    public static function pageTypes($selected = null)
    {
        $html = '';
        foreach (self::PAGES as $key => $page) {
            $selectedAttr = ($selected == $key) ? 'selected' : '';
            $html .= '<option ' . $selectedAttr . ' value="' . $key . '">' . trans($page) . '</option>';
        }

        return $html;
    }

}
