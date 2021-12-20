@extends('front.subviews.user.user_layout')
@section('page')
<div class="container">
    <div class="row">
        <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <form action="{{url("$language_locale/referrer_link?ref=$ref_link")}}" method="POST">

                <div class="col-md-12">

                    <div class="col-md-12">
                        <h4>{{show_content($user_homepage_keywords,"add_new_cashback_header")}}</h4>
                    </div>

                    <div class="col-md-6">
                        <div class="creat-agroub">
                            <input type="text" class="form-control" name="account_number" required placeholder="{{show_content($user_homepage_keywords,"account_number_label")}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="creat-agroub">
                            <select name="page_id" required class="form-control select_2_class">
                                {!! $select_items !!}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2" style="margin-top: 10px;">
                        {{csrf_field()}}
                        <button class="btn btn-success" type="submit">{{show_content($general_static_keywords,"save_btn")}}</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>
@endsection
