(function(exports) {
    "use strict";
  
    // This example creates a 2-pixel-wide red polyline showing the path of
    // the first trans-Pacific flight between Oakland, CA, and Brisbane,
    // Australia which was made by Charles Kingsford Smith.
    function initMap() {
        var iconBase = '../../../public_front_files/imgs/mapIcons/';
        var currentPos = window.coords[(window.coords.length - 1)];
        var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 10,
        center: window.center,
        mapTypeId: "terrain"
      });
      var flightPath = new google.maps.Polyline({
        path: window.coords,
        geodesic: true,
        strokeColor: "#bf1e1e",
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      var marker = new google.maps.Marker({
        position: currentPos,
        map:map,
        title : "Current Position",
        icon: iconBase + 'truck-25.png'
      });
      flightPath.setMap(map);
      const polyLengthInMeters = google.maps.geometry.spherical.computeLength(flightPath.getPath().getArray());
      $('.distance').text(Math.round((polyLengthInMeters/1000)))
    }
  
    exports.initMap = initMap;
  })((this.window = this.window || {}));