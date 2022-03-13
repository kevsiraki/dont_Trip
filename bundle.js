(function() { //minified functions
    function r(e, n, t) {
        function o(i, f) {
            if (!n[i]) {
                if (!e[i]) {
                    var c = "function" == typeof require && require;
                    if (!f && c) return c(i, !0);
                    if (u) return u(i, !0);
                    var a = new Error("Cannot find module '" + i + "'");
                    throw a.code = "MODULE_NOT_FOUND", a
                }
                var p = n[i] = {
                    exports: {}
                };
                e[i][0].call(p.exports, function(r) {
                    var n = e[i][1][r];
                    return o(n || r)
                }, p, p.exports, r, e, n, t)
            }
            return n[i].exports
        }
        for (var u = "function" == typeof require && require, i = 0; i < t.length; i++) o(t[i]);
        return o
    }
    return r
})()({
    1: [function(require, module, exports) {
        require('./map')
    }, {
        "./map": 2
    }],
    2: [function(require, module, exports) {
        const polyline = require("google-polyline"); //some required globals
        var iconExists = ["bar", "food", "park", "place_of_worship", "point_of_interest", "restaurant", "store"]; //mimicking google's json to display custom icons
        var markers = [];
        var placesList = document.getElementById("places");
        var waypoints = [];
        var placeListND = [];
        let locationButton = document.createElement("button");
        let service;
        let total = 0;
        let map;
		const d = new Date();

        function initMap() { //set up the map/styles, initial route from geolocation to endpoint, places along the route, and initial directions.
            if(d.getHours()>=6 && d.getHours()<=18) {
			iconExists = [];
			document.getElementById("sidebar").style.backgroundColor = "#FFFFED";
			document.getElementsByName("rust")[0].style.backgroundColor = "#FFFFED";
			document.getElementById("dragbar").style.backgroundColor = "#FFFFff";
			map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 36.778259,
                    lng: -119.417931
                },
                keyboardShortcuts: false,
                disableDefaultUI: true,
                zoom: 6,
                styles: 
				[{
                    "featureType": "poi",
                    "stylers": [{
                        "visibility": "off"
                    }]
                }]
			});
			}
			else {
			map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 36.778259,
                    lng: -119.417931
                },
                keyboardShortcuts: false,
                disableDefaultUI: true,
                zoom: 6,
				//getHours()
				
                styles: [{
                    elementType: "geometry",
                    stylers: [{
                        color: "#242f3e"
                    }]
                }, {
                    "featureType": "poi",
                    "stylers": [{
                        "visibility": "off"
                    }]
                }, {
                    elementType: "labels.text.stroke",
                    stylers: [{
                        color: "#242f3e"
                    }]
                }, {
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#746855"
                    }]
                }, {
                    featureType: "administrative.locality",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#d59563"
                    }],
                }, {
                    featureType: "poi",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#d59563"
                    }],
                }, {
                    featureType: "poi.park",
                    elementType: "geometry",
                    stylers: [{
                        color: "#263c3f"
                    }],
                }, {
                    featureType: "poi.park",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#6b9a76"
                    }],
                }, {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [{
                        color: "#38414e"
                    }],
                }, {
                    featureType: "road",
                    elementType: "geometry.stroke",
                    stylers: [{
                        color: "#212a37"
                    }],
                }, {
                    featureType: "road",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#9ca5b3"
                    }],
                }, {
                    featureType: "road.highway",
                    elementType: "geometry",
                    stylers: [{
                        color: "#746855"
                    }],
                }, {
                    featureType: "road.highway",
                    elementType: "geometry.stroke",
                    stylers: [{
                        color: "#1f2835"
                    }],
                }, {
                    featureType: "road.highway",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#f3d19c"
                    }],
                }, {
                    featureType: "transit",
                    elementType: "geometry",
                    stylers: [{
                        color: "#2f3948"
                    }],
                }, {
                    featureType: "transit.station",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#d59563"
                    }],
                }, {
                    featureType: "water",
                    elementType: "geometry",
                    stylers: [{
                        color: "#17263c"
                    }],
                }, {
                    featureType: "water",
                    elementType: "labels.text.fill",
                    stylers: [{
                        color: "#515c6d"
                    }],
                }, {
                    featureType: "water",
                    elementType: "labels.text.stroke",
                    stylers: [{
                        color: "#17263c"
                    }],
                }, ]
				
            });
			}
            infoWindow = new google.maps.InfoWindow();
            google.maps.event.addDomListener(window, 'load', initialize);
            const keywordIn = document.createElement("input"); //buttons and inputs
            keywordIn.type = "text";
            keywordIn.id = "keywordIn";
            keywordIn.placeholder = "Keywords";
            map.controls[google.maps.ControlPosition.LEFT].push(keywordIn);
            locationButton.textContent = "Plan it, Don't Trip!";
            locationButton.id = "go";
            locationButton.className = "btn-primary";
            map.controls[google.maps.ControlPosition.LEFT].push(locationButton);
            locationButton.addEventListener("click", () => {
                if (navigator.geolocation) { // Try HTML5 geolocation.
                    navigator.geolocation.
                    getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            const directionsService = new google.maps.DirectionsService(); //init directions services here.
                            const directionsRenderer = new google.maps.DirectionsRenderer({
                                draggable: false,
                                map,
                                panel: document.getElementById("panel"),
                            });
                            directionsRenderer.addListener("directions_changed", () => {
                                const directions = directionsRenderer.getDirections();
                                if (directions) {
                                    computeTotalDistance(directions);
                                    total = tot;
                                }
                                locationButton.addEventListener("click", () => {
                                    directionsRenderer.setMap(null);
                                    clear();
                                });
                            });
                            const geocoder = new google.maps.Geocoder();
                            const latlng = {
                                lat: parseFloat(pos.lat),
                                lng: parseFloat(pos.lng)
                            };
                            displayRoute(
                                latlng,
                                document.getElementById("myInput").value.toLowerCase(),
                                directionsService,
                                directionsRenderer
                            );
                            infoWindow.setPosition(pos);
                            infoWindow.setContent("Location found.");
                            infoWindow.open(map);
                            map.setCenter(pos);
                            let getNextPage;
                            const moreButton = document.getElementById("more");
                            moreButton.onclick = function() {
                                moreButton.disabled = true;
                                if (getNextPage) {
                                    getNextPage();
                                }
                            };
                            var waypoints = [];
                            var address = document.getElementById("myInput").value;
                            geocoder.geocode({ //get address
                                'address': address
                            }, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) { //use geocoder to convert dest to latlng object.
                                    var latlng2 = results[0].geometry.location;
                                    var request = {
                                        origin: latlng,
                                        destination: latlng2,
                                        travelMode: "DRIVING",
                                    };
                                    directionsService.route(request, function(result, status) {
                                        if (status == "OK") {
                                            directionsRenderer.setDirections(result);
                                            waypoints = polyline.decode(result.routes[0].overview_polyline);
                                        }
                                        const PolygonCoords = PolygonPoints();
                                        const PolygonBound = new google.maps.Polygon({
                                            paths: PolygonCoords,
                                            strokeColor: "#FF0000",
                                            strokeOpacity: 0.8,
                                            strokeWeight: 2,
                                            fillColor: "#FF0000",
                                            fillOpacity: 0.35,

                                        });
                                        PolygonBound.setMap(map); //splitting a polygon into points, calling nearbySearch on each point
                                        service = new google.maps.places.PlacesService(map);
                                        for (let j = 0; j < waypoints.length; j += 40) {
                                            service.nearbySearch({
                                                    location: {
                                                        lat: waypoints[j][0],
                                                        lng: waypoints[j][1]
                                                    },
                                                    radius: parseInt(total * 225),
                                                    keyword: document.getElementById("keywordIn").value.toLowerCase()
                                                },
                                                (results, status, pagination) => {
                                                    if (status !== "OK" || !results) return;
                                                    addPlaces(results, map, pos, latlng2);
                                                    moreButton.disabled = !pagination || !pagination.hasNextPage;
                                                    if (pagination && pagination.hasNextPage) {
                                                        getNextPage = () => {
                                                            pagination.nextPage(); // Note: nextPage will call the same handler function as the initial call
                                                            sortList();
                                                        };
                                                    }
                                                });
                                        }

                                    });
                                    directionsRenderer.setMap(map);
                                }
                            });
                        });
                }
            });
        }

        function clear() {
            clearMarkers();
            markers = [];
            document.getElementById("places").innerHTML = "";
            document.getElementById("panel").innerHTML = "";
            placeListND = [];
        }

        function addPlaces(places, map, pos, latlng2) { //shows places fetched from nearby search on map/li element
            const infowindow = new google.maps.InfoWindow();
            const infowindowContent = document.getElementById("infowindow-content");
            infowindow.setContent(infowindowContent);
            const service2 = new google.maps.places.PlacesService(map);
            const geocoder = new google.maps.Geocoder();
            for (const place of places) {
                placeListND.push(place.place_id); //list of previous places, avoids duplications
            }
            for (const place of places) { //sadly, O(n^3) time complexity loop
                if (place.geometry && place.geometry.location) {
                    var image = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    if (iconExists.includes(place.types[1], 0)&& !(d.getHours()>=6 && d.getHours()<=18)) {
                        image.url = "icons/" + place.types[1] + ".png";
                    } else if ((place.types[1].includes("store") || place.types[1].includes("grocery") )&& !(d.getHours()>=6 && d.getHours()<=18)) {
                        image.url = "icons/store.png";
                    }
                    var marker = new google.maps.Marker({
                        map,
                        icon: image,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    markers.push(marker);
                    const li = document.createElement("li");
                    if (getOccurrence(placeListND, place.place_id) == 1) { //kill duplicates	
                        li.textContent = place.name;
                        placesList.appendChild(li);
                        sortList();
                    }
                    marker.addListener("click", () => {
                        geocoder
                            .geocode({
                                placeId: place.place_id
                            })
                            .then(({
                                results
                            }) => {
                                marker.setPlace({ // Set the position of the marker using the place ID and location.
                                    placeId: place.place_id,
                                    location: results[0].geometry.location,
                                });
                                var request = {
                                    placeId: place.place_id,
                                    fields: ['name', 'rating', 'formatted_phone_number', 'geometry', "website", "opening_hours"]
                                };
                                marker.setVisible(true);
                                const content = document.createElement("div");
                                content.style.height = "150px";
                                content.style.width = "150px";
                                const nameElement = document.createElement("p");
                                nameElement.textContent = place.name;
                                content.appendChild(nameElement);
                                var photos = place.photos;
                                if (photos) {
                                    const photoElement = document.createElement("img");
                                    photoElement.src = photos[0].getUrl({
                                        maxWidth: 128,
                                        maxHeight: 128
                                    })
                                    content.appendChild(photoElement);
                                }
                                const placeAddressElement = document.createElement("p");
                                placeAddressElement.textContent = results[0].formatted_address;
                                content.appendChild(placeAddressElement);

                                const placePhoneNumber = document.createElement("p");
                                const placeRating = document.createElement("p");
                                const placeOpen = document.createElement("p");
                                const placeWebsite = document.createElement("a");
                                const br = document.createElement("br");
                                const brr = document.createElement("br");
								
                                service.getDetails(request, callback);

                                function callback(place, status) {
                                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                                        placePhoneNumber.textContent = place.formatted_phone_number;
                                        placeRating.textContent = place.rating + "/5";
                                        placeOpen.textContent = place.opening_hours.open_now ? "Open Now" : "Closed";
                                        placeWebsite.textContent = "Website";
                                        placeWebsite.href = place.website;
                                        placeWebsite.appendChild(br);
                                        placeWebsite.appendChild(brr);

                                    }
                                }

                                content.appendChild(placeOpen);
                                content.appendChild(placeRating);
                                content.appendChild(placePhoneNumber);
                                content.appendChild(placeWebsite);



                                service = new google.maps.places.PlacesService(map);
                                const dir = document.createElement("button");

                                dir.textContent = "Add to Route";
                                content.appendChild(dir);
								const dir2 = document.createElement("button");

                                dir2.textContent = "More Info";
                                content.appendChild(dir2);
                                dir2.addEventListener("click", () => { //add marker to waypoint along route onClick, can be cleared!
                                    document.getElementById("panel").innerHTML = "";
                                    const directionsService = new google.maps.DirectionsService();
                                    const directionsRenderer = new google.maps.DirectionsRenderer({
                                        draggable: false,
                                        map,
                                        panel: document.getElementById("panel"),
                                    });
                                    directionsRenderer.addListener("directions_changed", () => {
                                        var directions = directionsRenderer.getDirections();
                                        if (directions) {
                                            computeTotalDistance(directions);
                                            total = tot;
                                        }
                                        locationButton.addEventListener("click", () => {
                                            directionsRenderer.setMap(null);
                                            clear();
                                        });
                                    });
                                    displayRoute(
                                        pos,
                                        latlng2,
                                        directionsService,
                                        directionsRenderer
                                    );
                                    var waypoint = new Array();
                                    waypoint.push({
                                        location: place.geometry.location,
                                        stopover: true,
                                    });
                                    var request = {
                                        origin: pos,
                                        destination: latlng2,
                                        waypoints: waypoint,
                                        travelMode: "DRIVING",
                                    };
                                    directionsService.route(request, function(result, status) {
                                        if (status == "OK") {
                                            directionsRenderer.setDirections(result);
                                        }
                                    });
                                });
                                infowindow.setContent(content);
                                infowindow.open(map, marker);
                            })
                            .catch((e) => window.alert("Geocoder failed due to: " + e));
                    });
                    li.addEventListener("click", () => {
                        map.setCenter(place.geometry.location);
                        map.setZoom(20);
                    });
                }
            }
        }

        function initialize() {
            const dest = document.createElement("input");
            dest.type = "text";
            dest.id = "myInput";
            dest.placeholder = "Destination";
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(dest);
            dest.addEventListener("change", function() {
                dest.value = "";
            });
            dest.addEventListener("keydown", function(event) {
                var KeyID = event.keyCode;
                if (event.keyCode == 8 && event.repeat) {
                    dest.value = " ";
                }
            });
            new google.maps.places.Autocomplete(dest);
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) { //geolocation fetching error handler
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation."
            );
            infoWindow.open(map);
        }

        function displayRoute(origin, destination, service, display) { //function to display a route origin->dest
            service
                .route({
                    origin: origin,
                    destination: destination,
                    waypoints: [{
                        location: origin
                    }, {
                        location: destination
                    }, ],
                    travelMode: google.maps.TravelMode.DRIVING,
                    avoidTolls: true,
                })
                .then(
                    (result) => {
                        display.setDirections(result);
                    }
                )
                .catch((e) => {
                    alert("Could not display directions due to: " + e);
                });
        }

        function computeTotalDistance(result) { //computes the distance from an origin->dest (along the set route)
            const myroute = result.routes[0];
            if (!myroute) {
                return;
            }
            for (let i = 0; i < myroute.legs.length; i++) {
                total += myroute.legs[i].distance.value;
            }
            total = total / 1000;
            tot = total;
            document.getElementById("total").innerHTML = (total * 0.621371).toFixed(1) + " Miles"; //convert km to miles
        }

        function sortList() {
            var list, i, switching, b, shouldSwitch;
            list = document.getElementById("places");
            switching = true;
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
                // start by saying: no switching is done:
                switching = false;
                b = list.getElementsByTagName("LI");
                // Loop through all list-items:
                for (i = 0; i < (b.length - 1); i++) {
                    // start by saying there should be no switching:
                    shouldSwitch = false;
                    /* check if the next item should
                    switch place with the current item: */
                    if (b[i].innerHTML.toLowerCase() > b[i + 1].innerHTML.toLowerCase()) {
                        /* if next item is alphabetically
                        lower than current item, mark as a switch
                        and break the loop: */
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                    and mark the switch as done: */
                    b[i].parentNode.insertBefore(b[i + 1], b[i]);
                    switching = true;
                }
            }
        }

        function getOccurrence(arr, val) { //how many times val occurs in array
            var count = 0;
            arr.forEach((v) => (v === val && count++));
            return count;
        }

        function setMapOnAll(map) { // Sets the map on all markers in the array.
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        function clearMarkers() { // Removes the markers from the map, but keeps them in the array.
            setMapOnAll(null);
        }

        function PolygonPoints(waypointsParam) { //split a polygon into points
            let polypoints = waypoints
            let PolyLength = polypoints.length;
            let UpperBound = [];
            let LowerBound = [];
            for (let j = 0; j <= PolyLength - 1; j++) {
                let NewPoints = PolygonArray(polypoints[j][0]);
                UpperBound.push({
                    lat: NewPoints[0],
                    lng: polypoints[j][1]
                });
                LowerBound.push({
                    lat: NewPoints[1],
                    lng: polypoints[j][1]
                });
            }
            let reversebound = LowerBound.reverse();
            let FullPoly = UpperBound.concat(reversebound);
            return FullPoly;
        }

        function PolygonArray(latitude) { //return an array of smaller polygons from low->upper latitude
            const R = 6378137;
            const pi = 3.14;
            //distance in meters
            const upper_offset = 300;
            const lower_offset = -300;
            Lat_up = upper_offset / R;
            Lat_down = lower_offset / R;
            //OffsetPosition, decimal degrees
            lat_upper = latitude + (Lat_up * 180) / pi;
            lat_lower = latitude + (Lat_down * 180) / pi;
            return [lat_upper, lat_lower];
        }

        var script = document.createElement("script"); //append the API key to the DOM
        script.src =
            "https://maps.googleapis.com/maps/api/js?key=AIzaSyAPQgLLIUPBC6TWQi0c9qnf-1O6S5RksBA&callback=initMap&libraries=places";
        script.defer = true;
        window.initMap = function() {
            initMap();
        };
        document.head.appendChild(script);

    }, {
        "google-polyline": 5
    }],
    3: [function(require, module, exports) { //minified functions
        var PRECISION = 1e5

        function decode(value) { //decode a polyline into an array of points
            var points = []
            var lat = 0
            var lon = 0
            var values = decode.integers(value, function(x, y) {
                lat += x
                lon += y
                points.push([lat / PRECISION, lon / PRECISION])
            })
            return points
        }

        decode.sign = function(value) {
            return value & 1 ? ~(value >>> 1) : (value >>> 1)
        }

        decode.integers = function(value, callback) {
            var values = 0
            var x = 0
            var y = 0
            var byte = 0
            var current = 0
            var bits = 0
            for (var i = 0; i < value.length; i++) {
                byte = value.charCodeAt(i) - 63
                current = current | ((byte & 0x1F) << bits)
                bits = bits + 5
                if (byte < 0x20) {
                    if (++values & 1) {
                        x = decode.sign(current)
                    } else {
                        y = decode.sign(current)
                        callback(x, y)
                    }
                    current = 0
                    bits = 0
                }
            }
            return values
        }
        module.exports = decode
    }, {}],
    4: [function(require, module, exports) {
        var PRECISION = 1e5
        var CHARCODE_OFFSET = 63
        var CHARMAP = {}
        for (var i = 0x20; i < 0x7F; i++) {
            CHARMAP[i] = String.fromCharCode(i)
        }

        function encode(points) { //encode points into a string
            // px, py, x and y store rounded exponentiated versions of the values
            // they represent to compute the actual desired differences. This helps
            // with finer than 5 decimals floating point numbers.
            var px = 0,
                py = 0
            return reduce(points, function(str, lat, lon) {
                var x = Math.round(lat * 1e5)
                var y = Math.round(lon * 1e5)
                str += chars(sign((x - px))) +
                    chars(sign((y - py)))
                px = x
                py = y
                return str
            })
        }

        function reduce(points, callback) {
            var point = null
            var lat = 0
            var lon = 0
            var str = ''
            for (var i = 0; i < points.length; i++) {
                point = points[i]
                lat = point.lat || point.x || point[0]
                lon = point.lng || point.y || point[1]
                str = callback(str, lat, lon)
            }
            return str
        }

        function sign(value) {
            return (value < 0) ? ~(value << 1) : (value << 1)
        }

        function charCode(value) {
            return ((value & 0x1F) | 0x20) + 63
        }

        function chars(value) {
            var str = ''
            while (value >= 0x20) {
                str += CHARMAP[charCode(value)]
                value = value >> 5
            }
            str += CHARMAP[value + 63]
            return str
        }
        module.exports = encode
    }, {}],
    5: [function(require, module, exports) {
        module.exports = {
            encode: require('./encode'),
            decode: require('./decode'),
        }
    }, {
        "./decode": 3,
        "./encode": 4
    }]
}, {}, [1]);