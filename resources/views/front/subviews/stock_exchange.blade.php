@extends('front.main_layout')

@section('subview')

    <div class="container">
        <div class="row">

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <?php
                    $query = Request::query();
                    $url = "economic_calendar";
                    if (count($query))
                    {
                        $count = 0;
                        foreach ($query as $key => $value)
                        {
                            if ($count == 0)
                            {
                                $url .= "?$key=$value";
                            }
                            else{
                                $url .= "&$key=$value";
                            }

                            $count ++;
                        }
                    }
                ?>

                <div class="add-icon stock_exchange_icons">
                    <table>
                        <tr>
                            <td> <img src="{{(isset($events_keywords->strong_img->path)?get_image_or_default($events_keywords->strong_img->path):"")}}" /> {{show_content($events_keywords,"strong_label")}}  </td>
                            <td> <img src="{{(isset($events_keywords->middle_img->path)?get_image_or_default($events_keywords->middle_img->path):"")}}" /> {{show_content($events_keywords,"middle_label")}}  </td>
                            <td> <img src="{{(isset($events_keywords->weak_img->path)?get_image_or_default($events_keywords->weak_img->path):"")}}" /> {{show_content($events_keywords,"weak_label")}}  </td>
                            <td> <img src="{{(isset($events_keywords->very_weak_img->path)?get_image_or_default($events_keywords->very_weak_img->path):"")}}" /> {{show_content($events_keywords,"very_weak_label")}}  </td>
                            <td> <img src="{{(isset($events_keywords->not_important_img->path)?get_image_or_default($events_keywords->not_important_img->path):"")}}" /> {{show_content($events_keywords,"not_important_label")}}  </td>
                        </tr>
                    </table>
                </div>

                <div class="select-time">

                    <form action="{{url("$url")}}">

                        <input type="hidden" name="target" class="filter_target" value="{{$target}}">
                        <div class="col-md-12 stock_exchange_filter">
                            <div class="btn_new_sort">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a href="#" id="yesterday" class="nav-link {{($target == "yesterday")?"active":""}}">{{show_content($events_keywords,"yesterday_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" id="today" class="nav-link {{($target == "today")?"active":""}}">{{show_content($events_keywords,"today_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" id="tomorrow" class="nav-link {{($target == "tomorrow")?"active":""}}">{{show_content($events_keywords,"tomorrow_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" id="week" class="nav-link {{($target == "week")?"active":""}}">{{show_content($events_keywords,"this_week_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" id="next_week" class="nav-link {{($target == "next_week")?"active":""}}">{{show_content($events_keywords,"next_week_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" id="range_date" class="nav-link {{($target == "range_date")?"active":""}}"><i class="fa fa-calendar"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row range_date_container {{($target == "range_date")?"":"hide_div"}}">

                            <div class="col-md-5">
                                <label for="start_date"><b>{{show_content($events_keywords,"start_date_label")}}</b></label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{(isset($_GET['start_date']) && !empty($_GET['start_date']))?$_GET['start_date']:date("Y-m-d")}}">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date"><b>{{show_content($events_keywords,"end_date_label")}}</b></label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{(isset($_GET['end_date']) && !empty($_GET['end_date']))?$_GET['end_date']:date("Y-m-d")}}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success search_stock_range"><i class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 stock_exchange_timezone">
                                <select class="form-control sort_select change_timezone" name="timezone" style="padding: 5px;">
                                    <?php foreach(TIMEZONES as $key => $value): ?>
                                    <option {{($key == $selected_timezone)?"selected":""}} value="{{$key}}">
                                        {{$value}}
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <select class="form-control sort_select select_group_by stock_exchange_time_format" name="group_by" style="padding: 5px;">
                                    <option value="12" {{(!isset($_GET['group_by']) || $_GET['group_by'] == "12")?"selected":""}}>{{show_content($events_keywords,"12_hour_label")}}</option>
                                    <option value="24" {{(isset($_GET['group_by']) && $_GET['group_by'] == "24")?"selected":""}}>{{show_content($events_keywords,"24_hour_label")}}</option>
                                </select>
                            </div>

                        </div>

                    </form>

                    <div class="table_new stock_exchange_table">
                        <table class="table table-hover">

                            <thead>
                            <th>{{show_content($events_keywords,"time_label")}}</th>
                            <th>{{show_content($events_keywords,"currency_label")}}</th>
                            <th>{{show_content($events_keywords,"importance_label")}}</th>
                            <th>{{show_content($events_keywords,"event_label")}}</th>
                            <th>{{show_content($events_keywords,"current_label")}}</th>
                            <th>{{show_content($events_keywords,"expected_label")}}</th>
                            <th>{{show_content($events_keywords,"previous_label")}}</th>
                            </thead>

                            <tbody>

                            <?php foreach($events_group as $key => $events): ?>

                            <?php
                            if(!count($events))
                                continue;
                            ?>

                            <tr class="day_style">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>{{$key}}</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <?php foreach($events as $event_key => $event): ?>
                            <?php

                            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',($event->event_datetime));
                            if ($selected_time_format == 12)
                            {
                                $date = $date->setTimezone("$selected_timezone")->format('g:i A');
                            }
                            else{
                                $date = $date->setTimezone("$selected_timezone")->format('H:i A');
                            }

                            ?>
                            <tr>
                                <td>
                                    {{$date}}
                                </td>
                                <td>
                                    <img src="{{get_image_or_default($event->currency_img_path)}}" width="25">
                                    {{$event->cur_to}}
                                </td>
                                <td>
                                    <?php
                                    $level_img = "";
                                    if ($event->importance_degree == 0 && isset($events_keywords->strong_img->path))
                                    {
                                        $level_img = get_image_or_default($events_keywords->strong_img->path);
                                    }
                                    if ($event->importance_degree == 1 && isset($events_keywords->middle_img->path))
                                    {
                                        $level_img = get_image_or_default($events_keywords->middle_img->path);
                                    }
                                    if ($event->importance_degree == 2 && isset($events_keywords->weak_img->path))
                                    {
                                        $level_img = get_image_or_default($events_keywords->weak_img->path);
                                    }
                                    if ($event->importance_degree == 3 && isset($events_keywords->very_weak_img->path))
                                    {
                                        $level_img = get_image_or_default($events_keywords->very_weak_img->path);
                                    }

                                    if ($event->importance_degree == 4 && isset($events_keywords->not_important_img->path))
                                    {
                                        $level_img = get_image_or_default($events_keywords->not_important_img->path);
                                    }
                                    ?>
                                    <img src="{{$level_img}}" width="25" height="25">
                                </td>
                                <td>{{$event->page_title}}</td>
                                <td class="current_value_status_{{$event->current_value_status}}">{{$event->current_value}}</td>
                                <td>{{$event->expected_value}}</td>
                                <td>{{$event->previous_value}}</td>
                            </tr>
                            <?php endforeach; ?>

                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                </div>

            </main>

            <!-- Left Sidebar -->

            <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                @include('front.main_components.left_sidebar')
            </aside>

            <!-- ... end Left Sidebar -->

            <!-- Right Sidebar -->

            <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
                @include('front.main_components.right_sidebar')
            </aside>

            <!-- ... end Right Sidebar -->

        </div>
    </div>

@endsection
