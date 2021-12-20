@extends('front.main_layout')

@section('subview')


    <div class="container">
        <div class="row">

            <main class="col col-xl-12 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12" >

                <!-- TradingView Widget BEGIN -->
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                <script type="text/javascript">
                    new TradingView.widget({
                        "container_id": 'technical-analysis',
                        "width": 998,
                        "height": 610,
                        "symbol": "AAPL",
                        "interval": "D",
                        "timezone": "exchange",
                        "theme": "Light",
                        "style": "1",
                        "toolbar_bg": "#f1f3f6",
                        "withdateranges": true,
                        "hide_side_toolbar": false,
                        "allow_symbol_change": true,
                        "save_image": false,
                        "hideideas": true,
                        "studies": [ "ROC@tv-basicstudies",
                            "StochasticRSI@tv-basicstudies",
                            "MASimple@tv-basicstudies" ],
                        "show_popup_button": true,
                        "popup_width": "1000",
                        "popup_height": "650"
                    });
                </script>
                <!-- TradingView Widget END -->
                <div id="technical-analysis"></div>

            </main>

        </div>
        <br />
        <br />
        <br />
    </div>

@endsection