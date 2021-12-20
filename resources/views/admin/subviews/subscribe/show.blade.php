@extends('admin.main_layout')

@section('subview')

    <!--new_editor-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>
    <!--END new_editor-->


    <!-- Modal -->
    <div id="send_email_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="height: 400px;overflow-y: scroll">
                    <?php
                    echo generate_inputs_html(
                        $labels_name = array("sender_email","email_subject","email_body"),
                        $fields_name = array("sender_email","email_subject","email_body"),
                        $required = array("","",""),
                        $type = array("text","text","textarea"),
                        $values = array($email_settings->sender_email,$email_settings->email_subject,$email_settings->email_body),
                        $class = array("form-control sender_email","form-control email_subject","form-control email_body")
                    );
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" data-sender_email_modal="" data-sender_url_modal="" class="btn btn-primary submit_custom_email">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div class="show_errors"></div>
                </div>
            </div>

        </div>
    </div>


    <!-- Modal -->
    <div id="show_email_msg" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="height: 400px;overflow-y: scroll">

                    <?php
                    echo generate_inputs_html(
                        $labels_name = array("email_body_msg"),
                        $fields_name = array("email_body_msg"),
                        $required = array(""),
                        $type = array("textarea"),
                        $values = array(""),
                        $class = array("form-control email_body_msg")
                    );
                    ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <script>
        CKEDITOR.replace( 'email_body_id' );
        CKEDITOR.replace( 'email_body_msg_id' );
    </script>

    <div class="panel panel-info">
        <div class="panel-heading">
            Subscribers
        </div>
        <div class="panel-body">
            <table id="subscribe_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Email</td>
                    <td>Date</td>
                    <td>Email Body</td>
                    <td>sent?</td>
                    <td>seen?</td>
                    <td>Send Email</td>
                    <td>Delete</td>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td>#</td>
                    <td>Email</td>
                    <td>Date</td>
                    <td>Email Body</td>
                    <td>sent?</td>
                    <td>seen?</td>
                    <td>Send Email</td>
                    <td>Delete</td>
                </tr>
                </tfoot>

            </table>



            <div class="col-md-6">
                <div class="alert alert-info">
                    <p>
                        احنا هنبعت ايميل لكل الناس المشتركين في الموقع عن طريق الاعدادات الافترضية لل اميل وهبعت كل ساعة للعدد المكتوب في حد الارسال
                        يمكنك ان تغير الاعدادات الافتراضة عن طريق اللنك القادم

                        <b>
                            <a target="_blank" href="<?= url("admin/subscribe/email_settings") ?>">here</a>
                        </b>
                    </p>
                </div>
                <a href="<?= url("admin/subscribe/export_subscribe") ?>" class="btn btn-info btn-block" > Export To Excel Sheet </a>
                <button class="btn btn-primary btn-block send_all_subscribers_email" data-send_all_subscribers_url="<?= url("admin/subscribe/send_all_subscribers_email") ?>"> ارسال الايميل لكل المشتركين </button>
                <div class="show_errors_msgs"></div>
            </div>

            <div class="col-md-6">

                <h2>Sent Emails Count: (<?=$email_send_count?>) :-</h2>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width:<?php echo (int)(($email_send_count/count($emails))*100) ?>%">
                        <span class=""><?php echo (int)(($email_send_count/count($emails))*100) ?>% Sent </span>
                    </div>
                </div>

                <h2>Seen Emails Count: (<?=$email_seen_count?>) :-</h2>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width:<?php echo (int)(($email_seen_count/count($emails))*100 )?>%">
                        <span class=""><?php echo (int)(($email_seen_count/count($emails))*100) ?>% Seen </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-md-offset-3" style="margin-top: 10px;">
                <?php if ($email_settings->run_send == 1): ?>
                <div class="col-md-6">
                    <a href="<?= url("admin/subscribe/stop") ?>" class="btn btn-info btn-block" > Stop <i class="fa fa-close"></i> </a>
                </div>

                <div class="col-md-6">
                    <a href="<?= url("admin/subscribe/pause") ?>" class="btn btn-info btn-block" >Pause <i class="fa fa-pause"></i> </a>
                </div>

                <?php endif ?>

                <?php if ($email_settings->run_send == 0 && $email_settings->offset > 0): ?>
                <div class="col-md-6">
                    <a href="<?= url("admin/subscribe/resume") ?>" class="btn btn-info btn-block" > Run <i class="fa fa-play"></i> </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= url("admin/subscribe/stop") ?>" class="btn btn-info btn-block" > END <i class="fa fa-close"></i> </a>
                </div>
                <?php endif ?>

            </div>
        </div>
    </div>



@endsection