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
		$page_url_old="";
		$page_url2_old="";
        $page_url3_old="";
        $page_price="";

		if ($page_data!="")
		{
			$header_text="Edit '".$page_data->page_title."'";
			$page_id=$page_data->page_id;
            $page_url_old=$page_data->page_url_old;
            $page_url2_old=$page_data->page_url2_old;
            $page_url3_old=$page_data->page_url3_old;
            $page_price=$page_data->page_price;
		}

	?>


	<!--new_editor-->
	<script src="{{url("/")}}/public_html/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script src="{{url("/")}}/public_html/ckeditor/adapters/jquery.js" type="text/javascript"></script>

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

                    <?php if(false): ?>

                        <div class="panel panel-info">
                            <div class="panel-heading">Select {!! transform_underscore_text($page_type) !!} Category</div>
                            <div class="panel-body">
                                <?php

                                    echo generate_depended_selects(
                                        $field_name_1="parent_id",
                                        $field_label_1="Select Parent Category",
                                        $field_text_1=convert_inside_obj_to_arr($all_parent_cats,"cat_name"),
                                        $field_values_1=convert_inside_obj_to_arr($all_parent_cats,"cat_id"),
                                        $field_selected_value_1=(is_object($page_data)?$page_data->parent_cat_id:""),
                                        $field_required_1="",
                                        $field_class_1="form-control",
                                        $field_name_2="cat_id",
                                        $field_label_2="Select Child Category",
                                        $field_text_2=convert_inside_obj_to_arr($all_child_cats,"cat_name"),
                                        $field_values_2=convert_inside_obj_to_arr($all_child_cats,"cat_id"),
                                        $field_selected_value_2=(is_object($page_data)?$page_data->child_cat_id:""),
                                        $field_2_depend_values=convert_inside_obj_to_arr($all_child_cats,"parent_id"),
                                        $field_required_2="",
                                        $field_class_2="form-control"
                                    );
                                ?>

                            </div>
                        </div>


                    <?php endif; ?>

                    <?php if($page_type == "company"): ?>
                    <div class="panel panel-info">
                        <div class="panel-heading"><b>Broker Account URL</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">
                                        <b>Price per lot</b>
                                    </label>
                                    <input name="page_price" class="form-control" value="{{$page_price}}">
                                </div>
                            </div>
                        </div>
                    </div>

                        <?php if(count($pairs)): ?>

                        <div class="panel panel-info">
                            <div class="panel-heading"><b>Broker Prices</b></div>
                            <div class="panel-body" style="max-height: 300px;overflow-y: scroll;">

                                <div class="row">
                                    <div class="col-md-12">

                                        <table class="table">
                                            <?php foreach($pairs as $key => $pair): ?>
                                                <tr>
                                                    <th>{{$pair->pair_currency_name}}</th>
                                                    <td>
                                                        <input type="number" name="price[]" class="form-control" step="0.01" value="{{(isset($old_pairs[$pair->pair_currency_id]) && count($old_pairs[$pair->pair_currency_id]))?$old_pairs[$pair->pair_currency_id][0]->price:0.00}}">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php endif; ?>

                    <?php endif; ?>



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
                                                <img src="<?=get_image_or_default($lang->lang_img_path)?>" width="25"> {{$lang->lang_title}}
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

                                                    if ($page_type != "company")
                                                    {
                                                        $normal_tags=[
                                                            "page_title",
                                                            "page_slug","page_short_desc",
                                                            "page_body",
                                                            "page_meta_title","page_meta_desc","page_meta_keywords"
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


                                                        $attrs[0]["page_title"]="Title";
                                                        $attrs[0]["page_slug"]="Link <span style='color:#f56954;'>(Spaces & Symboles will removed)";
                                                        $attrs[0]["page_short_desc"]="Short Description";
                                                        $attrs[0]["page_body"]="Description";
                                                        $attrs[0]["page_meta_title"]="Meta Title";
                                                        $attrs[0]["page_meta_desc"]="Meta Desc";
                                                        $attrs[0]["page_meta_keywords"]="Meta Keywords";

                                                        $attrs[3]["page_short_desc"]="textarea";
                                                        $attrs[3]["page_body"]="textarea";
                                                        $attrs[3]["page_meta_desc"]="textarea";
                                                        $attrs[3]["page_meta_keywords"]="textarea";

                                                        $attrs[2]["page_slug"]="";
                                                        $attrs[5]["page_body"].=" ckeditor";
                                                        $attrs[5]["page_short_desc"] .= " ckeditor";

                                                    }
                                                    else{

                                                        if ($page_type == "company")
                                                        {
                                                            $normal_tags=[
                                                                "page_title","page_slug",
                                                                "page_url","page_url2","page_url3",
                                                                "page_broker_third_link_title","page_short_desc","page_body",
                                                                "page_meta_title","page_meta_desc","page_meta_keywords"
                                                            ];
                                                        }
                                                        else{
                                                            $normal_tags=[
                                                                "page_title","page_body"
                                                            ];
                                                        }


                                                        $attrs = generate_default_array_inputs_html(
                                                            $normal_tags,
                                                            $translate_data,
                                                            "yes",
                                                            $required
                                                        );


                                                        foreach ($attrs[1] as $key => $value) {
                                                            $attrs[1][$key].="[]";
                                                        }


                                                        $attrs[0]["page_title"]="Broker Name";
                                                        $attrs[0]["page_body"]="Description";
                                                        $attrs[3]["page_body"]="textarea";
                                                        $attrs[3]["page_short_desc"] = "textarea";
                                                        $attrs[3]["page_meta_desc"]="textarea";
                                                        $attrs[3]["page_meta_keywords"]="textarea";
                                                        $attrs[5]["page_body"].=" ckeditor";
                                                        $attrs[5]["page_short_desc"] .= " ckeditor";

                                                        if ($page_type == "company")
                                                        {

                                                            $attrs[0]["page_url"]  = "Full Url For Demo Account";
                                                            $attrs[0]["page_url2"] = "Full Url For Real Time Account";
                                                            $attrs[0]["page_url3"] = "Full Url For another Account";
                                                            $attrs[0]["page_slug"]="Slug <span style='color:#f56954;'>(Spaces & Symboles will removed)";
                                                            $attrs[4]["page_url"]  = (empty($translate_data->page_url)?$page_url_old:$translate_data->page_url);
                                                            $attrs[4]["page_url2"] = (empty($translate_data->page_url2)?$page_url2_old:$translate_data->page_url2);
                                                            $attrs[4]["page_url3"] = (empty($translate_data->page_url3)?$page_url3_old:$translate_data->page_url3);

                                                            $attrs[0]["page_broker_third_link_title"]="Broker Third link title if exist";
                                                            $attrs[2]["page_broker_third_link_title"]="";;
                                                            $attrs[2]["page_meta_title"]="";
                                                            $attrs[2]["page_meta_desc"]="";
                                                            $attrs[2]["page_meta_keywords"]="";
                                                        }

                                                    }

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


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Images
                        </div>
                        <div class="panel-body">
                            @if($page_type == 'news' || $page_type == 'article')
                                <label>PDF (en)</label>
                                <input type="file" name="pdf_en" class="form-control">
                                <p>{{ isset($page_data->pdf_en) ? 'Uploaded file: ' . $page_data->pdf_en : '' }}</p>
                                <label>PDF (ar)</label>
                                <input type="file" name="pdf_ar" class="form-control">
                                <p>{{ isset($page_data->pdf_ar) ? 'Uploaded file: ' . $page_data->pdf_ar : '' }}</p>
                            @endif
                            <?php
                                if(is_array($small_img_width_height)){
                                    $img_obj = isset($page_data->page_small_img) ? $page_data->page_small_img : "";

                                    echo generate_img_tags_for_form(
                                        $filed_name="small_img_file",
                                        $filed_label="small_img_file",
                                        $required_field="",
                                        $checkbox_field_name="small_img_checkbox",
                                        $need_alt_title="yes",
                                        $required_alt_title="",
                                        $old_path_value="",
                                        $old_title_value="",
                                        $old_alt_value="",
                                        $recomended_size=implode(",",array_keys($small_img_width_height))." ".implode(",",$small_img_width_height),
                                        $disalbed="",
                                        $displayed_img_width="100",
                                        $display_label="Upload Small Image",
                                        $img_obj
                                    );
                                }
                            ?>


                            <hr>
                            <?php
                                if(is_array($big_img_width_height)){
                                    $img_obj = isset($page_data->page_big_img) ? $page_data->page_big_img : "";

                                    echo generate_img_tags_for_form(
                                        $filed_name="big_img_file",
                                        $filed_label="big_img_file",
                                        $required_field="",
                                        $checkbox_field_name="big_img_checkbox",
                                        $need_alt_title="yes",
                                        $required_alt_title="",
                                        $old_path_value="",
                                        $old_title_value="",
                                        $old_alt_value="",
                                        $recomended_size=implode(",",array_keys($big_img_width_height))." ".implode(",",$big_img_width_height),
                                        $disalbed="",
                                        $displayed_img_width="100",
                                        $display_label="Upload Big Image",
                                        $img_obj
                                    );
                                }
                            ?>


                            <?php if(false): ?>
                                <hr>

                                <?php
                                    echo
                                    generate_slider_imgs_tags(
                                        $slider_photos=(isset($page_data->slider_imgs)&&isset_and_array($page_data->slider_imgs))?$page_data->slider_imgs:"",
                                        $field_name="page_slider_file",
                                        $field_label="Slider Images",
                                        $field_id="page_slider_file_id",
                                        $accept="image/*",
                                        $need_alt_title="yes"
                                    );
                                ?>
					        <?php endif; ?>

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



