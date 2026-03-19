/* ===============================
   Google Maps – Map Listing
   Refactored with GPS blocking
   FinPharma Ready
================================ */

/* ===============================
   GLOBALS
================================ */

var mapObject;
var markers = {};
var marker;

/* ===============================
   GPS HANDLER (BLOCKING)
================================ */

function getUserGPS() {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      reject("Geolocation not supported");
      return;
    }

    navigator.geolocation.getCurrentPosition(
      (position) => {
        resolve({
          lat: position.coords.latitude,
          lng: position.coords.longitude
        });
      },
      (error) => reject(error),
      {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
      }
    );
  });
}

/* ===============================
   MAP OPTIONS (DEFAULT)
================================ */

var mapOptions = {
  zoom: 12,
  center: null, // set dynamically
  mapTypeId: google.maps.MapTypeId.ROADMAP,
  mapTypeControl: false,
  panControl: false,
  zoomControl: true,
  scrollwheel: false,
  scaleControl: false,
  streetViewControl: true,

  zoomControlOptions: {
    style: google.maps.ZoomControlStyle.LARGE,
    position: google.maps.ControlPosition.RIGHT_BOTTOM
  },

  streetViewControlOptions: {
    position: google.maps.ControlPosition.RIGHT_BOTTOM
  },

  styles: [
    {
      featureType: "landscape",
      stylers: [{ hue: "#FFBB00" }, { saturation: 43.4 }, { lightness: 37.6 }]
    },
    {
      featureType: "road.highway",
      stylers: [{ hue: "#FFC200" }, { saturation: -61.8 }, { lightness: 45.6 }]
    },
    {
      featureType: "road.arterial",
      stylers: [{ hue: "#FF0300" }, { saturation: -100 }, { lightness: 51.2 }]
    },
    {
      featureType: "road.local",
      stylers: [{ hue: "#FF0300" }, { saturation: -100 }, { lightness: 52 }]
    },
    {
      featureType: "water",
      stylers: [{ hue: "#0078FF" }, { saturation: -13.2 }, { lightness: 2.4 }]
    },
    {
      featureType: "poi",
      stylers: [{ hue: "#00FF6A" }, { saturation: -1.1 }, { lightness: 11.2 }]
    }
  ]
};

/* ===============================
   INIT MAP (GPS BLOCKING)
================================ */

async function initMapListing() {
  try {
    // ⛔ Block until GPS is available
    const userLocation = await getUserGPS();

    mapOptions.center = new google.maps.LatLng(
      userLocation.lat,
      userLocation.lng
    );

    mapObject = new google.maps.Map(
      document.getElementById("map_listing"),
      mapOptions
    );

    // User position marker
    new google.maps.Marker({
      position: mapOptions.center,
      map: mapObject,
      icon: "img/pins/people.png"
    });

    createMarkers();

  } catch (error) {
    console.warn("GPS denied or failed. Using fallback.");

    // Morocco fallback center
    mapOptions.center = new google.maps.LatLng(31.7917, -7.0926);

    mapObject = new google.maps.Map(
      document.getElementById("map_listing"),
      mapOptions
    );

    createMarkers();
  }
}

/* ===============================
   CREATE MARKERS
================================ */

function createMarkers() {
  for (var category in markersData) {
    markers[category] = [];

    markersData[category].forEach(function (data) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(
          data.location_latitude,
          data.location_longitude
        ),
        map: mapObject,
        icon: "img/pins/location.png"
      });

      markers[category].push(marker);

      google.maps.event.addListener(marker, "click", function () {
        closeInfoBox();
        var infoBox = getInfoBox(data);
        infoBox.open(mapObject, this);
        mapObject.setCenter(this.getPosition());
      });
    });
  }

  // Cluster only doctors (safe check)
  if (markers.Doctors && typeof MarkerClusterer !== "undefined") {
    new MarkerClusterer(mapObject, markers.Doctors);
  }
}

/* ===============================
   HELPERS
================================ */

function hideAllMarkers() {
  for (var key in markers) {
    markers[key].forEach(function (marker) {
      marker.setMap(null);
    });
  }
}

function toggleMarkers(category) {
  hideAllMarkers();
  closeInfoBox();

  if (!markers[category]) return;

  markers[category].forEach(function (marker) {
    marker.setMap(mapObject);
    marker.setAnimation(google.maps.Animation.DROP);
  });
}

function closeInfoBox() {
  if (window.jQuery) {
    $("div.infoBox").remove();
  }
}

function onHtmlClick(category, index) {
  if (markers[category] && markers[category][index]) {
    google.maps.event.trigger(markers[category][index], "click");
  }
}

/* ===============================
   START
================================ */

document.addEventListener("DOMContentLoaded", function () {
  initMapListing();
});
