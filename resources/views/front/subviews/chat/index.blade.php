@extends('front.main_layout')

@section('subview')

    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">
                        <div class="ui-block" style="width: 100%;">

                            <!-- Single Post -->

                            <article class="hentry blog-post single-post single-post-v2">

                                <table id="" class="table table-striped table-bordered no-margin">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{show_content($chat_keywords,"chat_with_label")}}</th>
                                        <th>{{show_content($chat_keywords,"show_messages_label")}}</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>{{show_content($chat_keywords,"chat_with_label")}}</th>
                                        <th>{{show_content($chat_keywords,"show_messages_label")}}</th>
                                    </tr>
                                    </tfoot>

                                    <tbody>
                                    <?php foreach($chats as $key => $chat): ?>
                                    <tr id="row<?= $chat->chat_id ?>">
                                        <?php $owner = false; ?>
                                        <td>{{++$key}}</td>
                                        <td>
                                            <?php if($current_user->user_id == $chat->from_user_id): ?>

                                            <?php $owner = true; ?>
                                            {{$chat->to_user_full_name}}
                                            <?php else: ?>
                                            {{$chat->from_user_full_name}}

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="{{url('messages/'.$chat->chat_id)}}"
                                               target="_blank">
                                                    <span class="label label-info">
                                                        {{show_content($chat_keywords,"show_label")}} <i class="fa fa-envelope"></i>
                                                    </span>
                                            </a>
                                        </td>
                                    </tr>

                                    <?php endforeach; ?>

                                    </tbody>
                                </table>

                            </article>

                            <!-- ... end Single Post -->

                        </div>
                    </div>
                </div>

            </main>

            <!-- ... end Main Content -->


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