<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\currency_rates_m;
use App\models\langs_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class currencies extends admin_controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        if (!check_permission($this->user_permissions,"admin/currencies","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["currencies"] = currency_rates_m::get_all_currencies();
        return view("admin.subviews.currencies.index",$this->data);
    }

    public function save_currency(Request $request, $id = null)
    {

        if($id==null){
            if (!check_permission($this->user_permissions,"admin/currencies","add_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if (!check_permission($this->user_permissions,"admin/currencies","edit_action"))
            {
                Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }


        $this->data["currency_data"] = "";
        $cur_img = 0;

        if ($id != null)
        {
            $currency_data = currency_rates_m::get_all_currencies(" where cur.id = $id ","yes");
            $this->data["currency_data"] = $currency_data;
            $cur_img = $currency_data->cur_img;
        }

        if (isset($request) && count($request->all()) > 0)
        {
            $this->validate($request,[

                "cur_to" => "required|unique:currency_rates,cur_to,".$id.",id"

            ]);

            $request["cur_to"] = trim(string_safe($request["cur_to"]));

            $request["cur_img"] = $this->general_save_img($request , $item_id=$id, "cur_img_file",
                $new_title = "", $new_alt = "",
                $upload_new_img_check = $request["cur_img_checkbox"], $upload_file_path = "/currencies/",
                $width = 24, $height = 24, $photo_id_for_edit = $cur_img);

            // update
            if ($id != null)
            {

//                dump($request->all());
                $check = currency_rates_m::find($id)->update($request->all());

                if ($check == true)
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Edit </div>";
                }
                else{
                    $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }
            else{

                // insert
                $check = currency_rates_m::create($request->all());

                if (is_object($check))
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";

                }
                else{
                    $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }

            return Redirect::to('admin/currencies/save_currency/'.$id)->with([
                "msg" => $this->data["success"]
            ])->send();

        }


        return view("admin.subviews.currencies.save",$this->data);
    }

    public function delete_currency(Request $request){

        if (!check_permission($this->user_permissions,"admin/currencies","delete_action"))
        {
            return json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
        }

        $this->general_remove_item($request,'App\models\currency_rates_m');
    }

}
