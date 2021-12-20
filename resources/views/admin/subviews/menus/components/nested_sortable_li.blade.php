<li class="btm_level" data-levelvalue="<?=$level_value?>">
    <div class="row inner_li">

        <div class="col-md-8">
            
            
            <ul class="sortable_li_btns">
                <li>
                    <button class="btn btn-info show_hide_childs">(+/-)</button>
                </li>
                
                <li>
                    <?php echo $displayed_text; ?>
                </li>
            </ul>
            
        </div>
        <div class="col-md-4">
                <ul class="sortable_li_btns">
                    <li>
                        <button class="btn btn-primary collapse_open_btn"><i class="fa fa-arrows-v"></i></button>
                    </li>
                    
                    <li>
                        <button class="btn btn-info remove_menu_link"><i class="fa fa-times"></i></button>
                    </li>
                </ul>
        </div>
        
        <div class="col-md-4">
                <ul class="sortable_li_btns">
                    <li>
                        <button class="btn btn-info level_out_btn">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-info level_in_btn">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </li>
                </ul>
        </div>

        <div class="col-md-12">
            <div class="col-md-12 collapse collapse_div">
                <?php echo generate_inputs_html($label_name = array("Link Text", "Link Href", "Link Class"), $field_name = array("link_text", "link_href", "link_class"), $required = array("", "", ""), $type = array("text", "text", "text"), $values = array("$link_text", "$link_href", "$link_class"), $class = array("form-control", "form-control", "form-control")) ?>
            </div>
        </div>
    </div>
    
    
    <?php if (isset($childs)&&  is_array($childs)&&  count($childs)): ?>

        <?php foreach ($childs as $key => $child): ?>
                <?php if (isset($child["level_data"]["item_name"])): ?>
                    <ul class="btm_sortable_ul">
                        <?php 
                                $data["displayed_text"]=$child["level_data"]["item_name"];
                                $data["link_text"]=$child["level_data"]["item_name"];
                                $data["link_href"] = $child["level_data"]["item_slug"];
                                $data["link_class"]="";
                                $data["level_value"]=$level_value+1;
                                $data["childs"]="";
                                if (isset($child["level_childs"])) {
                                    $data["childs"]=$child["level_childs"];
                                }
                                
                        ?>
                        <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>
                    </ul>
    
                <?php else:?>

                    <?php foreach ($child as $key => $item): ?>
                        <?php
                            if(
                                !isset($item["item_name"])||
                                !isset($item["item_slug"])
                            ){
                                continue;
                            }
                        ?>

                        <ul class="btm_sortable_ul">
                        <?php 
                                $data["displayed_text"]=$item["item_name"];
                                $data["link_text"]=$item["item_name"];
                                $data["link_href"] = $item["item_slug"];
                                $data["link_class"]="";
                                $data["level_value"]=$level_value+1;
                                $data["childs"]="";
                        ?>
                            <?php echo (string)View::make("admin.subviews.menus.components.nested_sortable_li",$data)->render(); ?>
                        </ul>
                    <?php endforeach; ?>
                
                <?php endif; ?>
                    
            
            
        <?php endforeach; ?>
    <?php endif;//parent if ?>
    
</li>