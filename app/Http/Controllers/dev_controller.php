<?php

namespace App\Http\Controllers;

use App\models\attachments_m;
use App\models\notification_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SSP;

class dev_controller extends dashbaord_controller
{

    public $current_user_data;


    public function __construct()
    {
        parent::__construct();
        $this->middleware("check_dev");
        $this->current_user_data=$this->data["current_user"];

    }


}
