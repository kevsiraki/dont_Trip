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
        var map_options;
        const d = new Date();
        var waypoint = new Array();
        var addedWaypoint = new Array();
        var offset = 40;

        function initMap() { //set up the map/styles, initial route from geolocation to endpoint, places along the route, and initial directions.
            map = new google.maps.Map(document.getElementById("map"));
            map.setCenter({
                lat: 36.778259,
                lng: -119.417931
            });
            map.setZoom(6);
            if (d.getHours() >= 6 && d.getHours() <= 18) {
                iconExists = [];
                document.getElementById("sidebar").style.backgroundColor = "#FFFFED";
                document.getElementsByName("rust")[0].style.backgroundColor = "#FFFFED";
                document.getElementById("dragbar").style.backgroundColor = "#FFFFED";
                $.get('../style/light_styles.js', function(data) {
                    map.setOptions({
                        center: {
                            lat: 36.778259,
                            lng: -119.417931
                        },
                        keyboardShortcuts: false,
                        disableDefaultUI: true,
                        styles: JSON.parse(data)
                    });
                });
            } else {
                $.get('../style/dark_styles.js', function(data) {
                    map.setOptions({
                        center: {
                            lat: 36.778259,
                            lng: -119.417931
                        },
                        keyboardShortcuts: false,
                        disableDefaultUI: true,
                        styles: JSON.parse(data)
                    });
                });
            }
            infoWindow = new google.maps.InfoWindow();
            const br = document.createElement("br");
            google.maps.event.addDomListener(window, 'load', initialize);
            const keyword = document.createElement("div"); //buttons and inputs
            const keywordIn = document.createElement("input"); //buttons and inputs
            keywordIn.type = "text";
            keywordIn.id = "keywordIn";
            keywordIn.placeholder = "Keywords";
            keyword.classList.add("autocomplete");
            autocomplete(keywordIn, keywords);
            locationButton.textContent = "Go";
            locationButton.id = "go";
            locationButton.className = "btn-primary";
            var $_GET = {};
            if (document.location.toString().indexOf('?') !== -1) {
                var query = document.location
                    .toString()
                    // get the query string
                    .replace(/^.*?\?/, '')
                    // and remove any existing hash string (thanks, @vrijdenker)
                    .replace(/#.*$/, '')
                    .split('&');

                for (var i = 0, l = query.length; i < l; i++) {
                    var aux = decodeURIComponent(query[i]).split('=');
                    $_GET[aux[0]] = aux[1];
                }
                //get the 'index' query parameter
            }
            if ($_GET['keyVal']) {
                keywordIn.value = $_GET['keyVal'];
            }
            keyword.appendChild(keywordIn);
            keywordIn.addEventListener("keydown", function(event) {
                if (event.keyCode == 8 && event.repeat) {
                    keywordIn.value = " ";
                }
            });

            keyword.appendChild(locationButton);
            locationButton.setAttribute("style", "margin-left: 5px;");
            map.controls[google.maps.ControlPosition.LEFT].push(keyword);
            document.getElementById("clear").addEventListener("click", () => {
                locationButton.click();
            });
            document.getElementById("sortA").addEventListener("click", () => {
                sortListAlphabetically();
            });
            document.getElementById("sortD").addEventListener("click", () => {
                sortListByDistance();
            });
            locationButton.addEventListener("click", () => {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", "https://donttrip.technologists.cloud/donttrip/client/dt.php?go=yes&destination=" + document.getElementById("myInput").value + "&keyword=" + document.getElementById("keywordIn").value, true);
                xmlhttp.send();
                if (document.getElementById("myInput").value == null || document.getElementById("myInput").value == "") {
                    alert("Please Choose a Destination.");
                } else if (navigator.geolocation) { // Try HTML5 geolocation.
                    var xxx = document.getElementsByName("rust");
                    if (xxx[0].style.display === "none") {
                        xxx[0].style.display = "block";
                    }
                    var x = document.getElementById("clear");
                    if (x.style.display === "none") {
                        x.style.display = "block";
                    }
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
                                        avoidTolls: true,
                                    };
                                    directionsService.route(request, function(result, status) {
                                        if (status == "OK") {
                                            directionsRenderer.setDirections(result);
                                            waypoints = polyline.decode(result.routes[0].overview_polyline);

                                            directionsRenderer.setMap(map);
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
                                            if ((total * 0.621371).toFixed(1) > 350) {
                                                playSound();
                                                offset = 25;

                                            } else {
                                                offset = 40;
                                            }
                                            for (let j = 0; j < waypoints.length; j += offset) {
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
                                                            };
                                                        }
                                                    });
                                            }
                                            service.nearbySearch({
                                                    location: {
                                                        lat: latlng2.lat(),
                                                        lng: latlng2.lng()
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
                                                        };
                                                    }
                                                });
                                        } else {
                                            clear();
                                            alert("Please enter a desination you can drive to.");
                                        }
                                    });
                                }
                            });
                        });
                }
            });
        }

        function playSound() {
            var sound = document.getElementById("audio");
            sound.play();
        }

        function initialize() {
            const dest = document.createElement("input");
            dest.type = "text";
            dest.id = "myInput";
            dest.placeholder = "Destination";
            var $_GET = {};
            if (document.location.toString().indexOf('?') !== -1) {
                var query = document.location
                    .toString()
                    // get the query string
                    .replace(/^.*?\?/, '')
                    // and remove any existing hash string (thanks, @vrijdenker)
                    .replace(/#.*$/, '')
                    .split('&');

                for (var i = 0, l = query.length; i < l; i++) {
                    var aux = decodeURIComponent(query[i]).split('=');
                    $_GET[aux[0]] = aux[1];
                }
                //get the 'index' query parameter
            }
            if ($_GET['destVal']) {
                dest.value = $_GET['destVal'];
            }
            dest.addEventListener("change", function() {
                dest.value = null;
                $(".pac-container").remove();
            });
            dest.addEventListener("keydown", function(event) {
                var KeyID = event.keyCode;
                new google.maps.places.Autocomplete(dest);
                if (event.keyCode == 8 && event.repeat) {
                    dest.value = " ";
                }

            });
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(dest);
            if (!($_GET['destVal'])) {
                new google.maps.places.Autocomplete(dest);
            }
        }

        function clear() {
            clearMarkers();
            markers = [];
            document.getElementById("places").innerHTML = "";
            document.getElementById("panel").innerHTML = "";
            document.getElementById("total").innerHTML = "";
            placeListND = [];
            waypoint = [];
        }

        function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        function distanceTwoPoints(p3, p4) {
            return (google.maps.geometry.spherical.computeDistanceBetween(p3, p4) / 1000); //dividing by 1000 to get Kilometers
        }

        function sortListByDistance() {
            var list, switching, b, shouldSwitch;
            var regex = /[+]?\d+(\.\d+)?/g;
            list = document.getElementById("places");
            switching = true;
            while (switching) {
                switching = false;
                b = list.getElementsByTagName("LI");
                console.log(b.length - 1);
                for (var i = 0; i < (b.length - 1); i++) {
                    shouldSwitch = false;
                    let umm = b[i].innerHTML.toString();
                    let umm2 = b[i + 1].innerHTML.toString();
                    let um = umm.substring(umm.indexOf("<br>"));
                    let um2 = umm2.substring(umm2.indexOf("<br>"));
                    let floats = um.match(regex).map(function(v) {
                        return parseFloat(v);
                    })
                    let floats2 = um2.match(regex).map(function(v) {
                        return parseFloat(v);
                    })
                    if (floats[0] > floats2[0]) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    b[i].parentNode.insertBefore(b[i + 1], b[i]);
                    switching = true;
                }
            }
        }

        function sortListAlphabetically() {
            var list, i, switching, b, shouldSwitch;
            list = document.getElementById("places");
            switching = true;
            while (switching) {
                switching = false;
                b = list.getElementsByTagName("LI");
                for (i = 0; i < (b.length - 1); i++) {
                    shouldSwitch = false;
                    if (b[i].innerHTML.toLowerCase() > b[i + 1].innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    b[i].parentNode.insertBefore(b[i + 1], b[i]);
                    switching = true;
                }
            }
        }

        function addPlaces(places, map, pos, latlng2) { //shows places fetched from nearby search on map/li element
            const infowindow = new google.maps.InfoWindow();
            var photoElement;
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
                    if (iconExists.includes(place.types[1], 0) && !(d.getHours() >= 6 && d.getHours() <= 18)) {
                        //image.url = "../icons/" + place.types[1] + ".png"; //uncomment this line and below line for custom icons! modify "d" checks for day-mode icon switching.
                    } else if ((place.types[1].includes("store") || place.types[1].includes("grocery")) && !(d.getHours() >= 6 && d.getHours() <= 18)) {
                        //image.url = "../icons/store.png";
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
                        var br = document.createElement("br");
                        var s = document.createElement("SUB");
                        var distanceFromYou = (distanceTwoPoints(pos, place.geometry.location) * 0.621371).toFixed(1);
                        li.textContent = place.name;
                        var t = document.createTextNode(" " + distanceFromYou + " miles away");
                        var x = document.createElement("SUB");
                        x.appendChild(t);
                        li.appendChild(br);
                        li.appendChild(x);
                        placesList.appendChild(li);
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
                                var photos = place?.photos;
                                if (photos) {
                                    photoElement = document.createElement("img");
                                    photoElement.src = photos[0]?.getUrl({
                                        maxWidth: 1500,
                                        maxHeight: 400
                                    })
                                    //content.appendChild(photoElement);
                                }
                                const placeAddressElement = document.createElement("p");
                                placeAddressElement.textContent = results[0].formatted_address;
                                content.appendChild(placeAddressElement);
                                const placePhoneNumber = document.createElement("p");
                                const placeRating = document.createElement("p");
                                const placeOpen = document.createElement("p");
                                const placeWebsite = document.createElement("a");
                                const dir2 = document.createElement("button");
                                const moreInfo = document.createElement("a");
                                const openInMaps = document.createElement("a");
                                const b1 = document.createElement("br");
                                const b2 = document.createElement("br");
                                service.getDetails(request, callback);

                                function callback(place, status) {
                                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                                        placePhoneNumber.textContent = place.formatted_phone_number;
                                        if (place.rating) {
                                            placeRating.textContent = place.rating + "/5";
                                        } else {
                                            placeRating.textContent = "No Rating";
                                        }
                                        placeOpen.textContent = place?.opening_hours?.open_now ? "Open" : "Closed";
                                        placeWebsite.textContent = "Website";
                                        placeWebsite.href = place.website;
                                        placeWebsite.target = "_blank";
                                        placeWebsite.appendChild(document.createElement("br"));
                                        placeWebsite.appendChild(document.createElement("br"));
                                    }
                                    moreInfo.textContent = "More Info";
                                    moreInfo.href = "https://donttrip.technologists.cloud/donttrip/client/place" +
                                        "?rating=" +
                                        place.rating +
                                        "&name=" +
                                        (place.name).replace("&", "and") +
                                        "&dist=" +
                                        (distanceTwoPoints(pos, place.geometry.location) * 0.621371).toFixed(1) +
                                        "&phone=" +
                                        place.formatted_phone_number +
                                        "&status=" +
                                        placeOpen?.textContent +
                                        "&week=" +
                                        place.opening_hours?.weekday_text +
                                        "&website=" +
                                        encodeURIComponent(placeWebsite.href) +
                                        "&address=" +
                                        results[0].formatted_address +
                                        "&photo=" +
                                        encodeURIComponent(photoElement?.src);
                                    moreInfo.target = "_blank";
                                    moreInfo.appendChild(document.createElement("br"));
                                    moreInfo.appendChild(document.createElement("br"));
                                }
                                openInMaps.textContent = "View in Google Maps";
                                openInMaps.href = "https://www.google.com/maps/search/?api=1&query=" + place.geometry.location.lat() + "%2C" + place.geometry.location.lng() + "&query_place_id=" + place.place_id;
                                openInMaps.target = "_blank";
                                openInMaps.appendChild(document.createElement("br"));
                                openInMaps.appendChild(document.createElement("br"));
                                content.appendChild(placeOpen);
                                content.appendChild(placeRating);
                                //content.appendChild(placePhoneNumber);
                                //content.appendChild(placeWebsite);
                                content.appendChild(moreInfo);
                                service = new google.maps.places.PlacesService(map);
                                dir2.textContent = "Add to Route";
                                if (!addedWaypoint.includes(place.place_id)) {
                                    content.appendChild(dir2);
                                    content.appendChild(b1);
                                    content.appendChild(b2);
                                }

                                content.appendChild(openInMaps);
                                dir2.addEventListener("click", () => { //add marker to waypoint along route onClick, can be cleared!
                                    content.removeChild(dir2);
                                    content.removeChild(b1);
                                    content.removeChild(b2);
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
                                    waypoint.push({
                                        location: place.geometry.location,
                                        stopover: true,
                                    });
                                    addedWaypoint.push(place.place_id);
                                    console.log(addedWaypoint);
                                    console.log(waypoint);
                                    var request = {
                                        origin: pos,
                                        destination: latlng2,
                                        waypoints: waypoint,
                                        travelMode: "DRIVING",
                                        avoidTolls: true,
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

        function handleLocationError(browserHasGeolocation, infoWindow, pos) { //geolocation fetching error handler
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation."
            );
            infoWindow.open(map);
        }

        /*
        function displayRoute(origin, destination, service, display) { //function to display a route origin->dest
            service.route({
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING,
                    avoidTolls: true,
                })
                .then(
                    (result) => {
                        display.setDirections(result);
                    }
                )
                .catch((e) => {
                    alert("Please enter a desination you can drive to.");
                });
        }
		*/

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
            document.getElementById('api_key').value;
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