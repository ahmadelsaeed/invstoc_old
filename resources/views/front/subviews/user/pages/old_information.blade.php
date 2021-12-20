@extends('front.subviews.user.user_layout')
@section('page')

    <div class="info-page">
        <h1>معلومات عامة</h1>
        <div class="block-info">

            <?php if($user_obj->user_id == $current_user->user_id && $current_user->is_privet_account == 0): ?>

                <div class="col-md-12 nopadding align-items st-block-info group_info">
                    <form action="{{url("$language_locale/information/$user_obj->user_id/request_privet_account")}}" method="POST">
                        <div class="col-md-12">
                            <h3>طلب تحويل الاكونت الي privet</h3>
                            <br>
                            {{csrf_field()}}
                            <button class="btn btn-success" type="submit"><i class="fa fa-save" aria-hidden="true"></i> إرسال</button>
                        </div>
                    </form>
                </div>

            <?php endif; ?>


            <div class="col-md-12 nopadding align-items st-block-info group_info">
                <?php if($user_obj->user_id == $current_user->user_id): ?>
                <form action="{{url("$language_locale/information/$user_obj->user_id/update_personal_info")}}" method="POST">
                <?php endif; ?>
                <div class="col-md-3">
                    <h3> البيانات الشخصية
                    <br>
                    <br>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <a href="#" class="show_profile_edit_fields"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> تعديل</a>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-user" aria-hidden="true"></i> الاسم</label></div>
                        <div class="display_dev">
                            <div class="col-md-10">
                                <p>{{$user_obj->full_name}}</p>
                            </div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-5">
                                <p>
                                    <input type="text" style="direction: rtl;" name="first_name" placeholder="الاسم الاول" class="form-control" value="{{$user_obj->first_name}}">
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <input type="text" style="direction: rtl;" name="last_name" placeholder="الاسم الاخير" class="form-control" value="{{$user_obj->last_name}}">
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-user" aria-hidden="true"></i> النوع</label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{($user_obj->gender == "male"?"ذكر":"أنثي")}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <div class="form-group gender">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="male" {{($user_obj->gender == "male"?"checked":"")}}>ذكر
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" value="female" name="gender" {{($user_obj->gender != "male"?"checked":"")}}>انثى
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-calendar" aria-hidden="true"></i> تاريخ الميلاد</label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{$user_obj->birthdate}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <?php
                                $birthdate = $user_obj->birthdate;
                                $day = date("d",strtotime($birthdate));
                                $month = date("m",strtotime($birthdate));
                                $year = date("Y",strtotime($birthdate));
                            ?>
                            <div class="col-md-10">
                                <p class="birth"><strong>تاريخ الميلاد</strong></p>
                                <div class="form-group col-sm-3 col-xs-6">
                                    <label for="month">يوم</label>
                                    <select class="form-control" name="day" required id="day">
                                        <?php for($i = 1;$i<=31;$i++): ?>
                                        <option value="{{$i}}" {{($day == $i)?"selected":""}}>{{$i}}</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                    <label for="month">شهر</label>
                                    <select class="form-control" name="month" required id="month">
                                        <option value="1" {{($month == 1)?"selected":""}}>يناير</option>
                                        <option value="2" {{($month == 2)?"selected":""}}>فبراير</option>
                                        <option value="3" {{($month == 3)?"selected":""}}>مارس</option>
                                        <option value="4" {{($month == 4)?"selected":""}}>أبريل</option>
                                        <option value="5" {{($month == 5)?"selected":""}}>مايو</option>
                                        <option value="6" {{($month == 6)?"selected":""}}>يونيو</option>
                                        <option value="7" {{($month == 7)?"selected":""}}>يوليو</option>
                                        <option value="8" {{($month == 8)?"selected":""}}>أغسطس</option>
                                        <option value="9" {{($month == 9)?"selected":""}}>سبتمبر</option>
                                        <option value="10" {{($month == 10)?"selected":""}}>أكتوبر</option>
                                        <option value="11" {{($month == 11)?"selected":""}}>نوفمبر</option>
                                        <option value="12" {{($month == 12)?"selected":""}}>ديسمبر</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 col-xs-12">
                                    <label for="year">سنة</label>
                                    <select class="form-control" name="year" required id="year">
                                        {!! generate_years_options(1940,"$year",true) !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-envelope" aria-hidden="true"></i> البريد الالكتروني</label></div>
                        <div class="col-md-10"><p>{{$user_obj->email}}</p></div>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-calendar" aria-hidden="true"></i> مسجل من تاريخ </label></div>
                        <div class="col-md-10"><p>{{$user_obj->created_at}}</p></div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="col-md-2 pull-left">
                            {{csrf_field()}}
                            <button class="btn btn-success edit_dev" type="submit"><i class="fa fa-save" aria-hidden="true"></i> حفظ</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($user_obj->user_id == $current_user->user_id): ?>
                </form>
                <?php endif; ?>
            </div>

            <div class="col-md-12 nopadding align-items st-block-info group_info">
                <?php if($user_obj->user_id == $current_user->user_id): ?>
                <form action="{{url("information/$user_obj->user_id/update_work_info")}}" method="POST">
                <?php endif; ?>
                <div class="col-md-3">
                    <h3> العمل والتعليم
                        <br>
                        <br>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <a href="#" class="show_profile_edit_fields"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> تعديل</a>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-briefcase" aria-hidden="true"></i> أعمل لدى</label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{$user_obj->work_on}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <input type="text" style="direction: rtl;" name="work_on" placeholder="جهه العمل الحالية" class="form-control" value="{{$user_obj->work_on}}">
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-university" aria-hidden="true"></i> كلية / معهد </label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{$user_obj->faculty}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <input type="text" style="direction: rtl;" placeholder="اسم الكلية او المعهد او جهه تعليميه" name="faculty" class="form-control" value="{{$user_obj->faculty}}">
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-leaf" aria-hidden="true"></i> المجالات </label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{!! $user_obj->cat_items !!}</p></div>
                        </div>
                        <?php if( is_array($categories) && count($categories) && ($user_obj->user_id == $current_user->user_id)): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <?php

                                $categories_txt = convert_inside_obj_to_arr($categories,"cat_name");
                                $categories_values = convert_inside_obj_to_arr($categories,"cat_id");
                                echo generate_select_tags(
                                    $field_name = "cat_id",
                                    $label_name = "اختر المجالات",
                                    $text = $categories_txt,
                                    $values = $categories_values,
                                    $selected_value = "",
                                    $class="form-control select_2_class",
                                    $multiple="multiple",
                                    $required="",
                                    $disabled = "",
                                    $data = $user_obj,
                                    $parent_div_class = "form-group col-md-6");
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="col-md-2 pull-left">
                            {{csrf_field()}}
                            <button class="btn btn-success edit_dev" type="submit"><i class="fa fa-save" aria-hidden="true"></i> حفظ</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($user_obj->user_id == $current_user->user_id): ?>
                </form>
                <?php endif; ?>
            </div>

            <div class="col-md-12 nopadding align-items st-block-info group_info">
                <?php if($user_obj->user_id == $current_user->user_id): ?>
                <form action="{{url("information/$user_obj->user_id/update_contacts_info")}}" method="POST">
                <?php endif; ?>
                <div class="col-md-3">
                    <h3> العنوان وبيانات الإتصال
                        <br>
                        <br>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <a href="#" class="show_profile_edit_fields"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> تعديل</a>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-map-marker" aria-hidden="true"></i> البلد </label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{$user_obj->country}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <?php

                                echo generate_select_tags(
                                    $field_name = "country",
                                    $label_name = "",
                                    $text = COUNTRIES,
                                    $values = COUNTRIES,
                                    $selected_value = "",
                                    $class="form-control select_2_class",
                                    $multiple="",
                                    $required="required",
                                    $disabled = "",
                                    $data = $user_obj,
                                    $parent_div_class = "form-group col-md-6");
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-map-marker" aria-hidden="true"></i> المدينة </label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{$user_obj->city}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <input type="text" style="direction: rtl;" required placeholder="المدينة" name="city" class="form-control" value="{{$user_obj->city}}">
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-map-marker" aria-hidden="true"></i> محل السكن </label></div>
                        <div class="display_dev">
                            <div class="col-md-10"><p>{{$user_obj->address}}</p></div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-10">
                                <textarea name="address" placeholder="محل السكن" class="form-control" style="direction: rtl;"
                                          cols="20" rows="5">{{$user_obj->address}}</textarea>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 pull-left"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12"><label> <i class="fa fa-phone-square" aria-hidden="true"></i> أرقام الجوال </label></div>
                        <div class="display_dev">
                            <div class="col-md-10">
                                <p>{{$user_obj->mobile}}</p>
                                <p>{{$user_obj->telephone}}</p>
                                <p>{{$user_obj->fax}}</p>
                            </div>
                        </div>
                        <?php if($user_obj->user_id == $current_user->user_id): ?>
                        <div class="edit_dev">
                            <div class="col-md-5">
                                <input type="text" style="direction: rtl;" name="mobile" placeholder="رقم الجوال" class="form-control" value="{{$user_obj->mobile}}">
                            </div>
                            <div class="col-md-5">
                                <input type="text" style="direction: rtl;" name="telephone" placeholder="رقم الهاتف" class="form-control" value="{{$user_obj->telephone}}">
                            </div>
                            <div class="col-md-5">
                                <input type="text" style="direction: rtl;" name="fax" placeholder="رقم الفاكس" class="form-control" value="{{$user_obj->fax}}">
                            </div>
                        </div>
                        <div class="col-md-2 pull-left">
                            {{csrf_field()}}
                            <button class="btn btn-success edit_dev" type="submit"><i class="fa fa-save" aria-hidden="true"></i> حفظ</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($user_obj->user_id == $current_user->user_id): ?>
                </form>
                <?php endif; ?>
            </div>

            <?php if($user_obj->user_id == $current_user->user_id): ?>
            <form action="{{url("information/$user_obj->user_id/update_password")}}" method="POST">
            <div class="col-md-12 nopadding align-items st-block-info">
                <div class="col-md-3">
                    <h3> تغيير كلمة المرور</h3>
                </div>
                <div class="col-md-9">
                    <div class="">
                        <div class="col-md-4">
                            <input type="password" style="direction: rtl;" required placeholder="كلمة المرور القديمة" name="old_password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <input type="password" style="direction: rtl;" required placeholder="كلمة المرور الجديدة" name="password" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <input type="password" style="direction: rtl;" required placeholder="تأكيد كلمة المرور الجديدة" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pull-left">
                    {{csrf_field()}}
                    <button class="btn btn-success" type="submit"><i class="fa fa-save" aria-hidden="true"></i> حفظ</button>
                </div>
            </div>
            </form>
            <?php endif; ?>

        </div>
    </div>

@endsection
