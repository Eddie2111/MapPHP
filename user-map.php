<?php
include_once 'header.php';
include 'locations_model.php';
//get_unconfirmed_locations();exit;
?>
    <style>

        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
            margin-left: 20%;
            width:50%
        }
        #map { position:absolute;left: 350px; top:350px; bottom:0px;height:550px ;width:660px; }
        .geocoder {
            position:absolute;left: 350px; top:290px;
        }
    </style>


    <div class="container">
        <form action="" id="signupForm">

        </form>
    </div>

    <div class="geocoder">
        <div id="geocoder" ></div>
    </div>

    <div id="map"></div>


    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.css' rel='stylesheet' />
    <style>
    </style>

    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />

    <script>

        var saved_markers = <?= get_saved_locations() ?>;
        var user_location = [90.366721,23.804800];
        mapboxgl.accessToken = 'pk.eyJ1Ijoic3R1ZGVudDIwMTAxMDczIiwiYSI6ImNrdjdyYWJ0aThjYnIydnQ5N3FzdDIyNmkifQ.fzdTy5xChf9UAxyIjO53Pg';
        // try this for online pk.eyJ1Ijoic3R1ZGVudDIwMTAxMDczIiwiYSI6ImNrdndjZG1paDQ1MHMybnRrbzIxN2NpajAifQ.KuopN6eJSnMav-bJZQMZmw //
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: user_location,
            zoom: 10
        });
        //  geocoder here  //
        var geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
        });

        var marker ;

        // After the map style has loaded on the page, add a source layer and default
        // styling for a single point.
        map.on('load', function() {
            addMarker(user_location,'load');
            add_markers(saved_markers);

            // Listen for the `result` event from the MapboxGeocoder that is triggered when a user
            // makes a selection and add a symbol that matches the result.
            geocoder.on('result', function(ev) {
                alert("aaaaa");
                console.log(ev.result.center);

            });
        });
        map.on('click', function (e) {
            marker.remove();
            addMarker(e.lngLat,'click');
            //console.log(e.lngLat.lat);
            document.getElementById("lat").value = e.lngLat.lat;
            document.getElementById("lng").value = e.lngLat.lng;

        });

        function addMarker(ltlng,event) {

            if(event === 'click'){
                user_location = ltlng;
            }
            marker = new mapboxgl.Marker({draggable: true,color:"#d02922"})
                .setLngLat(user_location)
                .addTo(map)
                .on('dragend', onDragEnd);
        }
        function add_markers(coordinates) {

            var geojson = (saved_markers == coordinates ? saved_markers : '');

            console.log(geojson);
            // add markers to map
            geojson.forEach(function (marker) {
                console.log(marker);
                // make a marker for each feature and add to the map
                new mapboxgl.Marker()
                    .setLngLat(marker)
                    .addTo(map);
            });

        }

        function onDragEnd() {
            var lngLat = marker.getLngLat();
            document.getElementById("lat").value = lngLat.lat;
            document.getElementById("lng").value = lngLat.lng;
            console.log('lng: ' + lngLat.lng + '<br />lat: ' + lngLat.lat);
        }


///from here you will have to send data///



        $('#signupForm').submit(function(event){
            event.preventDefault();
            var lat = $('#lat').val();
            var lng = $('#lng').val();
            var url = 'locations_model.php?add_location&lat=' + lat + '&lng=' + lng;
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    alert(data);
                    location.reload();
                }
            });
        });

//////////////////////////////////////////////////

        document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

    </script>

<?php
include_once footer.php;
?>
