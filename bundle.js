(function() { //requiring some browserify JSON functions
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
		let waypoints = [];
		let map;
		let service;
		const locationButton = document.createElement("button");
		const clearCurrent = document.createElement("button");

		function initMap() { //set up the map/styles, initial route from geolocation to endpoint, places along the route, and initial directions.
			var directionsService = new window.google.maps.DirectionsService();
			var directionsRenderer = new window.google.maps.DirectionsRenderer();
			var i = 0;
			map = new google.maps.Map(document.getElementById("map"), {
				center: {
					lat: 36.778259,
					lng: -119.417931
				},
				zoom: 6,
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
					},
					{
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
					},
				],
			});

			infoWindow = new google.maps.InfoWindow();

			locationButton.textContent = "Go"; //buttons and inputs 
			locationButton.classList.add("custom-map-control-button");
			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(locationButton);

			clearCurrent.type = "submit";
			clearCurrent.textContent = "Change of Plans!";
			clearCurrent.id = "clearCurrent";
			map.controls[google.maps.ControlPosition.RIGHT].push(clearCurrent);

			const dest = document.createElement("input");
			dest.type = "text";
			dest.id = "myInput";
			dest.placeholder = "Destination";
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(dest);

			const typeIn = document.createElement("input");
			typeIn.type = "text";
			typeIn.id = "typeIn";
			typeIn.placeholder = "Type of POI";
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(typeIn);

			const keywordIn = document.createElement("input");
			keywordIn.type = "text";
			keywordIn.id = "keywordIn";
			keywordIn.placeholder = "Keywords";
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(keywordIn);

			const rad = document.createElement("input");
			rad.type = "text";
			rad.id = "rad";
			rad.placeholder = "Radius";
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(rad);

			locationButton.addEventListener("click", () => {
				// Try HTML5 geolocation.
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(
						(position) => {
							const pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude,
							};
							const directionsService = new google.maps.DirectionsService(); //init directions services here.
							var service = new google.maps.places.PlacesService(map);
							const directionsRenderer = new google.maps.DirectionsRenderer({
								draggable: true,
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
									clearMarkers();
									document.getElementById("places").innerHTML = "";
									document.getElementById("panel").innerHTML = "";
								});
							});
							const geocoder = new google.maps.Geocoder();
							const latlng = {
								lat: parseFloat(pos.lat),
								lng: parseFloat(pos.lng)
							};
							displayRoute(
								latlng,
								document.getElementById("myInput").value,
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
							var waypoints = new Array();
							//Function to covert destination string to Latitude and Longitude
							var address = document.getElementById("myInput").value;
							geocoder.geocode({
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
													radius: document.getElementById("rad").value,
													type: document.getElementById("typeIn").value,
													keyword: document.getElementById("keywordIn").value
												},
												(results, status, pagination) => {
													if (status !== "OK" || !results) return;
													addPlaces(results, map, pos, latlng2);
													moreButton.disabled = !pagination || !pagination.hasNextPage;
													if (pagination && pagination.hasNextPage) {
														getNextPage = () => {
															// Note: nextPage will call the same handler function as the initial call
															pagination.nextPage();
														};
													}
												},
												callback);

											function callback(results, status) { //unused for now, callback function after request complete.
												if (status == google.maps.places.PlacesServiceStatus.OK) {
													for (var i = 0; i < results.length; i++) {
														if (google.maps.geometry.poly.containsLocation(results[i].geometry.location, PolygonBound) == true) {
															new google.maps.Marker({
																position: results[i].geometry.location,
																map,
																title: "Test" 
															});

														}
													}
												}
											}

										}
									});
									directionsRenderer.setMap(map);
								}
							});
						});
				}
			});
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
			let total = 0;
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

		var markers = []; //for nuking markers on a new mapload
		const iconExists = ["bar", "food", "park", "place_of_worship", "point_of_interest", "restaurant", "store"]; //mimicking google's json to display custom icons

		function addPlaces(places, map, pos, latlng2) { //shows places fetched from nearby search on map/li element
			var placesList = document.getElementById("places");
			for (const place of places) {
				if (place.geometry && place.geometry.location) {
					var image = {
						url: place.icon,
						size: new google.maps.Size(71, 71),
						origin: new google.maps.Point(0, 0),
						anchor: new google.maps.Point(17, 34),
						scaledSize: new google.maps.Size(25, 25),
					};
					if (iconExists.includes(place.types[1], 0)) {
						image.url = "icons/" + place.types[1] + ".png";

					} else if (place.types[1].includes("store") || place.types[1].includes("grocery")) {
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
					li.textContent = place.name;
					placesList.appendChild(li);
					marker.addListener("click", () => { //add marker to waypoint along route onClick, can be cleared!
						document.getElementById("panel").innerHTML = "";
						const directionsService = new google.maps.DirectionsService();
						var service = new google.maps.places.PlacesService(map);
						const directionsRenderer = new google.maps.DirectionsRenderer({
							draggable: true,
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
								clearMarkers();
								document.getElementById("places").innerHTML = "";
								document.getElementById("panel").innerHTML = "";
							});
							clearCurrent.addEventListener("click", () => {
								directionsRenderer.setMap(null);

								document.getElementById("panel").innerHTML = "";
								var request = {
									origin: pos,
									destination: latlng2,
									travelMode: "DRIVING",
								};
								directionsService.route(request, function(result, status) {
									if (status == "OK") {
										directionsRenderer.setDirections(result);
									}
								});
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
						});;
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
					li.addEventListener("click", () => {
						map.setCenter(place.geometry.location);
						map.setZoom(20);
					});
				}
			}
		}

		// Sets the map on all markers in the array.
		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}

		// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
			setMapOnAll(null);
		}

		function PolygonPoints() { //split a polygon into points
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
	3: [function(require, module, exports) { //requiring some more browserify JSON functions
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