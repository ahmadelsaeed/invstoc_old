@extends('admin.main_layout')

@section("subview")
<style>
    hr{
        width: 100%;
        height:1px;
    }
</style>
<?php

$header="New Menu";
$menu_id=0;
$menu_title="";
$menu_items="";
if ($menu_data!="") {
    $menu_id=$menu_data->menu_id;
    $menu_title=$menu_data->menu_title;
    
    $menu_items=  json_decode($menu_data->menu_json,true);
    
    $header=$menu_title;
}



?>


<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="<?php echo url("public_html/jscode/admin/btm_nested_sortable.js") ?>" type="text/javascript"></script>

<style>
    .btm_sortable_ul{
        position: relative;
        margin: 5px;
        list-style: none;
    }

    .btm_sortable_ul li{
        cursor: move;
        border: 2px solid #f1f1f1;
    }

    .btm_sortable_ul li .inner_li{
        border:1px solid #000;
        background-color: #CCC;
        border-radius: 5px;
        padding: 5px;
        margin: 5px;
    }
    .sortable_li_btns{
        list-style: none;
        margin:0px;
        padding: 0px;
    }
    .sortable_li_btns li{
        float:left;
        border: none;
        margin-left: 10px;
    }



</style>
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
    #sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; }
    html>body #sortable li { height: 1.5em; line-height: 1.2em; }
    .ui-state-highlight { height: 2em; line-height: 2em;background-color: #285e8e; }
</style>

<div class="panel panel-primary">
    <div class="panel-heading">
        Menus
    </div>
    <div class="panel-body">
        <div class="">

            <div class="row">

                <div class="col-md-12">
                    <div class="col-md-12">
                        <h3><?php echo $header; ?></h3>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Enter Menu Title</label>
                            <input type="text" class="form-control" name="menu_title" id="menu_title_val" value="<?php echo $menu_title; ?>">
                            <input type="hidden" name="menu_id" id="menu_id_val" value="<?php echo $menu_id; ?>">
                            <input type="hidden" name="lang_id" id="lang_id_val" value="<?php echo $lang_id; ?>">
                        </div>
                    </div>

                </div>


                <?php if (is_array($menu_items)&&count($menu_items)): ?>

                <div class="col-md-6">


                    <div class="col-md-12">
                        <ul class="btm_sortable_ul">
                            <?php foreach ($menu_items as $key => $item): ?>
                            <?php
                                $data["displayed_text"] = $item["level_data"]["item_name"];
                                $data["link_text"] = $item["level_data"]["item_name"];
                                $data["link_href"] = $item["level_data"]["item_slug"];
                                $data["link_class"] = "";
                                $data["level_value"] = 0;
                                $data["childs"]="";
                                if (isset($item["level_childs"])) {
                                    $data["childs"] = $item["level_childs"];
                                }
                                ?>

                            <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>

                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php else: ?>

                <div class="col-md-6">
                    <div class="col-md-12">
                        <h3>Main Menu</h3>
                    </div>

                    <div class="col-md-12">
                        <ul class="btm_sortable_ul">

                            <?php
                            $data["displayed_text"] = "HomePage";
                            $data["link_text"] = "HomePage";
                            $data["link_href"] = "";
                            $data["link_class"] = "";
                            $data["level_value"] = 0;
                            $data["childs"]="";
                            ?>
                            <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>


                        </ul>
                    </div>

                </div>

                <?php endif; ?>

                <div class="col-md-6">

                    <div class="panel-group" id="accordion">


                        <!--pages-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_pages">
                                        Pages
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse_pages" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <!--pages-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <h3>Pages</h3>
                                        </div>
                                        <div class="col-md-12">
                                            <ul class="btm_sortable_ul">

                                                <?php foreach($static_pages as $key=>$static_page): ?>
                                                <?php
                                                    $data["displayed_text"] = $static_page["name"];
                                                    $data["link_text"] = $static_page["name"];
                                                    $data["link_href"] = $static_page["url"];
                                                    $data["link_class"] = "";
                                                    $data["level_value"] = 0;
                                                    $data["childs"]="";
                                                    ?>

                                                <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>
                                            <?php endforeach;?>

                                                <?php foreach ($pages as $key => $page): ?>
                                                <?php
                                                    $data["displayed_text"] = $page->page_title;
                                                    $data["link_text"] = $page->page_title;
                                                    $data["link_href"] = $page->page_slug;
                                                    $data["link_class"] = "";
                                                    $data["level_value"] = 0;
                                                    $data["childs"]="";
                                                    ?>

                                                <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>
                                            <?php endforeach; ?>




                                            </ul>
                                        </div>
                                    </div>
                                    <!--end pages-->
                                </div>
                            </div>
                        </div>
                        <!--END pages-->



                        <!--articles-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_articles">
                                        Articles Categories
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse_articles" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <!--Articles-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <h3>Articles Categories</h3>
                                        </div>
                                        <div class="col-md-12">
                                            <ul class="btm_sortable_ul">
                                                <?php foreach ($all_article as $key => $item): ?>

                                                <?php
                                                    $data["displayed_text"] = $item["level_data"]["item_name"];
                                                    $data["link_text"] = $item["level_data"]["item_name"];
                                                    $data["link_href"] = $item["level_data"]["item_slug"];
                                                    $data["link_class"] = "";
                                                    $data["level_value"] = 0;
                                                    $data["childs"]="";
                                                    $data["childs"] = $item["level_childs"];
                                                    ?>
                                                <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>
                                            <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--END Articles-->
                                </div>
                            </div>
                        </div>
                        <!--END articles-->



                        <!--Custom Links-->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse_custom_links">
                                        Custom Links
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse_custom_links" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <!--products-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <h3>Custom Links</h3>
                                        </div>
                                        <div class="col-md-12">
                                            <ul class="btm_sortable_ul">
                                                <?php
                                                $custom_links=array("level_data"=>array(
                                                    "item_name"=>"Custom Link",
                                                    "item_slug"=>"#"
                                                ));
                                                ?>
                                                <?php for($i=0;$i<10;$i++): ?>


                                                <?php
                                                    $data["displayed_text"] = $custom_links["level_data"]["item_name"];
                                                    $data["link_text"] = $custom_links["level_data"]["item_name"];
                                                    $data["link_href"] = $custom_links["level_data"]["item_slug"];
                                                    $data["link_class"] = "";
                                                    $data["level_value"] = 0;
                                                    $data["childs"] = "";
                                                    ?>
                                                <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>

                                            <?php endfor;?>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--END products-->
                                </div>
                            </div>
                        </div>
                        <!--END Custom Links-->


                    </div>


                </div>


            </div>
            <!--end row-->


            {{csrf_field()}}
            <button class="col-md-4 col-md-offset-4 btn btn-info btn-lg save_sortable_menu">Save Menu</button>
        </div>
    </div>
</div>


@endsection

