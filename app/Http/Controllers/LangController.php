<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class LangController extends Controller
{
    public function index($lang): JsonResponse
    {
        app()->setLocale($lang);
        session()->put('lang', $lang);
        return response()->json(['status' => 'success', 'lang' => session()->get('lang')]);
    }
}
