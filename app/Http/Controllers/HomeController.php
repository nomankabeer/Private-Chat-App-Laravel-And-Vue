<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Traits\AuthUser;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use AuthUser;
    public function __construct()
    {

    }

}
