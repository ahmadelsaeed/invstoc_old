<?php

namespace App\Http\Controllers\front;

use App\Events\notifications;
use App\models\category_m;
use App\models\followers_m;
use App\models\group_workshop\workshops_m;
use App\models\notification_m;
use App\models\pages\pages_m;
use App\models\pages\users_accounts_m;
use App\models\posts\posts_m;
use App\models\support_messages_m;
use App\models\users_or_pages_images_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class user_profile extends Controller
{
    public function __construct()
    {
        parent::__construct();
      //  $this->middleware("check_user");

        #region get all articles category

        $this->data["categories"]  = category_m::get_all_cats(
            $additional_where = " ",
            $order_by = "" ,
            $limit = "",
            $make_it_hierarchical=false,
            $default_lang_id=$this->lang_id
        );

        #endregion


       /* if(\Request::method() == "get" || \Request::method() == "GET"){


             //$segments = \Request::segments();


            $user_id = 5;

           // $user_id = array_last($segments);

            $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
            if (!count($user_obj)){
                return abort(404);
            }
            $user_obj = $user_obj[0];
            $this->data["user_obj"] = $user_obj;

            $get_media = users_or_pages_images_m::join("attachments","attachment_id","=","attachments.id")
                ->where("user_id_or_users_pages_id",$user_obj->user_id)
                ->orderBy("users_or_pages_image_id","desc")
                ->paginate(20);
            $this->data["medias"] = $get_media->pluck("path");


            $followers = followers_m::
            select(DB::raw("followers.from_user_id , logo.path as logo_path"))
                ->join("users as user_obj","user_obj.user_id","=","followers.from_user_id")
                ->leftJoin("attachments as logo",function ($join)
                {
                    $join->on("user_obj.logo_id","=","logo.id");
                })
                ->leftJoin("attachments as cover",function ($join)
                {
                    $join->on("user_obj.cover_id","=","cover.id");
                })

                ->where("followers.to_user_id",$user_obj->user_id)
                ->where("user_obj.user_type","user")->limit(14)
                ->get()->all();

            $this->data["followers"] = $followers;

        }*/


    }

    public function index(Request $request)
    {

    }

    function personalInfo(Request $request)
    {

        $user_id = $request->user_id;

        if ($user_id != $this->user_id)
        {
            return Redirect::to(session('language_locale', 'en').'/user/posts/all/'.$user_id)->send();
        }

        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!count($user_obj)){
            return abort(404);
        }
        $this->get_user_posts_count($user_id);

        $user_obj = $user_obj[0];


        #region get cat items (fields) to this user
        $user_obj = $this->_get_user_cat_items($user_obj);
        #endregion

        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["meta_title"] = $user_obj->full_name;
        $this->data["meta_desc"] = $user_obj->full_name;
        $this->data["meta_keywords"] = $user_obj->full_name;

//        dump($user_data);


        return view("front.subviews.user.pages.information",$this->data);
    }

    public function editCoverImage(Request $request, $user_id)
    {

        if ($request->user_id != $this->user_id)
        {
            return abort(404);
        }

        $cover_id = $this->data["current_user"]->cover_id;
        $old_cover_id = $cover_id;

        if ($request->method()=="POST")
        {

            $rules_values=[
                "cover_img" => $request["cover_img"],
            ];

            $rules_itself=[
                "cover_img" => "required|mimes:jpeg,png",
            ];

            $rules_messages = [
                "cover_img.required" => (
                    show_content($this->data["user_profile_keywords"],"cover_img")." ".
                    show_content($this->data["validation_messages"],"is_required_field")
                ),
                "cover_img.mimes" => (
                    show_content($this->data["user_profile_keywords"],"cover_img")." ".
                    show_content($this->data["validation_messages"],"not_valid_image")
                ),
            ];

            $validator = \Validator::make($rules_values,$rules_itself,$rules_messages);

            if (count($validator->messages()) == 0)
            {

                $new_cover_id = $this->general_save_img(
                    $request,
                    $item_id=null,
                    "cover_img",
                    $new_title = "",
                    $new_alt = "",
                    $upload_new_img_check = "on",
                    $upload_file_path = "/users/$this->user_id/cover",
                    $width = 900,
                    $height = 300,
                    $photo_id_for_edit = $cover_id
                );


                User::findOrFail($this->user_id)->update([
                    "cover_id" => $new_cover_id
                ]);

                if ($new_cover_id != $old_cover_id)
                {
                    users_or_pages_images_m::create([
                        "attachment_id" => $new_cover_id,
                        "user_id_or_users_pages_id" => $this->user_id,
                        "attachment_type" => 'cover'
                    ]);
                }

                $msg = show_content($this->data["validation_messages"],"msg_success");

                return Redirect::to("information/$this->user_id")->with(
                    ["msg"=>"<div class='alert alert-success'>$msg</div>"]
                )->send();

            }
            else{
                $errors=$validator->messages();
                $errors_msgs = "";


                foreach($errors->all() as $key => $error)
                {
                    $errors_msgs .= $error." || ";
                }

                return Redirect::to("information/$user_id")->with(
                    ["msg"=>"<div class='alert alert-danger'>$errors_msgs</div>"]
                )->send();

            }

        }
    }

    public function editProfileImage(Request $request, $user_id)
    {
        if ($request->user_id != $this->user_id)
        {
            return abort(404);
        }


        $logo_id = $this->data["current_user"]->logo_id;
        $old_logo_id = $logo_id;

        if ($request->method()=="POST")
        {

            $rules_values=[
                "profile_img" => $request["profile_img"],
            ];

            $rules_itself=[
                "profile_img" => "required|mimes:jpeg,png",
            ];

            $rules_messages = [
                "profile_img.required" => (
                    show_content($this->data["user_profile_keywords"],"profile_img")." ".
                    show_content($this->data["validation_messages"],"is_required_field")
                ),
                "profile_img.mimes" => (
                    show_content($this->data["user_profile_keywords"],"profile_img")." ".
                    show_content($this->data["validation_messages"],"not_valid_image")
                ),
            ];

            $validator = Validator::make($rules_values,$rules_itself,$rules_messages);

            if (count($validator->messages()) == 0)
            {

                $new_logo_id = $this->general_save_img(
                    $request,
                    $item_id=null,
                    "profile_img",
                    $new_title = "",
                    $new_alt = "",
                    $upload_new_img_check = "on",
                    $upload_file_path = "/users/$this->user_id/profile",
                    $width = 500,
                    $height = 500,
                    $photo_id_for_edit = $logo_id
                );


                User::findOrFail($this->user_id)->update([
                    "logo_id" => $new_logo_id
                ]);

                if ($new_logo_id != $old_logo_id)
                {
                    users_or_pages_images_m::create([
                        "attachment_id" => $new_logo_id,
                        "user_id_or_users_pages_id" => $this->user_id,
                        "attachment_type" => 'logo'
                    ]);
                }

                $msg = show_content($this->data["validation_messages"],"msg_success");

                return Redirect::to("information/$this->user_id")->with(
                    ["msg"=>"<div class='alert alert-success'>$msg</div>"]
                )->send();

            }
            else{
                $errors=$validator->messages();
                $errors_msgs = "";


                foreach($errors->all() as $key => $error)
                {
                    $errors_msgs .= $error." || ";
                }

                return Redirect::to("information/$user_id")->with(
                    ["msg"=>"<div class='alert alert-danger'>$errors_msgs</div>"]
                )->send();

            }

        }
    }

    public function changeTimezone(Request $request)
    {
        $user_id = $this->user_id;

        if ($request->method() == "POST")
        {

            $this->validate($request,
                [
                    "timezone" => "required",
                ],
                [
                    "timezone.required" => (
                        show_content($this->data["user_profile_keywords"],"about_me_timezone_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                ]
            );


            $inputs = [];

            $inputs["timezone"] = clean($request["timezone"]);

            User::findorFail($user_id)->update($inputs);

            $msg = show_content($this->data["validation_messages"],"msg_success");
            return Redirect::to('information/'.$user_id)->with(
                    ["msg"=>"<div class='alert alert-success'>$msg</div>"]
                )->send();

        }

    }

    public function updatePersonalInfo(Request $request, $user_id)
    {
        $user_id = $request->user_id;
        if ($user_id != $this->user_id)
        {
            return abort(404);
        }

        if ($request->method() == "POST")
        {

            $this->validate($request,
                [
                    "first_name" => "required",
                    "last_name" => "required",
                    "country" => "required",
                    "city" => "required",
                ],
                [
                    "first_name.required" => (
                        show_content($this->data["user_profile_keywords"],"about_me_first_name_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "last_name.required" => (
                        show_content($this->data["user_profile_keywords"],"about_me_second_name_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "country.required" => (
                        show_content($this->data["user_profile_keywords"],"about_me_country_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "city.required" => (
                        show_content($this->data["user_profile_keywords"],"about_me_city_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                ]
            );


            $inputs = [];

            $inputs["first_name"] = clean($request["first_name"]);
            $inputs["last_name"] = clean($request["last_name"]);
            $inputs["full_name"] = $inputs["first_name"]." ".$inputs["last_name"];
            $inputs["gender"] = clean($request["gender"]);
            $inputs["country"] = clean($request["country"]);
            $inputs["city"] = clean($request["city"]);
            $inputs["user_bio"] = clean($request["user_bio"]);
            $inputs["user_interests"] = clean($request["user_interests"]);
            $inputs["birthdate_privacy"] = clean($request["birthdate_privacy"]);
            $inputs["country_privacy"] = clean($request["country_privacy"]);
            $inputs["city_privacy"] = clean($request["city_privacy"]);

            $year = clean($request['year']);
            $month = clean($request['month']);
            $day = clean($request['day']);
            $birthdate = Carbon::create($year,$month,$day)->toDateString();

            $inputs['birthdate'] = $birthdate;

            User::findorFail($user_id)->update($inputs);

            $msg = show_content($this->data["validation_messages"],"msg_success");

            return Redirect::to('information/'.$user_id)->with(
                    ["msg"=>"<div class='alert alert-success'>$msg</div>"]
                )->send();

        }

    }

    public function updateWorkInfo(Request $request, $user_id)
    {
        if ($user_id != $this->user_id)
        {
            return abort(404);
        }

        if ($request->method() == "POST")
        {

            $rules_values=[];

            $rules_itself=[];


            $validator = Validator::make($rules_values,$rules_itself);

            if (count($validator->messages()) == 0)
            {
                $inputs = [];

                $inputs["work_on"] = clean($request["work_on"]);
                $inputs["faculty"] = clean($request["faculty"]);

                if (isset($request["cat_id"]))
                {
                    $cat_ids = clean($request["cat_id"]);

                    $cat_ids = json_encode($cat_ids);
                    $inputs['cat_id'] = $cat_ids;
                }


                User::findorFail($user_id)->update($inputs);

                $msg = show_content($this->data["validation_messages"],"msg_success");

                return Redirect::to('information/'.$user_id)->with(
                    ["msg"=>"<div class='alert alert-success'>$msg</div>"]
                )->send();

            }
            else {

                $errors=$validator->messages();

                return Redirect::to("information/$user_id")->with(
                    ["msg"=>"<div class='alert alert-danger'>$errors</div>"]
                )->send();

            }

        }

    }

    public function updateContactsInfo(Request $request, $user_id)
    {
        if ($user_id != $this->user_id)
        {
            return abort(404);
        }

        if ($request->method() == "POST")
        {

            $rules_values=[
                "country" => $request["country"],
                "city" => $request["city"],
            ];

            $rules_itself=[
                "country" => "required",
                "city" => "required",
            ];

            $rules_messages = [
                "country.required" => (
                    show_content($this->data["user_profile_keywords"],"about_me_country_label")." ".
                    show_content($this->data["validation_messages"],"is_required_field")
                ),
                "city.required" => (
                    show_content($this->data["user_profile_keywords"],"about_me_city_label")." ".
                    show_content($this->data["validation_messages"],"not_valid_image")
                ),
            ];

            $validator = Validator::make($rules_values,$rules_itself,$rules_messages);

            if (count($validator->messages()) == 0)
            {
                $inputs = [];

                $inputs["country"] = clean($request["country"]);
                $inputs["city"] = clean($request["city"]);
                $inputs["address"] = clean($request["address"]);
                $inputs["mobile"] = clean($request["mobile"]);
                $inputs["telephone"] = clean($request["telephone"]);
                $inputs["fax"] = clean($request["fax"]);

                User::findorFail($user_id)->update($inputs);

                $msg = show_content($this->data["validation_messages"],"msg_success");

                return Redirect::to('information/'.$user_id)->with(
                    ["msg"=>"<div class='alert alert-success'>$msg</div>"]
                )->send();

            }
            else {

                $errors=$validator->messages();

                return Redirect::to("information/$user_id")->with(
                    ["msg"=>"<div class='alert alert-danger'>$errors</div>"]
                )->send();

            }

        }

    }

    public function followUser(Request $request)
    {

        $output = [];
        $output["msg"] = "Not Valid Information Supplied !!";
        $output["status"] = "error";

        $user_id = $this->user_id;

        $target_id = clean($request->get('target_id'));
        $target_id = intval($target_id);

        if ($user_id > 0 && $target_id > 0)
        {

            $check_exist = followers_m::where("from_user_id",$user_id)->
                where("to_user_id",$target_id)->get()->first();

            if(!is_object($check_exist))
            {
                followers_m::create([
                    'from_user_id' => $user_id,
                    'to_user_id' => $target_id
                ]);

                //add notification here
                notifications::add_notification([
                    'not_title'=>"Followed You",
                    'not_type'=>"follow",
                    'not_link'=>"user/posts/all/$user_id",
                    'not_from_user_id'=>$user_id,
                    'not_to_user_id'=>$target_id
                ]);
            }

            $output["status"] = "success";
            $output["msg"] = show_content($this->data["validation_messages"],"followed_successfully");
        }


        echo json_encode($output);
        return;
    }

    public function unFollowUser(Request $request)
    {

        $output = [];
        $output["msg"] = "Not Valid Information Supplied !!";
        $output["status"] = "error";

        $user_id = $this->user_id;

        $target_id = clean($request->get('target_id'));
        $target_id = intval($target_id);

        if ($user_id > 0 && $target_id > 0)
        {

            $check_exist = followers_m::where("from_user_id",$user_id)->
            where("to_user_id",$target_id)->get()->first();

            if(is_object($check_exist))
            {
                followers_m::where("from_user_id",$user_id)->
                where("to_user_id",$target_id)->delete();
            }

            $output["status"] = "success";
            $output["msg"] = show_content($this->data["validation_messages"],"unfollowed_successfully");
        }


        echo json_encode($output);
        return;
    }

    public function showMedia(Request $request, $user_id)
    {
        $user_id = $request->user_id;

        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!count($user_obj)){
            return abort(404);
        }
        $this->get_user_posts_count($user_id);


        $user_obj = $user_obj[0];


        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        #region get following and followers data
        $this->_get_followers_followings_data($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;


        $this->data["meta_title"] = $user_obj->full_name." | Media ";
        $this->data["meta_desc"] = $user_obj->full_name." | Media ";
        $this->data["meta_keywords"] = $user_obj->full_name." | Media ";

        $get_media = users_or_pages_images_m::join("attachments","attachment_id","=","attachments.id")
            ->where("user_id_or_users_pages_id",$user_obj->user_id)
            ->orderBy("users_or_pages_image_id","desc")
            ->paginate(21);


        $this->data["get_media"] = $get_media;


        return view("front.subviews.user.pages.media",$this->data);
    }

    public function showFriends(Request $request, $user_id)
    {
        $user_obj = User::get_users("
            AND u.user_id = $request->user_id
        ");
        if (!count($user_obj)){
            return abort(404);
        }
        $this->get_user_posts_count($request->user_id);


        $user_obj = $user_obj[0];

        #region get followers & following
            $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        #region get following and followers data
            $this->_get_followers_followings_data($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["followers_following_data"] = "";

        $followers_followings_ids = [];

        if (isset($request->type) && $request->type == "followers")
        {
            $followers_followings_ids = $user_obj->followers;
            $followers_followings_ids = array_unique($followers_followings_ids);

        }
        else if (isset($request->type) && $request->type == "following")
        {
            $followers_followings_ids = $user_obj->following;
            $followers_followings_ids = array_unique($followers_followings_ids);
        }

        if(count($followers_followings_ids))
        {
            $this->data["followers_following_data"] = User::
            select(DB::raw("

                    users.*,

                    logo.path as 'logo_path',
                    logo.path as 'path',
                    logo.alt as 'logo_alt',
                    logo.title as 'logo_title',

                    cover.path as 'cover_path',
                    cover.alt as 'cover_alt',
                    cover.title as 'cover_title'

                "))
                ->leftJoin("attachments as logo",function ($join)
                {
                    $join->on("users.logo_id","=","logo.id");
                })
                ->leftJoin("attachments as cover",function ($join)
                {
                    $join->on("users.cover_id","=","cover.id");
                })->whereIn("users.user_id",$followers_followings_ids)->paginate(9);

        }


        $this->data["meta_title"] = $user_obj->full_name." | Friends ";
        $this->data["meta_desc"] = $user_obj->full_name." | Friends ";
        $this->data["meta_keywords"] = $user_obj->full_name." | Friends ";


        return view("front.subviews.user.pages.friends",$this->data);
    }

    #region Search Section

    public function advancedSearch(Request $request)
    {

        $slider_arr = array();
        $this->general_get_content(
            [
                "advanced_search"
            ]
            ,$slider_arr
        );

        $user_id = $this->user_id;

        $current_user = $this->data["current_user"];
        $all_except_user_ids = [];
        $followers_ids = [];
        $followings_ids = [];
        if (count($current_user->followers))
        {
            $followers_ids = $current_user->followers;
        }
        if (count($current_user->following))
        {
            $followings_ids = $current_user->following;
        }

        $all_except_user_ids = array_merge($followers_ids,$followings_ids);
        $all_except_user_ids[] = $user_id;

        $search = clean($_GET['search']);
        if (!isset($_GET['tab_type']))
        {
            $suggested_users_paginate = User::
            leftjoin("attachments","logo_id","=","attachments.id")
                ->where("user_type","=","user")
                ->where("user_can_login",1)
                ->where("user_is_blocked",0)
                ->where(function ($query) use($search){
                    $query->where("full_name","like","%$search%")
                        ->orWhere("username","like","%$search%")
                        ->orWhere("email","like","%$search%");
                })
                ->select("attachments.path as logo_path","users.*")
                ->paginate(12);

            $this->get_matched_friends($search,$all_except_user_ids);

            $this->data["workshops"] = workshops_m::join("users","workshops.owner_id","=","users.user_id")
                ->leftJoin("attachments","workshops.workshop_logo","=","attachments.id")
                ->where("workshops.workshop_name","like","%$search%")
                ->orderBy("workshops.workshop_id","desc")->paginate(8);

            $search_books = category_m::get_all_cats(
                $additional_where = "
                        AND cat_translate.cat_name like '%$search%'
                        AND cat.hide_cat=0
                        AND cat.cat_type='book'
                    ",
                $order_by = " order by cat_order " ,
                $limit = " limit 8 ",
                $make_it_hierarchical=false,
                $default_lang_id=$this->lang_id
            );

            $this->data["books"]=$search_books;

            $this->data["suggested_users_paginate"] = $suggested_users_paginate;
            $suggested_users = $suggested_users_paginate->all();
        }
        else{

            if($_GET['tab_type'] == 'friends')
                $this->get_matched_friends($search,$all_except_user_ids);
            elseif ($_GET['tab_type'] == "orders")
            {
                $this->data["post_type"]="order";
                $this->data["user_id"]=$user_id;
            }
            elseif ($_GET['tab_type'] == "posts")
            {
                $this->data["post_type"]="post";
                $this->data["user_id"]=$user_id;
            }
            elseif ($_GET['tab_type'] == "books")
            {

                $search_books = category_m::get_all_cats(
                    $additional_where = "
                        AND cat_translate.cat_name like '%$search%'
                        AND cat.hide_cat=0
                        AND cat.cat_type='book'
                    ",
                    $order_by = " order by cat_order " ,
                    $limit = " limit 8 ",
                    $make_it_hierarchical=false,
                    $default_lang_id=$this->lang_id
                );

                $this->data["books"]=$search_books;

            }
            elseif ($_GET['tab_type'] == "workshops")
            {
                $this->data["workshops"] = workshops_m::join("users","workshops.owner_id","=","users.user_id")
                    ->leftJoin("attachments","workshops.workshop_logo","=","attachments.id")
                    ->where("workshops.workshop_name","like","%$search%")
                    ->orderBy("workshops.workshop_id","desc")->paginate(8);
            }

        }


        return view("front.subviews.advanced_search",$this->data);

    }

    private function get_matched_friends($search = "",$all_except_user_ids)
    {
        $this->data["suggested_users"] = [];
        $this->data["suggested_users_paginate"] = "";

        if (!isset($_GET['search']) || empty($_GET['search']))
        {

            $suggested_users_paginate = User::
            leftjoin("attachments","logo_id","=","attachments.id")
                ->whereNotIn("user_id",$all_except_user_ids)
                ->where("user_type","=","user")
                ->where("user_can_login",1)
//                ->where("user_active",1)
                ->where("user_is_blocked",0)
                ->select("attachments.path as logo_path","users.*")
                ->paginate(12);

            $this->data["suggested_users_paginate"] = $suggested_users_paginate;
            $suggested_users = $suggested_users_paginate->all();

        }
        else{

            $search = clean($_GET['search']);
            $suggested_users_paginate = User::
            leftjoin("attachments","logo_id","=","attachments.id")
//                ->whereNotIn("user_id",$all_except_user_ids)
                ->where("user_type","=","user")
                ->where("user_can_login",1)
//                ->where("user_active",1)
                ->where("user_is_blocked",0)
                ->where(function ($query) use($search){
                    $query->where("full_name","like","%$search%")
                        ->orWhere("username","like","%$search%")
                        ->orWhere("email","like","%$search%");
                })
                ->select("attachments.path as logo_path","users.*")
                ->paginate(12);

            $this->data["suggested_users_paginate"] = $suggested_users_paginate;
            $suggested_users = $suggested_users_paginate->all();
        }

        if (count($suggested_users))
        {
            foreach($suggested_users as $key => $suggested_user)
            {
                #region get followers & following
                $suggested_user = $this->_get_followers_followings($suggested_user);
                #endregion

                $this->data["suggested_users"][] = $suggested_user;
                if ($suggested_user->user_id == 15)
                {
//                    dump($suggested_user);
                }

            }
        }
        else{
            $this->data["suggested_users"] = [];
        }


    }

    public function get_matched_user_posts(Request $request,$post_type,$user_id){

        if (!in_array($post_type,["all","order","post"])){
            return abort(404);
        }
        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!isset_and_array($user_obj)){
            return abort(404);
        }

        $this->get_user_posts_count($user_id);


        $user_obj = $user_obj[0];

        $offset=clean($request->get("offset","0"));

        $target_search = clean($request->get("target_search",""));

        $posts_ids=
            posts_m::
                select("posts.post_id")->
                join("users","posts.user_id","=","users.user_id");

        if (!empty($target_search))
        {
            $posts_ids = $posts_ids->where(function ($query) use($target_search){
                $query->where("posts.post_body",'like',"%$target_search%")
                    ->orWhere("users.full_name",'like',"%$target_search%")
                    ->orWhere("users.username",'like',"%$target_search%");
            });
        }

        if($post_type=="post"){
            $posts_ids=$posts_ids->where("posts.post_or_recommendation","post");
        }

        if($post_type=="order"){

            $not_closed = $request->get('not_closed','');
            if (!empty($not_closed))
            {
                $posts_ids = $posts_ids->where("posts.closed_price",0);
            }

            $posts_ids=$posts_ids->where("posts.post_or_recommendation","recommendation");
        }


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

    #endregion

    public function show_user_posts_page(Request $request, $post_type,$user_id){

        $post_type = $request->post_type;
        $user_id = $request->user_id;

        if (!in_array($post_type,["all","order","post"])){
            return abort(404);
        }

        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!isset_and_array($user_obj)){
            return abort(404);
        }

         $get_media = users_or_pages_images_m::join("attachments","attachment_id","=","attachments.id")
                ->where("user_id_or_users_pages_id",$user_id)
                ->orderBy("users_or_pages_image_id","desc")
                ->paginate(15);
            $this->data["medias"] = $get_media->pluck("path");


            $followers = followers_m::
            select(DB::raw("followers.from_user_id , logo.path as logo_path"))
                ->join("users as user_obj","user_obj.user_id","=","followers.from_user_id")
                ->leftJoin("attachments as logo",function ($join)
                {
                    $join->on("user_obj.logo_id","=","logo.id");
                })
                ->leftJoin("attachments as cover",function ($join)
                {
                    $join->on("user_obj.cover_id","=","cover.id");
                })

                ->where("followers.to_user_id",$user_id)
                ->where("user_obj.user_type","user")->limit(14)
                ->get()->all();

            $this->data["followers"] = $followers;

        $this->get_user_posts_count($user_id);

        $user_obj=$user_obj[0];
        $user_obj = $this->_get_followers_followings($user_obj);


        $this->data["post_type"]=$post_type;
        $this->data["user_id"]=$user_id;


        $this->data["user_obj"] = $user_obj;

        if ($post_type != "all")
        {
            $post_type = $post_type."s";
        }

        $this->data["meta_title"] = $user_obj->full_name." - ".$post_type;
        $this->data["meta_desc"] = $user_obj->full_name." - ".$post_type;
        $this->data["meta_keywords"] = $user_obj->full_name." - ".$post_type;
        $this->data['og_image'] = url('/') . '/' . $user_obj->logo_path;

        /**
         * I'm very sorry to do this. But, this bullshit code
         * encourage me to do so.
         *
         * @sorry
         */
        if(!auth()->check())
            $this->data['current_user'] = User::orderBy('created_at', 'desc')->first();


        return view("front.subviews.user.pages.show_user_posts",$this->data);
    }

    public function get_user_posts(Request $request,$post_type,$user_id){

        $post_type = request()->segment(4);
        $user_id = request()->segment(5);

        if (!in_array($post_type,["all","order","post"])){
            return abort(404);
        }
        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!isset_and_array($user_obj)){
            return abort(404);
        }

        $this->get_user_posts_count($user_id);


        $user_obj=$user_obj[0];

        $offset=clean($request->get("offset","0"));

        $posts_ids=
            posts_m::
            select("post_id")->
            where("user_id",$user_id);


        if($post_type=="post"){
            $posts_ids=$posts_ids->where("post_or_recommendation","post");
        }

        if($post_type=="order"){

            $not_closed = $request->get('not_closed','');
            if (!empty($not_closed))
            {
                $posts_ids = $posts_ids->where("closed_price",0);
            }

            $posts_ids=$posts_ids->where("post_or_recommendation","recommendation");
        }


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


    public function get_user_posts_count($user_id){
        //get posts count
        $this->data["posts_count"]=posts_m::
        where("post_or_recommendation","post")->
        where("user_id",$user_id)->count();

        $this->data["recommendations_count"]=posts_m::
        where("post_or_recommendation","recommendation")->
        where("user_id",$user_id)->count();
    }


    public function requestVerification()
    {
        $user_id = $this->user_id;

        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!count($user_obj)){
            return abort(404);
        }
        $this->get_user_posts_count($user_id);

        $user_obj = $user_obj[0];


        #region get cat items (fields) to this user
        $user_obj = $this->_get_user_cat_items($user_obj);
        #endregion

        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["meta_title"] = $user_obj->full_name;
        $this->data["meta_desc"] = $user_obj->full_name;
        $this->data["meta_keywords"] = $user_obj->full_name;

        return view("front.subviews.user.pages.request_verification",$this->data);

    }

    public function requestPrivetAccount(Request $request, $user_id)
    {
        if ($user_id != $this->user_id)
        {
            return abort(404);
        }

        if ($request->method() == "POST")
        {

            $current_user = $this->data["current_user"];

            $phone = clean($request->get("phone"));
            $full_name = clean($request->get("full_name"));
            $message = clean($request->get("message"));

//            $img_id = $this->general_save_img(
//                $request,
//                $item_id=null,
//                "attach_file",
//                $new_title = "",
//                $new_alt = "",
//                $upload_new_img_check = "on",
//                $upload_file_path = "/users/$this->user_id/verification",
//                $width = 0,
//                $height = 00,
//                $photo_id_for_edit = 0
//            );

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
                $path="/users/$this->user_id/verification"
            );

            $img_ids = json_encode($img_ids);

            support_messages_m::create([
                "user_id" => $this->user_id,
                "msg_type" => "request_privet_account",
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
                $subject = "Request Account Verify",
                $sender = "info@invstoc.com" ,
                $path_to_file = "",
                $email_template ="email.verify_account_request"
            );


            $msg = show_content($this->data["validation_messages"],"msg_success_and_wait_admin");
            return Redirect::to('information/'.$user_id)->with(
                ["msg"=>"<div class='alert alert-success'>$msg</div>"]
            )->send();

        }

    }

    public function changePassword(Request $request)
    {
        $user_id = $this->user_id;

        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!count($user_obj)){
            return abort(404);
        }
        $this->get_user_posts_count($user_id);

        $user_obj = $user_obj[0];


        #region get cat items (fields) to this user
        $user_obj = $this->_get_user_cat_items($user_obj);
        #endregion

        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["meta_title"] = $user_obj->full_name;
        $this->data["meta_desc"] = $user_obj->full_name;
        $this->data["meta_keywords"] = $user_obj->full_name;


        if ($request->method() == "POST")
        {

            $this->validate($request,
                [
                    "old_password" => "required|min:3",
                    "password" => "required|min:3|confirmed",
                    "password_confirmation" => "required|min:3",
                ],
                [
                    "old_password.required" => (
                        show_content($this->data["user_profile_keywords"],"enter_old_password_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "password.required" => (
                        show_content($this->data["user_profile_keywords"],"enter_new_password_label")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "password.confirmed" => (
                        show_content($this->data["user_profile_keywords"],"enter_new_password_label")." ".
                        show_content($this->data["validation_messages"],"is_confirmed_password_field")
                    ),
                    "password_confirmation.required" => (
                        show_content($this->data["user_profile_keywords"],"confirm_new_password")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                ]
            );

            $old_password = clean($request->get('old_password'));
            $new_password = clean($request->get('password'));

            if(crypt($old_password, $user_obj->password)!=$user_obj->password){
                $msg = show_content($this->data["user_profile_keywords"],"enter_old_password_label")." ".
                    show_content($this->data["validation_messages"],"not_valid_label");
                return Redirect::to("change_password")->with(
                    ["msg"=>"<div class='alert alert-danger'>$msg</div>"]
                )->send();
            }

            $inputs = [];
            $inputs['password'] = bcrypt($new_password);
            User::findorFail($user_id)->update($inputs);
            $msg = show_content($this->data["validation_messages"],"msg_success_and_relogin");
            return Redirect::to('logout')->with(
                ["msg"=>"<div class='alert alert-success'>$msg</div>"]
            )->send();

        }


        return view("front.subviews.user.pages.change_password",$this->data);

    }


    #region referrer link

    public function request_referrer_link(Request $request)
    {
        $user_id = $this->user_id;
        $current_user = $this->data["current_user"];

        $user_obj = $current_user;
        if (!is_object($user_obj) || $user_obj->request_referrer_link == 1){
            return abort(404);
        }
        $this->get_user_posts_count($user_id);


        #region get cat items (fields) to this user
        $user_obj = $this->_get_user_cat_items($user_obj);
        #endregion

        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["meta_title"] = $user_obj->full_name;
        $this->data["meta_desc"] = $user_obj->full_name;
        $this->data["meta_keywords"] = $user_obj->full_name;


        if ($request->method() == "POST")
        {

            $phone = clean($request->get("phone"));
            $full_name = clean($request->get("full_name"));

            User::findOrFail($this->user_id)->update([
                "request_referrer_link" => 1
            ]);

            support_messages_m::create([
                "user_id" => $this->user_id,
                "msg_type" => "request_referrer_link",
                "name" => $current_user->full_name." (".$current_user->username.")",
                "full_name" => $full_name,
                "email" => $current_user->email,
                "tel" => $phone,
            ]);

            $this->_send_email_to_all_users_type(
                $user_type = "admin" ,
                $data =
                    [
                        "name" => $current_user->full_name,
                        "code" => $current_user->username,
                    ] ,
                $subject = "Request Referrer Link",
                $sender = "info@invstoc.com" ,
                $path_to_file = "",
                $email_template ="email.request_referrer_link"
            );


            $msg = show_content($this->data["validation_messages"],"msg_success_and_wait_admin");
            return Redirect::to('information/'.$user_id)->with(
                ["msg"=>"<div class='alert alert-success'>$msg</div>"]
            )->send();

        }

        return view("front.subviews.user.pages.request_referrer_link",$this->data);

    }

    public function add_accounts_under_referrer_link(Request $request)
    {
        $ref_link = clean($request->get('ref'));

        if (empty($ref_link))
        {
            return abort(404);
        }

        $user_obj = User::get_users("

            AND u.referrer_link = '$ref_link'
            AND u.user_id <> $this->user_id

        ");


        abort_if((!count($user_obj)),404);
        $user_obj = $user_obj[0];

        $user_id = $user_obj->user_id;
        $this->data["ref_link"] = $ref_link;


        $this->get_user_posts_count($user_id);


        #region get cat items (fields) to this user
        $user_obj = $this->_get_user_cat_items($user_obj);
        #endregion

        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["meta_title"] = $user_obj->full_name;
        $this->data["meta_desc"] = $user_obj->full_name;
        $this->data["meta_keywords"] = $user_obj->full_name;


        // get current user accounts
        $this->data["select_items"] = "";
        $accounts_cond = "
            AND page.hide_page = 0
            AND page.page_type = 'company'
        ";

        $get_user_accounts = users_accounts_m::where("user_id",$this->user_id)->get()->all();

        $user_accounts = collect($get_user_accounts)->pluck("page_id")->all();

        if (count($user_accounts))
        {

            $pages_ids = implode(',',$user_accounts);

            $accounts_cond .= " AND page.page_id not in ($pages_ids) ";
        }

        $accounts = pages_m::get_pages("$accounts_cond");

        if (count($accounts))
        {
            foreach($accounts as $key => $account)
            {
                $this->data["select_items"] .= "<option value='$account->page_id'>$account->page_title</option>";
            }
        }

        if($request->method() == "POST")
        {

            $this->validate($request,
                [
                    "account_number" => "required",
                    "page_id" => "required",
                ],
                [
                    "account_number.required" =>
                        (
                            show_content($this->data["user_homepage_keywords"],"account_number_label")." ".
                            show_content($this->data["validation_messages"],"is_required_field")
                        ),
                    "page_id.required" =>
                        (
                            show_content($this->data["user_homepage_keywords"],"brokers_link")." ".
                            show_content($this->data["validation_messages"],"is_required_field")
                        ),
                ]
            );


            $request = clean($request->all());

            $page_id = $request["page_id"];
            $account_number = $request["account_number"];

            // check if exist
            $check_exist = users_accounts_m::where("user_id",$this->user_id)
                ->where("page_id",$page_id)->get()->first();
            if (is_object($check_exist))
            {

                return Redirect::to("referrer_link?ref=$ref_link")->with(
                    ["msg"=>"<div class='alert alert-danger'>Account is Existing !</div>"]
                )->send();

            }
            else{

                users_accounts_m::create([
                    "page_id" => $page_id,
                    "user_id" => $this->user_id,
                    "ref_user_id" => $user_obj->user_id,
                    "account_number" => $account_number,
                ]);

                User::findOrFail($user_obj->user_id)->update([
                    "referrer_count" => ($user_obj->referrer_count + 1)
                ]);

                #region send email to admins

                $get_company = pages_m::get_pages(
                    $additional_where = "
                        AND page.page_type = 'company'
                        AND page.hide_page = 0
                        AND page.page_id = $page_id
                    ",
                    $order_by = " order by page.page_id " ,
                    $limit = "",
                    $check_self_translates = false,
                    $default_lang_id=$this->lang_id,
                    $load_slider=false
                );
                $get_company = $get_company[0];

                $current_user = $this->data["current_user"];

                $data = " user '$current_user->full_name' with code '$current_user->username'
                        has added new account under company '$get_company->page_title'
                        with account number '$account_number'
                     ";
                $this->_send_email_to_all_users_type(
                    $user_type = "admin" ,
                    $data =
                        [
                            "body" => $data,
                        ] ,
                    $subject = "New Account under Company '$get_company->page_title'",
                    $sender = "info@invstoc.com" ,
                    $path_to_file = "",
                    $email_template ="email.body_template"
                );

                #endregion

                return Redirect::to("referrer_link?ref=$ref_link")->with(
                    ["msg"=>"<div class='alert alert-success'>Account is saved successfully !</div>"]
                )->send();

            }

        }

        return view("front.subviews.user.pages.add_accounts_under_referrer_link",$this->data);

    }

    #endregion


    public function user_report(Request $request, $user_id)
    {

        $user_id = $request->user_id;

        $user_obj = User::get_users("
            AND u.user_id = $user_id
        ");
        if (!count($user_obj)){
            return abort(404);
        }

        $user_obj = $user_obj[0];

        $this->get_user_posts_count($user_id);


        #region get cat items (fields) to this user
        $user_obj = $this->_get_user_cat_items($user_obj);
        #endregion

        #region get followers & following
        $user_obj = $this->_get_followers_followings($user_obj);
        #endregion

        $this->data["user_obj"] = $user_obj;

        $this->data["meta_title"] = $user_obj->full_name;
        $this->data["meta_desc"] = $user_obj->full_name;
        $this->data["meta_keywords"] = $user_obj->full_name;


        #region statistics

            $get_orders_profit = posts_m::where([
                "user_id" => $user_id,
                "recommendation_status" => 'profit',
            ])->count();

            $get_orders_lose = posts_m::where([
                "user_id" => $user_id,
                "recommendation_status" => 'lose',
            ])->count();

            $get_orders_equal = posts_m::where([
                "user_id" => $user_id,
                "recommendation_status" => 'equal',
            ])->count();

            $this->data["orders_statistics"] = [
                "profit" => $get_orders_profit,
                "equal" => $get_orders_equal,
                "loose" => $get_orders_lose,
            ];


            $get_all_profit = posts_m::where([
                "user_id" => $user_id,
                "recommendation_status" => 'profit',
            ])->get();

             $get_sum_profit=0;
             foreach ($get_all_profit as $get_profit) {
                 $get_sum_profit+=calc_order_diff_price($get_profit);
             }


            $get_all_lose = posts_m::where([
                "user_id" => $user_id,
                "recommendation_status" => 'lose',
            ])->get();

            $get_sum_lose=0;
             foreach ($get_all_lose as $get_lose) {
                 $get_sum_lose+=calc_order_diff_price($get_lose);
             }

            $this->data["profit_lose_statistics"] = [
                "profit" => $get_sum_profit,
                "lose" => $get_sum_lose,
            ];


        #endregion


        #fillter Performance

            $from_date = clean($request->get('from_date',''));
            $to_date = clean($request->get('to_date',''));

            $this->data["performance_report"] = [];

            if (!empty($from_date) && !empty($to_date))
            {

                $from_date = date("Y-m-d",strtotime($from_date));
                $to_date = date("Y-m-d",strtotime($to_date));



                     $get_orders_profit = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'profit',
                    ])->whereDate("created_at",">=",$from_date)
                     ->whereDate("created_at","<=",$to_date)
                     ->count();

                    $get_orders_lose = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'lose',
                    ])->whereDate("created_at",">=",$from_date)
                     ->whereDate("created_at","<=",$to_date)
                     ->count();

                    $get_orders_equal = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'equal',
                    ])->whereDate("created_at",">=",$from_date)
                     ->whereDate("created_at","<=",$to_date)
                     ->count();

                    $this->data["orders_statistics"] = [
                        "profit" => $get_orders_profit,
                        "equal" => $get_orders_equal,
                        "loose" => $get_orders_lose,
                    ];


                    $get_all_profit = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'profit',
                    ])->whereDate("created_at",">=",$from_date)
                     ->whereDate("created_at","<=",$to_date)
                     ->get();

                     $get_sum_profit=0;
                     foreach ($get_all_profit as $get_profit) {
                         $get_sum_profit+=calc_order_diff_price($get_profit);
                     }


                    $get_all_lose = posts_m::where([
                        "user_id" => $user_id,
                        "recommendation_status" => 'lose',
                    ])->whereDate("created_at",">=",$from_date)
                     ->whereDate("created_at","<=",$to_date)
                     ->get();

                    $get_sum_lose=0;
                     foreach ($get_all_lose as $get_lose) {
                         $get_sum_lose+=calc_order_diff_price($get_lose);
                     }

                    $this->data["profit_lose_statistics"] = [
                        "profit" => $get_sum_profit,
                        "lose" => $get_sum_lose,
                    ];



            }

        #endregion


        return view("front.subviews.user.pages.user_report",$this->data);

    }

}
