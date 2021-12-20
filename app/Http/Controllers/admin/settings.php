<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\models\settings_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class settings extends admin_controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if (!check_permission($this->user_permissions,"admin/settings","add_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["setting"] = settings_m::findOrFail(1);

        return view("admin.subviews.settings.show")->with($this->data);
    }

}
