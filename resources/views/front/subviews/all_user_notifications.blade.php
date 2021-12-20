@extends('front.main_layout')
@section('subview')


    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">
                        <div class="ui-block" style="width: 100%">


                            <div class="ui-block-title">
                                <h6 class="title">Notifications</h6>
                            </div>


                            <!-- Notification List -->

                            <ul class="notification-list">
                                <?php if(is_array($all_user_notifications->all()) && count($all_user_notifications->all())): ?>

                                    <?php foreach($all_user_notifications->all() as $key => $not_obj): ?>

                                    @include("blocks.notification_block")




                                    <?php endforeach; ?>

                                    <?php else: ?>
                                    <div class="col-md-12" style="text-align: center;font-weight: bold;">
                                        <div class="alert alert-warning">
                                            There's no notifications for you
                                        </div>
                                    </div>

                                <?php endif; ?>
                            </ul>

                            <!-- ... end Notification List -->


                            <div class="col-md-12">
                                {{$all_user_notifications->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                            </div>

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