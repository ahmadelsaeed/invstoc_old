<div class="section-login">
    <div class="col-md-12">
        <div class="block-login">
            <form class="form-inline" name="Login_form" action="{{url("/login")}}" method="POST">
                <div class="form-group">
                    <label>{{show_content($intro_keywords,"login_email_or_id")}}</label>
                    <input class="form-control " required
                           type="text" name="email" title="{{show_content($intro_keywords,"login_email_or_id")}}"
                           placeholder="{{show_content($intro_keywords,"login_email_or_id")}}"/>
                </div>
                <div class="form-group">
                    <label>{{show_content($intro_keywords,"login_password")}}</label>
                    <input class="form-control" required
                           type="password" name="password" title="{{show_content($intro_keywords,"login_password")}}"
                           placeholder="{{show_content($intro_keywords,"login_password")}}"/>
                </div>
                {{csrf_field()}}
                <button class="btn-login" type="submit">{{show_content($intro_keywords,"login_submit_btn")}}</button>
            </form>
        </div>
    </div>

    <div class="col-md-12" style="text-align: left;">
        <a href="{{url("password/reset")}}">{{show_content($intro_keywords,"login_forget_password")}}</a>
    </div>

    <div class="col-md-12">
        <div class="creat-account">

            <div class="col-md-12">
                <h3>{{show_content($intro_keywords,"register_header")}}</h3>
            </div>

            <form name="registration_form" method="POST" action="{{url("/register")}}">
                <div class="col-md-4 col-xs-12">
                    <input type="text" required name="first_name" class="form-control" placeholder="{{show_content($intro_keywords,"register_first_name")}}">
                </div>

                <div class="col-md-4 col-xs-12">
                    <input type="text" required name="last_name" class="form-control" placeholder="{{show_content($intro_keywords,"register_last_name")}}">
                </div>

                <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" required name="email" placeholder="{{show_content($intro_keywords,"register_email")}}">
                </div>

                <div class="col-md-8 col-xs-12">
                    <input type="password" class="form-control" required name="password" placeholder="{{show_content($intro_keywords,"register_password")}}">
                </div>

                <div class="col-md-8 col-xs-12">
                    <input type="password" class="form-control" required name="password_confirmation" placeholder="{{show_content($intro_keywords,"register_confirm_password")}}">
                </div>

                <div class="col-md-11 col-xs-12">
                    <div class="checkbox">
                        <label>
                            {{show_content($intro_keywords,"register_note")}}
                        </label>
                    </div>
                </div>
                <div class="col-md-11 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <a href="#" data-toggle="modal" data-target="#privacyModal">
                                {{show_content($intro_keywords,"register_privacy")}}
                            </a>
                        </label>
                    </div>
                </div>

                <div class="col-md-1 col-xs-12">
                    <div class="checkboxFour">
                        <input type="checkbox" value="1" id="checkboxFourInput" name="" />
                        <label for="checkboxFourInput"></label>
                    </div>
                </div>

                <div class="col-md-12 col-xs-12">
                    {{csrf_field()}}
                    <button class="btn-creat" type="submit">{{show_content($intro_keywords,"register_submit_btn")}}</button>
                </div>

            </form>

        </div>
    </div>
</div>