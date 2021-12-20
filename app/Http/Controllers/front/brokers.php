<?php

namespace App\Http\Controllers\front;

//use Illuminate\Http\Request;
use App\models\langs_m;
use App\models\pages\page_select_currency_m;
use App\models\pages\pages_m;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class brokers extends Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->middleware("check_user");
        $slider_arr = array();
        $slider_arr["brokers_keywords"]=["slider1"];
        $this->general_get_content(
            [
                "brokers_keywords"
            ]
            ,$slider_arr
        );

    }

    public function index()
    {

        $current_user = $this->data["current_user"];

        $this->data["brokers"] = [];
        $this->data["brokers_pagination"] = "";

        $this->data['all_brokers'] = page_select_currency_m::get_data(
            $additional_where = " AND page.page_type = 'company'
                AND page.hide_page = 0 ",
            $order_by = " order by page.page_id desc " ,
            $limit = "",
            $check_self_translates = false,
            $default_lang_id=$this->lang_id,
            $load_slider=false);

        $brokers_pagination = pages_m::where("page_type","company")
            ->join('pages_translate', 'pages.page_id', '=', 'pages_translate.page_id')
            ->where("hide_page",0)
            ->orderBy("pages.page_id","desc")->paginate(300);


        $this->data["brokers_pagination"] = $brokers_pagination;

        $get_brokers = $brokers_pagination;
        if (count($get_brokers)) {
            $get_brokers_ids = convert_inside_obj_to_arr($get_brokers, "page_id");
            $get_brokers_ids = implode(',', $get_brokers_ids);
            $this->data["brokers"] = pages_m::get_pages(
                "
                    AND page.page_id in ($get_brokers_ids)
                ",
                $order_by = " order by page.page_id desc ",
                $limit = "",
                $check_self_translates = false,
                $default_lang_id = $this->lang_id
            );
        }
//
//        $brokers_pagination = page_select_currency_m::join("pages","page_select_currency.page_id","=","pages.page_id")
//            ->where("pages.page_type","company")
//            ->where("pages.hide_page",0)
//            ->orderBy("pages.page_id","desc")
//            ->paginate(30);
//
//        $this->data["brokers_pagination"] = $brokers_pagination;
//        if (count($brokers_pagination))
//        {
//            $pages_ids = convert_inside_obj_to_arr($brokers_pagination,"page_id");
//            $pages_ids = implode(',',$pages_ids);
//            $this->data['brokers'] = page_select_currency_m::get_data(
//                $additional_where = " AND page.page_id in ($pages_ids) ",
//                $order_by = " order by page.page_id desc " ,
//                $limit = "",
//                $check_self_translates = false,
//                $default_lang_id=$this->lang_id,
//                $load_slider=false);
//        }

        $this->data["meta_title"]=show_content($this->data["pages_seo"],"brokers_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"brokers_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"brokers_meta_keywords");

        if (is_object($current_user))
        {
            return view('front.subviews.brokers.user_index', $this->data);
        }
        else{
            return view('front.subviews.brokers.visitor_index', $this->data);
        }

    }

    public function load_broker_currencies(Request $request)
    {
        $output["msg"] = "";
        $output["items"] = "";

        $broker_id = intval(clean($request->get("broker_id")));
        if (!($broker_id>0))
        {
            $output["msg"] = "<div class='alert alert-danger'> ".show_content($this->data["brokers_keywords"],"invalid_broker_msg")." </div>";
            echo json_encode($output);
            return;
        }

        $get_items = page_select_currency_m::get_data(
            $additional_where = "
                AND page.page_id = $broker_id
                AND cur.price > 0
                AND page.page_type = 'company'
                AND page.hide_page = 0 ",
            $order_by = " order by page.page_id desc " ,
            $limit = "",
            $check_self_translates = false,
            $default_lang_id=$this->lang_id,
            $load_slider=false);

        if (!count($get_items))
        {
            $output["msg"] = "<div class='alert alert-danger'> ".show_content($this->data["brokers_keywords"],"this_broker_not_have_pair_currencies")." </div>";
            echo json_encode($output);
            return;
        }

        foreach($get_items as $key => $item)
        {
            $output["items"] .= "<option value='$item->pair_currency_id'>$item->pair_currency_name</option>";
        }

        echo json_encode($output);
        return;
    }

    public function calc_broker_trade(Request $request)
    {
        $output["msg"] = "";

        $broker_id = intval(clean($request->get("broker_id")));
        if (!($broker_id>0))
        {
            $output["msg"] = "<div class='alert alert-danger'> ".show_content($this->data["brokers_keywords"],"invalid_broker_msg")." </div>";
            echo json_encode($output);
            return;
        }

        $pair_currency_id = intval(clean($request->get("pair_currency_id")));
        if (!($pair_currency_id>0))
        {
            $output["msg"] = "<div class='alert alert-danger'> ".show_content($this->data["brokers_keywords"],"invalid_select_pair_currency")." </div>";
            echo json_encode($output);
            return;
        }

        $trade_volume = doubleval(clean($request->get("trade_volume")));
        if (!($trade_volume>0))
        {
            $output["msg"] = "<div class='alert alert-danger'> ".show_content($this->data["brokers_keywords"],"invalid_trade_volume")." </div>";
            echo json_encode($output);
            return;
        }

        $get_items = page_select_currency_m::get_data(
            $additional_where = "
                AND page.page_id = $broker_id
                AND pair.pair_currency_id = $pair_currency_id
                AND cur.price > 0
                AND page.page_type = 'company'
                AND page.hide_page = 0
                ",
            $order_by = " order by page.page_id desc " ,
            $limit = "",
            $check_self_translates = false,
            $default_lang_id=$this->lang_id,
            $load_slider=false);

        if (!count($get_items))
        {
            $output["msg"] = "<div class='alert alert-danger'> ".show_content($this->data["brokers_keywords"],"this_broker_not_have_selected_pair_currency")." </div>";
            echo json_encode($output);
            return;
        }

        $get_items = $get_items[0];
        $new_calc = ($trade_volume * $get_items->cur_price);

        $output["msg"] = "<div class='alert alert-success'>
            ".show_content($this->data["brokers_keywords"],"this_broker_of_selected_currency_has_per_lot")." $get_items->cur_price
            <b> ".show_content($this->data["brokers_keywords"],"results_are")." $new_calc </b>
            </div>";


        echo json_encode($output);
        return;
    }

    public function get_broker(Request $request, $page_id)
    {
        $pageId = $request->page_id;
        $current_user = $this->data["current_user"];

        $this->data["broker_data"] = "";

        $broker_data = pages_m::get_pages(
            "
                    AND page_trans.page_slug = '$pageId'
                ",
            $order_by = " ",
            $limit = "",
            $check_self_translates = false,
            $default_lang_id = $this->lang_id
        );

        if (!count($broker_data))
        {
            return abort(404);
        }

        $this->data["broker_data"] = $broker_data[0];
        $this->data['broker_currencies'] = page_select_currency_m::get_data(
                $additional_where = " AND page_trans.page_slug = '$pageId' ",
                $order_by = " order by page.page_id desc " ,
                $limit = "",
                $check_self_translates = false,
                $default_lang_id=$this->lang_id,
                $load_slider=false);


        if (is_object($current_user))
        {
            $this->data['meta_title'] = $broker_data[0]->page_meta_title;
            $this->data['meta_desc'] = $broker_data[0]->page_meta_desc;
            $this->data['meta_keywords'] = $broker_data[0]->page_meta_keywords;

            $this->data['ogImage'] = isset($broker_data[0]->big_img_path) ? $broker_data[0]->big_img_path :  '';
            return view('front.subviews.brokers.user_item', $this->data);
        }
        else{
            $this->data['og_img'] = isset($broker_data[0]->big_img_path) ? url('/') . '/' . $broker_data[0]->big_img_path :  '';
            return view('front.subviews.brokers.visitor_item', $this->data);
        }
    }

}
