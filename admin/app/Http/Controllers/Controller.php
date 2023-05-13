<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getPerPageValue(Request $request, string $section): int
    {
        $key = $section.'_per_page';
        $cookieValue = Session::get($key, Cookie::get($key));
        $perPage = (int) $request->get($key, $cookieValue);

        $options = config("constants.pagination.per_page_options");
        if (!$perPage || !in_array($perPage, $options, true)) {
            $perPage = (int) config("constants.pagination.per_page_default");
        }

        Cookie::make($key, $perPage, 5);
        Session::put($key, $perPage);

        return $perPage;
    }
}
