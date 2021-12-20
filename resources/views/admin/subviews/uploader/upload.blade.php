@extends('admin.main_layout')

@section('subview')

    <!-- Custom styling plus plugins -->
    <link href="<?= url('public_html/dropzone/dropzone.css') ?>" rel="stylesheet">

    <!-- dropzone js -->
    <script src="<?= url('public_html/dropzone/dropzone.js') ?>"></script>

    <script>

        jQuery(function ($){

            Dropzone.options.myAwesomeDropzone = {
                paramName: "file", // The name that will be used to transfer the file
                //            maxFiles: 2,
                parallelUploads: 2,
                uploadMultiple: true, // for send multiple files in one ajax request
                //            maxFiles: 3,
                //            acceptedFiles: "image/*",
                autoProcessQueue: true, //  for pause uploading after make click event
                //            addRemoveLinks: true,
                init: function() {

                    // bakr
                    var submitButton = document.querySelector("#submit-all");
                    myDropzone = this; // closure

                    submitButton.addEventListener("click", function() {

                        myDropzone.processQueue(); // Tell Dropzone to process all queued files.

                    });

                    // You might want to show the submit button only when
                    // files are dropped here:
                    this.on("addedfile", function(data) {
                        // Show submit button here and/or inform user to click it.
                        console.log(data);
                    });

                    // .bakr
                    //return noop;
                },
                success: function(data){
                    console.log("success");
                    console.log(data.xhr.response);
                    var base_url2 = $(".base_url_class").val();
                    $(".show_uploads").prepend("<a href='"+base_url2+"/"+data.xhr.response+"'>"+data.name+"</a><br><hr><br>");
                }

            };
        });

    </script>

    <div class="panel panel-info">
        <div class="panel-heading">
            Uploader
        </div>
        <div class="panel-body">
            <div class="x_content">

                <p>Drag multiple files to the box below for multi upload or click to select files. This is for demonstration purposes only, the files are not uploaded to any server.</p>
                <form action="<?= url('/upload_files') ?>" method="POST" enctype="multipart/form-data" id="my-awesome-dropzone" class="dropzone" style="border: 1px solid #e5e5e5; height: 300px; ">
                    {{csrf_field()}}
                </form>
                <input type="hidden" class="base_url_class" value="<?php echo url("/") ?>">
                <br />
                <br />
                <br />
                <br />

                <div class="row">

                    <div class="col-md-12 show_uploads">

                    </div>

                </div>

            </div>
        </div>
    </div>



@endsection

