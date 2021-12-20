<?php

namespace App\Events;


use App\models\category_m;
use App\models\group_workshop\workshops_count_m;
use App\models\pair_currency_m;
use App\models\users_actions_count_m;

class trending
{

    #region Workshops , users

    public static function event_action($target_id,$post_id,$action_type,$target="workshop")
    {

        $current_date = date("Y-m-d");

        if ($target == "workshop")
        {
            $get_current_row = workshops_count_m::
            where("workshop_id",$target_id)
                ->where("counter_date",$current_date)->get()->first();
            if (!is_object($get_current_row))
            {
                $get_current_row = workshops_count_m::create([
                    "workshop_id" => $target_id,
                    "counter_date" => $current_date,
                ]);
            }

        }
        elseif($target == "profile"){
            $get_current_row = users_actions_count_m::
            where("user_id",$target_id)
                ->where("counter_date",$current_date)->get()->first();
            if (!is_object($get_current_row))
            {
                $get_current_row = users_actions_count_m::create([
                    "user_id" => $target_id,
                    "counter_date" => $current_date,
                ]);
            }

        }
        else{
            return;
        }



        if ($action_type == "like")
        {
            $get_current_row = $get_current_row->update([
                "counter" => ($get_current_row->counter+1)
            ]);
        }
        elseif ($action_type == "unlike")
        {
            if ($get_current_row->counter >= 1)
            {
                $get_current_row = $get_current_row->update([
                    "counter" => ($get_current_row->counter-1)
                ]);
            }

        }
        elseif ($action_type == "comment")
        {
            $get_current_row = $get_current_row->update([
                "counter" => ($get_current_row->counter+2)
            ]);
        }
        elseif ($action_type == "uncomment")
        {
            if ($get_current_row->counter >= 2)
            {
                $get_current_row = $get_current_row->update([
                    "counter" => ($get_current_row->counter-2)
                ]);
            }
        }
        elseif ($action_type == "remove_post")
        {

            // TODO get all likes and comments of this post to remove

//            if ($get_current_row->counter >= 2)
//            {
//                $get_current_row = $get_current_row->update([
//                    "counter" => ($get_current_row->counter-2)
//                ]);
//            }

        }


    }

    #endregion

}