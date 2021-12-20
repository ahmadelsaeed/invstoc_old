<?php
$price_table = json_decode($trip_data->trip_price_table)
?>
<?php if(is_object($price_table )): ?>
<div class="col-md-12">
    <div class="heading heading-v1 margin-bottom-20 st-tit">
        <h2>{{show_content($trip_keywords,"price_table")}}</h2>
    </div>
</div>

<?php if ($price_table->type == "type1"): ?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div >
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>No. Of Nights</th>
                        <th>03</th>
                        <th>04</th>
                        <th>07</th>
                    </tr>
                    </thead>

                    <?php
                    $single = $price_table->table->single;
                    $double = $price_table->table->double;
                    $triple = $price_table->table->triple;
                    ?>
                    <tbody>

                    <tr>
                        <td >Single</td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$single[0]}}">{{$single[0]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$single[1]}}">{{$single[1]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$single[2]}}">{{$single[2]}}</span>
                            <span class="currency_sign">$</span>
                        </td>

                    </tr>
                    <tr>
                        <td >Double</td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$double[0]}}">{{$double[0]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$double[1]}}">{{$double[1]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$double[2]}}">{{$double[2]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Triple</td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$triple[0]}}">{{$triple[0]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$triple[1]}}">{{$triple[1]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                            <span class="currency_value"  data-original_price="{{$triple[2]}}">{{$triple[2]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php if ($price_table->type == "type2"): ?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div >
                <table class="table table-striped">
                    <?php $price_inner = $price_table->table->tbl_price; ?>


                    <thead>
                    <tr>
                        <?php if(!empty($price_inner[0])): ?>
                        <th>Single</th>
                        <?php endif; ?>
                        <?php if(!empty($price_inner[1])): ?>
                        <th>02 - 03 </th>
                        <?php endif; ?>
                        <?php if(!empty($price_inner[2])): ?>
                        <th>04 - 06 </th>
                        <?php endif; ?>
                        <?php if(!empty($price_inner[3])): ?>
                        <th>07 - 10</th>
                        <?php endif; ?>
                        <th>More</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <?php if(!empty($price_inner[0])): ?>
                        <td>
                            <span class="currency_value"  data-original_price="{{$price_inner[0]}}">{{$price_inner[0]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <?php endif; ?>

                        <?php if(!empty($price_inner[1])): ?>
                        <td>
                            <span class="currency_value"  data-original_price="{{$price_inner[1]}}">{{$price_inner[1]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <?php endif; ?>

                        <?php if(!empty($price_inner[2])): ?>
                        <td>
                            <span class="currency_value"  data-original_price="{{$price_inner[2]}}">{{$price_inner[2]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <?php endif; ?>
                        <?php if(!empty($price_inner[3])): ?>
                        <td>
                            <span class="currency_value"  data-original_price="{{$price_inner[3]}}">{{$price_inner[3]}}</span>
                            <span class="currency_sign">$</span>
                        </td>
                        <?php endif; ?>
                        <td>
                            <a href="#check_availability">{{show_content($trip_keywords,"check_availability")}}</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php if ($price_table->type == "type3"): ?>

<div class="col-md-6">
    <div class="row">
        <div class="col-md-12">
            <div >
                <table class="table table-striped">
                    <?php if (!empty($price_table->table->table_price_3_header_1)): ?>
                    <tr>
                        <td colspan="3"><?= $price_table->table->table_price_3_header_1 ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>May-Sept</td>
                        <td>Oct-April</td>
                    </tr>
                    <tr>
                        <td>Double</td>
                        <td>
                                                            <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_1[0]}}">
                                                                {{$price_table->table->table_price_3_prices_1[0]}}
                                                            </span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                                                            <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_1[1]}}">
                                                                {{$price_table->table->table_price_3_prices_1[1]}}
                                                            </span>
                            <span class="currency_sign">$</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Solo</td>
                        <td>
                                                            <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_1[2]}}">
                                                                {{$price_table->table->table_price_3_prices_1[2]}}
                                                            </span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                                                            <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_1[3]}}">
                                                                {{$price_table->table->table_price_3_prices_1[3]}}
                                                            </span>
                            <span class="currency_sign">$</span>
                        </td>
                    </tr>
                    <?php endif ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="row">
        <div class="col-md-12">
            <div >
                <table class="table table-striped">
                    <?php if (!empty($price_table->table->table_price_3_header_2)): ?>
                    <tr>
                        <td colspan="3"><?= $price_table->table->table_price_3_header_2 ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>May-Sept</td>
                        <td>Oct-April</td>
                    </tr>
                    <tr>
                        <td>Double</td>
                        <td>
                                    <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_2[0]}}">
                                        {{$price_table->table->table_price_3_prices_2[0]}}
                                    </span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                                    <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_2[1]}}">
                                        {{$price_table->table->table_price_3_prices_2[1]}}
                                    </span>
                            <span class="currency_sign">$</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Solo</td>
                        <td>
                                    <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_2[2]}}">
                                        {{$price_table->table->table_price_3_prices_2[2]}}
                                    </span>
                            <span class="currency_sign">$</span>
                        </td>
                        <td>
                                    <span class="currency_value"  data-original_price="{{$price_table->table->table_price_3_prices_2[3]}}">
                                        {{$price_table->table->table_price_3_prices_2[3]}}
                                    </span>
                            <span class="currency_sign">$</span>
                        </td>
                    </tr>
                    <?php endif ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php endif; ?>
