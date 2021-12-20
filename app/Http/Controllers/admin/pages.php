<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\models\attachments_m;
use App\models\currency_rates_m;
use App\models\pages\page_select_currency_m;
use App\models\pages\pages_m;
use App\models\pages\pages_translate_m;
use App\models\pages\users_accounts_m;
use App\models\pair_currency_m;
use App\Events\notifications;
use App\Jobs\SendGeneralNotification;
use App\User;

use File;
use Illuminate\Http\Request;
use App\models\category_m;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class pages extends admin_controller
{

    public function __construct()
    {
        parent::__construct();

        //page-types default,article,video,photo_gallery

    }

    public function index(Request $request, $page_type = "default",$cat_id=null)
    {

        if (!in_array($page_type,["default","company","article","news","stock_exchange"]))
        {
            return Redirect::to('admin/dashboard')->send();
        }

        $page_check_permission="admin/pages";

        if ($page_type == "stock_exchange")
        {
            $slider_arr = array();
            $this->general_get_content(
                [
                    "events_keywords"
                ]
                ,$slider_arr
            );
            $page_check_permission="admin/stock_exchange";
        }

        if (!check_permission($this->user_permissions,$page_check_permission,"show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }


        $cat_cond="and page.page_type = '$page_type'";


        $this->data["pages_pagination"] = "";
        $this->data["pages"] = [];

        $pages_pagination = pages_m::join("pages_translate as page_trans","page_trans.page_id","=","pages.page_id")
            ->whereNull("page_trans.deleted_at")
            ->where("pages.page_type","$page_type")
            ->where("page_trans.page_title","<>",'');


        if ($cat_id!=null){
            $pages_pagination = $pages_pagination->where("pages.cat_id",$cat_id);
            $cat_cond.="and page.cat_id = $cat_id";
        }

        $this->data["page_type"]=$page_type;

        $pages = [];
        if ($page_type == "company")
        {
            $this->allowed_lang_ids = [$this->lang_id];
        }

        $this->allowed_lang_ids = array_map("intval",$this->allowed_lang_ids);
        $pages_pagination = $pages_pagination->whereIn("page_trans.lang_id",$this->allowed_lang_ids);

        $order_by_events = "";
        if (count($_GET) && isset($_GET['from_date']) && isset($_GET['to_date']))
        {

            $from_date = $_GET['from_date'];
            $to_date = $_GET['to_date'];

            if (!empty($from_date) && !empty($to_date))
            {
                $cat_cond .= "
                    AND date(page.event_datetime) >= '$from_date'
                    AND date(page.event_datetime) <= '$to_date'

                ";
                $order_by_events = "order by page.event_datetime asc";
                $pages_pagination = $pages_pagination->whereDate("pages.event_datetime",">=","$from_date");
                $pages_pagination = $pages_pagination->whereDate("pages.event_datetime","<=","$to_date");
                $pages_pagination = $pages_pagination->orderBy("pages.event_datetime","desc");

            }

        }


        if ($page_type == "stock_exchange")
        {
            $order_by_events = " order by page.event_datetime desc ";
            $pages_pagination = $pages_pagination->orderBy("pages.event_datetime","desc");
        }
        else{
            $pages_pagination = $pages_pagination->orderBy("pages.page_id","desc");
        }

        $pages_pagination = $pages_pagination->paginate(50);

        if (count($pages_pagination))
        {
            $this->data["pages_pagination"] = $pages_pagination;

            $page_ids = $pages_pagination->pluck('page_id')->all();
            $page_ids = implode(',',$page_ids);

            foreach($this->allowed_lang_ids as $key => $lang_id)
            {

                $results = pages_m::get_pages(
                    "
                    $cat_cond
                    AND page_trans.page_title <> ''
                    AND page.page_id in ($page_ids)
                ",
                    $order_by = $order_by_events ,
                    $limit = "",
                    $check_self_translates = false,
                    $default_lang_id=$lang_id,
                    $load_slider=false
                );

                $pages = array_merge($pages,$results);

            }

            $this->data["pages"] = $pages;
        }

        if ($page_type == "stock_exchange")
        {
            return view("admin.subviews.pages.show_stock_exchange")->with($this->data);
        }


        if ($request->method() == "POST")
        {
            $this->validate($request,[
                "from_date" => "required|date",
                "to_date" => "required|date",
            ]);

            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');

            pages_m::where("page_type","stock_exchange")
                ->whereDate("event_datetime",">=","$from_date")
                ->whereDate("event_datetime","<=","$to_date")->delete();

            return Redirect::to("admin/pages/show_all/stock_exchange")->with(
                ["msg"=>"<div class='alert alert-success'> Data Successfully Deleted </div>"]
            )->send();

        }

        return view("admin.subviews.pages.show")->with($this->data);
    }


    public function save_page(Request $request, $page_type = "default", $page_id = null)
    {

        if (!in_array($page_type,["default","company","article","news","stock_exchange"]))
        {
            return Redirect::to('admin/dashboard')->send();
        }

        $page_check_permission="admin/pages";
        if ($page_type == "stock_exchange")
        {
            $page_check_permission="admin/stock_exchange";
        }

        if(false){
            $all_parent_cats=category_m::get_all_cats(" AND cat.parent_id=0 AND cat.cat_type='article'");
            $all_child_cats=category_m::get_all_cats(" AND cat.parent_id>0 AND cat.cat_type='article'");

            $this->data["all_parent_cats"]=$all_parent_cats;
            $this->data["all_child_cats"]=$all_child_cats;
        }


        if($page_id==null){
            if (!check_permission($this->user_permissions,$page_check_permission,"add_action"))
            {
                return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }
        else{
            if (!check_permission($this->user_permissions,$page_check_permission,"edit_action"))
            {
                return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
            }
        }


        if (is_array($this->data["all_langs"]) && count($this->data["all_langs"]) == 0)
        {
            return Redirect::to('admin/langs/save_lang')->send();
        }

        $this->data["page_type"]=$page_type;


        $big_img_width_height=[
            "height"=>"0",
            "width"=>"0"
        ];

        $small_img_width_height=[
            "height"=>"0",
            "width"=>"0"
        ];

        if ($page_type == "company")
        {
            $big_img_width_height=[
                "height" => 490,
                "width"  => 735
            ];

            $small_img_width_height=[
                "height" => 75,
                "width" => 75
            ];
        }


        if($page_type=="default"){
            $small_img_width_height="";
        }


        $this->data["pairs"] = [];
        $this->data["old_pairs"] = [];
        if($page_type == "company")
        {
            $this->data["pairs"] = pair_currency_m::get()->all();
            if (!count($this->data["pairs"]))
            {
                return  Redirect::to('admin/pair_currency/save_pair_currency')->with(["msg"=>"<div class='alert alert-danger'>Add Pair of Currency First</div>"])->send();
            }
        }

        $this->data["big_img_width_height"]=$big_img_width_height;
        $this->data["small_img_width_height"]=$small_img_width_height;

        $this->data["all_currencies"] = [];
        if ($page_type == "stock_exchange")
        {
            $this->data["all_currencies"] = currency_rates_m::get_all_currencies();
            if (!count($this->data["all_currencies"]))
            {
                return  Redirect::to('admin/currencies/save_currency')->with(
                    ["msg"=>"<div class='alert alert-danger'>Add Currency First</div>"]
                )->send();
            }
        }


        $this->data["page_data"] = "";
        $all_page_translate_rows = collect([]);

        $small_img_id = 0;
        $big_img_id = 0;

        if ($page_id != null)
        {
            $page_result = pages_m::get_pages(" and page.page_id = $page_id ","","",true);
            if(isset_and_array($page_result)){
                $page_result=$page_result[0];

                $page_result->page_small_img=attachments_m::find($page_result->small_img_id);
                $page_result->page_big_img=attachments_m::find($page_result->big_img_id);
            }
            else{
                abort(404);
            }


            $this->data["page_data"] = $page_result;
            $small_img_id = $page_result->small_img_id;
            $big_img_id = $page_result->big_img_id;

            $all_page_translate_rows = pages_translate_m::where("page_id",$page_id)->get();

            $old_pairs = page_select_currency_m::where("page_id",$page_id)->get();
            $old_pairs = collect($old_pairs)->groupBy("pair_currency_id")->all();
            $this->data["old_pairs"] = $old_pairs;

        }

        $this->data["all_page_translate_rows"] = $all_page_translate_rows;


        if ($request->method()=="POST")
        {
            if ($page_type == "company")
            {
                $validator_value = [
                    "page_url"=>$request->get("page_url"),
                    "page_url2"=>$request->get("page_url2"),
                    'page_slug' => $request->get('page_slug')
                ];
                $validator_rule = [
                    "page_url"=>"required",
                    "page_url2"=>"required",
                    'page_slug.0' => 'required|unique:pages_translate,page_slug,' . $page_id . ',page_id'
                ];


                $validator = Validator::make(
                    $validator_value,$validator_rule
                );

                $validator->setAttributeNames([
                    "page_url"=>"Full Url for Demo Account",
                    "page_url2"=>"Full Url for Real Time Account",
                ]);

            }
            else if ($page_type == "stock_exchange")
            {
                $validator_value = [
                 //   "page_title"=>$request->get("page_title"),
                    "event_datetime"=>$request->get("event_datetime"),
                    "current_value"=>$request->get("current_value"),
                    "previous_value"=>$request->get("previous_value"),
                ];
                $validator_rule = [
                 //   "page_title.0"=>"required",
                    "event_datetime"=>"required",
                    "current_value"=>"required",
                    "previous_value"=>"required",
                ];


                $validator = Validator::make(
                    $validator_value,$validator_rule
                );

                $validator->setAttributeNames([
                    "page_title.0"=>"Event Title",
                    "event_datetime"=>"Event Date",
                    "current_value"=>"Current Value",
                    "previous_value"=>"Previous Value",
                ]);

            }
            else{
                $validator_value = [
               //    "page_title"=>$request->get("page_title"),
                // "page_slug"=>$request->get("page_title"),
                ];
                $validator_rule = [
                 //  "page_title.0"=>"required",
                //   "page_slug.0"=>"required",
                ];


                $validator = Validator::make(
                    $validator_value,$validator_rule
                );

                $validator->setAttributeNames([
                    "page_title.0"=>"Name",
                    "page_slug.0"=>"Link",
                ]);
            }


            if (count($validator->messages()) == 0)
            {
                $request["page_type"] = "$page_type";

                if(is_array($big_img_width_height)){
                    $request["big_img_id"] = $this->general_save_img(
                        $request ,
                        $item_id=$page_id,
                        "big_img_file",
                        $new_title = $request["big_img_filetitle"],
                        $new_alt = $request["big_img_filealt"],
                        $upload_new_img_check = $request["big_img_checkbox"],
                        $upload_file_path = "/pages",
                        $width = $big_img_width_height["width"],
                        $height = $big_img_width_height["height"],
                        $photo_id_for_edit = $big_img_id
                    );

                }

                if(is_array($small_img_width_height)){
                    $request["small_img_id"] = $this->general_save_img(
                        $request ,
                        $item_id=$page_id,
                        "small_img_file",
                        $new_title = $request["small_img_filetitle"],
                        $new_alt = $request["small_img_filealt"],
                        $upload_new_img_check = $request["small_img_checkbox"],
                        $upload_file_path = "/pages",
                        $width = 0,
                        $height = 0,
                        $photo_id_for_edit = $small_img_id
                    );
                }



                $request["json_values_of_sliderpage_slider_file"] = json_decode($request->get("json_values_of_sliderpage_slider_file"));

                $request["page_slider"] = $this->general_save_slider(
                    $request,
                    $field_name="page_slider_file",
                    $width=0,
                    $height=0,
                    $new_title_arr = $request->get("page_slider_file_title"),
                    $new_alt_arr = $request->get("page_slider_file_alt"),
                    $json_values_of_slider=$request["json_values_of_sliderpage_slider_file"],
                    $old_title_arr = $request->get("page_slider_file_edit_title"),
                    $old_alt_arr = $request->get("page_slider_file_edit_alt"),
                    $path="/pages/slider"
                );

                $request["page_slider"] = json_encode($request["page_slider"]);

                if (isset($request["related_pages"])){
                    $request["related_pages"]=json_encode($request["related_pages"]);
                }

                $redirect_page_type = $request->get('redirect_page_type','exit');

                $page_obj="";

                if ($page_type == "stock_exchange")
                {
                    $return_users=User::get_users();
                    $users = collect($return_users)->pluck('user_id')->all();
                    /*$delay_time = 0;
                    foreach($return_users as $key => $users)
                    {

                        #region create notification job
                        $users = collect($users)->pluck('user_id')->all();
                        $job = (new SendGeneralNotification($users,'New Economic calendar',"admin_notify","stock_exchange",$this->user_id))->delay($delay_time);
                        $this->dispatch($job);
                        #endregion

                        $delay_time += 1;
                    }


                    /*$jobs=(new SendGeneralNotification($users,'New Economic calendar',"admin_notify","stock_exchange",$this->user_id))->delay(0);
                    print_r($jobs);
                    exit();*/

//                    foreach ($users as $user_id) {
//                        notifications::add_notification([
//                        'not_title'=>"New Economic calendar",
//                        'not_type'=>"admin_notify",
//                        'not_link'=>"stock_exchange",
//                        'not_from_user_id'=>$this->user_id,
//                        'not_to_user_id'=>$user_id ]);
//                    }
                    $request['event_datetime'] = date("Y-m-d H:i:s",strtotime($request->get('event_datetime')));
                };


                if($page_type == 'news' || $page_type == 'article') {

                    $pdf_en = NULL;
                    $pdf_ar = NULL;

                    if(isset($request->pdf_en)) {
                        $extension = $request->file('pdf_en')->getClientOriginalExtension();
                        if($extension !== 'pdf') {
                            $this->data["success"] = "<div class='alert alert-danger'> The uploaded pdf is invalid. Make sure it has PDF extension</div>";
                            return view("admin.subviews.pages.save")->with($this->data);
                        }
                        $pdf_en = $this->cms_upload(
                            $request,
                            auth()->user()->id,
                            'pdf_en',
                            '/pdfs'
                        );
                    }

                    if(isset($request->pdf_ar)) {
                        $extension = $request->file('pdf_ar')->getClientOriginalExtension();
                        if($extension !== 'pdf') {
                            $this->data["success"] = "<div class='alert alert-danger'> The uploaded pdf is invalid. Make sure it has PDF extension</div>";
                            return view("admin.subviews.pages.save")->with($this->data);
                        }
                        $pdf_ar = $this->cms_upload(
                            $request,
                            auth()->user()->id,
                            'pdf_ar',
                            '/pdfs'
                        );
                    }
                }

                // update
                if ($page_id != null)
                {
                    $page_obj=pages_m::find($page_id);
                    $check = $page_obj->update($request->except('pdf_en', 'pdf_ar'));

                    if(isset($pdf_en)) {
                        $page_obj->pdf_en = $pdf_en[0];
                        $page_obj->save();
                    }

                    if(isset($pdf_ar)) {
                        $page_obj->pdf_ar = $pdf_ar[0];
                        $page_obj->save();
                    }

                    if ($check == true)
                    {
                        $this->data["success"] = "<div class='alert alert-success'> Data Successfully Edit </div>";
                        $return_id = $page_id;
                    }
                    else{
                        $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                    }

                }
                else{

                    // insert
                    $page_obj = pages_m::create($request->except('pdf_en', 'pdf_ar'));

                    if(isset($pdf_en)) {
                        $page_obj->pdf_en = $pdf_en[0];
                        $page_obj->save();
                    }

                    if(isset($pdf_ar)) {
                        $page_obj->pdf_ar = $pdf_ar[0];
                        $page_obj->save();
                    }


                    if (is_object($page_obj))
                    {
                        $this->data["success"] = "<div class='alert alert-success'> Data Successfully Inserted </div>";
                        $return_id = $page_obj->page_id;

                    }
                    else{
                        $this->data["success"] = "<div class='alert alert-danger'> Something Is Wrong !!!!</div>";
                    }

                }


                if ($page_type == "company")
                {

                    $price = $request->get('price');
//                    dump($price);
                    if (count($price))
                    {
                        $price_list = [];
                        foreach($this->data["pairs"] as $key => $pair)
                        {
                            $price_val = doubleval($price[$key]);
                            if ($price_val > 0)
                            {
                                $price_list[] = [
                                    "page_id" => $page_obj->page_id,
                                    "pair_currency_id" => $pair->pair_currency_id,
                                    "price" =>$price_val
                                ];
                            }

                        }
//                        dump($price_list);
//                        die();
//                        dump($page_obj->page_prices()->get());
//                        die();
                        if (count($price_list))
                        {
                            $page_obj->page_prices()->sync($price_list);
                        }

                    }

                }


                // save pages_translate
                $input_request = $request->all();

                foreach($this->data["all_langs"] as $lang_key => $lang_item)
                {
                    $inputs = array();
                    $inputs["page_id"] = $return_id;

                    $inputs["page_title"] = array_shift($input_request["page_title"]);

                    if($page_type == "company")
                    {
                        $inputs["page_url"] =  array_shift($input_request["page_url"]);
                        $inputs["page_url2"] =  array_shift($input_request["page_url2"]);
                        $inputs["page_url3"] =  array_shift($input_request["page_url3"]);
                        $inputs["page_short_desc"] =  $request->page_short_desc[$lang_key];
                        $inputs["page_body"] =  $request->page_body[$lang_key];

                        $inputs["page_broker_third_link_title"] =  array_shift($input_request["page_broker_third_link_title"]);
                        $inputs["page_meta_title"] =  $request->page_meta_title[$lang_key];
                        $inputs["page_meta_desc"] =  $request->page_meta_desc[$lang_key];
                        $inputs["page_meta_keywords"] =  $request->page_meta_keywords[$lang_key];
                    }

                    if ($page_type != "stock_exchange")
                    {
                        $inputs["page_slug"] = trim(string_safe(array_shift($input_request["page_slug"])));

                        $inputs["page_short_desc"] =  array_shift($input_request["page_short_desc"]);
                        $inputs["page_body"] =  $request->page_body[$lang_key];

                        $inputs["page_meta_title"] = array_shift($input_request["page_meta_title"]);
                        $inputs["page_meta_desc"] =  array_shift($input_request["page_meta_desc"]);
                        $inputs["page_meta_keywords"] =  array_shift($input_request["page_meta_keywords"]);
                    }


                    $inputs["lang_id"] = $lang_item->lang_id;

                    $toEdit = pages_translate_m::where('page_id', $page_id)
                                                ->where('lang_id', $lang_item->lang_id)
                                                ->first();

                    // edit
                    if (is_object($toEdit))
                    {
                        $toEdit->update($inputs);
                    }
                    else{
                        pages_translate_m::create($inputs);
                    }

                }


                if ($redirect_page_type == 'exit')
                {
                    return Redirect::to("admin/pages/show_all/$page_type")->with(
                        ["msg"=>"<div class='alert alert-success'>Page is Saved Successfully ..</div>"])->send();
                }

                return Redirect::to("admin/pages/save_page/$page_type")->with(
                    ["msg"=>"<div class='alert alert-success'>Page is Saved Successfully ..</div>"])->send();


            }
            else{
                $this->data["errors"] = $validator->messages();
            }

        }

        if ($page_type == "stock_exchange")
        {

            return view("admin.subviews.pages.save_stock_exchange")->with($this->data);
        }

        return view("admin.subviews.pages.save")->with($this->data);
    }


    public function check_validation_for_save_page(Request $request, $page_id = null)
    {
        \Debugbar::disable();
        $output = array();
        $output["msg_type"] = "success";

        $validator_value = [
            "page_title"=>$request->get("page_title"),
        ];
        $validator_rule = [
            "page_title.0"=>"required",
        ];


        $validator = Validator::make(
            $validator_value,$validator_rule
        );

        $validator->setAttributeNames([
            "page_title.0"=>"Name",
        ]);



        if (count($validator->messages()) > 0)
        {
            $output["msg_type"] = "error";
        }
        $output["msg"] = $validator->messages();
        echo json_encode($output);

    }


    public function users_accounts($page_id)
    {
        if (!check_permission($this->user_permissions,"admin/pages","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }

        $this->data["page_data"] = pages_m::get_pages("
            AND page.page_type = 'company'
            AND page.page_id = $page_id

        ");

        abort_if((!count($this->data["page_data"])),404);
        $this->data["page_data"] = $this->data["page_data"][0];

        $this->data["users_accounts"] = users_accounts_m::
            select(DB::raw("
                users_accounts.*,
                users.user_id,
                users.full_name,
                users.username,
                users.ads_balance,
                ref.full_name as 'ref_full_name',
                ref.username as 'ref_username'
            "))
            ->join("users","users.user_id","=","users_accounts.user_id")
            ->leftJoin("users as ref","ref.user_id","=","users_accounts.ref_user_id")
            ->where("page_id",$page_id)->get()->all();

        return view("admin.subviews.pages.users_accounts")->with($this->data);
    }

    public function load_book_pages(Request $request)
    {
        $output = [];
        $output["options"] = "";
        $output["trans_ids"] = "";

        $book_id = intval(clean($request->get('book_id')));

        $get_old_pages = pages_m::where("cat_id",$book_id)->where("page_type","book")->get()->all();

        if (count($get_old_pages))
        {
            $page_ids = convert_inside_obj_to_arr($get_old_pages,"page_id");
            $output["trans_ids"] = implode(',',$page_ids);

            $get_data = pages_translate_m::whereIn("page_id",$page_ids)
                ->where("lang_id",$this->lang_id)->orderBy("trans_order")->get()->all();
            foreach($get_data as $key => $value)
            {
                $output["options"] .= "<option value='".$value->page_id."'>".$value->page_title."</option>";
            }
        }

        echo json_encode($output);
        return;
    }

    public function load_page_translates(Request $request)
    {
        $output = [];
        $output["content_body"] = "";

        $page_id = intval(clean($request->get('page_id')));

        $output["content_body"] = pages_translate_m::where("page_id",$page_id)->get()->all();

        echo json_encode($output);
        return;
    }

    public function save_book_page(Request $request)
    {
        $output = [];
        $output["msg"] = "";

        $book_id = intval(clean($request->get('book_id')));
        $trans_id = intval(clean($request->get('trans_id')));
        $body_content = $request->get('body_content');
        $all_langs = $this->data["all_langs"];

        if ($book_id > 0 && (count($body_content) == count($all_langs)))
        {

            if ($trans_id > 0)
            {
                // edit
//                $get_trans = pages_translate_m::findOrFail($trans_id);
                $get_trans = pages_translate_m::where("page_id",$trans_id)->get()->all();
                if (!count($get_trans))
                {
                    $output["msg"] = "<div class='alert alert-danger'>Invalid Provided Data</div>";
                    echo json_encode($output);
                    return;
                }

                $get_trans = collect($get_trans);

                foreach($all_langs as $lang_key => $lang)
                {
                    $get_body = $body_content["page_body_$lang->lang_id"];
                    pages_translate_m::findOrFail($get_trans->where('lang_id',$lang->lang_id)->first()->id)
                        ->update([
                            "page_body" => $get_body,
                        ]);
//                    pages_translate_m::where("page_id",$get_trans->page_id)
//                        ->where("lang_id",$lang->lang_id)
//                        ->update([
//                            "page_body" => $get_body,
//                        ]);
                }

            }
            else{

                // save
                $get_old_pages = pages_m::where("cat_id",$book_id)->where("page_type","book")->get()->all();

                $new_page = pages_m::create([
                    "cat_id" => $book_id,
                    "page_type" => "book",
                    "show_in_homepage" => 1
                ]);

                foreach($all_langs as $lang_key => $lang)
                {
                    $get_body = $body_content["page_body_$lang->lang_id"];
                    pages_translate_m::create([
                        "page_id" => $new_page->page_id,
                        "page_title" => "Page N.".(count($get_old_pages)+1),
                        "trans_order" => (count($get_old_pages)+1),
                        "page_body" => $get_body,
                        "lang_id" => $lang->lang_id,
                    ]);
                }
            }


            $output["msg"] = "<div class='alert alert-success'>Saved Successfully ...</div>";
        }
        else{
            $output["msg"] = "<div class='alert alert-danger'>Invalid Provided Data</div>";
        }

        echo json_encode($output);
        return;
    }

    // for sortable
    public function load_book_pages_li(Request $request)
    {
        $output = [];
        $output["items"] = "";

        $book_id = intval(clean($request->get('book_id')));

        $get_old_pages = pages_m::where("cat_id",$book_id)->where("page_type","book")->get()->all();

        if (count($get_old_pages))
        {
            $page_ids = convert_inside_obj_to_arr($get_old_pages,"page_id");
            $get_data = pages_translate_m::whereIn("page_id",$page_ids)
                ->where("lang_id",$this->lang_id)->orderBy("trans_order")->get()->all();

            $output["items"] .= "<ul id='pages_sortable'>";
            foreach($get_data as $key => $value)
            {
                $output["items"] .= "<li class='ui-state-default' data-item_id='".$value->id."'>
                    <span class='ui-icon ui-icon-arrowthick-2-n-s'></span>
                    ".$value->page_title.
                    "</li>";
            }
            $output["items"] .= "</ul>";
        }

        echo json_encode($output);
        return;
    }

    public function order_pages_trans(Request $request)
    {
        $output = [];
        $output["msg"] = "";

        $book_id = intval(clean($request->get('book_id')));
        $items = clean($request->get('items'));

        if (count($items))
        {
            foreach($items as $key => $item)
            {
                $get_trans_item = pages_translate_m::find($item);
                if (!is_object($get_trans_item))
                    continue;

                pages_translate_m::where("page_id",$get_trans_item->page_id)
                    ->update([
                        "trans_order" => ($key + 1),
                        "page_title" => "Page N.".($key + 1),
                    ]);

                $output["msg"] = "<div class='alert alert-success'> Pages Successfully Ordered... </div>";

            }
        }

        echo json_encode($output);
        return;
    }

    public function remove_page(Request $request){

        if (check_permission($this->user_permissions,"admin/pages","delete_action"))
        {
            $this->general_remove_item($request,'App\models\pages\pages_m');
            return;
        }


        echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
        return;
    }

    public function search_for_page_name(Request $request){

        $output=[];
        $output["options"]="<option></option>";

        $page_title=$request->get("page_title");

        $pages=pages_m::get_pages(" AND page_trans.page_title like '%$page_title%' AND page.page_type='trip'");


        foreach ($pages as $page){
            $output["options"].="
                <option data-pagetitle='$page->page_title' data-pageid='$page->page_id'>".
                    $page->parent_cat_name."/".$page->child_cat_slug."/".$page->page_title.
                "</option>";
        }

        echo json_encode($output);
    }


}
