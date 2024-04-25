<?php
function domap($lat, $lon, $text){
    $lat = $lat;
    $ret = "<div style='height: 300px;' class='llmap' id='map'></div>";
    $ret .= " <script>
    var map = L.map('map').setView([$lat, $lon], 11);

    L.tileLayer('https://tiles.leberkasrechner.de/styles/osm-bright/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a>-Mitwirkende'
    }).addTo(map);
    
    L.marker([$lat, $lon]).addTo(map)
        .bindPopup('$text')
        .openPopup();
    </script>
    ";
    return $ret;
}
