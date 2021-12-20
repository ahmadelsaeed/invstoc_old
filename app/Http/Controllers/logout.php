<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class logout extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        \Auth::logout();
        return Redirect::to('/')->send();
    }

}
