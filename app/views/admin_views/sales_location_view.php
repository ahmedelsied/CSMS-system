<style>
    [title^="Current Position"]>img{
        width:24px !important
    }
</style>
<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-9 col-xs-12 text-center">
                            <label>جلب في تاريخ :</label>
                            <input type="date" class="start-date custom-date"/>
                            <input type="submit" value="تنفيذ" class="btn btn-danger" id="get-data"/>
                        </div>
                        <br class="visible-xs">
                        <br class="visible-xs">
                        <br class="visible-xs">
                        <hr class="visible-xs">
                        <div class="col-md-3 col-xs-12">
                            <p class="custom-date-card" id="today-date">اليوم</p>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-right" style="color: white;">
                    <strong class="pull-right">يظهر اخر تسجيل لحركة المندوب</strong>
                    <p class="pull-left">
                        المسافه المرسومه على الخريطه:
                        <strong class="distance"></strong>
                        كم
                    </p>
                </div>
                <br>
                <hr>
                <br>
                <div id="map" style="width:100%;height:800px"></div>
            </div>
        </div>
    </div>
</section>
    <script>
(function(exports) {


    "use strict";

    function call_map_script(){
        $('body').append("<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyC3lRYSsvHZipWUbYIx_-GT7gY2ao7vn5I&callback=initMap&libraries=geometry' defer>"+"<"+"/script>");
    }
    window.set_params = function (coords){
        window.coords = coords;
        window.center = window.coords[(window.coords.length - 1)];
    }
    
    var coords = <?=!empty($this->current_coords) ? $this->current_coords : '[]'?>;
    call_map_script();
    window.set_params(coords);
    // This example creates a 2-pixel-wide red polyline showing the path of
    // the first trans-Pacific flight between Oakland, CA, and Brisbane,
    // Australia which was made by Charles Kingsford Smith.
    function initMap() {
        var iconBase = '../../../public_front_files/imgs/mapIcons/',
            currentPos = window.coords[(window.coords.length - 1)],
            map_el = document.getElementById("map");
        var map = new google.maps.Map(map_el, {
        zoom: 10,
        center: window.center,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#263c3f'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#6b9a76'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#38414e'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#212a37'}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#9ca5b3'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#746855'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#1f2835'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#f3d19c'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#2f3948'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#17263c'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#515c6d'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#17263c'}]
            }
          ]
      });
      window.flightPath = new google.maps.Polyline({
        path: window.coords,
        geodesic: true,
        strokeColor: "#bf1e1e",
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      window.marker = new google.maps.Marker({
        position: currentPos,
        map:map,
        title : "Current Position",
        icon: iconBase + 'truck-25.png'
      });
      window.flightPath.setMap(map);
      const polyLengthInMeters = google.maps.geometry.spherical.computeLength(flightPath.getPath().getArray());
      $('.distance').text(Math.round((polyLengthInMeters/1000)))
    }
  
    exports.initMap = initMap;
  })((this.window = this.window || {}));

</script>
<script>

function set_position(lat,lng){
    var path = window.flightPath.getPath(),
        new_pos = new google.maps.LatLng(lat,lng);
    path.push(new_pos);
    window.flightPath.setPath(path);
    window.marker.setPosition(new_pos);
    var polyLengthInMeters = google.maps.geometry.spherical.computeLength(path.getArray());
    $('.distance').text(Math.round((polyLengthInMeters/1000)))
}
</script>
<!-- Handle Socket -->
<script>
function handle_socket(){
    window.adminSocket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?type=admin&access_token=<?=$this->getSession('access_token')?>&id=<?=$this->getSession('user_id')?>");
    window.adminSocket.onclose = function(){
        alert('لجلب الموقع بشكل مباشر قم بالضغط على اليوم');
    }
    window.adminSocket.onmessage = function(e){
        var data = JSON.parse(e.data);
        if(data['sales_id'] == <?=$this->sales_id_location?>){
            set_position(data['coords']['lat'],data['coords']['lng'])
        }
    }
}
handle_socket();
</script>
<!-- Get Location Today -->
<script>
    var today_btn = $('#today-date');
    window.url  = window.domain + '/admin/sales/get_location_with_date/<?=$this->sales_id_location?>';
    today_btn.on('click',function(){
        var data = {
            date : 'today',
        }
        if(window.adminSocket.readyState == window.adminSocket.CLOSED){
            ajaxRequest(window.url,'POST',data,'text',ajaxUndilevredSuccess,ajaxError);
        }
    });
    function ajaxUndilevredSuccess(data){
        if(data.length == 0 ){
            alert('لا يوجد بيانات لهذا المندوب اليوم');
            return;
        }
        window.set_params(JSON.parse(data));
        initMap();
        handle_socket();
    }
</script>
<!-- Get Location With Date -->
<script>
    var btn_submit = $('#get-data');
    btn_submit.on('click',function(){
        var data = {date:$(this).prev().val()};
        ajaxRequest(window.url,'POST',data,'text',get_location_success,ajaxError);
    });
    function get_location_success(data){
        if(data.length == 0 ){
            alert('لا يوجد بيانات بهذا التاريخ');
            return;
        }
        typeof(window.adminSocket == 'object') ? window.adminSocket.close() : '';
        window.set_params(JSON.parse(data));
        initMap();        
    }
    function ajaxError(){
        alert('هناك شئ ما خطأ');
    }
</script>