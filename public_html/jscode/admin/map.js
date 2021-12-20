$(function () {

    var base_url2 = $(".url_class").val();
    var base_url = base_url2 + "public_html/";

    var csrf_kuwait_tv = $(".csrf_input_class").val();
    
    var pos = {
        lat: 29.9809061,
        lng: 31.318960899999997
    };
    
    var map;
    
    // Try HTML5 geolocation.
    function initMap()
    {
        
        // not load map if not on page edit shop
        if (!($("#shop_map").length>0)) {
            return;
        }
        
        // get current location of user by GPS
        navigator.geolocation.getCurrentPosition(function(position) {
            console.log('your pos');
            console.log(position.coords.latitude);
            console.log(position.coords.longitude);
            pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            
            
            // get old shop directions
            var shop_lat;
            var shop_lng;

            shop_lat = $('.lat').val();
            shop_lng = $('.lng').val();

            // check if shop directions is found or not
            if (shop_lat > 0 && shop_lng >0) {

                pos.lat = shop_lat;
                pos.lng = shop_lng;
                console.log('shop_lat'+shop_lat);
                console.log('shop_lng'+shop_lng);
            }
            console.log('pos after get shop');
            console.log(pos);

            // load the map and set your coordinates
            map = new google.maps.Map(document.getElementById('shop_map'), {
              center: new google.maps.LatLng(pos.lat,pos.lng),
              zoom: 16
            });
            
            // Start Add Search box
            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                var markers = [];

                if (places.length == 0) {
                  return;
                }

                // Clear out the old markers.
                markers.forEach(function(marker) {
                  marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                  var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                  };

                  // Create a marker for each place.
                  markers.push(new google.maps.Marker({
                    map: map,
//                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                  }));
                  
                $(".lat").val(place.geometry.location.lat());
                $(".lng").val(place.geometry.location.lng());
                  
                  
                    if (place.geometry.viewport) {
                      // Only geocodes have viewport.
                      bounds.union(place.geometry.viewport);
                    } else {
                      bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
            });
            // End Add Search box
            
            // set marker to your previous location
            placeMarker(new google.maps.LatLng(pos.lat,pos.lng));
            
            // set your text input values 
            $(".lat").val(pos.lat);
            $(".lng").val(pos.lng);
            
            // set your new pos by marker and 
            google.maps.event.addListener(map, 'click', function(event) {
                
                marker.setPosition(event.latLng);
                
                $(".lat").val(event.latLng.lat);
                $(".lng").val(event.latLng.lng);
            });
            
        });
        
      
      
      
    }
    
    // definition function of marker
    function placeMarker(location) {
        marker = new google.maps.Marker({
            position: location, 
            map: map
        });

        map.setCenter(location);
    }
    google.maps.event.addDomListener(window,"load",initMap);
            
        


});

    