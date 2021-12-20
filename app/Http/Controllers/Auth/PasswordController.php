<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest');
    }


    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');
        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'))->with($this->data);
        }

        if (view()->exists('auth.passwords.reset')) {
            return view('auth.passwords.reset')->with(compact('token', 'email'))->with($this->data);
        }


        return view('auth.reset')->with(compact('token', 'email'))->with($this->data);
    }

    public function getEmail()
    {
        return $this->showLinkRequestForm();
    }

    public function showLinkRequestForm()
    {
//        if (property_exists($this, 'linkRequestView')) {
//            return view($this->linkRequestView)->with($this->data);
//        }

        if (view()->exists('auth.passwords.email')) {
            return view('auth.passwords.email')->with($this->data);
        }

        return view('auth.password')->with($this->data);
    }





}
