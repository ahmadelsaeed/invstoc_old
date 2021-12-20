@extends('admin.main_layout')

@section("subview")


    <div class="panel panel-info">
        <div class="panel-heading">
            Edit Default Settings
        </div>

        <div class="panel-body">


            <table class="table table-striped table-bordered table-hover">

                <tr>
                    <td>Register to Site Require Verification</td>
                    <td>
                        <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$setting,
                                $item_primary_col="set_id",
                                $accept_or_refuse_col="register_require_verification",
                                $model='App\models\settings_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Allow users to Delete Orders ?</td>
                    <td>
                        <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$setting,
                                $item_primary_col="set_id",
                                $accept_or_refuse_col="allow_delete_order",
                                $model='App\models\settings_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Show Trending Brokers ?</td>
                    <td>
                        <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$setting,
                                $item_primary_col="set_id",
                                $accept_or_refuse_col="show_brokers_trending",
                                $model='App\models\settings_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                        ?>
                    </td>
                </tr>

            </table>


        </div>
    </div>


@endsection