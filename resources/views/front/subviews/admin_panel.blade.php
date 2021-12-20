@extends('admin_login.main_layout')

@section('subview')

<div class="container">

    <div class="card card-container">
        <img id="profile-img" class="profile-img-card" src="{{url("public_html/front/images/bg-profile.png")}}" />
        <p id="profile-name" class="profile-name-card"></p>
        <form method="POST" class="form-signin" action="{{url('/UUYdfsf_234DSFsfsdf65DFG213123ASasmPiT')}}">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="email" id="inputEmail" class="form-control" placeholder="Email or Username" name="email"  required autofocus>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
            <div id="remember" class="checkbox">
                <label>
                    <input type="checkbox" name="remember" checked="">Remember Me ?
                </label>
            </div>
            {{csrf_field()}}
            <button class="btn btn-lg btn-success btn-block btn-signin" style="cursor: pointer;" type="submit">Login</button>
        </form><!-- /form -->
        <a href="{{url('/password/reset/')}}" class="forgot-password">
            Forgot the password ?
        </a>
    </div><!-- /card-container -->
</div>

@endsection
