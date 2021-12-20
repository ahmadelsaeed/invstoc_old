<?php

namespace App\Http\Controllers\front;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class change_currency extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        $output = [];
        $output["error"] = "error";

        $code = clean($request->get("code"));
        $sign = clean($request->get("sign"));
        $rate = clean($request->get("rate"));

        if (isset($code) && !empty($code) && isset($sign) && !empty($sign)
            && isset($rate) && !empty($rate) )
        {

            \Session::set("selected_currency_value",$code);
            \Session::set("selected_currency_rate",$rate);
            \Session::set("selected_currency_sign",$sign);

            $output["error"] = "success";
            echo json_encode($output);
            return;
        }


        echo json_encode($output);
        return;
    }


}
