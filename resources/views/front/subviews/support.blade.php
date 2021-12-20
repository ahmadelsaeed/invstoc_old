@extends('front.main_layout')

@section('subview')


    <section class="mt-0">
        <div class="container">
            <div id="map">
                {!! show_content($support,"map") !!}
            </div>
        </div>
    </section>

    <section class="medium-padding120 bg-body contact-form-animation scrollme">
        <div class="container">
            <div class="row">
                <div class="col col-xl-10 col-lg-10 col-md-12 col-sm-12  m-auto">

                    <!-- Contacts Form -->

                    <div class="contact-form-wrap contact_us_parent_div">
                        <div class="contact-form-thumb">
                            <h2 class="title">{{show_content($support,"header")}}</h2>
                            <p></p>
                            <img src="{{url("/")}}/public_html/front/new_design/img/crew.png" alt="crew" class="crew">
                        </div>
                        <form class="contact-form" action="{{ "support"}}" enctype="multipart/form-data" method="POST">
                            <div class="row">

                                <div class="col col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">{{show_content($support,"form_name")}}</label>
                                        <input class="form-control" name="name" required placeholder=""
                                               type="text" value="{{isset($current_user->full_name)?$current_user->full_name:""}}">
                                    </div>
                                </div>
                                <div class="col col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">{{show_content($support,"form_phone")}}</label>
                                        <input class="form-control" placeholder=""
                                               type="text" name="phone" required value="">
                                    </div>
                                </div>
                                <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group label-floating">
                                        <label class="control-label">{{show_content($support,"form_email")}}</label>
                                        <input class="form-control" placeholder=""
                                               type="email" name="email" required value="{{isset($current_user->email)?$current_user->email:""}}">                                    </div>

                                    <div class="form-group label-floating is-empty">
                                        <label class="control-label">{{show_content($support,"form_msg")}}</label>
                                        <textarea class="form-control" name="message" required placeholder=""></textarea>
                                    </div>

                                    <input type="hidden" class="msg_type" id="msg_type" value="support">

                                    {{csrf_field()}}

                                    <div class="form-group label-floating is-select">
                                        <?php

                                        echo generate_img_tags_for_form(
                                            $filed_name="attach_file[]",
                                            $filed_label="attach_file",
                                            $required_field="multiple",
                                            $checkbox_field_name="attach_checkbox",
                                            $need_alt_title="no",
                                            $required_alt_title="",
                                            $old_path_value="",
                                            $old_title_value="",
                                            $old_alt_value="",
                                            $recomended_size="",
                                            $disalbed="",
                                            $displayed_img_width="100",
                                            $display_label=show_content($support,"form_upload_label"),
                                            $img_obj = ""
                                        );

                                        ?>
                                    </div>

                                    <button class="btn btn-purple btn-lg full-width"> {{show_content($support,"form_btn")}} </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ... end Contacts Form -->

                </div>
            </div>
        </div>

        <div class="half-height-bg bg-white"></div>
    </section>

@endsection

