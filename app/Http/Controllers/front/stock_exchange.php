<?php

namespace App\Http\Controllers\front;

//use Illuminate\Http\Request;
use App\models\langs_m;
use App\models\pages\page_select_currency_m;
use App\models\pages\pages_m;
use Carbon\Carbon;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class stock_exchange extends Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->middleware("check_user");
        $this->data["Months_names"] = [
            "1" => "Jan",
            "2" => "Feb",
            "3" => "Mar",
            "4" => "Apr",
            "5" => "May",
            "6" => "Jun",
            "7" => "Jul",
            "8" => "Aug",
            "9" => "Sep",
            "10" => "Oct",
            "11" => "Nov",
            "12" => "Dec",
        ];

        $slider_arr = array();
        $this->general_get_content(
            [
                "events_keywords"
            ]
            ,$slider_arr
        );

    }

    public function index()
    {

        $current_user = $this->data["current_user"];
        $timezone =  $this->data["default_timezone"];
        if (is_object($current_user))
        {
            $timezone = $current_user->timezone;
        }

        $this->data["events_group"] = [];
        $this->data["selected_timezone"] = $timezone;
        $this->data["selected_time_format"] = "12";
        $this->data["target"] = "today";
        $this->data["summer_time"] = "";

        $cond = "";

        $this->data["meta_title"]=show_content($this->data["pages_seo"],"stock_exchange_meta_title");
        $this->data["meta_desc"]=show_content($this->data["pages_seo"],"stock_exchange_meta_description");
        $this->data["meta_keywords"]=show_content($this->data["pages_seo"],"stock_exchange_meta_keywords");

        if (isset($_GET["timezone"]))
        {
            $this->data["selected_timezone"] = $_GET["timezone"];
        }

        if (isset($_GET["summer_time"]))
        {
            $this->data["summer_time"] = $_GET["summer_time"];
        }

        if (isset($_GET["group_by"]))
        {
            $this->data["selected_time_format"] = $_GET["group_by"];
        }

        if (isset($_GET["target"]))
        {
            $target = $_GET["target"];
            $this->data["target"] = $target;

            if ($target == "yesterday")
            {
                $yesterday_date = Carbon::createFromTimestamp(strtotime(date("Y-m-d")))->subDays(1)->toDateString();
                $cond .= "  AND date(page.event_datetime) = '$yesterday_date'  ";
            }
            elseif($target == "today")
            {
                $current_date = date("Y-m-d");
                $cond .= " AND date(page.event_datetime) = '$current_date' ";
            }
            elseif($target == "tomorrow")
            {
                $tomorrow_date = Carbon::createFromTimestamp(strtotime(date("Y-m-d")))->addDays(1)->toDateString();
                $cond .= "  AND date(page.event_datetime) = '$tomorrow_date'  ";
            }
            elseif($target == "week")
            {
                $get_week_start = Carbon::createFromTimestamp(strtotime(date("Y-m-d")))->startOfWeek()->toDateString();
                $get_week_end = Carbon::createFromTimestamp(strtotime(date("Y-m-d")))->endOfWeek()->toDateString();
                $cond .= " 
                    AND date(page.event_datetime) >= '$get_week_start' 
                    AND date(page.event_datetime) <= '$get_week_end' 
                 ";
            }
            elseif($target == "next_week")
            {
                $get_currenct_week_end = Carbon::createFromTimestamp(strtotime(date("Y-m-d")))->endOfWeek()->toDateString();

                $get_week_start = Carbon::createFromTimestamp(strtotime($get_currenct_week_end))->addWeek()->startOfWeek()->toDateString();
                $get_week_end = Carbon::createFromTimestamp(strtotime($get_currenct_week_end))->addWeek()->endOfWeek()->toDateString();

                $cond .= " 
                    AND date(page.event_datetime) >= '$get_week_start' 
                    AND date(page.event_datetime) <= '$get_week_end' 
                 ";
            }
            elseif($target == "range_date")
            {

                if (isset($_GET['start_date']) && !empty($_GET['start_date']))
                {
                    $start_date = clean($_GET['start_date']);
                    $start_date = date("Y-m-d",strtotime($start_date));

                    $cond .= " 
                        AND date(page.event_datetime) >= '$start_date' 
                     ";
                }

                if (isset($_GET['end_date']) && !empty($_GET['end_date']))
                {
                    $end_date = clean($_GET['end_date']);
                    $end_date = date("Y-m-d",strtotime($end_date));

                    $cond .= " 
                        AND date(page.event_datetime) <= '$end_date' 
                     ";
                }

            }

        }
        else{
            $current_date = date("Y-m-d");
            $cond .= " AND date(page.event_datetime) = '$current_date' ";
        }

        $events_group = pages_m::get_pages(
            " 
                    AND page.page_type = 'stock_exchange'
                    AND page.hide_page = 0
                    $cond
                ",
            $order_by = " order by page.event_datetime asc ",
            $limit = "",
            $check_self_translates = false,
            $default_lang_id = $this->lang_id
        );

        if (count($events_group))
        {
            $events_group = collect($events_group)->groupBy("event_date")->all();
            $this->data["events_group"] = $events_group;
        }

        if (is_object($current_user))
        {
            return view('front.subviews.stock_exchange.user_view_index',$this->data);
        }
        else{
            return view('front.subviews.stock_exchange.visitor_view_index',$this->data);
        }

    }

    public function events(Request $request)
    {

        $output = [];
        $output["events"] = "";
        $output["events_keywords"] = $this->data["events_keywords"];
        $output["general_static_keywords"] = $this->data["general_static_keywords"];

        $current_datetime = date("Y-m-d H:i:00");
        $target_datetime = \Carbon\Carbon::createFromTimestamp(strtotime($current_datetime))->addHour(4)->toDateTimeString();

        $current_user = $this->data["current_user"];

        // get events to next 4 hours
        /*
        AND page.event_datetime >= '$current_datetime' 
        AND page.event_datetime <= '$target_datetime'*/
        
        $get_events = pages_m::get_pages("
                AND page.page_type = 'stock_exchange'
                AND ( page.event_datetime > '$current_datetime' OR page.event_datetime = '$current_datetime' ) 

                
                order by page.event_datetime asc
                LIMIT 5
            ");



        foreach($get_events as $key => $event)
        {
            $event_datetime = $event->event_datetime;
            $event_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s', strtotime("$event_datetime"))) ;
            $event_datetime = $event_datetime->setTimezone($current_user->timezone)->format('g:i A');

            $get_events[$key]->event_datetime = $event_datetime;
        }


        $events_arr = [
            "start_datetime" => $current_datetime,
            "target_datetime" => $target_datetime,
            "events" => $get_events
        ];

        $output["events"] = $events_arr;
//        btm_dump($output["events"]);

        echo json_encode($output);
        return;

    }


    public function notification(Request $request)
    {
        $current_datetime = date("Y-m-d H:i:00");
        $datetime_15 = \Carbon\Carbon::createFromTimestamp(strtotime($current_datetime))->addMinutes(15)->toDateTimeString();
        $datetime_30 = \Carbon\Carbon::createFromTimestamp(strtotime($current_datetime))->addMinutes(30)->toDateTimeString();
        $datetime_5 = \Carbon\Carbon::createFromTimestamp(strtotime($current_datetime))->addMinutes(5)->toDateTimeString();
        
        $get_events = pages_m::get_pages("
                AND page.page_type = 'stock_exchange'
                AND ( page.event_datetime = '$datetime_5' OR  page.event_datetime = '$datetime_15' 
                OR page.event_datetime = '$datetime_30' OR page.event_datetime = '$current_datetime' )
                
                order by page.event_datetime asc
                LIMIT 5
            ");
        
        $count=count($get_events);    
        if ($count>0) {
            echo json_encode($count);
            return;

        }else {
            echo json_encode(0);
            return;
        }

    }





}
