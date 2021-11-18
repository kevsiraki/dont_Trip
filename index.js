let map, infoWindow;

function initMap() {
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
			},
			{
				"featureType": "poi",
				"stylers": [{
					"visibility": "off"
				}]
			},
			{
				elementType: "labels.text.stroke",
				stylers: [{
					color: "#242f3e"
				}]
			},
			{
				elementType: "labels.text.fill",
				stylers: [{
					color: "#746855"
				}]
			},
			{
				featureType: "administrative.locality",
				elementType: "labels.text.fill",
				stylers: [{
					color: "#d59563"
				}],
			},
			{
				featureType: "poi",
				elementType: "labels.text.fill",
				stylers: [{
					color: "#d59563"
				}],
			},
			{
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
			},
			{
				featureType: "road",
				elementType: "geometry",
				stylers: [{
					color: "#38414e"
				}],
			},
			{
				featureType: "road",
				elementType: "geometry.stroke",
				stylers: [{
					color: "#212a37"
				}],
			},
			{
				featureType: "road",
				elementType: "labels.text.fill",
				stylers: [{
					color: "#9ca5b3"
				}],
			},
			{
				featureType: "road.highway",
				elementType: "geometry",
				stylers: [{
					color: "#746855"
				}],
			},
			{
				featureType: "road.highway",
				elementType: "geometry.stroke",
				stylers: [{
					color: "#1f2835"
				}],
			},
			{
				featureType: "road.highway",
				elementType: "labels.text.fill",
				stylers: [{
					color: "#f3d19c"
				}],
			},
			{
				featureType: "transit",
				elementType: "geometry",
				stylers: [{
					color: "#2f3948"
				}],
			},
			{
				featureType: "transit.station",
				elementType: "labels.text.fill",
				stylers: [{
					color: "#d59563"
				}],
			},
			{
				featureType: "water",
				elementType: "geometry",
				stylers: [{
					color: "#17263c"
				}],
			},
			{
				featureType: "water",
				elementType: "labels.text.fill",
				stylers: [{
					color: "#515c6d"
				}],
			},
			{
				featureType: "water",
				elementType: "labels.text.stroke",
				stylers: [{
					color: "#17263c"
				}],
			},
		],
	});

	infoWindow = new google.maps.InfoWindow();
	const locationButton = document.createElement("button");

	locationButton.textContent = "Go to Destination";
	locationButton.classList.add("custom-map-control-button");
	map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

	const dest = document.createElement("input");
	dest.type = "text";
	dest.id = "myInput";
	dest.placeholder = "Destination";
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(dest);
	var total;
	locationButton.addEventListener("click", () => {

		// Try HTML5 geolocation.
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(
				(position) => {
					const pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude,
					};
					const directionsService = new google.maps.DirectionsService();
					const service = new google.maps.places.PlacesService(map);
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
							//directionsService.setMap(null);
							clearMarkers();
							document.getElementById("places").innerHTML = "";
							document.getElementById("panel").innerHTML = "";
							for (var i = 0; i < rectangles.length; i++) {
								rectangles[i].setMap(null);
							}
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
					//array of rects to remove;
					var rectangles = [];
					var circles = [];
					//Function to covert destination string to Latitude and Longitude
					var address = document.getElementById("myInput").value;
					geocoder.geocode({
						'address': address
					}, function(results, status) {
						
						var typeParam = "restaurant";
						var keywordParam = "african";
						
						if (status == google.maps.GeocoderStatus.OK) {
							var latlng2 = results[0].geometry.location;
							var bounds = new google.maps.LatLngBounds();
							bounds.extend(latlng);
							bounds.extend(latlng2);
							map.fitBounds(bounds);
							var rectangle = new google.maps.Rectangle({
								map: map,
								bounds: bounds
							});
							
							/*
							var line = new google.maps.Polyline({
							path: [latlng, latlng2],
							geodesic: true,
							strokeColor: '#FF0000'
							});
							line.setMap(map);
							
							
							var len = google.maps.geometry.spherical.computeLength(line.getPath())/1000;
							for(var i = 0; i<len;i++) {
								 circles[i] = new google.maps.Circle({
									strokeColor: "#FF0000",
									strokeOpacity: 0.8,
									strokeWeight: 2,
									fillColor: "#FF0000",
									fillOpacity: 0.35,
									map: map,
									center: {
										lat: (latlng.lat) + (Math.sign(latlng2.lat()-latlng.lat )*i/135),
										lng: (latlng.lng) + (Math.sign(latlng2.lng()-latlng.lng )*i/135)
									},
									radius: 100
									});
							
									service.nearbySearch({
									location: circles[i].center,
									radius: 100,
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);
							}
							
							console.log(len);
							*/
							
							//rectangle from origin to middle
							var boundsToMid = new google.maps.LatLngBounds();
							boundsToMid.extend(latlng);
							boundsToMid.extend(bounds.getCenter());
							var rectangleToMid = new google.maps.Rectangle({
								map: map,
								bounds: boundsToMid
							});
							//rectangle from middle to destination
							var boundsToDest = new google.maps.LatLngBounds();
							boundsToDest.extend(latlng2);
							boundsToDest.extend(bounds.getCenter());
							var rectangleToDest = new google.maps.Rectangle({
								map: map,
								bounds: boundsToDest
							});
							var bounds = rectangle.getBounds();
							var NE = bounds.getNorthEast();
							var SW = bounds.getSouthWest();
							// North West
							var NW = new google.maps.LatLng(NE.lat(), SW.lng());
							// South East
							var SE = new google.maps.LatLng(SW.lat(), NE.lng());

							//rectangle from middle to NE
							var boundsToNE = new google.maps.LatLngBounds();
							boundsToNE.extend(NE);
							boundsToNE.extend(bounds.getCenter());
							var rectangleToNE = new google.maps.Rectangle({
								map: map,
								bounds: boundsToNE
							});

							//rectangle from middle to SW
							var boundsToSW = new google.maps.LatLngBounds();
							boundsToSW.extend(SW);
							boundsToSW.extend(bounds.getCenter());
							var rectangleToSW = new google.maps.Rectangle({
								map: map,
								bounds: boundsToSW
							});

							
							rectangles.push(rectangle);
							rectangles.push(rectangleToMid);
							rectangles.push(rectangleToDest);
							rectangles.push(rectangleToNE);
							rectangles.push(rectangleToSW);
							
							//NEARBY SEARCHES SPLIT UP
							
							// Perform a nearby search to DEST.
							service.nearbySearch({
									location: latlng,
									radius: 1000,
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);

							// Perform a nearby search to DEST.
							service.nearbySearch({
									location: latlng2,
									radius: 1000,
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}

								}
							);

							// Perform a nearby search to ROUTE.
							service.nearbySearch({
									bounds: rectangleToNE.getBounds(),
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);
							// Perform a nearby search to ROUTE.
							service.nearbySearch({
									bounds: rectangleToSW.getBounds(),
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);

							// Perform a nearby search to ROUTE.
							service.nearbySearch({
									bounds: rectangle.getBounds(),
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);

							// Perform a nearby search to origin.
							service.nearbySearch({
									bounds: rectangleToMid.getBounds(),
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);

							// Perform a nearby search to DEST.
							service.nearbySearch({
									bounds: rectangleToDest.getBounds(),
									type: typeParam,
									keyword: keywordParam
								},
								(results, status, pagination) => {
									if (status !== "OK" || !results) return;
									addPlaces(results, map);
									moreButton.disabled = !pagination || !pagination.hasNextPage;
									if (pagination && pagination.hasNextPage) {
										getNextPage = () => {
											// Note: nextPage will call the same handler function as the initial call
											pagination.nextPage();
										};
									}
								}
							);
						}
					});
				},
				() => {
					handleLocationError(true, infoWindow, map.getCenter());
				}
			);
		} else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
		}
	});
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
	infoWindow.setPosition(pos);
	infoWindow.setContent(
		browserHasGeolocation ?
		"Error: The Geolocation service failed." :
		"Error: Your browser doesn't support geolocation."
	);
	infoWindow.open(map);
}

function displayRoute(origin, destination, service, display) {
	service
		.route({
			origin: origin,
			destination: destination,
			waypoints: [{
					location: origin
				},
				{
					location: destination
				},
			],
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



function computeTotalDistance(result) {
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
	document.getElementById("total").innerHTML = total + " km";
}

var markers = [];

function addPlaces(places, map) {
	var placesList = document.getElementById("places");
	var i = 0;
	for (const place of places) {

		if (place.geometry && place.geometry.location) {
			const image = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25),
			};

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
			li.addEventListener("click", () => {
				map.setCenter(place.geometry.location);
			});
		}
		i++;
	}
	//placesList = '';
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

