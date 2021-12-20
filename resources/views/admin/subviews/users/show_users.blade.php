@extends('admin.main_layout')

@section('subview')

    <!--new_editor-->
    <script src="{{url("/public_html/ckeditor/ckeditor.js")}}" type="text/javascript"></script>
    <script src="{{url("/public_html/ckeditor/adapters/jquery.js")}}" type="text/javascript"></script>

    <div class="panel panel-info">
        <div class="panel-heading">
            Users
        </div>
        <div class="panel-body" style="overflow-x: scroll;">

            <div class="float-right">
                <form action="{{route('admin.user.search')}}" method="get">
                    <input class="form-control" type="text" name="q" placeholder="Search by email, name, username" />
                </form>
            </div>
            <table {{--id="cat_table"--}} class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>User Code</td>
                    <td>Full Name</td>
                    <td>Email</td>
                    <td>Country</td>
                    <td>City</td>
                    <td>Orders Not Closed</td>
                    <td>Created At</td>
                    <td>Ads Balance</td>
                    <td>Referrers Balance</td>
                    <td>Referrers Count</td>
                    <td>User Can login?</td>
                    <td>Is Privet?</td>
                    <td>Has Referrer ?</td>
                    <td>Actions</td>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td>#</td>
                    <td>User Code</td>
                    <td>Full Name</td>
                    <td>Email</td>
                    <td>Country</td>
                    <td>City</td>
                    <td>Orders Not Closed</td>
                    <td>Created At</td>
                    <td>Ads Balance</td>
                    <td>Referrers Balance</td>
                    <td>Referrers Count</td>
                    <td>User Can login?</td>
                    <td>Is Privet?</td>
                    <td>Has Referrer ?</td>
                    <td>Actions</td>
                </tr>
                </tfoot>

                <tbody>
                <?php foreach ($users as $key => $user): ?>
                <tr id="row<?= $user->user_id ?>">
                    <td><?=$key+1?></td>
                    <td>
                        <?=$user->username ?>
                        <br>
                            <a href="#" data-user_id="{{$user->user_id}}" target="_blank" title="Change Code"
                               data-username="{{$user->username}}"
                               class="btn btn-info open_change_code">  <i class="fa fa-wrench" style="color: #fff;"></i></a>
                    </td>
                    <td><?=$user->full_name ?></td>
                    <td><?=$user->email ?></td>
                    <td><?=$user->country ?></td>
                    <td><?=$user->city ?></td>
                    <td>
                        <?php if(count($orders_not_closed) && isset($orders_not_closed[$user->user_id])): ?>
                            <a href="{{url("user/posts/order/$user->user_id?not_closed=true")}}" target="_blank"
                               class="btn btn-info"> {{count($orders_not_closed[$user->user_id])}} <i class="fa fa-user"></i></a>
                            <?php else: ?>
                            0
                        <?php endif; ?>

                    </td>
                    <td>{{dump_date($user->created_at)}}</td>
                    <td>
                        <span class="general_self_edit"
                              data-url="<?= url("/general_self_edit") ?>"
                              data-row_id="<?= $user->user_id ?>"
                              data-tablename="App\User"
                              data-input_type="number"
                              data-row_primary_col="user_id"
                              data-field_name="ads_balance"
                              data-field_value="<?= $user->ads_balance ?>"
                              title="Click To Edit">
                            <?= $user->ads_balance ?> <i class="fa fa-edit"></i>
                        </span>
                    </td>
                    <td>
                        <span class="general_self_edit"
                              data-url="<?= url("/general_self_edit") ?>"
                              data-row_id="<?= $user->user_id ?>"
                              data-tablename="App\User"
                              data-input_type="number"
                              data-row_primary_col="user_id"
                              data-field_name="referrer_balance"
                              data-field_value="<?= $user->referrer_balance ?>"
                              title="Click To Edit">
                            <?= $user->referrer_balance ?> <i class="fa fa-edit"></i>
                        </span>
                    </td>
                    <td>({{$user->referrer_count}})</td>
                    <td>
                        <?php
                        echo generate_multi_accepters(
                            $accepturl=url("/admin/users/change_user_can_login"),
                            $item_obj=$user,
                            $item_primary_col="user_id",
                            $accept_or_refuse_col="user_can_login",
                            $model='',
                            $accepters_data=["0"=>"Can Not Login","1"=>"Can Login"]
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo generate_multi_accepters(
                            $accepturl=url("/admin/users/is_privet_account"),
                            $item_obj=$user,
                            $item_primary_col="user_id",
                            $accept_or_refuse_col="is_privet_account",
                            $model='',
                            $accepters_data=["0"=>"No","1"=>"Yes"]
                        );
                        ?>
                    </td>
                    <td>
                        <?php if($user->request_referrer_link == 1): ?>

                        <?php if(!empty($user->referrer_link)): ?>

                            <a href="{{url("/referrer_link?ref=$user->referrer_link")}}" class="btn btn-info">Referrer Link</a>

                            <?php else: ?>
                                <?php
                                    echo generate_multi_accepters(
                                        $accepturl=url("/admin/users/has_referrer_link"),
                                        $item_obj=$user,
                                        $item_primary_col="user_id",
                                        $accept_or_refuse_col="request_referrer_link",
                                        $model='',
                                        $accepters_data=["0"=>"Yes","1"=>"No"]
                                    );
                                ?>

                            <?php endif; ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="#" target="_blank" data-user_id="{{$user->user_id}}"
                           data-full_name="{{$user->full_name}}"
                           data-type="chat_message"
                           class="btn btn-info open_send_message">  <i class="fa fa-envelope" style="color: #fff;"></i></a> &nbsp;
                        <a href="#" data-user_id="{{$user->user_id}}" target="_blank"
                           data-full_name="{{$user->full_name}}"
                           data-type="notification"
                           class="btn btn-info open_send_message">  <i class="fa fa-bell" style="color: #fff;"></i></a> &nbsp;
                        <a href="{{route('admin.messages.user', ['user_id' => $user->user_id])}}" class="btn btn-info">
                            Messages
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <div class="float-right">
                {{$users->links()}}
            </div>
        </div>
    </div>


    <div class="modal fade message_notify_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">your Message</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">
                                <b>Message content : </b>
                            </label>
                            <textarea class="form-control message_content ckeditor" id="message_content" style="resize: vertical" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save_message_notify" disabled>Save</button>
                    <label><b class="show_msg_status"></b></label>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade change_code_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">User Code</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">
                                <b>Should be Unique * : </b>
                            </label>
                            <input type="text" class="form-control user_new_code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save_change_code" disabled>Save</button>
                    <label><b class="show_msg_status"></b></label>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection
