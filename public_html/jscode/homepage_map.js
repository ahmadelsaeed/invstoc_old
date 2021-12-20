var pos = {
            lat: 29.9809061,
            lng: 31.318960899999997
        };

function set_position(new_pos){

    pos.lat = new_pos.lat;
    pos.lng = new_pos.lng;

}

$(function() {


    if (!($("#show_homepage_map").length > 0)) {
        return;
    }

    function set_homepage_model_error()
    {
        $("#show_homepage_map").html("<div class='alert alert-danger show_map_alert'>There No Map To Show Now !!</div>");
        $('.show_map_alert').css({"text-align": "center", "margin-top": "20%"});
        return;
    }


    var map;

    function initMap() {

        // setTimeout(function () {


            // Try HTML5 geolocation.
            console.log(navigator.geolocation);

            if (navigator.geolocation) {

                var map_motion = $('.map_motion').val();
                var map_zoom = $('.map_zoom').val();
                var map_cities = $('.map_cities').val();
                var center_location_lat = $('.center_location_lat').val();
                var center_location_lng = $('.center_location_lng').val();

                if (typeof(map_motion) == "undefined" || typeof(map_zoom) == "undefined"
                    || typeof(map_cities) == "undefined" || typeof(center_location_lat) == "undefined"
                    || typeof(map_cities) == "center_location_lng") {
                    set_homepage_model_error();
                }

                if (map_cities == "[]") {
                    set_homepage_model_error();
                }

                map_zoom = parseInt(map_zoom);
                center_location_lat = parseFloat(center_location_lat);
                center_location_lng = parseFloat(center_location_lng);
                map_cities = JSON.parse(map_cities);

                if (map_cities.length == 0) {
                    set_homepage_model_error();
                }

                // console.log("enter to map");
                // console.log(map_cities);
                // console.log(center_location_lat);
                // console.log(center_location_lng);


                var markers = [];
                var addMarkers = function (map_cities, map) {
                    $.each(map_cities, function (i, city) {
                        var mymotion = google.maps.Animation.BOUNCE;

                        if (map_motion != "true") {
                            mymotion = null;
                        }

                        var latlng = new google.maps.LatLng(city.cat_lat, city.cat_lng);
                        var marker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            icon: new google.maps.MarkerImage(
                                base_url + 'front/images/markers/marker-red.png',
                                null,
                                null,
                                // new google.maps.Point(0,0),
                                null,
                                new google.maps.Size(30, 30)
                            ),
                            draggable: false,

                            animation: mymotion,

                        });
                        var contentString =
                            '<div class="infoW" style="text-align: center">'
                                +'<h1 style="background-color: #4F5D73;color: #fff !important;font-size: 30px !important;">'
                                    +'<a href=' + base_url2 + '/' + encodeURI(city.parent_cat_slug) + '/' + encodeURI(city.cat_slug) + '>' + city.cat_name + '</a>'
                                +'</h1>'

                                +'<img src="' + base_url2 + '/' + city.small_img_path + '" style="width: 40%;float: right;">'

                                +'<p style="width: 55%">' + city.cat_short_desc + '</p>'

                            +'</div>';

                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        marker.addListener('click', function() {
                            infowindow.open(map, marker);
                        });


                        $(document).on('click', '.closeInfo', function () {
                            infowindow.open(null, null);
                        });

                        markers.push(marker);
                    });


                };//end add marks function



                $('body').removeClass('notransition');
                map = new google.maps.Map(document.getElementById('show_homepage_map'), {
                    zoom: map_zoom,
                    center: {lat:center_location_lat,lng:center_location_lng}
                });
                // var styledMapType = new google.maps.StyledMapType(styles, {
                //     name: 'Styled'
                // });
                // console.log("hereeee222");
                // map.mapTypes.set('Styled', styledMapType);
                // map.setZoom(map_zoom);

                addMarkers(map_cities, map);


            } else {
                alert("Please use google chrome or firefox final versions");
            }


        // }, 100);

    }
    if(typeof(google) == "undefined")
    {
        set_homepage_model_error();
    }
    google.maps.event.addDomListener(window,"load",initMap);

});