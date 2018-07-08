function runcrew_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof RUNCREW_STORAGE['googlemap_init_obj'] == 'undefined') runcrew_googlemap_init_styles();
	RUNCREW_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		RUNCREW_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: RUNCREW_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		runcrew_googlemap_create(id);

	} catch (e) {
		
		dcl(RUNCREW_STORAGE['strings']['googlemap_not_avail']);

	};
}

function runcrew_googlemap_create(id) {
	"use strict";

	// Create map
	RUNCREW_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(RUNCREW_STORAGE['googlemap_init_obj'][id].dom, RUNCREW_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in RUNCREW_STORAGE['googlemap_init_obj'][id].markers)
		RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	runcrew_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (RUNCREW_STORAGE['googlemap_init_obj'][id].map)
			RUNCREW_STORAGE['googlemap_init_obj'][id].map.setCenter(RUNCREW_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function runcrew_googlemap_add_markers(id) {
	"use strict";
	for (var i in RUNCREW_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (RUNCREW_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (RUNCREW_STORAGE['googlemap_init_obj'].geocoder == '') RUNCREW_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			RUNCREW_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			RUNCREW_STORAGE['googlemap_init_obj'].geocoder.geocode({address: RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = RUNCREW_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						RUNCREW_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						RUNCREW_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					RUNCREW_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						runcrew_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(RUNCREW_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: RUNCREW_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].title;
			RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (RUNCREW_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				RUNCREW_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				RUNCREW_STORAGE['googlemap_init_obj'][id].map.setCenter(RUNCREW_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in RUNCREW_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								RUNCREW_STORAGE['googlemap_init_obj'][id].map,
								RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			RUNCREW_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function runcrew_googlemap_refresh() {
	"use strict";
	for (id in RUNCREW_STORAGE['googlemap_init_obj']) {
		runcrew_googlemap_create(id);
	}
}

function runcrew_googlemap_init_styles() {
	// Init Google map
	RUNCREW_STORAGE['googlemap_init_obj'] = {};
	RUNCREW_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.runcrew_theme_googlemap_styles!==undefined)
		RUNCREW_STORAGE['googlemap_styles'] = runcrew_theme_googlemap_styles(RUNCREW_STORAGE['googlemap_styles']);
}