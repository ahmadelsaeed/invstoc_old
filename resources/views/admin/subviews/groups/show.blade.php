@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
        Groups
        </div>
        <div class="panel-body">
            <div class="" >

                <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Group Name</td>
                        <td>Owner Name</td>
                        <td>Admins Count</td>
                        <td>Members Count</td>
                        <td>All Types Count</td>
                        <td>Visits Count</td>
                        <td>Creation Date</td>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <td>#</td>
                        <td>Group Name</td>
                        <td>Owner Name</td>
                        <td>Admins Count</td>
                        <td>Members Count</td>
                        <td>All Types Count</td>
                        <td>Visits Count</td>
                        <td>Creation Date</td>
                    </tr>
                    </tfoot>

                    <tbody>
                    <?php foreach ($groups as $key => $group): ?>

                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $group->group_name ?></td>
                        <td>
                            <img src='{{url(get_image_or_default($group->path))}}' width='50' height='50'>
                            <br>
                            <?= $group->full_name ?>
                        </td>
                        <td><?= $group->admins_count ?></td>
                        <td><?= $group->members_count ?></td>
                        <td><?= ($group->members_count + $group->admins_count) ?></td>
                        <td><?= $group->group_visits ?></td>
                        <td>
                            <label class="label label-info">{{$group->group_creation_date}}</label>
                            <label class="label label-success">
                                {{\Carbon\Carbon::createFromTimestamp(strtotime($group->group_creation_date))->diffForHumans()}}
                            </label>
                        </td>

                    </tr>
                    <?php endforeach ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>




@endsection