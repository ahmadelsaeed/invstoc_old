<?php
$all_brokers_names = convert_inside_obj_to_arr($all_brokers,"page_title");
$all_brokers_names = array_unique($all_brokers_names);
$all_brokers_ids = convert_inside_obj_to_arr($all_brokers,"page_id");
$all_brokers_ids = array_unique($all_brokers_ids);
?>

<div class="ui-block">

    <div class="ui-block-title">
        <h6 class="title">
            <i class="fa fa-code-branch"></i>
            {{show_content($brokers_keywords,"choose_your_broker")}}
        </h6>
    </div>

    <div class="form-company trade_calculations">
        <div class="col-md-12">
            <br>
            <select class="form-control broker_id" style="padding: 5px">
                <option value="0">{{show_content($brokers_keywords,"choose_your_broker")}}</option>
                <?php foreach($all_brokers_names as $key => $broker_name): ?>
                <option value="{{$all_brokers_ids[$key]}}">{{$broker_name}}</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-12">
            <br>
            <select class="form-control pair_currency_id" disabled style="padding: 5px">
                <option>{{show_content($brokers_keywords,"choose_pair_currency")}}</option>
            </select>
        </div>
        <div class="col-md-12">
            <br>
            <input type="number" min="0" class="form-control trade_volume" placeholder="{{show_content($brokers_keywords,"monthly_trade_volume")}}">
        </div>
        <div class="col-md-12">
            <br>
            <button class="btn btn-success get_trade_sum">{{show_content($brokers_keywords,"submit_btn")}}</button>
        </div>
        <div class="col-md-12 load_trade_message" style="color: #fff;"></div>
    </div>

</div>






