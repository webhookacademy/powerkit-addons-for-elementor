/* global google */
window.pkaeGmapQueue = window.pkaeGmapQueue || [];

window.pkaeGmapInit = function () {
  window.pkaeGmapQueue.forEach(function (mapId) {
    pkaeInitMap(mapId);
  });
  window.pkaeGmapQueue = [];
  window.pkaeGmapReady = true;
};

function pkaeInitMap(mapId) {
  var el = document.getElementById(mapId);
  if (!el || typeof google === 'undefined') return;

  var config = {};
  try { config = JSON.parse(el.getAttribute('data-config') || '{}'); } catch (e) {}

  var markers  = config.markers || [];
  var styleJson = [];
  if (config.styleJson) {
    try { styleJson = JSON.parse(config.styleJson); } catch (e) {}
  }

  // Default center: first latlng marker or fallback
  var defaultCenter = { lat: 40.7128, lng: -74.0060 };
  var firstLatLng = markers.find(function (m) { return m.type === 'latlng' && m.lat && m.lng; });
  if (firstLatLng) {
    defaultCenter = { lat: parseFloat(firstLatLng.lat), lng: parseFloat(firstLatLng.lng) };
  }

  var mapOptions = {
    zoom:               config.zoom || 14,
    center:             defaultCenter,
    mapTypeId:          config.mapType || 'roadmap',
    zoomControl:        !!config.zoomControl,
    mapTypeControl:     !!config.mapTypeControl,
    streetViewControl:  !!config.streetView,
    fullscreenControl:  !!config.fullscreen,
    scrollwheel:        !!config.scrollWheel,
    draggable:          config.draggable !== false,
    styles:             styleJson,
  };

  var map = new google.maps.Map(el, mapOptions);
  var geocoder = new google.maps.Geocoder();
  var infoWindow = new google.maps.InfoWindow();

  markers.forEach(function (m) {
    if (m.type === 'latlng' && m.lat && m.lng) {
      pkaeAddMarker(map, infoWindow, { lat: parseFloat(m.lat), lng: parseFloat(m.lng) }, m);
    } else if (m.type === 'address' && m.address) {
      geocoder.geocode({ address: m.address }, function (results, status) {
        if (status === 'OK') {
          pkaeAddMarker(map, infoWindow, results[0].geometry.location, m);
          // Center map on first address marker
          if (markers.indexOf(m) === 0 && !firstLatLng) {
            map.setCenter(results[0].geometry.location);
          }
        }
      });
    }
  });
}

function pkaeAddMarker(map, infoWindow, position, m) {
  var markerOpts = { map: map, position: position, title: m.title || '' };

  if (m.icon) {
    markerOpts.icon = {
      url: m.icon,
      scaledSize: new google.maps.Size(m.icon_size || 40, m.icon_size || 40),
    };
  }

  var marker = new google.maps.Marker(markerOpts);

  if (m.title || m.desc) {
    marker.addListener('click', function () {
      var content = '<div class="pkae-gmap-info">';
      if (m.title) content += '<strong>' + m.title + '</strong>';
      if (m.desc)  content += '<p>' + m.desc + '</p>';
      content += '</div>';
      infoWindow.setContent(content);
      infoWindow.open(map, marker);
    });
  }
}

// Elementor editor re-init
if (typeof window.elementorFrontend !== 'undefined') {
  window.elementorFrontend.hooks.addAction('frontend/element_ready/pkae-google-map.default', function ($scope) {
    var el = $scope[0].querySelector('.pkae-gmap__canvas');
    if (!el) return;
    if (window.pkaeGmapReady) {
      pkaeInitMap(el.id);
    } else {
      window.pkaeGmapQueue.push(el.id);
    }
  });
}
