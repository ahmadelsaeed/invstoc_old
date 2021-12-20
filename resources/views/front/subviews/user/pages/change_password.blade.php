@extends('front.subviews.user.user_layout')
@section('page')


    <div class="col-md-12 nopadding align-items st-block-info group_info">
        <form action="{{url("$language_locale/change_password")}}" method="POST">
            <h3>{{show_content($user_profile_keywords,"change_password_header")}}</h3>
            <br>

            <div class="col-md-6">
                <label for=""><b>{{show_content($user_profile_keywords,"enter_old_password_label")}}</b></label>
                <input type="password" name="old_password" required style="border: 1px solid green" class="form-control">
            </div>

            <div class="col-md-6">
                <label for=""><b>{{show_content($user_profile_keywords,"enter_new_password_label")}}</b></label>
                <input type="password" name="password" required style="border: 1px solid green" class="form-control">
            </div>

            <div class="col-md-6">
                <label for=""><b>{{show_content($user_profile_keywords,"confirm_new_password")}}</b></label>
                <input type="password" name="password_confirmation" required style="border: 1px solid green" class="form-control">
            </div>

            {{csrf_field()}}
            <div class="col-md-12">
                <button class="btn btn-success" type="submit"><i class="fa fa-save" aria-hidden="true"></i> {{show_content($user_profile_keywords,"change_password_btn")}}</button>
            </div>
        </form>
    </div>


@endsection
