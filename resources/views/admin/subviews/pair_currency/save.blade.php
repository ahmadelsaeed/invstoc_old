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

    $header_text="Add New Pair Currency";
    $id="";

    if (is_object($pair_currency_data)) {
        $header_text="Edit >> ".$pair_currency_data->pair_currency_name;
        $id=$pair_currency_data->pair_currency_id;
    }


    ?>


    <div class="panel panel-info">
        <div class="panel-heading">
            <?=$header_text?>
        </div>
        <div class="panel-body">
            <div class="">
                <form id="save_form" action="<?=url("admin/pair_currency/save_pair_currency/$id")?>" method="POST" enctype="multipart/form-data">

                    <?php

                    $normal_tags=array("pair_currency_name");
                    $attrs = generate_default_array_inputs_html(
                            $normal_tags,
                            $pair_currency_data,
                            "yes",
                            $required = "required"
                    );

                    $attrs[0]["pair_currency_name"]=" Pair Currency name";

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


                    {{csrf_field()}}
                    <input id="submit" type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">

                </form>
            </div>

        </div>
    </div>


@endsection



