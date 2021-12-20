<?php

namespace App\Http\Controllers\front;

use App\models\chat\chat_messages_m;
use App\models\chat\chats_m;
use App\models\notification_m;
use App\models\subscribe_m;
use App\models\support_messages_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class subscribe_contact extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $slider_arr = array();
        $this->general_get_content(
            [
                "support"
            ]
            ,$slider_arr
        );

        $this->data["meta_title"]=show_content($this->data["pages_seo"],"support_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"support_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"support_meta_keywords");

        if ($request->method() == "POST")
        {
            $this->validate($request,
                [
                    "email" => "required|email",
                    "message" => "required",
                    "name" => "required",
                    "phone" => "required",
                ],
                [
                    "email.required" => (
                        show_content($this->data["support"],"form_email")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "email.email" => (
                        show_content($this->data["support"],"form_email")." ".
                        show_content($this->data["validation_messages"],"is_not_valid_email_field")
                    ),
                    "message.required" => (
                        show_content($this->data["support"],"form_msg")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "name.required" => (
                        show_content($this->data["support"],"form_name")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "phone.required" => (
                        show_content($this->data["support"],"form_phone")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                ]
            );

            $current_user = $this->data["current_user"];

            $phone = clean($request->get("phone"));
            $full_name = clean($request->get("full_name"));
            $message = clean($request->get("message"));

           /*$img_id = $this->general_save_img(
               $request,
               $item_id=null,
               "attach_file",
               $new_title = "",
               $new_alt = "",
               $upload_new_img_check = "on",
               $upload_file_path = "/users/$this->user_id/support",
               $width = 0,
               $height = 0,
               $photo_id_for_edit = 0
           );*/


            $img_ids = $this->general_save_slider(
                $request,
                $field_name="attach_file",
                $width=0,
                $height=0,
                $new_title_arr = "",
                $new_alt_arr = "",
                $json_values_of_slider="",
                $old_title_arr = "",
                $old_alt_arr = "",
                $path="/users/$this->user_id/support"
            );

            $img_ids = json_encode($img_ids);

            support_messages_m::create([
                "msg_type" => "support",
                "name" => $current_user->full_name." (".$current_user->username.")",
                "full_name" => $full_name,
                "email" => $current_user->email,
                "img_id" => $img_ids,
                "tel" => $phone,
                "message" => $message,
            ]);

            $this->_send_email_to_all_users_type(
                $user_type = "admin" ,
                $data =
                    [
                        "name" => $current_user->full_name,
                        "code" => $current_user->username,
                    ] ,
                $subject = "Support Message",
                $sender = "info@invstoc.com" ,
                $path_to_file = "",
                $email_template ="email.support"
            );

            #region send notification and message to user

            $admin_notify = show_content($this->data["validation_messages"],"msg_success_and_wait_admin");
            notification_m::create([
                "not_title" => $admin_notify,
                "not_type" => 'admin_notify',
                "not_from_user_id" => 1,
                "not_to_user_id" => $this->user_id,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ]);


            if (false)
            {

                // check if there is exist chat between them or not
                $get_chat = chats_m::get_chats("
                        AND (chat.from_user_id = 1 AND chat.to_user_id = $this->user_id)
                        OR (chat.to_user_id= $this->user_id AND chat.from_user_id  = 1)
                    ");

                if (count($get_chat))
                {
                    $get_chat = $get_chat[0];
                    $chat_id = $get_chat->chat_id;
                }
                else{
                    // create chat
                    $new_chat = chats_m::create([
                        "chat_name" => "رسائل خاصه من الموقع",
                        "from_user_id" => 1,
                        "to_user_id" => $this->user_id,
                    ]);

                    if (!is_object($new_chat))
                    {
                        $output['msg'] = 'Something is wrong try again later !!';
                        echo json_encode($output);
                        return;
                    }

                    $chat_id = $new_chat->chat_id;
                }

                // create new message
                $msg_obj=chat_messages_m::create([
                    "chat_id" => $chat_id,
                    "from_user_id" => 1,
                    "to_user_id" => $this->user_id,
                    "message" => $admin_notify,
                ]);
            }

            #endregion


            #region increase notifications , messages count

            User::findOrFail($this->user_id)->update([
                "not_seen_all_notifications" => ($current_user->not_seen_all_notifications + 1)
//                "not_seen_messages" => ($current_user->not_seen_messages + 1),
            ]);

            #endregion

            $msg = show_content($this->data["support"],"form_success_msg");
            session()->put(['msg' => "<div class='alert alert-success'>$msg</div>"]);
            return Redirect::to('/support');

        }

        return view("front.subviews.support",$this->data);
    }

    public function subscribe(Request $request)
    {
        $output["error"] = "";

        $request["email"] = clean($request["email"]);

        $validator = Validator::make(
            [
                "email" => $request["email"]
            ],
            [
                "email" => "email|required|unique:subscribe,email"
            ]
        );

        if (count($validator->messages()) == 0)
        {
            $check = subscribe_m::create($request->all());
            if(is_object($check))
            {
                $output["error"] = "";
            }
            else{
                $output["error"] = "error";
                $output["error_msg"] = show_content($this->data["validation_messages"],"error_occured");
            }
        }
        else{
            $output["error"] = "error";
            $output["error_msg"] = $validator->messages();
//            $output["error_msg"] = $output["error_msg"]->email;
        }

        return json_encode($output);

    }

    public function make_a_contact(Request $request)
    {

        $output=array();
        \Debugbar::disable();


        $validator = Validator::make(

            [
                "email" => $request["email"],
                "message" => $request["message"],
                "name" => $request["name"],
            ],

            [
                "email" => "required|email",
                "message" => "required",
                "name" => "required",
            ]

        );



        //Input::all();

        $this->general_get_content(array("email_page"));

        if (count($validator->messages()) == 0)
        {
            $inputs=Input::all();

            try{
                $ip=get_client_ip();
                $inputs["country"] = ip_info($ip)['country'];
            }catch(Exception $e){
                $inputs["country"]="";
            }

            $inputs["source"]=Cookie::get('source');
            if($inputs["source"]==null){
                $inputs["source"]="";
            }

            $current_user = $this->data["current_user"];
            if (is_object($current_user))
            {
                $inputs["user_id"] = $current_user->user_id;
            }

            $inputs["name"]=(isset($inputs["name"]))?clean($inputs["name"]):"";
            $inputs["tel"]=(isset($inputs["tel"]))?clean($inputs["tel"]):"";
            $inputs["message"]=(isset($inputs["message"]))?clean($inputs["message"]):"";
            $inputs["title"]=(isset($inputs["title"]))?clean($inputs["title"]):"";
            $inputs["phone"]=(isset($inputs["phone"]))?clean($inputs["phone"]):"";
            $inputs["msg_type"]=(isset($inputs["msg_type"]))?clean($inputs["msg_type"]):"";
            $inputs["current_url"]=(isset($inputs["current_url"]))?clean($inputs["current_url"]):"";
            $inputs["source"]=(isset($inputs["source"]))?clean($inputs["source"]):"";

            $inputs["other_data"]=[];
            $inputs["other_data"]["fax"]=(isset($inputs["fax"]))?clean($inputs["fax"]):"";
            $inputs["other_data"]["address"]=(isset($inputs["address"]))?clean($inputs["address"]):"";

            $inputs["other_data"]["adults_number"]=(isset($inputs["adults_number"]))?clean($inputs["adults_number"]):"";
            $inputs["other_data"]["children_number"]=(isset($inputs["children_number"]))?clean($inputs["children_number"]):"";
            $inputs["other_data"]["arrival_date"]=(isset($inputs["arrival_date"]))?clean($inputs["arrival_date"]):"";
            $inputs["other_data"]["departure_date"]=(isset($inputs["departure_date"]))?clean($inputs["departure_date"]):"";

            $inputs["other_data"]=json_encode($inputs["other_data"]);

            $inputs["trip_id"]=(isset($inputs["trip_id"]))?clean($inputs["trip_id"]):"";



            $support_message_obj=support_messages_m::create($inputs);


//            $subscribe_option=$inputs["subscribe_option"];
            //if subscribe_option== true then add
            //emdil to subscribe if uniqe
//            if ($subscribe_option=="true") {
//
//                $old_row=subscribe_m::where("email","=",$inputs["email"])->first();
//
//                if (!is_object($old_row)) {
//                    subscribe_m::create([
//                        "email" => $inputs["email"]
//                    ]);
//                }
//            }


            $admin_emails=User::where("user_type","admin")->get();
            $admin_emails=convert_inside_obj_to_arr($admin_emails->all(),"email");

            unset($inputs["_token"],$inputs["source"]);

            //send email to admins and to user email

            if(is_object($support_message_obj)){

                // send notification
                notification_m::create([
                    "not_title" => $inputs["name"]." make a contact request...",
                    "not_date" => Carbon::now()
                ]);

                $email_data_for_user=[
                    "obj"=>$this->data["email_page"],
                    "msg"=>"You've send your message successfully"
                ];

                $email_data_for_admins=[
                    "obj"=>$this->data["email_page"],
                    "msg"=>"Someone send you a support message",
                    "email_data"=>$inputs
                ];


                //1)admin
                $this->_send_email_to_custom(
                    $emails = $admin_emails,
                    $data = $email_data_for_admins ,
                    $subject = "someone send you a support message $support_message_obj->id",
                    $sender = "support@invstoc.com" ,
                    $path_to_file = ""
                );

                //2)to user
                $this->_send_email_to_custom(
                    $emails = [$support_message_obj->email],
                    $data = $email_data_for_user ,
                    $subject = "Your Message Is Sent Successfully",
                    $sender = "support@invstoc.com" ,
                    $path_to_file = ""
                );

                $output["msg"] = "<div class='alert alert-success'>".show_content($this->data["support"],"form_success_msg")."</div>";

            }

        }
        else{
            $output["msg"]="<div class='alert alert-danger'>";

            foreach ($validator->messages()->all() as $key => $msg) {
                $output["msg"].=$msg."<br>";
            }

            $output["msg"] .= "</div>";
        }

        echo json_encode($output);
    }

}
