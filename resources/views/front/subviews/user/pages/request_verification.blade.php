
@extends('front.subviews.user.user_layout')
@section('page')



    <?php if($current_user->is_privet_account == 0): ?>
    <div class="container">
        <div class="row">
            <div class="col col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-12">
                <div class="ui-block">
                    <div class="ui-block-title">
                        <h6 class="title">{{show_content($user_profile_keywords,"request_verification_header")}}</h6>
                    </div>
                    <div class="ui-block-content">
                       <form action="{{url("$language_locale/information/$current_user->user_id/request_privet_account")}}" enctype="multipart/form-data" method="POST">

                          <div class="row">

                            <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"request_verification_full_name")}} *</label>
                                    <input class="form-control"  type="text" name="full_name" value="{{$current_user->first_name." ".$current_user->last_name}}"  required >
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



                              <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                  <div class="form-group label-floating">
                                      <?php

                                      echo generate_img_tags_for_form(
                                          $filed_name="attach_file[]",
                                          $filed_label="attach_file",
                                          $required_field="required multiple",
                                          $checkbox_field_name="attach_checkbox",
                                          $need_alt_title="no",
                                          $required_alt_title="",
                                          $old_path_value="",
                                          $old_title_value="",
                                          $old_alt_value="",
                                          $recomended_size="",
                                          $disalbed="",
                                          $displayed_img_width="100",
                                          $display_label=show_content($user_profile_keywords,"request_verification_attach_id"),
                                          $img_obj = ""
                                      );

                                      ?>
                                  </div>
                              </div>


                              <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">{{show_content($user_profile_keywords,"request_verification_bank_details")}} </label>
                                    <textarea class="form-control" name="message" required></textarea>
                                </div>
                            </div>


                              {{csrf_field()}}

                            <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                <button class="btn btn-primary btn-lg full-width" type="submit">{{show_content($general_static_keywords,"request_verification_submit")}}</button>
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







