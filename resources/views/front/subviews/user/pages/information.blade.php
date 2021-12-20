
@extends('front.subviews.user.user_layout')
@section('page')




<div class="container">
    <div class="row">
        <div class="col col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-12">

            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Personal Information</h6>
                </div>
                <div class="ui-block-content">
                    <form action="{{url("$language_locale/information/$user_obj->user_id/update_personal_info")}}" method="POST">

                        <div class="row">

                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_name_label")}}</label>
                                    <input class="form-control"  type="text" name="first_name"  value="{{$user_obj->first_name}}">
                                </div>
                            </div>



                            <?php
                            $birthdate = $user_obj->birthdate;
                            $day = date("d",strtotime($birthdate));
                            $month = date("m",strtotime($birthdate));
                            $year = date("Y",strtotime($birthdate));
                            ?>
                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_second_name_label")}}</label>
                                    <input class="form-control"  type="text" name="last_name" placeholder=""  value="{{$user_obj->last_name}}">
                                </div>
                            </div>


                            <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
                                <div class="form-group label-floating is-select">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_day_label")}}</label>
                                    <select class="selectpicker form-control" name="day" required id="day">
                                        <?php for($i = 1;$i<=31;$i++): ?>
                                        <option value="{{$i}}" {{($day == $i)?"selected":""}}>{{$i}}</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
                                <div class="form-group label-floating is-select">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_month_label")}}</label>
                                    <select class="selectpicker form-control" name="month" required id="month" >
                                        <?php for($i = 1;$i<=31;$i++): ?>
                                            <option value="1" {{($month == 1)?"selected":""}}>1</option>
                                            <option value="2" {{($month == 2)?"selected":""}}>2</option>
                                            <option value="3" {{($month == 3)?"selected":""}}>3</option>
                                            <option value="4" {{($month == 4)?"selected":""}}>4</option>
                                            <option value="5" {{($month == 5)?"selected":""}}>5</option>
                                            <option value="6" {{($month == 6)?"selected":""}}>6</option>
                                            <option value="7" {{($month == 7)?"selected":""}}>7</option>
                                            <option value="8" {{($month == 8)?"selected":""}}>8</option>
                                            <option value="9" {{($month == 9)?"selected":""}}>9</option>
                                            <option value="10" {{($month == 10)?"selected":""}}>10</option>
                                            <option value="11" {{($month == 11)?"selected":""}}>11</option>
                                            <option value="12" {{($month == 12)?"selected":""}}>12</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
                                <div class="form-group label-floating is-select">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_year_label")}}</label>
                                    <select class="selectpicker form-control" name="year" required id="year" >
                                        {!! generate_years_options(1940,"$year",true) !!}
                                    </select>
                                </div>
                            </div>

                            <div class="col col-lg-3 col-md-3 col-sm-12 col-12">
                                    <div class="form-group label-floating is-select">
                                        <select class="selectpicker form-control" name="birthdate_privacy" >
                                            <option value="0" {{($user_obj->birthdate_privacy == 0)?"selected":""}}>{{show_content($user_profile_keywords,"about_me_only_me_label")}}</option>
                                            <option value="1" {{($user_obj->birthdate_privacy == 1)?"selected":""}}>{{show_content($user_profile_keywords,"about_me_public_label")}}</option>
                                        </select>
                                    </div>
                            </div>



                            <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                                <div class="form-group label-floating is-select">
                                    <?php

                                    echo generate_select_tags(
                                        $field_name = "country",
                                        $label_name = "",
                                        $text = COUNTRIES,
                                        $values = COUNTRIES,
                                        $selected_value = "",
                                        $class="form-control selectpicker",
                                        $multiple="",
                                        $required="required",
                                        $disabled = "",
                                        $data = $user_obj,
                                        $parent_div_class = "");
                                    ?>

                                </div>
                            </div>




                            <div class="col col-lg-2 col-md-2 col-sm-12 col-12">

                                <div class="form-group label-floating is-select">
                                    <select class="selectpicker form-control" name="country_privacy" >
                                        <option value="0" {{($user_obj->country_privacy == 0)?"selected":""}}>Only Me</option>
                                        <option value="1" {{($user_obj->country_privacy == 1)?"selected":""}}>Public</option>
                                    </select>
                                </div>

                            </div>



                            <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_city_label")}}</label>
                                    <input class="form-control"  type="text" required placeholder="" name="city" value="{{$user_obj->city}}">
                                </div>
                            </div>



                            <div class="col col-lg-2 col-md-2 col-sm-12 col-12">

                                <div class="form-group label-floating is-select">
                                    <select class="selectpicker form-control" name="city_privacy" >
                                        <option value="0" {{($user_obj->city_privacy == 0)?"selected":""}}>Only Me</option>
                                        <option value="1" {{($user_obj->city_privacy == 1)?"selected":""}}>Public</option>
                                    </select>
                                </div>

                            </div>



                            <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group label-floating is-select">
                                    <select class="selectpicker form-control" name="gender" >
                                        <option value="male" {{($user_obj->gender == "male"?"selected":"")}}>{{show_content($user_profile_keywords,"about_me_male_label")}}</option>
                                        <option value="female" {{($user_obj->gender == "female"?"selected":"")}}>{{show_content($user_profile_keywords,"about_me_female_label")}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_bio_label")}} </label>
                                    <textarea class="form-control" name="user_bio" >{{$user_obj->user_bio}}</textarea>
                                </div>
                            </div>


                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"about_me_interests_label")}} </label>
                                    <textarea class="form-control" name="user_interests" >{{$user_obj->user_interests}}</textarea>
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



@endsection




