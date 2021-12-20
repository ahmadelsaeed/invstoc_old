<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\langs_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class langs extends admin_controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        if (!check_permission($this->user_permissions,"admin/langs","show_action"))
        {
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["show_all_langs"] = langs_m::get_all_langs();
        return view("admin.subviews.langs.show",$this->data);
    }

    public function save_lang(Request $request, $lang_id = null)
    {

        if($lang_id==null){
            if (!check_permission($this->user_permissions,"admin/langs","add_action"))
            {
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if (!check_permission($this->user_permissions,"admin/langs","edit_action"))
            {
                Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }


        $this->data["lang_data"] = "";
        $lang_img_id = 0;

        if ($lang_id != null)
        {
            $lang_obj = langs_m::get_all_langs(" and lang.lang_id = $lang_id ");
            $this->data["lang_data"] = $lang_obj[0];
            $lang_img_id = $lang_obj[0]->lang_img_id;
        }

        if (isset($request) && count($request->all()) > 0)
        {
            $this->validate($request,[

                "lang_title" => "required|unique:langs,lang_title,".$lang_id.",lang_id,deleted_at,NULL"

            ]);

            $request["lang_title"] = trim(string_safe($request["lang_title"]));

            $request["lang_img_id"] = $this->general_save_img($request , $item_id=$lang_id, "lang_img_file",
                $new_title = $request["lang_img_filetitle"], $new_alt = $request["lang_img_filealt"],
                $upload_new_img_check = $request["lang_img_checkbox"], $upload_file_path = "/langs/".trim(string_safe($request["lang_title"])),
                $width = 0, $height = 0, $photo_id_for_edit = $lang_img_id);

            // update
            if ($lang_id != null)
            {

//                dump($request->all());
                $check = langs_m::find($lang_id)->update($request->all());

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
                $check = langs_m::create($request->all());

                if (is_object($check))
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";

                }
                else{
                    $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }

            return Redirect::to('admin/langs/save_lang/'.$lang_id)->with([
                "msg" => $this->data["success"]
            ])->send();

        }


        return view("admin.subviews.langs.save",$this->data);
    }

    public function delete_lang(Request $request){

        if (!check_permission($this->user_permissions,"admin/langs","delete_action"))
        {
            return json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
        }

        $this->general_remove_item($request,'App\models\langs_m');
    }

}
