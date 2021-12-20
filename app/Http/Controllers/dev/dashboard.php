<?php

namespace App\Http\Controllers\dev;

use App\Http\Controllers\dev_controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class dashboard extends dev_controller
{

    public function index()
    {
        return "index";
    }
    

}
