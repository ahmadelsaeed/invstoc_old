<?php

namespace App\Http\Controllers;

use App\models\email_settings_m;
use App\models\subscribe_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class subscribe_cron_jop extends Controller
{

    function index()
    {

        $temp_settings = email_settings_m::find(1);
        if (is_object($temp_settings) && !empty($temp_settings))
        {
            $temp_subscribe = subscribe_m::all();

            // check if run_send is allowed and there is other email not send yet
            if ($temp_settings->run_send == 1 && $temp_settings->offset <= (count($temp_subscribe)-$temp_settings->limit)) {

                // fetch the emails to be sent
//                $group_emails = DB::table('subscribe')->where("deleted_at","!=","null")->skip($temp_settings->offset)->take($temp_settings->limit)->get();
                $group_emails = subscribe_m::limit($temp_settings->limit)->offset($temp_settings->offset)->get()->all();

                // send emails to $group_emails

                if (is_array($group_emails) && count($group_emails)) {

                    foreach ($group_emails as $key => $value) {

                        // send email to each of them
                        $img_url = url("subscribe_cron_jop/show_email?myemail=".$value->email);

                        $email_body_img = " <img src='$img_url' height='1' width='1' style='display:none;'>";

                        $this->_send_email_to_custom(["$value->email"],$temp_settings->email_body." ".$email_body_img.$email_body_img,$temp_settings->email_subject,$temp_settings->sender_email);

                        // edit message_send = 1 and last_message

                        subscribe_m::find($value->id)->update([
                            "last_message" =>$temp_settings->email_body,
                            "message_send" =>1,
                        ]);

                    }

                }

                email_settings_m::find(1)->update([
                    "offset" =>$temp_settings->offset + $temp_settings->limit
                ]);

            }
            else{

                // disable the run_send and initialze the offset = 0
                email_settings_m::find(1)->update([
                    "run_send" =>0,
                    "offset" =>0
                ]);
            }

        }

    }

    public function show_email() {

        $email = $_GET['myemail'];
        if (!empty($email)) {

            subscribe_m::where("email","$email")->update([
                "message_viewed"=>1,
                "message_send"=>1
            ]);

        }

    }

}
