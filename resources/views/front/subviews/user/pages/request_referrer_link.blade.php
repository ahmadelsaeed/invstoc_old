
@extends('front.subviews.user.user_layout')
@section('page')


    <?php if($current_user->request_referrer_link == 0 && empty($current_user->referrer_link)): ?>
    <div class="container">
        <div class="row">
            <div class="col col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-12">
                <div class="ui-block">
                    <div class="ui-block-title">
                        <h6 class="title">{{show_content($user_homepage_keywords,"request_referrer_link")}}</h6>
                    </div>
                    <div class="ui-block-content">
                        <form action="{{url("$language_locale/request_referrer_link")}}" enctype="multipart/form-data" method="POST">

                            <div class="row">

                                <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">{{show_content($user_profile_keywords,"request_verification_full_name")}} *</label>
                                        <input class="form-control"  type="text" name="full_name" value="{{$current_user->first_name." ".$current_user->last_name}}" required >
                                    </div>
                                </div>


                                <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">{{show_content($user_profile_keywords,"request_verification_phone")}} *</label>
                                        <input class="form-control"  type="text" name="phone" required >
                                    </div>
                                </div>

                                <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">{{show_content($user_profile_keywords,"request_verification_email")}} *</label>
                                        <input class="form-control"  type="text" name="email" value="{{$current_user->email}}" readonly >
                                    </div>
                                </div>


                                {{csrf_field()}}

                                <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                    <button class="btn btn-primary btn-lg full-width" type="submit">{{show_content($general_static_keywords,"save_btn")}}</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Left Sidebar -->
                @include('front.subviews.user.pages.left_sidbar')
            <!-- ... end Left Sidebar -->

        </div>
    </div>
    <?php endif;?>



@endsection

