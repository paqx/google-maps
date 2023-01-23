/**
 * Variable used to draw the map
 * 
 * @type google.maps.Map
 */
var map;

/**
 * Array that stores information about saved markers
 * 
 * @type Array
 */
var markers = [];

/**
 * This variable stores information about a new marker that was drawn on the map, 
 * but not saved permanently yet
 * 
 * @type google.maps.Marker
 */
var newMarker = null;

/**
 * Initialize the map and draw all permanently saved markers
 * 
 * @returns {undefined}
 */
function initMap() {
	const defaultLatlng = { lat: 55.76, lng: 37.59 };

	map = new google.maps.Map(document.getElementById("map"), {
		zoom: 8,
		center: defaultLatlng
	});
	
	//var marker;
	//var latLng;
	
	drawAllSavedMarkers();
	
	map.addListener("click", (event) => {
		drawNewMarker(event.latLng);
	});
}

/**
 * Draw all permanently saved markers
 * 
 * @returns {undefined}
 */
function drawAllSavedMarkers() {
	jsonMarkers.forEach(function(jsonMarker) {
		latLng = {
			lat: Number(jsonMarker.lat),
			lng: Number(jsonMarker.lng)
		}
		drawMarker(latLng, jsonMarker.icon, jsonMarker.comment);
	});
}

/**
 * Clear the map canvas from all drawn markers
 * 
 * @returns {undefined}
 */
function removeAllMarkers() {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}

/**
 * Draw a single marker on the map canvas
 * 
 * @param {type} latLng
 * @param {type} icon
 * @param {type} comment
 * @returns {undefined}
 */
function drawMarker(latLng, icon, comment) {
	const marker = new google.maps.Marker({
		position: latLng,
		map,
		label: {
			text: String.fromCharCode(parseInt(icon, 16)),
			fontFamily: "Material Icons",
			color: "#ffffff",
			fontSize: "18px",
		},
		title: comment,
	});
	markers.push(marker);
}

/**
 * Draw a temporary marker that can be saved permanently
 * 
 * @param {type} latLng
 * @returns {undefined}
 */
function drawNewMarker(latLng) {
	if (newMarker != null) {
		newMarker.setMap(null);
	}
	
	newMarker = new google.maps.Marker({
		position: latLng,
		map
	});
	
	document.getElementById("latitude").value = latLng.lat();
	document.getElementById("longitude").value = latLng.lng();
}

$(document).ready(function() {
	window.initMap = initMap;
	$("#messages").hide();

	$("#addMarker").submit(function(event) {
		event.preventDefault();
		
		var form = $(this);
		var actionUrl = form.attr('action');
		
		$.ajax({
			type: "POST",
			url: actionUrl,
			data: form.serialize(), 
			success: function(response) {
				console.log(response);
				
				if (response.status == "success") {
					newMarker.setMap(null);
					removeAllMarkers();
					jsonMarkers = response.jsonMarkers;
					drawAllSavedMarkers();
					
					$("#comment").val('');
					$("#latitude").val('');
					$("#longitude").val('');
					
					$("#messages").removeClass("alert-danger");
					$("#messages").addClass("alert-success");
					$("#messages").html("Marker successfully saved");
					$("#messages").show();
				}
				if (response.status == "error") {
					var errors;
					
					errors = '<ul class="mb-0">';
					response.messages.forEach(function(message) {
						errors += "<li>" + message + "</li>";
					});
					errors += "</ul>";
					
					$("#messages").removeClass("alert-success");
					$("#messages").addClass("alert-danger");
					$("#messages").html(errors);
					$("#messages").show();
				}
			}
    	});	
	});
});