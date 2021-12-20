<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\group_workshop\workshops_count_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class workshops extends admin_controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        if (!check_permission($this->user_permissions,"admin/workshops","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }


        $this->data["workshops"] = workshops_count_m::get_top_workshops();


        return view("admin.subviews.workshops.show")->with($this->data);
    }


}
