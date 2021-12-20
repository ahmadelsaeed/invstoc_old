<?php

namespace App\Http\Controllers;

use App\models\attachments_m;
use App\models\notification_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SSP;

class admin_controller extends dashbaord_controller
{

    public $current_user_data;
    public $user_permissions;
    public $allowed_lang_ids;


    public function __construct()
    {
        parent::__construct();
        $this->middleware("check_admin");
        $this->current_user_data=$this->data["current_user"];

        $this->allowed_lang_ids=json_decode($this->current_user_data->allowed_lang_ids);


        $this->data["meta_title"] = "Forex | Admin";
        $this->data["meta_desc"] = "Forex | Admin";
        $this->data["meta_keywords"] = "Forex | Admin";


        $date = date('Y-m-d');

        $this->user_permissions = $this->get_user_permissions();
        $this->data["user_permissions"] = $this->user_permissions;
        $this->data["notifications"] = notification_m::get_notifications(" AND date(note.created_at) = '$date' AND not_to_user_id = $this->user_id order by note.created_at desc");


        $this->data["display_lang"]=\Session::get("display_lang","en");
    }

}
