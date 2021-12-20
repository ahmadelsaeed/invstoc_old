<?php

namespace App\Http\Controllers\admin;

use App\Events\posts_events;
use App\Http\Controllers\actions\posts\posts;
use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;

use App\Http\Requests;
use App\models\chat\chat_messages_m;
use App\models\chat\chats_m;
use App\models\notification_m;
use App\User;
use Illuminate\Http\Request;

class dashboard extends admin_controller
{

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        return view("admin.subviews.dashboard",$this->data);
    }


    public function add_post(){
        $this->data=array_merge($this->data,posts_events::before_add_post());

        return view("admin.subviews.add_post",$this->data);
    }


    public function send_message(Request $request)
    {

        $output = [];
        $output['msg'] = '';


        $user_id = intval(clean($request->get('user_id')));
        $type = (clean($request->get('type')));
        $message = (clean($request->get('message')));

        $to_user_obj=User::get_users("
                AND u.user_id=$user_id
            ");

        if (!isset($to_user_obj[0]))
        {
            $output['msg'] = 'User is not Exist !!';
            echo json_encode($output);
            return;
        }

        $to_user_obj = $to_user_obj[0];


        if ($type == 'chat_message')
        {
            // check if there is exist chat between them or not
            $get_chat = chats_m::get_chats("
                    AND (chat.from_user_id = 1 AND chat.to_user_id = $to_user_obj->user_id)
                    OR (chat.to_user_id= $to_user_obj->user_id AND chat.from_user_id = 1)
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
                    "to_user_id" => $to_user_obj->user_id,
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
                "to_user_id" => $to_user_obj->user_id,
                "message" => $message,
            ]);

            $user_obj = User::findOrfail($to_user_obj->user_id);
            $user_obj->update([
                "not_seen_messages" => ($user_obj->not_seen_messages + 1)
            ]);

            $output['msg'] = 'تم إرسال الرسالة بنجاح ';
            $subject = "رسالة جديدة من الموقع";
        }
        else{

            notification_m::create([
                "not_title" => $message,
                "not_type" => 'admin_notify',
                "not_from_user_id" => 1,
                "not_to_user_id" => $to_user_obj->user_id,
                "created_at" => date("Y-m-d"),
                "updated_at" => date("Y-m-d"),
            ]);

            $user_obj = User::findOrfail($to_user_obj->user_id);
            $user_obj->update([
                "not_seen_all_notifications" => ($user_obj->not_seen_all_notifications + 1)
            ]);

            $subject = "إشعار جديدة من الموقع";
            $output['msg'] = 'تم إرسال الإشعار بنجاح ';
        }


        // send email to target user
        $this->_send_email_to_custom(
            $emails = array($to_user_obj->email) ,
            $data = "$message" ,
            $subject);


        echo json_encode($output);
        return;

    }

    public function change_code(Request $request)
    {

        $output = [];
        $output['msg'] = '';

        $user_id = intval(clean($request->get('user_id')));
        $username = (clean($request->get('username')));

        $to_user_obj=User::get_users("
                AND u.user_id=$user_id
            ");

        if (!isset($to_user_obj[0]))
        {
            $output['msg'] = 'User is not Exist !!';
            echo json_encode($output);
            return;
        }

        $to_user_obj = $to_user_obj[0];

        // check if unique username
        $check_unique = User::where("user_id","<>",$user_id)
            ->where("username","$username")->get()->all();

        if (count($check_unique))
        {
            $output['msg'] = 'Code is Exist, Please Change it';
            echo json_encode($output);
            return;
        }

        User::FindOrFail($user_id)->update([
            "username" => "$username"
        ]);

        $output['msg'] = 'Code is Changed Successfully';

        echo json_encode($output);
        return;

    }

}
