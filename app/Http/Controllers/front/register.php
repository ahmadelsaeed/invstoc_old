<?php

namespace App\Http\Controllers\front;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class register extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index(Request $request)
    {

//        $slider_arr = array();
//
//        $this->general_get_content([
//            "register_page"
//        ],$slider_arr);

        $slider_arr = array();
        $this->general_get_content(
            [
                "intro_keywords"
            ]
            ,$slider_arr
        );

        $this->data["register_login"] = "yes";

        if(is_object($this->data["current_user"]))
        {
            $current_user = $this->data["current_user"];
            if (in_array($current_user->user_type,["admin","dev"]))
            {
                return redirect()->intended('/admin/dashboard');
            }
            else if (in_array($current_user->user_type,["user"]))
            {
                return redirect()->intended('/home');
            }
        }


        if ($request->method() == "POST")
        {

            \Debugbar::disable();

            if(is_null($request['g-recaptcha-response']) || empty($request['g-recaptcha-response'])) {
                $msg="<div class='alert alert-danger'>".show_content($this->data["validation_messages"],"Recaptcha is Required.")."</div>";
                return redirect()->back()->with(["msg"=>$msg]);
            }

            if(is_null($request['terms']) || empty($request['terms'])) {
                $msg="<div class='alert alert-danger'>".show_content($this->data["validation_messages"],"Please read and agree terms & conditions")."</div>";
                return redirect()->back()->with(["msg"=>$msg]);
            }


            $user_id = null;
            $this->validate($request,
                [
                    "first_name" => "required",
                    "last_name" => "required",
                    "email" => "required|email|unique:users,email,".$user_id.",user_id,deleted_at,NULL",
                    "password" => "required|min:|confirmed",
                ],
                [
                    "first_name.required" => (
                        show_content($this->data["intro_keywords"],"register_first_name")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "last_name.required" => (
                        show_content($this->data["intro_keywords"],"register_last_name")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "email.required" => (
                        show_content($this->data["intro_keywords"],"register_email")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                    "email.email" => (
                        show_content($this->data["intro_keywords"],"register_email")." ".
                        show_content($this->data["validation_messages"],"is_not_valid_email_field")
                    ),
                    "email.unique" => (
                        show_content($this->data["intro_keywords"],"register_email")." ".
                        show_content($this->data["validation_messages"],"is_unique_field")
                    ),
                    "password.required" => (
                        show_content($this->data["intro_keywords"],"register_password")." ".
                        show_content($this->data["validation_messages"],"is_required_field")
                    ),
                ]
            );

            // clean inputs
            $request["first_name"] = trim(clean($request["first_name"]));
            $request["last_name"] = trim(clean($request["last_name"]));
            $request["full_name"] = $request["first_name"]." ".$request["last_name"];
            $request["email"] = trim(clean($request["email"]));
            $request["password"] = clean($request["password"]);

            $request["user_type"] = "user";
            $email = $request["email"];
            $password = $request["password"];
            $request["password"] = bcrypt($request["password"]);

            $request['user_active'] = 0;
            $request['user_can_login'] = 0;
            $request['gender'] = "";
            $request['gender'] = "";

            $check = User::create($request->all());

            $settings = $this->data["settings"];

            if ($check)
            {

                $rand_val = rand(111111,999999).$check->user_id;
                $check->update([
                    "verification_code" => $rand_val,
                ]);


                $this->_send_email_to_custom(
                    $emails = array($email) ,
                    $data = [
                        'name' => $request["full_name"],
                        'link' => url("/en/verify_new_account?email=$email&code=$rand_val")
                    ] ,
                    $subject = "Invstoc Verification Alert",
                    $sender = "registration@invstoc.com" ,
                    $path_to_file = "",
                    $email_template ="email.registration"
                );

                if (!$settings->register_require_verification)
                {

                    // not require admin verification

                    $check->update([
                        "user_active" => 1,
                        "user_can_login" => 1
                    ]);

                    $msg="<div class='alert alert-success'>".show_content($this->data["validation_messages"],"verify_your_email")."</div>";
                    return redirect()->back()->with(["msg"=>$msg]);

//                    $login=\Auth::attempt([
//                        "email"=>$email,
//                        "password"=>$password,
//                        "user_type"=>"user",
//                    ],$request->get("remember"));
//
//                    if($login){
//                        \Auth::login(\Auth::user());
//                        $request->session()->save();
//                        //return redirect()->intended('/information/'.$check->user_id);
//                        return redirect()->intended('/home');
//                    }else{
//                        $msg="<div class='alert alert-danger'>".show_content($this->data["validation_messages"],"invalid_login_data")."</div>";
//                        return redirect()->back()->with(["msg"=>$msg]);
//                    }
                }
                else{
                    $msg="<div class='alert alert-success'>".show_content($this->data["validation_messages"],"verify_your_email")."</div>";
                    return redirect()->back()->with(["msg"=>$msg]);
                }


            }
            else{
                $msg="<div class='alert alert-danger'>".show_content($this->data["validation_messages"],"failed_register_msg")."</div>";
                return redirect()->back()->with(["msg"=>$msg]);
            }

        }

        return view('front.subviews.register_login',$this->data);
    }

    public function verify_new_account(Request $request)
    {

        $email = clean($request->get('email',''));
        $code = clean($request->get('code',''));

        abort_if((empty($email) || empty($code)),404);

        // check for exist user
        $check_user = User::where("email","$email")
            ->where("user_type","user")
            ->where("verification_code","$code")->first();

        abort_if((!is_object($check_user)),404);


        // check if user is already verified
        if ($check_user->email_is_verified)
        {
            $msg="<div class='alert alert-success'>".show_content($this->data["validation_messages"],"success_register_msg")."</div>";
            return redirect()->back()->with(["msg"=>$msg]);
        }
        else{
            $get_users = User::where("user_type","user")->where("email_is_verified",1)->get()->all();
            $user_code = (10000+count($get_users)+1);

            // check if code exist
            $check_exist_code = User::where("username","$user_code")->first();
            if (is_object($check_exist_code))
            {
                $check_last_code = User::
                where("user_type","user")->where("email_is_verified",1)
                    ->get()->last();

                $username = intval($check_last_code->username);

                $user_code = ($username+1);
            }

            // another check if code exist
            $check_exist_code = User::where("username","$user_code")->first();
            if (is_object($check_exist_code))
            {
                $user_code = ($user_code+1);
            }

            $new_referrer_link = str_random(10).$check_user->user_id.str_random(3);


            User::findOrFail($check_user->user_id)->update([
                "email_is_verified" => 1,
                "user_active" => 1,
                "user_can_login" => 1,
                "username" => "$user_code",
                "referrer_link" => $new_referrer_link,
                "request_referrer_link"=>'1',
                'email_verification_date' => date("Y-m-d H:i:s")
            ]);


            #region send email to user with his code


            $this->_send_email_to_custom(
                $emails = array($email) ,
                $data = [
                    'name' => $check_user->full_name,
                    'code' => $user_code
                ] ,
                $subject = "Invstoc Successfully Verification ",
                $sender = "registration@invstoc.com" ,
                $path_to_file = "",
                $email_template ="email.success_registration"
            );


            #endregion


            $msg="<div class='alert alert-success'>".show_content($this->data["validation_messages"],"success_register_msg")."</div>";
            return redirect()->back()->with(["msg"=>$msg]);
        }

    }

    public function login(Request $request)
    {

        if(is_object($this->data["current_user"]))
        {
            $current_user = $this->data["current_user"];
            if (in_array($current_user->user_type,["admin","dev"]))
            {
                return redirect()->intended('/admin/dashboard');
            }
            else if (in_array($current_user->user_type,["user"]))
            {
                return redirect()->intended('/home');
            }
        }

        if ($request->method() == "POST")
        {
            if(is_null($request['g-recaptcha-response']) || empty($request['g-recaptcha-response'])) {
                $msg="<div class='alert alert-danger'>".show_content($this->data["validation_messages"],"Recaptcha is Required.")."</div>";
                return redirect()->back()->with(["msg"=>$msg]);
            }

            $email_login=\Auth::attempt([
                "email"=>clean($request->get('email')),
                "password"=>clean($request->get('password')),
                "user_type"=>"user",
                "user_can_login"=>1,
                "email_is_verified"=>1,
            ],$request->get("remember_Me"));

            $username_login=\Auth::attempt([
                "username"=>clean($request->get('email')),
                "password"=>clean($request->get('password')),
                "user_type"=>"user",
                "user_can_login"=>1,
                "email_is_verified"=>1,
            ],$request->get("remember_Me"));

            if($email_login || $username_login){
                Auth::login(\Auth::user());
                $request->session()->save();
                $user_obj = \Auth::user();
//                return redirect()->intended('/information/'.\Auth::user()->user_id);
//                return redirect()->intended('/home');
                return redirect()->back();

            }else{
                $msg="<div class='alert alert-danger'>".show_content($this->data["validation_messages"],"invalid_login_data")."</div>";
                return redirect()->back()->with(["msg"=>$msg]);
            }

        }

        return view('front.subviews.register_login',$this->data);
    }

    public function test()
    {

        $this->data["obj"] = $this->data["email_page"];
        return view("email.test",$this->data);
    }

}
