@extends('admin.main_layout')

@section('subview')

    <input type="hidden" class="all_langs_titles" value="{{json_encode(convert_inside_obj_to_arr($all_langs,"lang_title"))}}">

	<script src="{{url("/public_html/tinymce/tinymce.min.js")}}" type="text/javascript"></script>
	<script>
		tinymce.init({
			selector: '.tinymce'
		});
	</script>


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
        .hide_input{
            display: none;
        }
        .selected_trip{
            margin: 5px;
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

		$header_text="Add New ";
		$page_id="";
		$page_url="";
        $page_price="";

		if ($page_data!="")
		{
			$header_text="Edit '".$page_data->page_title."'";
			$page_id=$page_data->page_id;
            $page_url=$page_data->page_url;
            $page_price=$page_data->page_price;
		}

	?>


	<!--new_editor-->
	<script src="{{url("/public_html/ckeditor/ckeditor.js")}}" type="text/javascript"></script>
	<script src="{{url("/public_html/ckeditor/adapters/jquery.js")}}" type="text/javascript"></script>

    <!-- Select 2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- END Select 2 -->

    <script type="text/javascript">
        $(function(){
            $('.page_select_tags').select2();


            <?php if($page_type!="video"): ?>
                $(".add_video_panel").hide();
            <?php endif; ?>

            <?php if($page_type=="photo_gallery"||$page_type=="video"): ?>
                $("div[data-hidediv='page_body[]_div_id']").hide();
            <?php endif; ?>

        });
    </script>

	<!--END new_editor-->
	<div class="panel panel-primary">
		<div class="panel-heading">
            <?=$header_text?>
		</div>
		<div class="panel-body">
			<div class="">
				<form id="save_form" action="<?=url("admin/pages/save_page/$page_type/$page_id")?>" method="POST" enctype="multipart/form-data">

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Event Details
                        </div>
                        <div class="panel-body">
                            <?php

                                echo generate_select_tags(
                                    $field_name="currency_id",
                                    $label_name="Select Currency ?",
                                    $text=convert_inside_obj_to_arr($all_currencies,"cur_to"),
                                    $values=convert_inside_obj_to_arr($all_currencies,"id"),
                                    $selected_value="",
                                    $class="form-control",
                                    $multiple="",
                                    $required="required",
                                    $disabled = "",
                                    $data = $page_data,
                                    $parent_div_class = "form-group col-md-6"
                                );

                                echo generate_select_tags(
                                    $field_name="importance_degree",
                                    $label_name="Select Importance ?",
                                    $text=["Red","Orange","Yellow","White","Not Important"],
                                    $values=[0,1,2,3,4],
                                    $selected_value="",
                                    $class="form-control",
                                    $multiple="",
                                    $required="required",
                                    $disabled = "",
                                    $data = $page_data,
                                    $parent_div_class = "form-group col-md-6"
                                );

                                echo generate_select_tags(
                                    $field_name="current_value_status",
                                    $label_name="Select Current Value Color ?",
                                    $text=["Red","Green","Gray"],
                                    $values=['red','green','gray'],
                                    $selected_value="",
                                    $class="form-control",
                                    $multiple="",
                                    $required="required",
                                    $disabled = "",
                                    $data = $page_data,
                                    $parent_div_class = "form-group col-md-6"
                                );

                                $normal_tags=[
                                    "event_datetime","current_value","expected_value","previous_value"
                                ];

                                $attrs = generate_default_array_inputs_html(
                                    $normal_tags,
                                    $translate_data = $page_data,
                                    "yes",
                                    $required="required"
                                );

                                $attrs[0]["event_datetime"]="Event Datetime";
                                $attrs[0]["current_value"]="Current Value";
                                $attrs[0]["expected_value"]="Expected Value";
                                $attrs[0]["previous_value"]="Previous Value";

                                $attrs[2]["expected_value"]="";

                                $attrs[3]["event_datetime"]="date_time";

                                $attrs[4]["event_datetime"]=(isset($page_data->event_datetime)?$page_data->event_datetime:date("Y-m-d H:i:s"));

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

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Translation Data
                        </div>
                        <div class="panel-body">
                            <div class="panel-group" id="accordion">
                                <?php foreach ($all_langs as $lang_key => $lang): ?>
                                    <?php
                                        $lang_id=$lang->lang_id;
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$lang_id}}">
                                        <h4 class="panel-title">
                                            <a>
                                                <img src="<?=url('/').'/'.$lang->lang_img_path?>" width="25"> {{$lang->lang_title}}
                                            </a>
                                        </h4>
                                    </div>


                                        <?php

                                            $lang_id=$lang->lang_id;

                                            $collapse_in = "";
                                            if ($lang_key == 0)
                                            {
                                                $collapse_in = "in";
                                            }

                                            $lang_img_url = url($lang->lang_img_path);
                                        ?>

                                        <div id="collapse{{$lang_id}}" class="panel-collapse  collapse <?php echo ($lang_key==0)?"in":""; ?>">
                                            <div class="panel-body">
                                                <div>

                                                    <?php

                                                    $translate_data=array();


                                                    $current_row = $all_page_translate_rows->filter(function ($value, $key) use($lang_id) {
                                                        if ($value->lang_id == $lang_id)
                                                        {
                                                            return $value;
                                                        }

                                                    });

                                                    if(is_object($current_row->first())){
                                                        $translate_data=$current_row->first();
                                                    }


                                                    $required=($lang_key==0)?"required":"";

                                                    $normal_tags=[
                                                        "page_title"
                                                    ];


                                                    $attrs = generate_default_array_inputs_html(
                                                        $normal_tags,
                                                        $translate_data,
                                                        "yes",
                                                        $required=""
                                                    );


                                                    foreach ($attrs[1] as $key => $value) {
                                                        $attrs[1][$key].="[]";
                                                    }


                                                    $attrs[0]["page_title"]="Event Title";

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
                                        </div>


                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>


					{{csrf_field()}}
                    <div class="col-md-12 save_new_page_div">
                        <input type="hidden" name="redirect_page_type" class="redirect_page_type" value="exit">
                        <button class="btn btn-primary save_new_page save_page_and_exit">Save And Exit</button>
                        <button class="btn btn-primary save_new_page save_page_and_new">Save And New</button>
                    </div>

                    <?php if(false): ?>
                    <!--general_check_validation-->

                        <button type="button" data-formid="save_form" data-link="<?=url("admin/pages/check_validation_for_save_page/$page_id")?>" class="col-md-3 col-md-offset-2 btn btn-warning btn-lg general_check_validation">Check Data</button>
                        <div class="col-md-8 col-md-offset-2">
                            <div class="general_check_validation_msg"></div>
                        </div>
                        <!--END general_check_validation-->
					<?php endif; ?>

				</form>
			</div>
		</div>
	</div>



@endsection


	
