(function($){
	$(document).ready(function() {

		$("#location-map").keydown(function (e) { if(e.which == 13) e.preventDefault(); });

		//google map custom marker icon - .png fallback for IE11
		var is_internetExplorer11= navigator.userAgent.toLowerCase().indexOf('trident') > -1;
		var marker_url = ( is_internetExplorer11 ) ? location_obj.img_url + 'cd-icon-location.png' : location_obj.img_url + 'cd-icon-location.svg';
		//define the basic color of your map, plus a value for saturation and brightness
		
		var	main_color = '#2d313f',
			saturation_value= -20,
			brightness_value= 5;

		//we define here the style of the map
		var style= [ 
			{
				//set saturation for the labels on the map
				elementType: "labels",
				stylers: [
					{saturation: saturation_value}
				]
			},  
		    {	//poi stands for point of interest - don't show these lables on the map 
				featureType: "poi",
				elementType: "labels",
				stylers: [
					{visibility: "off"}
				]
			},
			{
				//don't show highways lables on the map
		        featureType: 'road.highway',
		        elementType: 'labels',
		        stylers: [
		            {visibility: "off"}
		        ]
		    }, 
			{ 	
				//don't show local road lables on the map
				featureType: "road.local", 
				elementType: "labels.icon", 
				stylers: [
					{visibility: "off"} 
				] 
			},
			{ 
				//don't show arterial road lables on the map
				featureType: "road.arterial", 
				elementType: "labels.icon", 
				stylers: [
					{visibility: "off"}
				] 
			},
			{
				//don't show road lables on the map
				featureType: "road",
				elementType: "geometry.stroke",
				stylers: [
					{visibility: "off"}
				]
			}, 
			//style different elements on the map
			{ 
				featureType: "transit", 
				elementType: "geometry.fill", 
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			}, 
			{
				featureType: "poi",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "poi.government",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "poi.sport_complex",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "poi.attraction",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "poi.business",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "transit",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "transit.station",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "landscape",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
				
			},
			{
				featureType: "road",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			},
			{
				featureType: "road.highway",
				elementType: "geometry.fill",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			}, 
			{
				featureType: "water",
				elementType: "geometry",
				stylers: [
					{ hue: main_color },
					{ visibility: "on" }, 
					{ lightness: brightness_value }, 
					{ saturation: saturation_value }
				]
			}
		];

		var modalCloseBtn = $('#modalDismiss');
		var modalWrapper  = $('.download-app');
		var geocoder = new google.maps.Geocoder();
		var marker = null;
		var map = null;

		$('#formAddress').on('keyup keypress', function(e) {
			var code = e.keyCode || e.which;
			if (code == 13) { 
				e.preventDefault();
				return false;
			}
		});

		modalCloseBtn.on('click', function(e) {
			modalWrapper.css('display', 'none');

			e.preventDefault();
		});

		function initialize() {
			var $latitude = document.getElementById('latitude');
			var $longitude = document.getElementById('longitude');
			var latitude = -7.72711716283;
			var longitude = 110.40847454603272;
			var zoom = 16;

			$.get( 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + ',' + longitude, function( data ) {
				var address = (document.getElementById('location-map'));
				address.value = data.results[0].formatted_address;
			});

			var LatLng = new google.maps.LatLng(latitude, longitude);

			var mapOptions = {
				zoom: zoom,
				center: LatLng,
				panControl: false,
				zoomControl: false,
				scaleControl: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles: style,
			};

			map = new google.maps.Map(document.getElementById('map'), mapOptions);
			if (marker && marker.getMap) marker.setMap(map);
			marker = new google.maps.Marker({
				position: LatLng,
				map: map,
				title: 'Drag Me!',
				draggable: true,
				icon: marker_url,
			});

			var infowindow = new google.maps.InfoWindow({
			    content: 'contentString'
			  });

			google.maps.event.addListener(marker, 'dragend', function(marker) {
				var latLng = marker.latLng;
				alert('lat : '+ latLng.lat() + ', lang : ' + latLng.lng() );
				console.log(latLng);
				$latitude.value = latLng.lat();
				$longitude.value = latLng.lng();
			});

			var address = (document.getElementById('location-map'));
			var autocomplete = new google.maps.places.Autocomplete(address);
			autocomplete.setTypes(['geocode']);
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();
				find();
				if (!place.geometry) {
					return;
				}
			});
		}

		initialize();

		setTimeout(getLocation, 3000);

		$('#findbutton').click(function (e) {
			find();
			e.preventDefault();
		});

		function find() {
			var address = $("#location-map").val();
			geocoder.geocode({ 'address': address }, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					marker.setPosition(results[0].geometry.location);

					$(latitude).val(marker.getPosition().lat());
					$(longitude).val(marker.getPosition().lng());
				} else {
					console.log("Geocode was not successful for the following reason: " + status);
				}
			});
		}

		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} else {
				console.log("Geolocation is not supported by this browser.");
			}
		}

		function showPosition(position) {
			map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));

			var $latitude = document.getElementById('latitude');
			var $longitude = document.getElementById('longitude');

			$latitude.value = position.coords.latitude;
			$longitude.value = position.coords.longitude;

			$.get( 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + position.coords.latitude + ',' + position.coords.longitude, function( data ) {
				var address = (document.getElementById('location-map'));
				address.value = data.results[0].formatted_address;

				marker.setMap(null);

				marker = new google.maps.Marker({
					position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
					map: map,
					animation: google.maps.Animation.DROP,
					title: 'Your location',
					draggable: true,
					icon: marker_url,
				});

				google.maps.event.addListener(marker, 'dragend', function(marker) {
					var latLng = marker.latLng;

					alert('lat : '+ latLng.lat() + ', lang : ' + latLng.lng() );
					console.log(latLng);

					$latitude.value = latLng.lat();
					$longitude.value = latLng.lng();
				});
			});
		}
	});
})(jQuery);