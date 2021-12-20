<?php

namespace App\Http\Controllers\dev;

use App\Http\Controllers\dev_controller;
use App\models\permissions\permission_pages_m;
use App\models\permissions\permissions_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;


class permissions extends dev_controller
{

    public function show_all_permissions_pages()
    {

        $this->data["all_permissions_pages"]=permission_pages_m::all();

        return view("dev.subviews.permissions.permissions_pages.show",$this->data);
    }

    public function save_permission_page(Request $request, $permission_page_id = null)
    {

        $permission_page_data= "";

        if ($permission_page_id != null)
        {
            $permission_page_data = permission_pages_m::findOrFail($permission_page_id);
        }

        $this->data["permission_page_data"]=$permission_page_data;

        if ($request->method()=="POST")
        {

            $this->validate($request,
                [
                    "page_name" => "required|unique:permission_pages,page_name,$permission_page_id,per_page_id",
                    "sub_sys" => "required"

                ]);


            $request["all_additional_permissions"] = array_diff($request["all_additional_permissions"],[""]);
            $request["all_additional_permissions"]=json_encode($request["all_additional_permissions"]);



            // update
            if ($permission_page_id != null)
            {
                $check = $permission_page_data->update($request->all());

                if ($check == true)
                {
                    $this->data["msg"] = "<div class='alert alert-success'> Data Successfully Edit </div>";
                }
                else{
                    $this->data["msg"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

            }
            else{
                $request["show_in_admin_panel"]="1";

                $check = permission_pages_m::create($request->all());

                if (is_object($check))
                {
                    $this->data["msg"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";
                }
                else{
                    $this->data["msg"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

                $permission_page_id=$check->per_page_id;
            }

            return Redirect::to('dev/permissions/permissions_pages/save/'.$permission_page_id)->with([
                "msg"=>$this->data["msg"]
            ])->send();
        }


        return view("dev.subviews.permissions.permissions_pages.save")->with($this->data);
    }

    public function delete_permission_page(Request $request){
        $this->general_remove_item($request);
    }

    public function assign_permission_for_this_user(Request $request){

        $user_id=1;
        $user_obj=User::where("user_id",$user_id)->get()->first();

        $this->data["user_obj"]=$user_obj;

        //get all permission pages
        $all_permission_pages=permission_pages_m::get()->all();
        $all_permission_pages=array_combine(convert_inside_obj_to_arr($all_permission_pages,"per_page_id"),$all_permission_pages);

        //get all user permissions
        $all_user_permissions=permissions_m::where("user_id",$user_id)->get()->all();
        $all_user_permissions=array_combine(convert_inside_obj_to_arr($all_user_permissions,"per_page_id"),$all_user_permissions);

        $this->data["all_permission_pages"]=$all_permission_pages;
        $this->data["all_user_permissions"]=$all_user_permissions;


        foreach($all_user_permissions as $user_per_key=>$user_per_val){
            unset($all_permission_pages[$user_per_key]);
        }


        if(isset_and_array($all_permission_pages)){
            foreach($all_permission_pages as $page_key=>$page_val){
                permissions_m::create([
                    "user_id"=>"$user_id",
                    "per_page_id"=>"$page_key"
                ]);
            }

            return Redirect::to('dev/permissions/assign_permission_for_this_user')->send();
        }


        if($request->method()=="POST"){

            foreach($all_user_permissions as $user_per_key=>$user_per_val){
                $new_perms=$request->get("additional_perms_new".$user_per_val->per_id);
                permissions_m::where("per_id",$user_per_val->per_id)->update([
                    "additional_permissions"=>json_encode($new_perms)
                ]);
            }


            return Redirect::to('dev/permissions/assign_permission_for_this_user')->with([
                "msg"=>"<div class='alert alert-success'>Update User Permissions</div>"
            ])->send();

        }



        return view("dev.subviews.permissions.user_permissions",$this->data);
    }


}
