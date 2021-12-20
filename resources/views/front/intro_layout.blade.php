@include('front.intro_components.header')

<!-- ... end Header-BP-logout -->

<!-- <div class="header-spacer"></div> -->

<div class="container">
    <div class="row">
        @yield('subview')
    </div>
</div>



@include('front.main_components.footer_block')



<div class="modal fade" id="registration-login-form-popup" tabindex="-1" role="dialog" aria-labelledby="registration-login-form-popup" aria-hidden="true">
    <div class="modal-dialog window-popup registration-login-form-popup" role="document">
        <div class="modal-content">
            <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">
                <svg class="olymp-close-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
            </a>
            <div class="modal-body">
                <div class="registration-login-form">
                    <!-- Nav tabs -->
                   <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item " >
                            <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">
                               <div class="h6 ">{{show_content($intro_keywords,"login_submit_btn")}}</div>
                              <!-- <svg class="olymp-login-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-login-icon"></use></svg> -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#home" role="tab">
                          <div class="h6">{{show_content($intro_keywords,"register_submit_btn")}}</div>
                          <!-- <svg class="olymp-register-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-register-icon"></use></svg> -->
                            </a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane" id="home" role="tabpanel" data-mh="log-tab">
                            <div class="title h6">{{show_content($intro_keywords,"register_header")}}</div>

                            <form class="content"  name="registration_form" method="POST" action="{{url("/register")}}">
                                <div class="row">
                                    <div class="col col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"register_first_name")}}</label>
                                            <input type="text" required name="first_name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"register_last_name")}}</label>
                                            <input type="text" required name="last_name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"register_email")}}</label>
                                            <input type="text" class="form-control" required name="email" >
                                        </div>
                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"register_password")}}</label>
                                            <input type="password" class="form-control" required name="password" >
                                        </div>

                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"register_confirm_password")}}</label>
                                            <input type="password" class="form-control" required name="password_confirmation" >
                                        </div>


                                        <div class="remember">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="1" id="checkboxFourInput" name="" />

                                                    <a href="#" data-toggle="modal" data-target="#privacyModal">
                                                        {{show_content($intro_keywords,"register_privacy")}}
                                                    </a>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="g-recaptcha" data-sitekey="6LfR7eAZAAAAAL2bf-BYZtAkzPfEygQ86TJ-1UQY"></div>

                                        {{csrf_field()}}
                                        <button class="btn btn-grey btn-lg full-width" style="margin-top: 10px" type="submit">{{show_content($intro_keywords,"register_submit_btn")}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane active" id="profile" role="tabpanel" data-mh="log-tab">
                            <div class="title h6">{{show_content($intro_keywords,"login_header")}}</div>

                            <form class="content"  name="Login_form" action="{{url("/login")}}" method="POST">
                                <div class="row">
                                    <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"login_email_or_id")}}</label>
                                            <input class="form-control " required
                                                   type="text" name="email" title="{{show_content($intro_keywords,"login_email_or_id")}}" />

                                        </div>
                                        <div class="form-group label-floating">
                                            <label class="control-label">{{show_content($intro_keywords,"login_password")}}</label>
                                            <input class="form-control" required
                                                   type="password" name="password" title="{{show_content($intro_keywords,"login_password")}}" />

                                        </div>

                                        <div class="remember">

                                            <div class="checkbox">
                                                <label>
                                                    <input name="remember_Me" type="checkbox">
                                                    Remember Me
                                                </label>
                                            </div>
                                            <a href="{{url("password/reset")}}">{{show_content($intro_keywords,"login_forget_password")}}</a>

                                        </div>

                                        <div class="g-recaptcha" data-sitekey="6LfR7eAZAAAAAL2bf-BYZtAkzPfEygQ86TJ-1UQY"></div>

                                        {{csrf_field()}}
                                        <button class="btn btn-lg btn-grey full-width" style="margin-top: 10px" type="submit">{{show_content($intro_keywords,"login_submit_btn")}}</button>

                                        <?php if(false): ?>
                                            <div class="or"></div>

                                            {{--<a href="#" class="btn btn-lg bg-facebook full-width btn-icon-left"><i class="fab fa-facebook-f" aria-hidden="true"></i>Login with Facebook</a>--}}

                                            {{--<a href="#" class="btn btn-lg bg-twitter full-width btn-icon-left"><i class="fab fa-twitter" aria-hidden="true"></i>Login with Twitter</a>--}}


                                            <p>Don’t you have an account? <a data-toggle="tab" href="#home" role="tab" aria-selected="false">Register Now!</a> it’s really simple and you can start enjoing all the benefits!</p>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div style="margin: 0 25px">
                            @if(count($static_pages))
                                @foreach($static_pages as $key => $static_page)
                                    <a style="margin-right: 7px" href="{{url(urlencode($static_page->page_slug))}}">{{$static_page->page_title}}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@include('front.general_components.scripts')
@yield('jspdf')
</body>


</html>
