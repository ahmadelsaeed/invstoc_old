<?php

namespace App\Http\Controllers\front;

use App\Events\notifications;
use App\Events\posts_events;
use App\models\category_m;
use App\models\chat\chat_messages_m;
use App\models\chat\chats_m;
use App\models\group_workshop\group_members_m;
use App\models\group_workshop\group_requests_m;
use App\models\group_workshop\groups_m;
use App\models\group_workshop\workshop_followers_m;
use App\models\group_workshop\workshops_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use App\models\posts\posts_m;
use App\User;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class group_workshop extends Controller
{

    public function __construct(){
        parent::__construct();
//        $this->middleware("check_user");
        $slider_arr = array();
        $this->general_get_content(
            [
                "group_keywords"
            ]
            ,$slider_arr
        );
    }

    public $rename_group_or_workshop_limit=100;

    public function get_user_groups_and_workshops(){

        $output=[];
        $output["html"]=" ";

        //get all user workshops
        $all_workshops=workshops_m::where("owner_id",$this->user_id)->get()->all();

        //get all groups that user is member in
        $all_groups=groups_m::
        join("group_members","group_members.group_id","=","groups.group_id")->
        where("group_members.user_id",$this->user_id)->
        groupBy("groups.group_id")->
        get()->all();

        //get all followed workshops
        $followed_workshops_ids=workshop_followers_m::
        where("user_id",$this->user_id)->
        get()->
        pluck("workshop_id")->all();

        $output["html"].=\View::make("front.subviews.groups_workshops.workshops_li")->
        with([
            "all_workshops"=>$all_workshops,
            "workshops_li"=>show_content($this->data["user_homepage_keywords"],"your_workshops")
        ])->
        render();


        $output["html"].=\View::make("front.subviews.groups_workshops.groups_li")->
        with(
            [
                "all_groups"=>$all_groups,
                "user_homepage_keywords"=>$this->data["user_homepage_keywords"],
            ]
        )->
        render();

        if (isset_and_array($followed_workshops_ids)){
            $followed_workshops=workshops_m::whereIn("workshop_id",$followed_workshops_ids)->get()->all();

            if (isset_and_array($followed_workshops)){
                $output["html"].=\View::make("front.subviews.groups_workshops.workshops_li")->
                with([
                    "all_workshops"=>$followed_workshops,
                    "workshops_li"=>show_content($this->data["user_homepage_keywords"],"your_followed_workshops_label"),
                ])->
                render();
            }
        }


        //get activities_depended_select


        echo json_encode($output);
    }

    #region groups
    public function check_your_membership_in_group($group_id,$membership_type="member"){
        //check if this user is member in it
        $group_member=group_members_m::
        where("group_id",$group_id)->
        where("user_id",$this->user_id)->get()->first();

        $this->data["this_user_group_membership"]=$group_member;

        if(!is_object($group_member)){
            return Redirect::to('request_to_join_group/'.$group_id)->with([])->send();
        }

        if($membership_type=="admin"&&$group_member->user_role!="admin"){
            abort(404);
        }
    }

    public function create_group(Request $request){

        $output=[];

        $group_name=clean($request->get("group_name"));
        if(strlen($group_name)==0){
            echo json_encode($output);
            return;
        }

        $group_name = string_safe2($group_name," ");

        $created_group_obj=groups_m::create([
            'group_owner_id'=>$this->user_id,
            'group_name'=>$group_name
        ]);

        group_members_m::create([
            'group_id'=>$created_group_obj->group_id,
            'user_id'=>$this->user_id,
            'user_role'=>"admin"
        ]);

        $output["added_html"]=\View::make("front.subviews.groups_workshops.group_li")->
        with(["group_obj"=>$created_group_obj])->
        render();

        echo json_encode($output);
    }

    public function show_group(){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $this->data["group_obj"]=groups_m::leftJoin("attachments","groups.group_logo","=","attachments.id")
            ->where("groups.group_id",$group_id)->first();

//        $this->check_your_membership_in_group($group_id);

        $this->data=array_merge($this->data,posts_events::before_add_post());

        $this->data["group_obj"]->update([
            'group_visits' => ($this->data["group_obj"]->group_visits + 1)
        ]);


        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;
        $this->data["og_img"] = $this->data['workshop_obj']->path;

        /**
         * I'm very sorry to do this. But, this bullshit code
         * encourage me to do so.
         *
         * @sorry
         */
        if(!auth()->check())
            $this->data['current_user'] = User::orderBy('created_at', 'desc')->first();

        return view("front.subviews.groups_workshops.group.subviews.group",$this->data);
    }

    public function request_to_join_group(Request $request,$group_id)
    {

        $group_obj = groups_m::findOrFail($group_id);
        $this->data["group_obj"] = $group_obj;
        $this->data["is_requested_before"] = false;
        $this->data["request_join"] = true;
        $current_user = $this->data["current_user"];

        //check if this user is member in it
        $group_member = group_members_m::
        where("group_id",$group_id)->
        where("user_id",$this->user_id)->get()->first();

        if (is_object($group_member))
        {
            return Redirect::to("group/$group_obj->group_name/$group_id")->with([])->send();
        }

        $check_if_requested = group_requests_m::where("group_id",$group_id)
            ->where("user_id",$this->user_id)->first();
        if (is_object($check_if_requested))
        {
            $this->data["is_requested_before"] = true;
        }

        if ($request->method() == "POST")
        {

            group_requests_m::create([
                "group_id" => $group_id,
                "user_id" => $this->user_id,
            ]);


            $get_group_admins = group_members_m::where("group_id",$group_id)
                ->where("user_role","admin")->get();

            if (count($get_group_admins))
            {

                $get_group_admins_ids = convert_inside_obj_to_arr($get_group_admins,"user_id");
                $get_admins = User::whereIn("user_id",$get_group_admins_ids)->get();
                $get_admins_emails = convert_inside_obj_to_arr($get_admins,"email");

                // send notifications to admins
                foreach ($get_admins as $key => $admin_obj)
                {
                    notifications::add_notification([
                        'not_title'=>"Request to join group '$group_obj->group_name' ",
                        'not_type'=>"join_group",
                        'not_link'=>"group/$group_obj->group_name/$group_id",
                        'not_from_user_id'=>$this->user_id,
                        'not_to_user_id'=>$admin_obj->user_id
                    ]);

                }


                // send email to admins
                $this->_send_email_to_custom(
                    $emails = $get_admins_emails ,
                    $data = "$current_user->full_name request to join group '$group_obj->group_name'" ,
                    $subject = "$current_user->full_name request to join group '$group_obj->group_name' "
                );

            }

            $msg = show_content($this->data["validation_messages"],"request_send_and_wait_approval");

            return Redirect::to("home")->with([
                'msg' => "<div class='alert alert-success'> $msg </div>"
            ])->send();

        }


        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        return view("front.subviews.groups_workshops.group.subviews.request_to_join_group",$this->data);
    }

    public function group_requests($group_name,$group_id)
    {
        $group_obj = groups_m::findOrFail($group_id);
        $this->data["group_obj"] = $group_obj;

        $this->check_your_membership_in_group($group_id);

        $this->data=array_merge($this->data,posts_events::before_add_post());

        $this->data["users_requests"] = [];
        $group_requests = group_requests_m::where("group_id",$group_id)->get()->all();

        if (count($group_requests))
        {
            $group_requests_ids = convert_inside_obj_to_arr($group_requests,"user_id");
            $group_requests_ids = implode(',',$group_requests_ids);
            $this->data["users_requests"] = User::get_users("
                AND u.user_id in ($group_requests_ids)
            ");
        }


        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        return view("front.subviews.groups_workshops.group.subviews.group_requests",$this->data);
    }

    public function accept_request_join(Request $request)
    {

        $output = [];
        $output["status"] = "";
        $output["msg"] = "";

        $current_user = $this->data["current_user"];

        $group_id = intval(clean($request->get("group_id")));
        $user_id = intval(clean($request->get("user_id")));

        if (!($group_id > 0) || !($user_id > 0))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $group_obj = groups_m::findOrFail($group_id);
        if (!is_object($group_obj))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $target_user = User::findOrFail($user_id);
        if (!is_object($target_user))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $get_request = group_requests_m::where("group_id",$group_id)->where("user_id",$user_id)->first();
        if (!is_object($get_request))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $check_if_permitted = group_members_m::where("group_id",$group_id)
        ->where("user_id",$this->user_id)->where("user_role","admin")->first();
        if (!is_object($check_if_permitted))
        {
            $msg = show_content($this->data["validation_messages"],"not_allowed");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $check_if_member = group_members_m::where("group_id",$group_id)
            ->where("user_id",$user_id)->first();
        if(!is_object($check_if_member))
        {
            group_members_m::create([
                "group_id" => $group_id,
                "user_id" => $user_id,
                "user_role" => "member",
            ]);
        }

        group_requests_m::where("group_id",$group_id)->where("user_id",$user_id)->forceDelete();

        notifications::add_notification([
            'not_title'=>"accepted to join group '$group_obj->group_name' ",
            'not_type'=>"join_group",
            'not_link'=>"group/$group_obj->group_name/$group_id",
            'not_from_user_id'=>$this->user_id,
            'not_to_user_id'=>$user_id
        ]);

        // send email to admins
        $this->_send_email_to_custom(
            $emails = [$target_user->email] ,
            $data = "$current_user->full_name has accepted your request on '$group_obj->group_name'" ,
            $subject = "$current_user->full_name has accepted your request on  '$group_obj->group_name' "
        );


        $output["status"] = "success";
        echo json_encode($output);
        return;
    }

    public function remove_request_join(Request $request)
    {

        $output = [];
        $output["status"] = "";
        $output["msg"] = "";

        $current_user = $this->data["current_user"];

        $group_id = intval(clean($request->get("group_id")));
        $user_id = intval(clean($request->get("user_id")));

        if (!($group_id > 0) || !($user_id > 0))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $group_obj = groups_m::findOrFail($group_id);
        if (!is_object($group_obj))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $target_user = User::findOrFail($user_id);
        if (!is_object($target_user))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $get_request = group_requests_m::where("group_id",$group_id)->where("user_id",$user_id)->first();
        if (!is_object($get_request))
        {
            $msg = show_content($this->data["validation_messages"],"not_valid_data_supplied");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        $check_if_permitted = group_members_m::where("user_id",$this->user_id)->where("user_role","admin")->first();
        if (!is_object($check_if_permitted))
        {
            $msg = show_content($this->data["validation_messages"],"not_allowed");
            $output["status"] = "error";
            $output["msg"] = $msg;
            echo json_encode($output);
            return;
        }

        group_requests_m::where("group_id",$group_id)->where("user_id",$user_id)->forceDelete();

        $output["status"] = "success";
        echo json_encode($output);
        return;

    }

    public function group_settings(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $group_obj=groups_m::findOrFail($group_id);
        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        if ($request->method()=="POST"){

            $group_post_options=$request->get("group_post_options");

            if(!in_array($group_post_options,["0","1","2"])){
                $group_post_options="0";
            }


            $group_obj->update([
                "group_post_options"=>$group_post_options
            ]);

            return Redirect::to("group/settings/$group_obj->group_name/$group_obj->group_id")->send();
        }


        return view("front.subviews.groups_workshops.group.subviews.settings",$this->data);
    }

    public function group_members(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $group_obj=groups_m::findOrFail($group_id);
        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        //get all members
        $all_members=group_members_m::
            select("group_members.*","users.full_name","attachments.path as 'logo_path'")->
            join("users","users.user_id","=","group_members.user_id")->
            join("attachments","attachments.id","=","users.logo_id")->
            where("group_id",$group_id)->
            where("users.user_id","!=",$group_obj->group_owner_id)->
            get();

        $this->data["all_members"]=$all_members;
        $all_members_ids=$all_members->pluck("user_id")->all();
        $all_members_ids[]=$group_obj->group_owner_id;


        if ($request->method()=="POST"){
            $selected_members=$request->get("selected_members");
            $selected_members=array_diff($selected_members,[""]);
            $selected_members=array_map("intval",$selected_members);
            $selected_members=array_diff($selected_members,$all_members_ids);

            if(isset_and_array($selected_members)){
                $add_rows=[];

                foreach ($selected_members as $key => $user_id) {
                    $add_rows[]=[
                        'group_id'=>$group_id,
                        'user_id'=>$user_id,
                        'user_role'=>"member"
                    ];
                }

                group_members_m::insert($add_rows);
            }


            return Redirect::to("group/members/$group_obj->group_name/$group_obj->group_id")->send();
        }


        return view("front.subviews.groups_workshops.group.subviews.members",$this->data);
    }

    public function change_member_role(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $group_obj=groups_m::findOrFail($group_id);
        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        $g_m_id=(int)$request->get("member_id");

        if(!($g_m_id>0))return abort(404);

        $member_data=group_members_m::
        select("group_members.*","users.full_name","attachments.path as 'logo_path'")->
        join("users","users.user_id","=","group_members.user_id")->
        join("attachments","attachments.id","=","users.logo_id")->
        where("group_id",$group_id)->
        where("g_m_id",$g_m_id)->
        get()->first();

        if($member_data->user_id==$group_obj->group_owner_id){
            $msg = show_content($this->data["validation_messages"],"not_allowed");

            return Redirect::to("group/members/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-warning'>$msg</div>
            ")->send();
        }

        if(!is_object($member_data))return abort(404);

        $this->data["member_data"]=$member_data;

        if($request->method()=="POST"){
            $user_role=clean($request->get("user_role"));

            if(!in_array($user_role,["admin","member"])){
                $user_role="member";
            }

            group_members_m::findOrFail($g_m_id)->update([
                "user_role"=>$user_role
            ]);

            return Redirect::to("group/change_member_role/$group_obj->group_name/$group_obj->group_id?member_id=$g_m_id")->send();

        }

        return view("front.subviews.groups_workshops.group.subviews.change_member_role",$this->data);
    }

    public function remove_member(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $group_obj=groups_m::findOrFail($group_id);
        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        $g_m_id=(int)$request->get("member_id");

        if(!($g_m_id>0))return abort(404);

        $member_data=group_members_m::
        select("group_members.*","users.full_name","attachments.path as 'logo_path'")->
        join("users","users.user_id","=","group_members.user_id")->
        join("attachments","attachments.id","=","users.logo_id")->
        where("group_id",$group_id)->
        where("g_m_id",$g_m_id)->
        get()->first();

        if($member_data->user_id==$group_obj->group_owner_id){
            $msg = show_content($this->data["validation_messages"],"not_allowed_to_delete");

            return Redirect::to("group/members/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-warning'>$msg</div>
            ")->send();
        }

        if(!is_object($member_data))return abort(404);

        $this->data["member_data"]=$member_data;

        if($request->method()=="POST"){
            group_members_m::destroy($g_m_id);

            $msg = show_content($this->data["validation_messages"],"deleted_successfully_msg");

            return Redirect::to("group/members/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.group.subviews.remove_member",$this->data);
    }

    public function load_group_posts(Request $request){
        $offset=$request->get("offset","0");
        $group_id=$request->get("group_id","0");

        $this->data["group_obj"]=groups_m::findOrFail($group_id);

        $this->check_your_membership_in_group($group_id);

        $posts_ids=
            posts_m::
            select("post_id")->
            where("post_where","group")->
            where("post_where_id",$group_id);

        $next_posts_ids=$posts_ids;

        $posts_ids=$posts_ids->limit($this->post_limit)->
        offset($offset)->
        orderBy("post_id","desc")->
        get()->pluck("post_id")->all();

        $next_posts_ids=$next_posts_ids->limit($this->post_limit)->
        offset($offset+$this->post_limit)->
        get()->pluck("post_id")->all();

        if(isset_and_array($next_posts_ids)){
            $output["post_limit"]=$this->post_limit;
        }

        $output["posts_ids"]=$posts_ids;
        echo json_encode($output);
    }

    public function rename_group(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $group_obj=groups_m::findOrFail($group_id);
        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        if($this->user_id!=$group_obj->group_owner_id) return abort(404);

        //get user members
        $all_members_count=group_members_m::where("group_id",$group_id)->count();

        if($all_members_count>$this->rename_group_or_workshop_limit){
            $msg = show_content($this->data["validation_messages"],"not_allowed_to_rename_group");
            return Redirect::to("group/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-warning'>$msg</div>
            ")->send();
        }

        if($request->method()=="POST"){

            $group_name = clean($request->get("group_name"));
            $request["group_name"]=$group_name;

            $this->validate($request,[
                "group_name"=>"required"
            ]);

            $group_name = string_safe2($group_name," ");

            $group_obj->update([
                "group_name"=>$group_name
            ]);

            $msg = show_content($this->data["validation_messages"],"msg_success");

            return Redirect::to("group/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.group.subviews.rename_group",$this->data);
    }

    public function change_logo_group(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));

        $group_obj=groups_m::leftJoin("attachments","groups.group_logo","=","attachments.id")
            ->where("groups.group_id",$group_id)->first();
        abort_if(!is_object($group_obj),404);


        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        if($this->user_id!=$group_obj->group_owner_id) return abort(404);

        //get user members
        $all_members_count=group_members_m::where("group_id",$group_id)->count();

        if($all_members_count>$this->rename_group_or_workshop_limit){
            return Redirect::to("group/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-warning'>you can not change your group name because your group members count exceeded the number that you can change group name </div>
            ")->send();
        }

        if($request->method()=="POST"){

            if (!empty($request["group_logo"]))
            {

                $group_logo = $this->general_save_img(
                    $request ,
                    $item_id=$group_id,
                    "group_logo",
                    $new_title = $group_obj->group_name,
                    $new_alt = $group_obj->group_name,
                    $upload_new_img_check ="on",
                    $upload_file_path = "/groups",
                    $width = 500,
                    $height = 500,
                    $photo_id_for_edit = $group_obj->group_logo
                );

                $group_obj->update([
                    "group_logo" => $group_logo
                ]);
            }


            $msg = show_content($this->data["validation_messages"],"msg_success");

            return Redirect::to("group/$group_obj->group_name/$group_obj->group_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();

        }

        return view("front.subviews.groups_workshops.group.subviews.change_logo",$this->data);
    }

    public function delete_group(Request $request){
        //get group id form last segment of ulr
        $group_id=\Request::segment(count(\Request::segments()));
        $group_obj=groups_m::findOrFail($group_id);
        $this->data["group_obj"]=$group_obj;

        $this->data["meta_title"]=$this->data["group_obj"]->group_name;
        $this->data["meta_desc"]=$this->data["group_obj"]->group_name;
        $this->data["meta_keywords"]=$this->data["group_obj"]->group_name;

        $this->check_your_membership_in_group($group_id,"admin");

        if($this->user_id!=$group_obj->group_owner_id) return abort(404);

        if($request->method()=="POST"){
            groups_m::destroy($group_id);

            $msg = show_content($this->data["validation_messages"],"deleted_successfully_msg");

            return Redirect::to("/")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.group.subviews.delete_group",$this->data);
    }

    #endregion

    #region workshop

    public function load_workshop_posts(Request $request){
        $offset=$request->get("offset","0");
        $workshop_id=$request->get("workshop_id","0");

        $this->data["workshop_obj"]=workshops_m::findOrFail($workshop_id);

        $posts_ids=
            posts_m::
            select("post_id")->
            where("post_where","workshop")->
            where("post_where_id",$workshop_id);

        $next_posts_ids=$posts_ids;

        $posts_ids=$posts_ids->limit($this->post_limit)->
        offset($offset)->
        orderBy("post_id","desc")->
        get()->pluck("post_id")->all();

        $next_posts_ids=$next_posts_ids->limit($this->post_limit)->
        offset($offset+$this->post_limit)->
        get()->pluck("post_id")->all();

        if(isset_and_array($next_posts_ids)){
            $output["post_limit"]=$this->post_limit;
        }

        $output["posts_ids"]=$posts_ids;
        echo json_encode($output);
    }

    public function create_workshop(Request $request){

        $output=[];

        $workshop_name=clean($request->get("workshop_name"));
        $workshop_cat_id=$request->get("workshop_cat_id");

        $workshop_name = string_safe2($workshop_name," ");

        if(strlen($workshop_name)==0){
            echo json_encode($output);
            return;
        }

        if(!($workshop_cat_id>0)){
            echo json_encode($output);
            return;
        }

        $created_workshop_obj=workshops_m::create([
            'owner_id'=>$this->user_id,
            'workshop_name'=>$workshop_name,
            'cat_id'=>$workshop_cat_id
        ]);


        $output["added_html"]=\View::make("front.subviews.groups_workshops.workshop_li")->
        with(["workshop_obj"=>$created_workshop_obj])->
        render();

        echo json_encode($output);

    }

    public function show_workshop(){
        //get group id form last segment of ulr
        $workshop_id=\Request::segment(count(\Request::segments()));
        $this->data['workshop_obj']=workshops_m::leftJoin("attachments","workshops.workshop_logo","=","attachments.id")
            ->where("workshops.workshop_id",$workshop_id)->first();

        $this->data["meta_title"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_desc"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_keywords"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["og_img"] = $this->data['workshop_obj']->path;

        $this->data=array_merge($this->data,posts_events::before_add_post());

        $this->data["workshop_obj"]->update([
            'workshop_visits' => ($this->data["workshop_obj"]->workshop_visits + 1)
        ]);

        $this->data["count_followers"] = false;
        $this->data["is_follower"] = false;

        $check_is_follower = workshop_followers_m::
            where("workshop_id",$this->data["workshop_obj"]->workshop_id)
            ->where("user_id",$this->user_id)->first();

        if (is_object($check_is_follower))
        {
            $this->data["is_follower"] = true;
        }

        $this->data["count_followers"] = workshop_followers_m::where("workshop_id",$workshop_id)->get()->count();

        /**
         * I'm very sorry to do this. But, this bullshit code
         * encourage me to do so.
         *
         * @sorry
         */
        if(!auth()->check())
            $this->data['current_user'] = User::orderBy('created_at', 'desc')->first();

        return view("front.subviews.groups_workshops.workshop.subviews.workshop",$this->data);
    }

    public function delete_workshop(Request $request){
        //get group id form last segment of ulr
        $workshop_id=\Request::segment(count(\Request::segments()));
        $workshop_obj=workshops_m::findOrFail($workshop_id);
        $this->data["workshop_obj"]=$workshop_obj;

        $this->data["meta_title"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_desc"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_keywords"]=$this->data["workshop_obj"]->workshop_name;

        $this->data["count_followers"] = false;
        $this->data["is_follower"] = false;

        if($this->user_id!=$workshop_obj->owner_id) return abort(404);

        if($request->method()=="POST"){
            workshops_m::destroy($workshop_id);

            $msg = show_content($this->data["validation_messages"],"deleted_successfully_msg");

            return Redirect::to("/")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.workshop.subviews.delete",$this->data);
    }

    public function rename_workshop(Request $request){
        //get group id form last segment of ulr
        $workshop_id=\Request::segment(count(\Request::segments()));
        $workshop_obj=workshops_m::findOrFail($workshop_id);
        $this->data["workshop_obj"]=$workshop_obj;

        $this->data["meta_title"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_desc"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_keywords"]=$this->data["workshop_obj"]->workshop_name;

        $this->data["count_followers"] = false;
        $this->data["is_follower"] = false;

        if($this->user_id!=$workshop_obj->owner_id) return abort(404);

        //check if this workshop followers is > 100 or not
        $count_followers= workshop_followers_m::where("workshop_id",$workshop_id)->get()->count();

        if($count_followers>100){
            $msg = show_content($this->data["validation_messages"],"not_allowed_to_rename_workshop");

            return Redirect::to("/workshop/$workshop_obj->workshop_name/$workshop_obj->workshop_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        if($request->method()=="POST"){

            $workshop_name = clean($request->get("workshop_name"));
            $request["workshop_name"]=$workshop_name;

            $workshop_name = string_safe2($workshop_name," ");

            $this->validate($request,[
                "workshop_name"=>"required"
            ]);

            $workshop_obj->update([
                "workshop_name" => $workshop_name
            ]);

            $msg = show_content($this->data["validation_messages"],"msg_success");

            return Redirect::to("/workshop/$workshop_obj->workshop_name/$workshop_obj->workshop_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.workshop.subviews.rename",$this->data);
    }

    public function change_activity_workshop(Request $request){
        //get group id form last segment of ulr
        $workshop_id=\Request::segment(count(\Request::segments()));
        $workshop_obj=workshops_m::findOrFail($workshop_id);
        $this->data["workshop_obj"]=$workshop_obj;
        $old_cat_id = $workshop_obj->cat_id;

        $this->data["parent_activity"] = "";

        $parent_activity = category_m::where("cat_id",$old_cat_id)
                ->where("hide_cat",0)
                ->where("cat_type",'activity')->first();
        abort_if((!is_object($parent_activity)),404);

        $this->data["parent_activity"] = $parent_activity;

        $this->data["meta_title"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_desc"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_keywords"]=$this->data["workshop_obj"]->workshop_name;

        $this->data["count_followers"] = false;
        $this->data["is_follower"] = false;

        if($this->user_id!=$workshop_obj->owner_id) return abort(404);

        if($request->method()=="POST"){

            $cat_id = intval(clean($request->get("cat_id",$old_cat_id)));

            $this->validate($request,[
                "cat_id"=>"required"
            ]);

            $workshop_obj->update([
                "cat_id" => $cat_id
            ]);

            $msg = show_content($this->data["validation_messages"],"msg_success");

            return Redirect::to("/workshop/change_activity/$workshop_obj->workshop_name/$workshop_obj->workshop_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.workshop.subviews.change_activity",$this->data);
    }

    public function change_logo_workshop(Request $request){
        //get group id form last segment of ulr
        $workshop_id=\Request::segment(count(\Request::segments()));
        $workshop_obj=workshops_m::leftJoin("attachments","workshops.workshop_logo","=","attachments.id")
            ->where("workshops.workshop_id",$workshop_id)->first();
        abort_if(!is_object($workshop_obj),404);

        $this->data["workshop_obj"]=$workshop_obj;

        $this->data["meta_title"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_desc"]=$this->data["workshop_obj"]->workshop_name;
        $this->data["meta_keywords"]=$this->data["workshop_obj"]->workshop_name;

        $this->data["count_followers"] = false;
        $this->data["is_follower"] = false;

        if($this->user_id!=$workshop_obj->owner_id) return abort(404);

        if($request->method()=="POST"){

            if (!empty($request["workshop_logo"]))
            {
                $workshop_logo = $this->general_save_img(
                    $request ,
                    $item_id=$workshop_id,
                    "workshop_logo",
                    $new_title = $workshop_obj->workshop_name,
                    $new_alt = $workshop_obj->workshop_name,
                    $upload_new_img_check ="on",
                    $upload_file_path = "/workshops",
                    $width = 500,
                    $height = 500,
                    $photo_id_for_edit = $workshop_obj->workshop_logo
                );

                $workshop_obj->update([
                    "workshop_logo" => $workshop_logo
                ]);
            }


            $msg = show_content($this->data["validation_messages"],"msg_success");

            return Redirect::to("/workshop/$workshop_obj->workshop_name/$workshop_obj->workshop_id")->with("msg","
                <div class='alert alert-info'>$msg</div>
            ")->send();
        }

        return view("front.subviews.groups_workshops.workshop.subviews.change_logo",$this->data);
    }

    public function follow_workshop(Request $request)
    {

        $output = [];
        $output["msg"] = "Not Valid Information Supplied !!";
        $output["status"] = "error";

        $status = clean($request->get('status'));

        $workshop_id = clean($request->get('workshop_id'));
        $workshop_id = intval($workshop_id);


        if (!in_array($status,["follow","unfollow"]) || !($workshop_id > 0))
        {
            echo json_encode($output);
            return;
        }

        $get_workshop = workshops_m::where("workshop_id",$workshop_id)->first();
        if (!is_object($get_workshop))
        {
            echo json_encode($output);
            return;
        }

        if ($status == "follow")
        {
            workshop_followers_m::create([
                'workshop_id' => $workshop_id,
                'user_id' => $this->user_id
            ]);

            //add notification here
            notifications::add_notification([
                'not_title'=>"Followed Your Workshop",
                'not_type'=>"follow",
                'not_link'=>"user/posts/all/$this->user_id",
                'not_from_user_id'=>$this->user_id,
                'not_to_user_id'=>$get_workshop->owner_id
            ]);
            $output["msg"] = show_content($this->data["validation_messages"],"followed_successfully");

        }
        else{
            $get_check = workshop_followers_m::where("workshop_id",$workshop_id)
                ->where("user_id",$this->user_id)->first();

            if (is_object($get_check))
            {
                workshop_followers_m::findOrFail($get_check->id)->delete();
            }

            $output["msg"] = show_content($this->data["validation_messages"],"unfollowed_successfully");

        }

        $output["status"] = "success";

        echo json_encode($output);
        return;

    }

    #endregion





}
