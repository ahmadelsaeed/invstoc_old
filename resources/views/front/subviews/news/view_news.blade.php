<div class="container mb60 mt50">
    <div class="row">

        <div class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
            <div class="ui-block">

                <!-- Single Post -->

                <article class="hentry blog-post single-post single-post-v2">

                    <h1 class="h1 post-title">{{$news_data->page_title}}</h1>

                    <div class="post-thumb">
                        <img src="{{get_image_or_default($news_data->big_img_path)}}" style="width: 834px;height: 564px;">
                    </div>

                    <div class="post-content-wrap">
                        @if(isset($news_data->{'pdf_'.$language_locale}))
                            <a download class="btn btn-primary" href="{{ asset($news_data->{'pdf_'.$language_locale}) }}">Download PDF</a>
                        @else
                            <button id="cmd" class="btn btn-primary">Download PDF</button>
                        @endif
                        <div class="post-content" id="post-content">
                            <p>{!! $news_data->page_body !!}</p>
                        </div>
                        <div id="editor"></div>
                    </div>

                </article>

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
                doc.save('{{$news_data->page_title}}.pdf');
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
