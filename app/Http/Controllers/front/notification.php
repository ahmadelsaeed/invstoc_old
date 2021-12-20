<?php

namespace App\Http\Controllers\front;

use App\Events\notifications;
use App\Events\posts_events;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\models\ads_m;
use App\models\category_m;
use App\models\followers_m;
use App\models\notification_m;
use App\models\pages\pages_m;
use App\models\posts\posts_m;
use App\models\settings_m;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class notification extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware("check_user");

    }


    public function get_all_user_notifications(){


        $this->data["all_user_notifications"] = notification_m::
            select(\DB::raw("
                notification.*,
                user_obj.full_name as 'from_user_full_name',
                from_user_att.path as 'from_user_logo_path',
                notification.created_at as 'note_created_at'
            "))
            ->join("users as user_obj",function($join)
            {
                $join->on("user_obj.user_id","=","notification.not_from_user_id")
                    ->whereNull("user_obj.deleted_at");
            })
            ->leftJoin("attachments as from_user_att",function($join)
            {
                $join->on("from_user_att.id","=","user_obj.logo_id");
            })
            ->where("notification.not_to_user_id",$this->user_id)
            ->orderBy("notification.not_id","desc")
            ->paginate(notifications::$max_notification_number);

        $this->data["current_user_timezone"] = $this->data["current_user"]->timezone;

        return view("front.subviews.all_user_notifications",$this->data);
    }


}
