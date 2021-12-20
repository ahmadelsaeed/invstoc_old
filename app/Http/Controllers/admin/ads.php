<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\models\ads_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ads extends admin_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        if (!check_permission($this->user_permissions,"admin/ads","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["ads"] = ads_m::get_ads_img();
        return view("admin.subviews.ads.show",$this->data);
    }


    public function save_ad(Request $request , $id = null)
    {



        if($id==null){
            if (!check_permission($this->user_permissions,"admin/category","add_action"))
            {
                return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if (!check_permission($this->user_permissions,"admin/category","edit_action"))
            {
                return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }


        $this->data["ad_data"] = "";
        $ad_img = 0;

        if ($id != null)
        {
            $this->data["ad_data"] = ads_m::get_ads_img(" and ad.id = $id ");
            $this->data["ad_data"] = $this->data["ad_data"][0];
            $ad_img = $this->data["ad_data"]->ad_img;
        }

        if (isset($request) && count($request->all()) > 0)
        {

            $this->validate($request,[
                "ads_title" => "required|unique:ads,ads_title,".$id.",id,deleted_at,NULL"
            ]);

            $request["ad_img"] = $this->general_save_img($request , $item_id=$id, "ad_img_file",
                $new_title = $request["ad_img_filetitle"], $new_alt = $request["ad_img_filealt"],
                $upload_new_img_check = $request["ad_img_checkbox"], $upload_file_path = "/ads",
                $width = 0, $height = 0, $photo_id_for_edit = $ad_img);

            $request["ads_title"] = trim(string_safe($request["ads_title"]));

            // update
            if ($id != null)
            {

                $check = ads_m::find($id)->update($request->all());
                if ($check == true)
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Edit </div>";
                    Redirect::to('admin/ads/save_ad/'.$id)->with([
                        "msg" => "<div class='alert alert-success'> Data Successfully Edit </div>"
                    ])->send();
                }
                else{
                    $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }
            else{

                // insert
                $check = ads_m::create($request->all());
                if (is_object($check))
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";
                }
                else{
                    $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }

        }


        return view("admin.subviews.ads.save",$this->data);
    }

    public function remove_ads(Request $request){
        if (!check_permission($this->user_permissions,"admin/ads","delete_action"))
        {
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }

        $this->general_remove_item($request,'App\models\ads_m');
    }

}
