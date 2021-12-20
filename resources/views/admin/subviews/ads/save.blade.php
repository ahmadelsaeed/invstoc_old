@extends('admin.main_layout')

@section('subview')

    <link rel="stylesheet" href="{{url('/public_html')}}/css/style.css" type="text/css" media="screen">
    <style>
        hr{
            width: 100%;
            height:1px;
        }
        .select_related_pages{
            width: 50%;
        }

        .select_related_sites{
            width: 50%;
        }
    </style>
    <?php

    if (count($errors->all()) > 0)
    {
        $dump = "<div class='alert alert-danger'>";
        foreach ($errors->all() as $key => $error)
        {
            $dump .= $error." <br>";
        }
        $dump .= "</div>";

        echo $dump;
    }


    if (isset($success)&&!empty($success)) {
        echo $success;
    }

    $header_text="Add New Ads.";
    $id="";
    $page_name="";
    $ad_show="";
    $position="";



    $ad_img="";
    $ad_img_path="";
    $ad_img_title="";
    $ad_img_alt="";

    $page_slider = "";

    $related_pages=array();

    $disabled_upload_imgs="";
    if (is_object($ad_data)) {
        $header_text="Edit '".$ad_data->ads_title."' Last Updated At: ".$ad_data->updated_at;
        $id=$ad_data->id;
        $page_name=$ad_data->page_name;
        $ad_show=$ad_data->ad_show;
        $position=$ad_data->position;

        $ad_img=$ad_data->ad_img;
        if ($ad_img > 0)
        {
            $ad_img_path=url($ad_data->ad_img_path);
            $ad_img_title=$ad_data->ad_img_title;
            $ad_img_alt=$ad_data->ad_img_alt;
        }

        $disabled_upload_imgs="disabled";
    }


    ?>

    <!--new_editor-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>
    <!--END new_editor-->


    <div class="panel panel-info">
        <div class="panel-heading">
            <?=$header_text?>
        </div>
        <div class="panel-body">
            <div>
                <form id="save_form" action="<?=url("admin/ads/save_ad/$id")?>" method="POST" enctype="multipart/form-data">

                    <div class="panel panel-primary">
                        <div class="panel-heading">Main Data..</div>
                        <div class="panel-body">
                            <?php

                            $normal_tags=array("ads_title","ad_link","ad_script");
                            $attrs = generate_default_array_inputs_html(
                                $normal_tags,
                                $ad_data,
                                "yes",
                                ""
                            );

                            $attrs[0]["ads_title"]="Adress Title <span style='color:#f56954;'>(Spaces & Symboles will removed)";
                            $attrs[0]["ad_link"]="Ads Link";
                            $attrs[0]["ad_script"]="Ads Script";


                            $attrs[2]["ads_title"] = "required";


                            $attrs[3]["ad_script"]="textarea";

                            echo
                            generate_inputs_html(
                                reformate_arr_without_keys($attrs[0]),
                                reformate_arr_without_keys($attrs[1]),
                                reformate_arr_without_keys($attrs[2]),
                                reformate_arr_without_keys($attrs[3]),
                                reformate_arr_without_keys($attrs[4]),
                                reformate_arr_without_keys($attrs[5])
                            );


                            ?>

                        </div>

                    </div>

                    <hr>


                    <?=
                    generate_img_tags_for_form(
                        "ad_img_file[]",
                        "ad_img_file",
                        "",
                        "ad_img_checkbox",
                        "yes",
                        "",
                        $ad_img_path,
                        $ad_img_title,
                        $ad_img_alt,
                        "",
                        $disabled_upload_imgs,
                        $displayed_img_width="100",
                        $display_label="Upload Ads Image"
                    )
                    ?>

                    <hr>
                    <?php

//                    echo generate_select_tags(
//                        $field_name="page_name",
//                        $label_name="Select Page",
//                        $text=array("HomePage"),
//                        $values=array("homepage"),
//                        $selected_value=array($page_name),
//                        $class="form-control"
//                    );

                    ?>
                    <input type="hidden" name="page_name" value="homepage">
                    <hr>


                    <?php
                    echo generate_select_tags(
                        $field_name="ad_show",
                        $label_name="Ads Type",
                        $text=array("Google Script", "Image"),
                        $values=array("script","image"),
                        $selected_value=array($ad_show),
                        $class="form-control");
                    ?>
                    <hr>
                    <?php
                    echo generate_select_tags(
                        $field_name="position",
                        $label_name="Ads Position",
                        $text=array("homepage_body1","homepage_body2","homepage_right_script1","homepage_left_script1","homepage_left_script2","profile_right","profile_left"),
                        $values=array("homepage_body1","homepage_body2","homepage_right_script1","homepage_left_script1","homepage_left_script2","profile_right","profile_left"),
                        $selected_value=array($position),
                        $class="form-control");
                    ?>


                    {{csrf_field()}}
                    <input id="submit" type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">

                </form>
            </div>
        </div>
    </div>



@endsection



