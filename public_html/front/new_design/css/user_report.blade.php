@extends('front.subviews.user.user_layout')
@section('page')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <input type="hidden" class="orders_statistics_label" value="{{show_content($user_profile_keywords,"orders_statistics_label")}}">
        <input type="hidden" class="orders_posts_statistics_label" value="{{show_content($user_profile_keywords,"orders_posts_statistics_label")}}">
        <input type="hidden" class="orders_label" value="{{show_content($user_profile_keywords,"orders_label")}}">
        <input type="hidden" class="posts_label" value="{{show_content($user_profile_keywords,"posts_label")}}">
        <input type="hidden" class="profit_label" value="{{show_content($user_profile_keywords,"profit_label")}}">
        <input type="hidden" class="equal_label" value="{{show_content($user_profile_keywords,"equal_label")}}">
        <input type="hidden" class="lose_label" value="{{show_content($user_profile_keywords,"lose_label")}}">
        <input type="hidden" class="performance_chart_header" value="{{show_content($user_profile_keywords,"performance_chart_header")}}">


    <div class="container">
        <div class="row">
            <?php if($orders_statistics["profit"] > 0 || $orders_statistics["equal"] > 0 || $orders_statistics["loose"] > 0): ?>
                <div class="col col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="ui-block responsive-flex">

                        <div class="ui-block-title">
                            <div class="h6 title">{{show_content($user_profile_keywords,"statistics_label")}}</div>
                        </div>
                        <div class="ui-block-content">

                            <div class="chart-js chart-radar">

                                <input type="hidden" class="orders_statistics" value="{{json_encode($orders_statistics)}}">
                                <div id="orders_statistics" style="width: 450px; height: 400px;"></div>

                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

                <?php if($profit_lose_statistics["profit"] > 0 || $profit_lose_statistics["lose"] > 0 ): ?>

                <div class="col col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="ui-block responsive-flex">

                        <div class="ui-block-title">
                            <div class="h6 title">{{show_content($user_profile_keywords,"statistics_label")}}</div>
                        </div>
                        <div class="ui-block-content">
                            <div class="chart-js chart-radar">
                                <input type="hidden" class="orders_posts_statistics" value="{{json_encode($profit_lose_statistics)}}">
                                <div id="orders_posts_statistics" style="width: 450px; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

        </div>
    </div>




    <div class="container">
        <div class="row">
            <div class="col col-lg-12 col-sm-12 col-12">
                <div class="ui-block responsive-flex">

                    <div class="ui-block-title">
                        <div class="h6 title">{{show_content($user_profile_keywords,"filter_performance_header")}}</div>
                    </div>
                    <div class="ui-block-content">
                        <form action="{{url("report/user/$user_obj->user_id")}}">
                            <div class="row">
                                <div class="col-md-4">
                                    <label><b>{{show_content($user_profile_keywords,"from_date_label")}}</b></label>
                                    <input type="date" class="form-control" name="from_date" value="{{(isset($_GET["from_date"])?$_GET["from_date"]:"")}}">
                                </div>

                                <div class="col-md-4">
                                    <label><b>{{show_content($user_profile_keywords,"to_date_label")}}</b></label>
                                    <input type="date" class="form-control" name="to_date" value="{{(isset($_GET["to_date"])?$_GET["to_date"]:"")}}">
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-success" style="margin-top: 30px;">{{show_content($user_profile_keywords,"get_report_label")}}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col col-lg-12 col-sm-12 col-12">
                <div class="ui-block responsive-flex">


                    <div class="ui-block-title">
                        <div class="h6 title">{{show_content($user_profile_keywords,"performance_chart_header")}}</div>
                    </div>
                    <div class="ui-block-content">

                        <?php if(count($performance_report)): ?>

                        <input type="hidden" class="performance_report" value="{{json_encode($performance_report)}}">
                        <div id="performance_report" style="width: 900px; height: 500px"></div>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
