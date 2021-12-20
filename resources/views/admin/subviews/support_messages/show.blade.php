@extends('admin.main_layout')

@section('subview')

    <div id="general_show_all_data_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">All Data</h4>
                </div>
                <div class="modal-body row" style="font-weight: bold;word-break: break-word;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


    <div class="panel panel-info">
        <div class="panel-heading">
        {!! transform_underscore_text($msg_type) !!} Requests
        </div>
        <div class="panel-body">
            <div class="" >

                <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Code</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Date</td>
                        <td>All Data</td>
                        <td>Delete</td>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <td>#</td>
                        <td>Code</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Date</td>
                        <td>All Data</td>
                        <td>Delete</td>
                    </tr>
                    </tfoot>

                    <tbody>
                    <?php foreach ($all_messages as $key => $single): ?>
                    <?php
                        $single->other_data=json_decode($single->other_data);

                        $single->attachments = "";

                        if (is_array($single->imgs_data) && count($single->imgs_data))
                        {
                            foreach ($single->imgs_data as $key2 => $img_obj)
                            {
                                $img_path = url($img_obj->path);
                                $single->attachments .= "<a href='$img_path' target='_blank'> <img src='$img_path' height='100px' width='100px'> </a>";
                            }

                        }

                    ?>

                    <tr id="row<?= $single->id ?>">
                        <td><?= $key + 1 ?></td>
                        <td><?= $single->username ?></td>
                        <td><?= $single->name ?></td>
                        <td><?= $single->email ?></td>
                        <td><?= $single->tel ?></td>
                        <td><?= $single->created_at ?></td>

                        <?php
                        if($msg_type=="check_availability"){
                            $single->trip_link=$single->current_url;
                            unset($single->current_url);
                        }
                        unset(
                            $single->created_at,$single->updated_at,$single->deleted_at,
                            $single->trip_id,$single->msg_type,$single->source,$single->alt,$single->title,
                            $single->img_id,$single->imgs_data
                        );
                        ?>
                        <td><button class="btn btn-primary show_all_data btn-block show_general_data" data-alldata="<?= htmlentities(json_encode($single), ENT_QUOTES, 'UTF-8'); ?>" ><i class="fa fa-expand"></i></button></td>
                        <td><a href='#' class="general_remove_item" data-deleteurl="<?= url("/admin/delete_support_messages") ?>" data-tablename="App\models\support_messages_m"  data-itemid="<?= $single->id ?>"><span class="label label-danger"> مسح <i class="fa fa-remove"></i></span></a></td>
                    </tr>
                    <?php endforeach ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>




@endsection