@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
        Workshops
        </div>
        <div class="panel-body">
            <div class="" >

                <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Workshops Name</td>
                        <td>Owner Name</td>
                        <td>Interactions Count</td>
                        <td>Visits Count</td>
                        <td>Creation Date</td>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <td>#</td>
                        <td>Workshops Name</td>
                        <td>Owner Name</td>
                        <td>Interactions Count</td>
                        <td>Visits Count</td>
                        <td>Creation Date</td>
                    </tr>
                    </tfoot>

                    <tbody>
                    <?php foreach ($workshops as $key => $workshop): ?>

                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $workshop->workshop_name ?></td>
                        <td>
                            <img src='{{url(get_image_or_default($workshop->path))}}' width='50' height='50'>
                            <br>
                            <?= $workshop->full_name ?>
                        </td>
                        <td><?= $workshop->trend_counter ?></td>
                        <td><?= $workshop->workshop_visits ?></td>
                        <td>
                            <label class="label label-info">{{$workshop->workshop_creation_date}}</label>
                            <label class="label label-success">
                                {{\Carbon\Carbon::createFromTimestamp(strtotime($workshop->workshop_creation_date))->diffForHumans()}}
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