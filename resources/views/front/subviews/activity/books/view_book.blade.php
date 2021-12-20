@extends('front.main_layout')

@section('subview')

    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">

                        <div class="ui-block" style="width: 100%">

                            <!-- Single Post -->

                            <div class="ui-block-title">
                                <h6 class="title">
                                    {{$cat_data->cat_name}}
                                </h6>
                            </div>

                            <div class="col-md-12">

                                <div class="input-group mb-3 go_to_page">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-default dropdown-toggle btn_lang_book" type="button" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            <img src="{{get_image_or_default($current_book_lang->lang_img_path)}}" width="30px" height="30px">
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php foreach($all_langs as $key => $lang_obj): ?>
                                                <a href="#" class="dropdown-item change_book_language" data-lang_id="{{$lang_obj->lang_id}}">
                                                    <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="20px" height="20px">
                                                    {{$lang_obj->lang_text}}
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="btn btn-success get_page_btn" type="button" id="button-addon1"  >
                                            {{show_content($general_static_keywords,"go_label")}}
                                        </button>
                                    </div>
                                    <input type="number" min="0" step="1" class="form-control get_page_number" placeholder="{{show_content($general_static_keywords,"go_to_page_label")}}"
                                           aria-label="Example text with button addon" data-book_id="{{$cat_data->cat_id}}" aria-describedby="button-addon1">
                                </div>

                            </div>

                            <!-- ... end Single Post -->

                            <!-- <div class="post-thumb" style="text-align: center;">
                                <img src="{{get_image_or_default($cat_data->small_img_path)}}" style="width: 200px;">
                            </div> -->
                        </div>
                        
                        @if(isset($cat_data->pdf_path))
                        <div class="ui-block" style="text-align:center;  width: 100%">
                            <!-- pdf File -->
                            <div class="ui-block-title">
                                 <button class="btn btn-success" onclick="window.open('{{ url($cat_data->pdf_path) }}');" type="button" id="button-addon1"  >
                                         View PDF File  
                                </button>
                                 <a class="btn btn-primary" href="{{ url($cat_data->pdf_path) }}" download="{{$cat_data->cat_name}}">
                                         Download PDF File
                                </a>
                            </div>
                        </div>  
                        @endif  



                        <div class="ui-block post-div" style="width: 100%">

                            <div class="post-content-wrap" style="margin: 10px">

                                <div class="post-text" >

                                    <?php if(count($page_data)): ?>
                                        <?php foreach($page_data as $key => $page_block): ?>
                                            <p class="book_page_block_{{$current_book_lang->lang_direction}}">
                                                <?php if(!empty($page_block->path)): ?>
                                                <img src="{{get_image_or_default($page_block->path)}}"
                                                     class="img_{{$page_block->block_img_position}}">
                                                <br>
                                                <?php endif; ?>
                                                
                                                <?php
                                                $page_block->block_body = str_replace('http://invstoc','https://www.invstoc',$page_block->block_body);
                                                ?>
                                                {!! $page_block->block_body !!}
                                            </p>
                                        <?php endforeach; ?>

                                        <?php else: ?>
                                        <div class="des-view-book">
                                            <div class="alert alert-warning" style="text-align: center;font-weight: bold;">
                                                This Book Not Have Pages until now keep following
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>


                        <?php if(!empty($pages_pagination)): ?>
                        <nav aria-label="Page navigation">
                            {{$pages_pagination->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                        </nav>
                        <?php endif; ?>


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