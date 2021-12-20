@extends('front.main_layout')

@section('subview')


    <input type="hidden" class="allow_copy">

    <div class="container">
        <div class="bodycontent">
            <div class="row">
                <!---------  right site ----------->
                <div class="col-md-12 padd-left padd-right">

                    <div class="col-md-12" style="padding-bottom: 10px;">
                        <div class="part-sectios bar-top-view">

                                <div class="tit-section">
                                    <h1 style="font-size: 17px;margin: 0.67em 0;">All Files</h1>
                                </div>

                        </div>
                    </div>

                    <div class="col-md-12">

                        <form>
                            <input type="text" name="search_for_file" placeholder="ابحث عن صورة">
                            <button class="btn">Search</button>
                        </form>

                    </div>


                    <div class="col-md-12">

                        <?php
                        //get all files in uploads/ckeditor folder
                        if ($handle = opendir('uploads/ckeditor')) {

                        while (false !== ($entry = readdir($handle))) {
                        if(in_array($entry,[".",'..']))continue;

                        if(!empty($search_for_file)&&str_contains($entry,$search_for_file)==false){
                            continue;
                        }
                        ?>

                        <div class="col-md-3">
                            <img src="{{url("/uploads/ckeditor/$entry")}}" alt="" style="width: 100%;">
                            <input type="text" value="{{url("/uploads/ckeditor/$entry")}}">
                            <h5>{{$entry}}</h5>
                        </div>


                        <?php


                        }

                        closedir($handle);
                        }


                        ?>



                    </div>

                </div>
                <!---------  end right site ----------->

            </div>
        </div>
    </div>



@endsection