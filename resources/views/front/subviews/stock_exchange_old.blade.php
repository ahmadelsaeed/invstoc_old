@extends('front.main_layout')

@section('subview')

    <div id="page-contents">
        <div class="container">
            <div class="row">

                <div class="col-md-2 static padd-left padd-right">
                    @include('front.main_components.right_sidebar')
                </div>

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
//                    dump($url);
                ?>

                <div class="col-md-8 nopadding">


                    <form action="{{url("$url")}}">
                        <div class="col-md-5">
                            <div class="block-time">
                                <h3> {{show_content($events_keywords,"current_timezone_label")}} </h3>
                                <select class="form-control change_timezone" name="timezone" style="width: 95% !important;">
                                    <?php foreach(TIMEZONES as $key => $value): ?>
                                        <option {{($key == $selected_timezone)?"selected":""}} value="{{$key}}">
                                            {{$key}}
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="add-icon">
                                <p> <img src="{{(isset($events_keywords->strong_img->path)?get_image_or_default($events_keywords->strong_img->path):"")}}" /> {{show_content($events_keywords,"strong_label")}}  </p>
                                <p> <img src="{{(isset($events_keywords->middle_img->path)?get_image_or_default($events_keywords->middle_img->path):"")}}" /> {{show_content($events_keywords,"middle_label")}}  </p>
                                <p> <img src="{{(isset($events_keywords->weak_img->path)?get_image_or_default($events_keywords->weak_img->path):"")}}" /> {{show_content($events_keywords,"weak_label")}}  </p>
                                <p> <img src="{{(isset($events_keywords->very_weak_img->path)?get_image_or_default($events_keywords->very_weak_img->path):"")}}" /> {{show_content($events_keywords,"very_weak_label")}}  </p>
                                <p> <img src="{{(isset($events_keywords->not_important_img->path)?get_image_or_default($events_keywords->not_important_img->path):"")}}" /> {{show_content($events_keywords,"not_important_label")}}  </p>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="block-time">
                                <select class="form-control select_group_by" name="group_by" style="width: 95% !important;">
                                    <option value="day" {{(!isset($_GET['group_by']) || $_GET['group_by'] == "day")?"selected":""}}>{{show_content($events_keywords,"day_label")}}</option>
                                    <option value="week" {{(isset($_GET['group_by']) && $_GET['group_by'] == "week")?"selected":""}}>{{show_content($events_keywords,"week_label")}}</option>
                                    <option value="month" {{(isset($_GET['group_by']) && $_GET['group_by'] == "month")?"selected":""}}>{{show_content($events_keywords,"month_label")}}</option>
                                </select>
                                <h3> {{show_content($events_keywords,"default_view_label")}} </h3>
                            </div>
                        </div>
                    </form>

                    <div class="select-time">

                        <div class="col-md-12">
                            <div class="table-note">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{$label}}</th>
                                            <th>{{show_content($events_keywords,"time_label")}}</th>
                                            <th>{{show_content($events_keywords,"currency_label")}}</th>
                                            <th>{{show_content($events_keywords,"importance_label")}}</th>
                                            <th>{{show_content($events_keywords,"event_label")}}</th>
                                            <th>{{show_content($events_keywords,"current_label")}}</th>
                                            <th>{{show_content($events_keywords,"expected_label")}}</th>
                                            <th>{{show_content($events_keywords,"previous_label")}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(count($events_group)): ?>
                                        <?php foreach($events_group as $key => $events): ?>
                                            <?php

                                                if (!isset($_GET["group_by"]) || $_GET["group_by"] == "day")
                                                {
                                                    $events = $events->all();
                                                    $first_event = $events[0];
                                                    $theader = $first_event->event_date;
                                                }
                                                else if(isset($_GET["group_by"]) && $_GET["group_by"] == "week"){
                                                    $first_event = $events[0];
                                                }
                                                else if(isset($_GET["group_by"]) && $_GET["group_by"] == "month"){
                                                    $events = $events->all();
                                                    $first_event = $events[0];
                                                    $theader = $Months_names[$first_event->event_month]." <br> ".$first_event->event_year;
                                                }

                                                $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',($first_event->event_datetime));
//                                                $date = $date->setTimezone("$selected_timezone")->toTimeString();
                                                $date = $date->setTimezone("$selected_timezone")->format('g:i A');

                                            ?>
                                            <tr>
                                                <th>{!! $theader !!}</th>
                                                <td>
                                                    {{$date}}
                                                    <br>
                                                    {{date("Y-m-d",strtotime($first_event->event_datetime))}}
                                                </td>
                                                <td>
                                                    <img src="{{get_image_or_default($first_event->currency_img_path)}}" width="25">
                                                    {{$first_event->cur_to}}
                                                </td>
                                                <td>
                                                    <?php
                                                        $level_img = "";
                                                        if ($first_event->importance_degree == 0 && isset($events_keywords->strong_img->path))
                                                        {
                                                            $level_img = get_image_or_default($events_keywords->strong_img->path);
                                                        }
                                                        if ($first_event->importance_degree == 1 && isset($events_keywords->middle_img->path))
                                                        {
                                                            $level_img = get_image_or_default($events_keywords->middle_img->path);
                                                        }
                                                        if ($first_event->importance_degree == 2 && isset($events_keywords->weak_img->path))
                                                        {
                                                            $level_img = get_image_or_default($events_keywords->weak_img->path);
                                                        }
                                                        if ($first_event->importance_degree == 3 && isset($events_keywords->very_weak_img->path))
                                                        {
                                                            $level_img = get_image_or_default($events_keywords->very_weak_img->path);
                                                        }
                                                        if ($first_event->importance_degree == 4 && isset($events_keywords->not_important_img->path))
                                                        {
                                                            $level_img = get_image_or_default($events_keywords->not_important_img->path);
                                                        }
                                                    ?>
                                                    <img src="{{$level_img}}" width="25" height="25">
                                                </td>
                                                <td>{{$first_event->page_title}}</td>
                                                <td>{{$first_event->current_value}}</td>
                                                <td>{{$first_event->expected_value}}</td>
                                                <td>{{$first_event->previous_value}}</td>
                                            </tr>
                                            <?php foreach($events as $event_key => $event): ?>
                                            <?php
                                                if($event_key == 0)
                                                    continue;

                                                $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',($event->event_datetime));
                                                $date = $date->setTimezone("$selected_timezone")->format('g:i A');
//                                                $date = $date->setTimezone("$selected_timezone")->toTimeString();
                                            ?>
                                                <tr>
                                                    <th></th>
                                                    <td>
                                                        {{$date}}
                                                        <br>
                                                        {{date("Y-m-d",strtotime($event->event_datetime))}}
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
                                    <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-2 static nopadding">
                    <?php if(count($latest_news)): ?>
                    <?php foreach($latest_news as $key => $news_obj): ?>
                    @include("blocks.homepage_news_block")
                    <?php endforeach; ?>
                    <?php endif; ?>


                    <?php if(isset($get_ads['homepage_body1'])): ?>
                    <div class="banner-img">
                        {!! get_adv($get_ads['homepage_body1']->all(),"210px","120px") !!}
                    </div>
                    <?php endif; ?>

                    <?php if(isset($get_ads['homepage_body2'])): ?>
                    <div class="banner-img">
                        {!! get_adv($get_ads['homepage_body2']->all(),"210px","120px") !!}
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

@endsection