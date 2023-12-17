var Map,
    butchers,
    cm




class leberkasMap extends L.map {
    constructor (htmlId) {
        super(htmlId, {center: new L.LatLng(48.1372, 11.5796), 
            zoom: 10
        })

        var OpenStreetMap = L.tileLayer('https://tiles.leberkasrechner.de/{z}/{x}/{y}.png', {maxZoom: 10, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>-Mitwirkende'}).addTo(this);
        var OpenStreetMap_DE = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {maxZoom: 18, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>-Mitwirkende'});

        var markerList = [];

        for(var i = 0; i < butchers.length; i++) {
            var b = butchers[i];
            try {
                var markertext = `<b>${b["tags"]["name"]}</b>`;
                var bt = b["tags"]
                if(bt["addr:street"] && bt["addr:housenumber"]) {
                    var t = `<br><i>${bt["addr:street"]} ${bt["addr:housenumber"]}`
                    if(bt["addr:postcode"] && bt["addr:city"]) {
                        t+= `,<br>${bt["addr:postcode"]} ${bt["addr:city"]}`
                    }
                    t+="</i>"
                    markertext += t
                }
                if(b["tags"]["opening_hours"]) {
                   // TODO: Code optimieren
                    //var oh = formatOpeningHours(b["tags"]["opening_hours"]);
                    //markertext += `<br><br><u>Öffnungszeiten:</u><br>${oh}`;
                }

                if(b["tags"]["website"]) {
                    markertext += `<br><a target="_blank" href="${b["tags"]["website"]}">${b["tags"]["website"]}</a>`;
                }
                
                markertext += `<br><br><a href="/butcher.php?id=${b["id"]}">Mehr Informationen</a>`;


                var marker = L.marker(new L.LatLng(b["lat"], b["lon"]), {title: markertext});
            }
            catch(error) {} 
            marker.bindPopup(markertext);
            markerList.push(marker);
        }
        var markers = new L.MarkersCanvas();
        markers.addTo(this)
        markers.addMarkers(markerList);

        this.baseMaps = {
            "OpenStreetMap": OpenStreetMap,
            "OpenStreetMap DE": OpenStreetMap_DE,
        };
        
        this.overlayMaps = {
            "Metzgereien": markers 
        };

        
        L.control.layers(this.baseMaps, this.overlayMaps).addTo(this);

        //Suche in Leaflet
        this.addControl( new L.Control.Search({
            url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
            jsonpParam: 'json_callback',
            propertyName: 'display_name',
            propertyLoc: ['lat','lon'],
            marker: L.marker([0,0]),
            autoCollapse: true,
            autoType: false,
            minLength: 2
        }) );

        //Fullscreen
        this.addControl(new L.Control.Fullscreen());


    }
    
    showCoordinates (e) {
        alert(e.latlng);
    }
    
    centerMap (e) {
        this.panTo(e.latlng);
    }
    
    zoomIn (e) {
        this.zoomIn();
    }
    
    zoomOut (e) {
        this.zoomOut();
    }

}


function formatOpeningHours(input) {
    var output = input.replace(/;/g, '<br>');
    output = output.replace('Mo', 'Montags');
    output = output.replace('Tu', 'Dienstags');
    output = output.replace('We', 'Mittwochs');
    output = output.replace('Th', 'Donnerstags');
    output = output.replace('Fr', 'Freitags');
    output = output.replace('Sa', 'Samstags');
    output = output.replace('Su', 'Sonntags');
    output = output.replace('PH', 'Feiertags');
    output = output.replace(',', ' und ');
    output = output.replace('off', 'geschlossen');
    return output;
}


fetch('/static/butchers.json')
    .then(response => response.json())
    .then(jsonData => {
        butchers = jsonData;
        lmap = new leberkasMap('meineKarte')
    })
    .catch(error => console.error(error));
