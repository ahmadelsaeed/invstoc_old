
    <div class="container">
        <div class="row">

            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="col-md-12" style="padding: 30px">
                    <div class="modal-content" style="overflow: auto">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                               {{show_content($user_homepage_keywords,"profile_trending_header")}}
                            </h5>
                           
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-hover sidebar_ul_height scroll">
                                <tr>
                                    <th>User</th>
                                    <th>Loss Trads</th>
                                    <th>Win Trads</th>
                                    <th>Win Trads %</th>
                                    <th>Loss PIPS</th>
                                    <th>Win PIPS</th>
                                    <th>Win PIPS %</th>
                                </tr>
                                @foreach($trend_users as $user)
                                <tr>
                                    <td>
                                        <div class="post__author author inline-items">
                                            <img src="{{url(get_image_or_default($user->path))}}" alt="{{$user->full_name}}" title="{{$user->full_name}}" style="width: 40px;height: 40px;">
                                            <div class="">
                                                <a href='{{url("report/user/$user->user_id")}}' class="h6 post__author-name fn">
                                                    {{$user->full_name}} 
                                                    <?php if($user->is_privet_account): ?>
                                                        @include('blocks.verify_padge_block')
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <label>{{$user->orders_statistics['lose']}}</label>
                                    </td>
                                    <td>
                                        <label>{{$user->orders_statistics['profit']}}</label>
                                    </td>
                                    <td>
                                        <label style="color:  <?php if($user->orders_statistics['trade_percentage'] >= 50){ echo 'green';} else{echo 'red';}   ?> ">
                                            {{$user->orders_statistics['trade_percentage']  }} %</label>
                                    </td>
                                    <td>
                                        <label>{{$user->profit_lose_statistics['lose']}}</label>
                                    </td>
                                    <td>
                                        <label>{{$user->profit_lose_statistics['profit']}}</label>
                                    </td>
                                    <td>
                                        <label style="color:  <?php if($user->orders_statistics['trade_percentage'] >= 50){ echo 'green';} else{echo 'red';}   ?> ">
                                            {{$user->profit_lose_statistics['pips_percentage']}} %</label>
                                    </td>
                                </tr>
                                @endforeach
                            </table>                                  
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>


