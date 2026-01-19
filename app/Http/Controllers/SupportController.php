<?php

namespace App\Http\Controllers;

use App\Traits\SupportManager;

class SupportController extends Controller
{
    use SupportManager;

    public function __construct()
    {
        $this->layout = 'frontend';

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            if ($this->user) {
                $this->layout = 'master';
            }
            return $next($request);
        });

        $this->redirectLink = 'support.view';
        $this->userType = 'user';
        $this->column = 'user_id';
    }
}
