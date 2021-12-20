

<div class="container mb60 mt50">
    <div class="row">

        <div class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
            <div class="ui-block">

                <!-- Single Post -->

                <article class="hentry blog-post single-post single-post-v2">

                    <h1 class="h1 post-title">{{$article_data->page_title}}</h1>

                    <div class="post-thumb">
                        <img src="{{get_image_or_default($article_data->big_img_path)}}" alt="{{$article_data->big_img_path}}" style="width: 834px;height: 564px;">
                    </div>

                    <div class="post-content-wrap">
                        @if(isset($news_data->{'pdf_'.$language_locale}))
                            <a download class="btn btn-primary" href="{{ asset($news_data->{'pdf_'.$language_locale}) }}">Download PDF</a>
                        @else
                            <button id="cmd" class="btn btn-primary">Download PDF</button>
                        @endif
                        <div class="post-content" id="post-content">
                            <p>{!! $article_data->page_body !!}</p>
                        </div>
                        <div id="editor"></div>
                    </div>

                         <div class="choose-reaction reaction-colored">
                        <div class="title">Choose your <span>Rete !</span> - {{$count_rates}} Users rate this </div>
                        <div class='starrr' id='article_star'></div>
                           @if(Auth::user())
                             <script>
                             var user_id = {{$current_user->user_id}} ;
                             var article_id = {{$article_data->page_id}};

                          $('#article_star').starrr({
                              rating: {{$rates}} ,
                              change: function(e, value){
                                 $.ajax({
                                      url: base_url2 + "/articles/add_article_rate",
                                      type:'post',
                                     data:  {'_token':_token,'value':value,'user_id':user_id,'article_id':article_id },
                                      success: function (data)
                                      { //console.log(data);

                                       }
                                    });
                                }
                            })

                           </script>
                           @else
                             <script >
                            $('#article_star').starrr({
                              readOnly: true,
                              rating: {{$rates}},

                            });
                        </script>
                           @endif

                    </div>
                </article>

                {{-- Comments --}}
                <ul class="comments-list article_comments">

                    @foreach ($comments as $comment)

                    <li class="comment-item">
						<div class="post__author author vcard inline-items">
							<img src="{{article_comment_image($comment->user_image)}}" alt="author">

							<div class="author-date">
								<a class="h6 post__author-name fn" href="{{url("user/posts/all/$comment->user_id")}}">{{$comment->full_name}}</a>
								<div class="post__date">
									<time class="published" datetime="2017-03-24T18:18">
										 {{\Carbon\Carbon::createFromTimestamp(strtotime($comment->comment_date))->diffForHumans()}}

                                    </time>
								</div>
                            </div>

                            {{-- <div class="more comment_options_33">
                                <svg class="olymp-three-dots-icon">
                                    <use xlink:href="http://localhost/invstoc/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                                </svg>
                                <ul class="more-dropdown">
                                    <li><a class="edit_comment" data-commentid="36" href="#">Edit</a></li>
                                    <li><a class="delete_comment" data-commentid="37" href="#">Delete</a></li>
                                </ul>
                            </div> --}}

							<a href="#" class="more">
								<svg class="olymp-three-dots-icon">
									<use xlink:href="#olymp-three-dots-icon"></use>
								</svg>
							</a>
						</div>

                        <p>{{$comment->comment}}</p>
                    </li>
                    @endforeach
                </ul>

                {{-- end Comments --}}

                {{-- form Comment --}}

                @if(Auth::user())

                <form class="comment-form inline-items comment_replies post-comment write_comment_block"  >

                    <div class="post__author author vcard inline-items write_comment_block write_comment_div">
                        <img src="{{user_default_image($current_user)}}" alt="author">

                        <div class="form-group with-icon-right is-empty">
                            <textarea class="form-control add_artical_comment" data-userid="{{$current_user->user_id}}"  data-articleid="{{$article_data->page_id}}" placeholder=""></textarea>
                            <div class="add-options-message">
                                <a href="#" class="options-message" data-toggle="modal" data-target="#update-header-photo">
                                    <svg class="olymp-camera-icon">
                                        <use xlink:href="#olymp-camera-icon"></use>
                                    </svg>
                                </a>
                            </div>
                        <span class="material-input"></span>
                       </div>
                    </div>

                    <div class="comment_btn">
                     <button class="btn btn-md-2 btn-primary add_artical_comment_btn"><i class="fa fa-send-o"></i></button>
                    </div>
                </form>
                @endif

                {{-- end form Comment --}}

                <!-- ... end Single Post -->

            </div>

        </div>

        <!-- Left Sidebar -->

        <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
            @include('front.main_components.left_sidebar')
        </aside>

        <!-- ... end Left Sidebar -->

    </div>
</div>


<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5d21161988860efb"></script>

@section('jspdf')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            $('#cmd').on('click', function () {
                @if($language_locale == 'ar')
                    let wind = window.open('', 'PRINT', 'height=800,width=800');

                    wind.document.write($("#post-content").html())
                    wind.document.close();
                    wind.focus();
                    wind.print();
                @else
                doc.fromHTML($('#post-content').html(), 15, 15, {
                    'width': 170,
                    'elementHandlers': specialElementHandlers,
                });
                doc.save('{{$article_data->page_title}}.pdf');
                @endif
            });

            var doc = new jsPDF();
            var specialElementHandlers = {
                '#editor': function (element, renderer) {
                    return true;
                }
            };
        });
    </script>
@endsection
