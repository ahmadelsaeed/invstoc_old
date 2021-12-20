<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\attachments_m;
use App\models\chat\chat_messages_m;
use App\models\langs_m;
use App\models\permissions\permission_pages_m;
use App\models\permissions\permissions_m;
use App\models\posts\posts_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class users extends admin_controller
{

    public function __construct(){
        parent::__construct();
    }

    public function get_all_admins()
    {

        if(!$this->check_user_permission("admin/admins","show_action")){
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["users"]=User::get_users(" 
            AND (
                u.user_type='admin' or u.user_type='dev'
            ) 
            
            "
        );

        return view("admin.subviews.users.show_admins",$this->data);
    }


    public function get_all_users()
    {

        if(!$this->check_user_permission("admin/users","show_action")){
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $get_orders_not_closed = posts_m::where("post_or_recommendation","recommendation")
            ->where("closed_price",0)->get();
        $get_orders_not_closed = collect($get_orders_not_closed)->groupBy("user_id")->all();

        $this->data["orders_not_closed"] = $get_orders_not_closed;

        $this->data["users"]=User::get_users(" 
            AND (
                u.user_type='user'
            ) 
            
            order by u.user_id desc
            
            "
        );

        $this->data["users"] = User::orderBy('created_at', 'desc')->paginate(10);

        return view("admin.subviews.users.show_users",$this->data);
    }

    public function get_all_messages($user_id) {
        if(!$this->check_user_permission("admin/users","show_action")){
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data['user'] = User::find($user_id);

        $this->data["chats"] = chat_messages_m::where(function ($q) use ($user_id) {
            $q->where('from_user_id', $user_id)->orWhere('to_user_id', $user_id);
        })->orderBy('created_at', 'dsec')->get();

        return view("admin.subviews.users.show_messages", $this->data);
    }

    public function search(Request $request) {

        if(!$this->check_user_permission("admin/users","show_action")){
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $get_orders_not_closed = posts_m::where("post_or_recommendation","recommendation")
            ->where("closed_price",0)->get();
        $get_orders_not_closed = collect($get_orders_not_closed)->groupBy("user_id")->all();

        $this->data["orders_not_closed"] = $get_orders_not_closed;


        $this->data["users"] = User::where(function ($q) use ($request) {
            $q->where('username', $request->q)
                ->orWhere('first_name', $request->q)
                ->orWhere('last_name', $request->q)
                ->orWhere('full_name', $request->q)
                ->orWhere('email', $request->q);
        })->orderBy('created_at', 'desc')->paginate(10);

        return view("admin.subviews.users.show_users",$this->data);

    }

    public function save_user(Request $request, $user_id = null)
    {

        if($user_id==null){
            if(!$this->check_user_permission("admin/admins","add_action")){
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if(!$this->check_user_permission("admin/admins","edit_action")){
                return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }

        $this->data["all_langs"]=langs_m::all();


        $this->data["user_data"] = "";
        $password_required = "required";
        $logo_id=0;

        if ($user_id != null)
        {
            $password_required = "";
            $this->data["user_data"] = User::get_users(" AND u.user_id=$user_id");

            if(!isset_and_array($this->data["user_data"])){
                return Redirect::to('admin/dashboard')->send();
            }
            $this->data["user_data"]=$this->data["user_data"][0];
            $logo_id=$this->data["user_data"]->logo_id;
            $this->data["user_data"]->user_img_file=attachments_m::find($logo_id);


        }

        if ($request->method()=="POST")
        {
            $this->validate($request,
                [
                    "password" => $password_required,
                    "email" => "required|email|unique:users,email,".$user_id.",user_id,deleted_at,NULL",
                    "username" => "required",
                    "full_name"=>"required",
                    "allowed_lang_ids.*" => "required"
                ]);



            if (isset($request["password"]) && !empty($request["password"]))
            {
                $request["password"] = bcrypt($request["password"]);
            }
            else{
                $request["password"] = $this->data["user_data"]->password;
            }

            $request["allowed_lang_ids"] = json_encode($request["allowed_lang_ids"]);

            $request["logo_id"] = $this->general_save_img(
                $request ,
                $item_id=$user_id,
                "user_img_file",
                $new_title = "",
                $new_alt = "",
                $upload_new_img_check = $request["user_img_checkbox"],
                $upload_file_path = "/admins",
                $width = 0,
                $height = 0,
                $photo_id_for_edit = $logo_id
            );

            // update
            if ($user_id != null)
            {
                $check = User::find($user_id)->update($request->all());

                if ($check == true)
                {
                    $this->data["msg"] = "<div class='alert alert-success'> Data Successfully Edit </div>";
                }
                else{
                    $this->data["msg"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }


            }
            else{
                $request["user_type"] = "admin";
                $request["user_active"] = "1";
                $request["user_can_login"] = "1";

                $check = User::create($request->all());

                if (is_object($check))
                {
                    $this->data["msg"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";


                }
                else{
                    $this->data["msg"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                }

                $user_id=$check->user_id;
            }

            return Redirect::to('admin/users/save/'.$user_id)->with([
                "msg"=>$this->data["msg"]
            ])->send();
        }


        return view("admin.subviews.users.save")->with($this->data);
    }

    public function assign_permission(Request $request,$user_id){

        if(!$this->check_user_permission("admin/admins","manage_permissions")){
            return Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $user_obj=User::where("user_id",$user_id)->get()->first();

        if(!is_object($user_obj)){
            return Redirect::to('admin/dashboard')->send();
        }

        $this->data["user_obj"]=$user_obj;

        //get all permission pages
        $all_permission_pages=permission_pages_m::where("sub_sys","admin")->get()->all();
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

            return Redirect::to('admin/users/assign_permission/'.$user_id)->send();
        }


        if($request->method()=="POST"){

            foreach($all_user_permissions as $user_per_key=>$user_per_val){
                $new_perms=$request->get("additional_perms_new".$user_per_val->per_id);
                permissions_m::where("per_id",$user_per_val->per_id)->update([
                    "additional_permissions"=>json_encode($new_perms)
                ]);
            }


            return Redirect::to('admin/users/assign_permission/'.$user_id)->with([
                "msg"=>"<div class='alert alert-success'>Update User Permissions</div>"
            ])->send();

        }


        return view("admin.subviews.users.user_permissions",$this->data);
    }


    public function remove_admin(Request $request){

        if(!$this->check_user_permission("admin/admins","delete_action")){
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }

        $this->general_remove_item($request,'App\User');

    }

    public function change_user_can_login(Request $request)
    {
        $model_name='App\User';
        $field_name="user_can_login";

        $output = array();
        $item_id = $request->get("item_id");

        if($model_name==""){
            $model_name = $request->get("table_name");
        }

        if($field_name==""){
            $field_name = $request->get("field_name");
        }

        $accept = $request->get("accept");
        $item_primary_col= $request->get("item_primary_col");
        $accepters_data= $request->get("acceptersdata");
        $accept_url= $request->get("accept_url");


        if ($item_id > 0)
        {

            $obj = $model_name::find($item_id);
            if ($accept == 1)
            {
                // user can login
                $this->_send_email_to_custom(
                    $emails = [$obj->email] ,
                    $data = " Your Account Has been approved , Now you can login !! " ,
                    $subject = "Forex Login Alert",
                    $sender = "official@invstoc.com"
                );
            }
            else{
                // user disallowed from login
                $this->_send_email_to_custom(
                    $emails = [$obj->email] ,
                    $data = " Your Account Has been Suspended from login!! " ,
                    $subject = "Forex Login Alert",
                    $sender = "official@invstoc.com"
                );
            }


            $return_statues=$obj->update(["$field_name"=>"$accept"]);
            $obj->update(["user_active"=>"$accept"]);


            $output["msg"]=generate_multi_accepters($accept_url,$obj,$item_primary_col,$field_name,$model_name,json_decode($accepters_data));
        }


        echo json_encode($output);
    }

    public function is_privet_account(Request $request)
    {
        $model_name='App\User';
        $field_name="is_privet_account";

        $output = array();
        $item_id = $request->get("item_id");

        if($model_name==""){
            $model_name = $request->get("table_name");
        }

        if($field_name==""){
            $field_name = $request->get("field_name");
        }

        $accept = $request->get("accept");
        $item_primary_col= $request->get("item_primary_col");
        $accepters_data= $request->get("acceptersdata");
        $accept_url= $request->get("accept_url");


        if ($item_id > 0)
        {

            $obj = $model_name::find($item_id);
            if ($accept == 1)
            {
                // user can login
                $this->_send_email_to_custom(
                    $emails = [$obj->email] ,
                    $data = " Your Account Has been approved to be Privet Account " ,
                    $subject = "Forex Account Alert",
                    $sender = "official@invstoc.com"
                );
            }
            else{
                // user disallowed from login
                $this->_send_email_to_custom(
                    $emails = [$obj->email] ,
                    $data = " Your Account Has been Removed from Privet Account !! " ,
                    $subject = "Forex Account Alert",
                    $sender = "official@invstoc.com"
                );
            }


            $return_statues=$obj->update(["$field_name"=>"$accept"]);


            $output["msg"]=generate_multi_accepters($accept_url,$obj,$item_primary_col,$field_name,$model_name,json_decode($accepters_data));
        }


        echo json_encode($output);
    }

    public function has_referrer_link(Request $request)
    {
        $model_name='App\User';
        $field_name="is_privet_account";

        $output = array();
        $item_id = $request->get("item_id");

        if($model_name==""){
            $model_name = $request->get("table_name");
        }

        if($field_name==""){
            $field_name = $request->get("field_name");
        }

        $accept = $request->get("accept");
        $item_primary_col= $request->get("item_primary_col");
        $accepters_data= $request->get("acceptersdata");
        $accept_url= $request->get("accept_url");


        if ($item_id > 0)
        {

            $obj = $model_name::find($item_id);
            if ($accept == 0)
            {

                $new_referrer_link = str_random(10).$item_id.str_random(3);

                $return_statues=$obj->update(
                    [
                        "referrer_link" => $new_referrer_link,
                    ]
                );

                $new_referrer_link = url("/referrer_link?ref=$new_referrer_link");

                $this->_send_email_to_custom(
                    $emails = [$obj->email],
                    $data =
                        [
                            "name"          => $obj->full_name,
                            "code"          => $obj->username,
                            "referrer_link" => $new_referrer_link,
                        ] ,
                    $subject = "New Referrer Link",
                    $sender = "info@invstoc.com" ,
                    $path_to_file = "",
                    $email_template ="email.receive_referrer_link"
                );

                $output["msg"] = "<div class='alert alert-success'>$new_referrer_link</div>";

            }
            else{

                $return_statues=$obj->update(
                    [
                        "request_referrer_link" => 0,
                    ]
                );

                $this->_send_email_to_custom(
                    $emails = [$obj->email],
                    $data =
                        [
                            "name"          => $obj->full_name,
                            "code"          => $obj->username,
                        ] ,
                    $subject = "Referrer Link is cancelled",
                    $sender = "info@invstoc.com" ,
                    $path_to_file = "",
                    $email_template ="email.cancel_referrer_link"
                );

                $output["msg"] = "<div class='alert alert-danger'>Request is cancelled !</div>";
            }


        }
        else{
            $output["msg"] = "<div class='alert alert-danger'>Invalid Request !</div>";
        }


        echo json_encode($output);
    }





}
