<?php

namespace App\Http\Controllers\front;

use App\models\category_m;
use App\models\chat\chat_messages_m;
use App\models\chat\chats_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use App\User;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class chat extends Controller
{
    //
    public $lang_seg_1=false;
    public function __construct(){
        parent::__construct();
        $this->middleware("check_user");
        $url_seg_1=\Request::segment(1);
        $all_langs_titles=convert_inside_obj_to_arr($this->data["all_langs"],"lang_title");

        if(in_array($url_seg_1,$all_langs_titles)) {
            $this->lang_seg_1=true;
        }

        $slider_arr = array();
        $this->general_get_content(
            [
                "chat_keywords"
            ]
            ,$slider_arr
        );
    }

    public function index()
    {

        $this->data["chats"] = [];
        $this->data["chats"] = chats_m::get_chats("
                    and chat.from_user_id = $this->user_id
                    or chat.to_user_id = $this->user_id
                ");


        $this->data["meta_title"]=show_content($this->data["pages_seo"],"chat_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"chat_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"chat_meta_keywords");


        return view("front.subviews.chat.index",$this->data);
    }

    public function chat_messages(Request $request, $chat_id)
    {
        $chat_id = $request->chat_id;
        $current_user = $this->data["current_user"];


        $chat = chats_m::get_chats("
            and chat.chat_id = $chat_id
            and (chat.from_user_id = $this->user_id
            or chat.to_user_id = $this->user_id
            )
            ");

        if(!isset_and_array($chat)){
            return abort(404);
        }


        if (is_array($chat) && count($chat))
        {

            $this->data["chat_obj"] = $chat[0];

            $meta_title = show_content($this->data["chat_keywords"],"chat_with_label")." '".
                $this->data["chat_obj"]->to_user_full_name."' ";

            $this->data["meta_title"] = $meta_title;
            $this->data["meta_desc"] = $meta_title;
            $this->data["meta_keywords"] = $meta_title;


            $get_msgs = chat_messages_m::get_chat_messages(
                "
                    where msg.chat_id = $chat_id
                    order by msg.chat_msg_id asc
                 "
            );

            $this->data["messages"] = $get_msgs;

        }


        return view("front.subviews.chat.view_chat",$this->data);
    }

    public function search_for_users(Request $request){


        $output = [];
        $output["msg"] = "";
        $output["items"] = "";


        $search_val = clean($request->get("current_val"));
        if (empty($search_val))
        {
            $output["msg"] = show_content($this->data["chat_keywords"],"empty_search_data");
            echo json_encode($output);
            return;
        }

        $get_users = User::get_users("
            AND u.user_id <> $this->user_id
            AND u.user_type = 'user'
            AND (u.username = '$search_val'
            OR u.full_name like '%$search_val%')
        ");

        if (count($get_users))
        {
            $options = "";
            foreach($get_users as $key => $user_obj)
            {
                $options .= "<option value='$user_obj->user_id'>$user_obj->full_name</option>";
            }
            $output["items"] = $options;
        }

        echo json_encode($output);
        return;
    }

    public function send_chat_message(Request $request){


        $output = [];
        $output["msg"] = "";
        $output["status"] = "";

        $current_user = $this->data["current_user"];

        $message_content = clean($request->get("message_content"));
        $users = clean($request->get("users"));

        if (empty($message_content))
        {
            $output["status"] = "danger";
            $output["msg"] = show_content($this->data["chat_keywords"],"message_can_not_be_empty");
            echo json_encode($output);
            return;
        }

        if (empty($users) || !count($users))
        {
            $output["status"] = "danger";
            $output["msg"] = show_content($this->data["chat_keywords"],"please_select_users");
            echo json_encode($output);
            return;
        }

        $users = array_unique($users);
        $user_ids = implode(',',$users);

        $get_users = User::get_users("
            AND u.user_id <> $this->user_id
            AND u.user_id in ($user_ids)
        ");

        $from_user_obj=User::get_users("
                AND u.user_id=$this->user_id
            ");

        if (!isset_and_array($from_user_obj)){
            return abort(404);
        }
        $from_user_obj=$from_user_obj[0];

        if (count($get_users))
        {

            foreach($get_users as $key => $user_obj)
            {

                User::findOrfail($user_obj->user_id)->update([
                    "not_seen_messages" => ($user_obj->not_seen_messages + 1)
                ]);

                // check if there is exist chat between them or not
                $get_chat = chats_m::get_chats("
                    AND (chat.from_user_id = $this->user_id AND chat.to_user_id = $user_obj->user_id)
                    OR (chat.to_user_id= $this->user_id AND chat.from_user_id  = $user_obj->user_id)
                ");

                if (count($get_chat))
                {
                    $get_chat = $get_chat[0];
                    $chat_id = $get_chat->chat_id;
                }
                else{
                    // create chat
                    $new_chat = chats_m::create([
                        "chat_name" => "محادثة جديدة بين $current_user->full_name و $user_obj->full_name",
                        "from_user_id" => $this->user_id,
                        "to_user_id" => $user_obj->user_id,
                    ]);

                    if (!is_object($new_chat))
                        continue;

                    $chat_id = $new_chat->chat_id;
                }

                // create new message
                $msg_obj=chat_messages_m::create([
                    "chat_id" => $chat_id,
                    "from_user_id" => $this->user_id,
                    "to_user_id" => $user_obj->user_id,
                    "message" => $message_content,
                ]);

                // send email to target user
                $this->_send_email_to_custom(
                    $emails = array($user_obj->email) ,
                    $data = "$message_content" ,
                    $subject = show_content($this->data["chat_keywords"],"new_message_from").
                        " $current_user->full_name");


                #region save cache for to user


                $user_cache_msgs=\Cache::get("user_cache_msgs_".$user_obj->user_id);

                if(!is_string($user_cache_msgs)){
                    $user_cache_msgs="";
                }

                $user_cache_msgs=json_decode($user_cache_msgs,true);
                if(!isset_and_array($user_cache_msgs)){
                    $user_cache_msgs=[];
                }

                $msg_view_data=[
                    "user_obj"=>$from_user_obj,
                    "chat_id"=>$chat_id,
                    "msg_obj"=>$msg_obj
                ];

                $user_cache_msgs[$msg_obj->chat_msg_id]=
                    \View::make("front.subviews.chat.blocks.chat_block")->with($msg_view_data)->render();

                \Cache::put("user_cache_msgs_".$user_obj->user_id,json_encode($user_cache_msgs),60);

                #endregion

            }
            $output["status"] = "success";
            $output["msg"] = show_content($this->data["chat_keywords"],"message_send_successfully");
        }

        echo json_encode($output);
        return;
    }

    public function send_chat_message_to_user(Request $request){


        $output = [];
        $output["msg"] = "";
        $output["status"] = "";
        $output["block"] = "";

        $current_user = $this->data["current_user"];

        $message_content = clean($request->get("message",""));
        $chat_id = intval(clean($request->get("chat_id",0)));

        if (empty($message_content))
        {
            $output["status"] = "danger";
            $output["msg"] = show_content($this->data["chat_keywords"],"message_can_not_be_empty");
            echo json_encode($output);
            return;
        }

        $message_content = trim($message_content);
        $message_content = nl2br($message_content);

        if ($chat_id == 0)
        {
            $output["status"] = "danger";
            $output["msg"] = show_content($this->data["chat_keywords"],"chat_not_exist");
            echo json_encode($output);
            return;
        }


        $chat_obj = chats_m::where("chat_id",$chat_id)->first();
        if (!is_object($chat_obj))
        {
            $output["status"] = "danger";
            $output["msg"] = show_content($this->data["chat_keywords"],"chat_not_exist");
            echo json_encode($output);
            return;
        }

        $get_target_id = $chat_obj->from_user_id;
        if ($chat_obj->from_user_id == $this->user_id)
        {
            $get_target_id = $chat_obj->to_user_id;
        }

        $get_target_user = User::where("user_id",$get_target_id)->first();
        if (!is_object($get_target_user))
        {
            $output["status"] = "danger";
            $output["msg"] = show_content($this->data["chat_keywords"],"user_not_exist");
            echo json_encode($output);
            return;
        }

        User::findOrfail($get_target_id)->update([
            "not_seen_messages" => ($get_target_user->not_seen_messages + 1)
        ]);

        // create new message
        $msg_obj=chat_messages_m::create([
            "chat_id" => $chat_id,
            "from_user_id" => $this->user_id,
            "to_user_id" => $get_target_id,
            "message" => $message_content,
        ]);

        // send email to target user
        $this->_send_email_to_custom(
            $emails = array($get_target_user->email) ,
            $data = "$message_content" ,
            $subject = show_content($this->data["chat_keywords"],"new_message_from").
                " $current_user->full_name");


        #region save cache for to user


        $user_cache_msgs=\Cache::get("user_cache_msgs_".$get_target_user->user_id);

        if(!is_string($user_cache_msgs)){
            $user_cache_msgs="";
        }

        $user_cache_msgs=json_decode($user_cache_msgs,true);
        if(!isset_and_array($user_cache_msgs)){
            $user_cache_msgs=[];
        }

        $msg_view_data=[
            "user_obj"=>$current_user,
            "chat_id"=>$chat_id,
            "msg_obj"=>$msg_obj
        ];

        $user_cache_msgs[$msg_obj->chat_msg_id]=
            \View::make("front.subviews.chat.blocks.chat_block")->with($msg_view_data)->render();

        \Cache::put("user_cache_msgs_".$get_target_user->user_id,json_encode($user_cache_msgs),60);

        #endregion

        $get_chat_msg_obj = chat_messages_m::get_chat_messages("
            where msg.chat_msg_id = $msg_obj->chat_msg_id
        ");

        $get_chat_msg_obj = $get_chat_msg_obj[0];

        $output["block"] = \View::make("front.subviews.chat.blocks.right")->with([
            "message" => $get_chat_msg_obj
        ])->render();

        $output["status"] = "success";
        $output["msg"] = show_content($this->data["chat_keywords"],"message_send_successfully");

        echo json_encode($output);
        return;
    }

    public function load_msgs(){

        $output=[];
        $output["msgs_html"]="";

        User::findOrfail($this->user_id)->update([
            "not_seen_messages" => 0
        ]);

        //get last  5 msgs
        $msgs=chat_messages_m::
        where("to_user_id",$this->user_id)->
        orderBy("chat_msg_id","desc")->
        limit(5)->
//        groupBy("chat_id")->
        get();

        if($msgs->count()==0){
            echo json_encode($output);
            return;
        }

        $from_user_ids=$msgs->pluck("from_user_id")->all();
        $from_user_objs=User::get_users(" AND u.user_id in (".implode(",",$from_user_ids).")");
        $from_user_objs=collect($from_user_objs)->groupBy("user_id");


        $msg_view_data=[
            "from_user_objs"=>$from_user_objs,
            "msgs"=>$msgs,
        ];

        $output["msgs_html"]=
            \View::make("front.subviews.chat.blocks.chat_foreach_block")->
            with($msg_view_data)->render();

        echo json_encode($output);
    }


}
