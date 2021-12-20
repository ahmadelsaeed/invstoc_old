<?php

namespace App\Http\Controllers\front;

use App\support_messages_m;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class admin_panel extends Controller
{
    public function __construct(){

        parent::__construct();

    }

    public function index(){

        if(is_object(Auth::user())){
            return Redirect::to('admin/dashboard')->send();
        }

        return view("front.subviews.admin_panel",$this->data);
    }

    public function try_login(Request $request){

        if(is_object(\Auth::user())){
            Redirect::to('admin/dashboard')->send();
        }


        $email_login=\Auth::attempt([
            "email"=>$request->get("email"),
            "password"=>$request->get("password"),
            "user_active"=>1,
            "user_can_login"=>1
        ],
        $request->get("remember"));

        $username_login=\Auth::attempt([
            "username"=>$request->get("email"),
            "password"=>$request->get("password"),
            "user_active"=>1,
            "user_can_login"=>1
        ],
        $request->get("remember"));



        if($email_login)
        {
            Auth::login(\Auth::user());
            $request->session()->save();
            $user_obj = \Auth::user();

            if ($user_obj->user_type == "admin" || $user_obj->user_type == "dev" )
            {
                return redirect()->intended('admin/dashboard');
            }

        }
        elseif($username_login)
        {
            Auth::login(\Auth::user());
            $request->session()->save();
            $user_obj = \Auth::user();

            if ($user_obj->user_type == "admin" || $user_obj->user_type == "dev" )
            {
                return redirect()->intended('admin/dashboard');
            }

        }
        else{
            $msg="<div class='alert alert-danger'>Invalid email Or Password</div>";
            return redirect()->back()->with(["msg"=>$msg]);
        }
    }



}
