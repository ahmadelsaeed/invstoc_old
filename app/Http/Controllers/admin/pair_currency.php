<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\currency_rates_m;
use App\models\langs_m;
use App\models\pair_currency_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class pair_currency extends admin_controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        if (!check_permission($this->user_permissions,"admin/pair_currency","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["pair_currencies"] = pair_currency_m::all();
        return view("admin.subviews.pair_currency.index",$this->data);
    }

    public function save_pair_currency(Request $request, $id = null)
    {

        if($id==null){
            if (!check_permission($this->user_permissions,"admin/pair_currency","add_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if (!check_permission($this->user_permissions,"admin/pair_currency","edit_action"))
            {
                Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }


        $pair_currency_data = "";
        $cur_img = 0;

        if ($id != null)
        {
            $pair_currency_data = pair_currency_m::findOrFail($id);
        }
        $this->data["pair_currency_data"] = $pair_currency_data;

        if ($request->method()=="POST")
        {
            $this->validate($request,[

                "pair_currency_name" => "required|unique:pair_currency,pair_currency_name,".$id.",pair_currency_id"

            ]);


            if ($id != null)
            {

                $check = pair_currency_m::find($id)->update($request->all());

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
                $check = pair_currency_m::create($request->all());
                $id=$check->pair_currency_id;
                if (is_object($check))
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";

                }
                else{
                    $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }

            return Redirect::to('admin/pair_currency/save_pair_currency/'.$id)->with([
                "msg" => $this->data["success"]
            ])->send();

        }


        return view("admin.subviews.pair_currency.save",$this->data);
    }

    public function delete_pair_currency(Request $request){

        if (!check_permission($this->user_permissions,"admin/pair_currency","delete_action"))
        {
            return json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
        }

        $this->general_remove_item($request,'App\models\pair_currency_m');
    }

}
