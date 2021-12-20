@extends('front.main_layout')

@section('subview')

    <!-- Main CSS -->
    <link href="{{url('/public_html/front/css/chat.css')}}" rel="stylesheet" media="screen" />


    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">
                        <div class="ui-block" style="width: 100%;">

                            <!-- Single Post -->

                            <div class="card">

                                <div class="card-header">
                                    <div class="col-md-12" style="text-align: center;">
                                        <div class="tit-harmonic">
                                            <h3 class="chat_with_header">
                                                <?php if($current_user->full_name == $chat_obj->to_user_full_name): ?>
                                                    {{$chat_obj->from_user_full_name}}
                                                <?php else: ?>
                                                    {{$chat_obj->to_user_full_name}}
                                                <?php endif; ?>

                                            </h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="card text-white">
                                        <div class="chat_container">

                                            <div class="">

                                                <div class="card-body">
                                                    <ul class="chat append_to_chat scroll">
                                                        <?php
                                                        $messages_ids_value = "";
                                                        $messages_ids = convert_inside_obj_to_arr($messages,"chat_msg_id");
                                                        if (is_array($messages_ids) && count($messages_ids))
                                                        {
                                                            $messages_ids_value = implode(',',$messages_ids);
                                                        }
                                                        ?>
                                                        <input type="hidden" data-get_new_msgs="{{url('user/chats/get_new_messages')}}" class="messages_ids" value="{{$messages_ids_value}}">
                                                        <?php foreach($messages as $key => $message): ?>

                                                        <?php if($current_user->user_id == $message->from_user_id): ?>
                                                        @include('front.subviews.chat.blocks.right')

                                                        <?php else: ?>
                                                        @include('front.subviews.chat.blocks.left')

                                                        <?php endif; ?>

                                                        <?php endforeach; ?>

                                                    </ul>
                                                </div>

                                                <?php if( !in_array($chat_obj->from_user_user_type,["admin","dev"]) && !in_array($chat_obj->to_user_user_type,["admin","dev"]) ): ?>
                                                <div class="card-footer">
                                                    <div class="input-group">
                                                        <textarea class="form-control chat_input_message" style="resize: vertical" id="btn-input" cols="70" rows="5" placeholder="{{show_content($user_homepage_keywords,"message_body")}}" dir="ltr"></textarea>
                                                        <span class="input-group-btn">
                                                    <button class="btn btn-success send_chat_message_to_user" data-chat_id="{{$chat_obj->chat_id}}" id="btn-chat">
                                                        {{show_content($user_homepage_keywords,"send_btn")}}
                                                    </button>
                                                </span>
                                                    </div>
                                                </div>
                                                <?php endif; ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

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