<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\models\email_settings_m;
use App\Http\Controllers\dashbaord_controller;
use App\models\subscribe_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class subscribe extends admin_controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        if (!check_permission($this->user_permissions,"admin/subscribe","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["emails"] = "";
        $this->data["emails_json"] = [];

        $this->data["email_settings"] = "";

        $this->data["email_send_count"] = "";
        $this->data["email_seen_count"] = "";

        $this->data["email_send_count"] = count(subscribe_m::where("message_send",1)->get()->all());
        $this->data["email_seen_count"] = count(subscribe_m::where("message_viewed",1)->get()->all());

        $this->data["emails"] = subscribe_m::all();
        $this->data["email_settings"] = email_settings_m::find(1);

        foreach($this->data["emails"] as $key => $email)
        {
            $temp_arr = [];
            $temp_arr[] = $key;
            $temp_arr[] = $email->email;
            $temp_arr[] = "$email->created_at";
            $temp_arr[] = '<a href=\'#\' class="show_last_email" data-last_email_msg="'.htmlentities(json_encode($email->last_message),ENT_QUOTES,"UTF-8").'" ><span class="label label-primary">Show Message <i class="fa fa-send-o"></i></span></a>';
            if ($email->submit_msg == 1 && $email->message_send == 1)
            {
                $temp_arr[] = '<span class="label label-success">Sent <i class="fa fa-check"></i></span>';
            }
            else if($email->submit_msg == 1 && $email->message_send == 0)
            {
                $temp_arr[] = '<span class="label label-warning">Pending <i class="fa fa-check"></i></span>';
            }
            else if($email->submit_msg == 0 && $email->message_send == 0)
            {
                $temp_arr[] = '';
            }
            else if($email->submit_msg == 0 && $email->message_send == 1)
            {
                $temp_arr[] = '';
            }

            if ($email->message_viewed == 1)
            {
                $temp_arr[] = '<span class="label label-success">Seen <i class="fa fa-check"></i></span>';
            }
            else{
                $temp_arr[] = '<span class="label label-warning">Not Seen <i class="fa fa-close"></i></span>';
            }
            $temp_arr[] = '<a href=\'#\' class="general_send_email" data-sender_email="'.$email->email.'" data-send_url="'.url("admin/subscribe/send_custom_email") .'" ><span class="label label-primary">Send Email <i class="fa fa-envelope-o"></i></span></a>';
            $temp_arr[] = '<a href=\'#\' class="general_remove_item_ajax" data-deleteurl="'.url("/admin/subscribe/remove_email").'" data-tablename="App\models\subscribe_m" data-itemid="'.$email->id.'"><span class="label label-danger">Remove <i class="fa fa-remove"></i></span></a>';
            $this->data["emails_json"][] = $temp_arr;
        }
        $this->data["emails_json"] = array("data"=>$this->data["emails_json"]);
        file_put_contents("uploads/datatables_json/subscribe.json",json_encode($this->data["emails_json"]));
//        dump($this->data["emails_json"]);
        return view("admin.subviews.subscribe.show")->with($this->data);
    }

    public function save_email(Request $request)
    {
        if (!check_permission($this->user_permissions,"admin/subscribe","add_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        if (isset($request) && count($request->all()) >0)
        {

            $this->validate($request,[
                "email" => "email|unique:subscribe,email,deleted_at,NULL"
            ]);

            if (isset($request["sheet_url"]) && !empty($request["sheet_url"]))
            {

                $path = str_replace(url("/"), "", $request["sheet_url"]);
                $path = substr($path,1);

                Excel::load("$path",function($reader){

                    // Getting all results

                    $results = $reader->toArray();
                    if (is_array($results) && count($results) > 0)
                    {

                        foreach ($results as $key => $value)
                        {
                            $check_unique = subscribe_m::where("email",$value[0])->get()->all();
                            if (is_array($check_unique) && count($check_unique) == 0)
                            {
                                subscribe_m::create([
                                    "email" => $value[0]
                                ]);
                            }
                            else{
                                continue;
                            }
                        }

                        $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";

                    }

                });

            }

            if (isset($request["email"]) && !empty($request["email"]))
            {
                $obj = subscribe_m::create([
                    "email" => $request["email"]
                ]);

                if (is_object($obj))
                {
                    $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";
                }
            }


        }


        return view("admin.subviews.subscribe.save_email")->with($this->data);
    }

    public function export_subscribe()
    {

        if (!check_permission($this->user_permissions,"admin/subscribe","export_subscribe"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $results = subscribe_m::all();

        $results = convert_inside_obj_to_arr($results,"email","array","yes");

//        $results = array_prepend($results,array(""));

        Excel::create('subscribe', function($excel) use($results) {

            $excel->setTitle('Subscribe Emails Backup');

            $excel->setCreator('Seoera')
                ->setCompany('Seoera');

            $excel->sheet('sheet name', function($sheet) use($results) {
                $sheet->fromArray($results);
            });

        })->download('xls');


    }

    public function email_settings(Request $request)
    {
        if (!check_permission($this->user_permissions,"admin/subscribe","email_settings"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        if (isset($request) && count($request->all()) >0)
        {

            $this->validate($request,[
                "sender_email" => "required|email",
                "email_subject" => "required",
                "email_body" => "required",
                "limit" => "required"
            ]);

            email_settings_m::find(1)->update([
                "sender_email" => $request["sender_email"],
                "email_subject" => $request["email_subject"],
                "email_body" => $request["email_body"],
                "limit" => $request["limit"]
            ]);

            $this->data["success"] = "<div class='alert alert-success'> Data Updated Successfully... </div>";

        }

        $this->data["email_settings"] = email_settings_m::find(1);

        return view("admin.subviews.subscribe.email_settings")->with($this->data);
    }

    public function send_custom_email(Request $request) {

        if (!check_permission($this->user_permissions,"admin/subscribe","send_custom_email"))
        {
            $output["success"] = "error";
            $output["error"]=  '<div class="alert alert-danger">
                    <strong>You can not access here!!!</strong>
                    </div>';
            echo json_encode($output);
            return;
        }

        $output = array();
        $output["success"] = "";
        $output["error"] = "";

        $email = $request["email"];
        $sender_email = $request["sender_email"];
        $email_subject = $request["email_subject"];
        $email_body = $request["email_body"];

        $output["dump"] = $email."<br>";
        $output["dump"] .= $sender_email."<br>";
        $output["dump"] .= $email_subject."<br>";
        $output["dump"] .= $email_body."<br>";

        if (empty($email) || empty($sender_email) || empty($email_subject) || empty($email_body)) {
            $output["error"] = '<div class="alert alert-danger">
                    <strong>Check To Fill All Data !!!</strong>
                    </div>';

            $output["success"] = "error";
            echo json_encode($output);
            return;
        }

        $img_url = url("subscribe_cron_jop/show_email?myemail=".$email);
        $email_body_img = " <img src='$img_url' height='1' width='1' style='display:none;'>";

        $output["temp_body"] = $email_body.$email_body_img;


        $this->_send_email_to_custom(["$email"],$email_body.$email_body_img,$email_subject,$sender_email);
        $output["success"] = '<div class="alert alert-success">
                    <strong>Your Message send successfully...</strong>
                    </div>';

        $subscriber = subscribe_m::where("email",$email)->get()->first();

        if (is_object($subscriber) && !empty($subscriber)) {

            subscribe_m::find($subscriber->id)->update([
                "last_message"=>$email_body,
                "message_viewed"=>0,
                "submit_msg"=>1,
                "message_send"=>1
            ]);

        }

        echo json_encode($output);

    }

    public function send_all_subscribers_email(Request $request) {

        $output = array();
        $output["success"] = "";
        $output["error"] = "";

        if (!check_permission($this->user_permissions,"admin/subscribe","send_all_subscribers_email"))
        {
            $output["success"] = "error";
            $output["error"]=  '<div class="alert alert-danger">
                    <strong>You can not access here!!!</strong>
                    </div>';
            echo json_encode($output);
            return;
        }



        $temp_settings = email_settings_m::find(1);
        if (is_object($temp_settings) && !empty($temp_settings))
        {

            email_settings_m::find(1)->update([
                "run_send" =>1,
                "offset" =>0
            ]);

            subscribe_m::where("id",">",0)->update([
                "message_viewed" => 0,
                "submit_msg" => 1,
                "message_send" => 0
            ]);

            $output["error"]=  '<div class="alert alert-success">
                    <strong>The Email Will Send soon with groups to all Subscribers...</strong>
                    </div>';

            $output["redirect_url"] = url("admin/subscribe");
        }
        else{
            $output["success"] = "error";
            $output["error"]=  '<div class="alert alert-danger">
                    <strong>There is an Error in Your Email Default Setting!!!</strong>
                    </div>';
        }

        echo json_encode($output);
    }

    public function stop() {

        if (!check_permission($this->user_permissions,"admin/subscribe","stop"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        email_settings_m::find(1)->update([
            "run_send"=>0,
            "offset"=>0
        ]);

        return redirect("admin/subscribe");

    }
    public function pause() {

        if (!check_permission($this->user_permissions,"admin/subscribe","pause"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        email_settings_m::find(1)->update([
            "run_send"=>0
        ]);

        return redirect("admin/subscribe");

    }
    public function resume() {

        if (!check_permission($this->user_permissions,"admin/subscribe","resume"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        email_settings_m::find(1)->update([
            "run_send"=>1
        ]);

        return redirect("admin/subscribe");

    }


    public function remove_email(Request $request){

        if (!check_permission($this->user_permissions,"admin/subscribe","delete_action"))
        {
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }

        $this->general_remove_item($request,'App\models\subscribe_m');
    }

}
