<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\Http\Controllers\is_admin_controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class uploader extends admin_controller
{

    public function __construct()
    {
        parent::__construct();

    }


    //uploader
    public function index()
    {
        if (!check_permission($this->user_permissions,"admin/uploader","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        return view("admin.subviews.uploader.upload")->with($this->data);
    }


    public function load_files(Request $request)
    {
        if (!check_permission($this->user_permissions,"admin/uploader","add_action"))
        {
            return  "<div class='alert alert-danger'>You can not access here</div>";
        }

        // for multiple files
        $uploaded = $this->cms_upload(
            $request,
            $user_id = $this->user_id,
            $file_name = "file",
            $folder = "/general_uploader",
            $width = 0, $height = 0);
        echo $uploaded[0];
    }


}
