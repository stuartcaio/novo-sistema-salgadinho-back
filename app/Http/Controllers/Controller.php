<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $adminUsers = [
        ["email" => "caiostuart01@gmail.com", "password" => "stuartcaio01"],
        ["email" => "caiostuart02@gmail.com", "password" => "stuartcaio02"]
    ];
}