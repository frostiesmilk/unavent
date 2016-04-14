/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function reloadMap(){
    setTimeout(function() {
        google.maps.event.trigger(map, 'resize');
    }, 300);  
}

function initMap() {  
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 48.8534100, lng: 2.3488000},
    zoom: 12
  });
  var input = /** @type {!HTMLInputElement} */(
      document.getElementById('pac-input'));

  var types = document.getElementById('type-selector');
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });
  
  autocomplete.addListener('place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      window.alert("Autocomplete's returned place contains no geometry");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(15);  // Why 17? Because it looks good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    }));
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
      addressbis = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
      ].join(' ');
      
      addresscity = [
        (place.address_components[2] && place.address_components[2].short_name || ''),
      ].join(' ');
      
      codepostal = [
        (place.address_components[4] && place.address_components[4].short_name || ''),
      ].join(' ');
      
      country = [
        (place.address_components[5] && place.address_components[5].short_name || ''),
      ].join(' ');
      
      longueur=codepostal.length;
      if (longueur != 5){
        codepostal ='';
      }
      if (country=='FR'){
          country='France';
      }

    }

    document.getElementById(initMapPrefix+"postalAddress_name").value = place.name;
    document.getElementById(initMapPrefix+"postalAddress_address").value = addressbis;
    document.getElementById(initMapPrefix+"postalAddress_city").value = addresscity;
    document.getElementById(initMapPrefix+"postalAddress_zipcode").value = codepostal;
    document.getElementById(initMapPrefix+"postalAddress_country").value = country;
    document.getElementById(initMapPrefix+"postalAddress_coordinates").value = place.geometry.location;

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  });

}

/**
 * show map **
 */
function showMap() {
    
    if(!pLocation){ // no coordinates
        return false;
    }
    
    var coordinates = pLocation.split(','); 
    
    console.log(coordinates[0]+ coordinates[1]);
  var location = new google.maps.LatLng(coordinates[0], coordinates[1]);
  var map = new google.maps.Map(document.getElementById('map'), {
    center: location,
    zoom: 14
  });

  var coordInfoWindow = new google.maps.InfoWindow();
  coordInfoWindow.setContent(createInfoWindowContent(location, map.getZoom()));
  coordInfoWindow.setPosition(location);
  coordInfoWindow.open(map);

  map.addListener('zoom_changed', function() {
    coordInfoWindow.setContent(createInfoWindowContent(location, map.getZoom()));
    coordInfoWindow.open(map);
  });
}

var TILE_SIZE = 256;

function createInfoWindowContent(latLng, zoom) {
  var scale = 1 << zoom;

  var worldCoordinate = project(latLng);

  var pixelCoordinate = new google.maps.Point(
      Math.floor(worldCoordinate.x * scale),
      Math.floor(worldCoordinate.y * scale));

  var tileCoordinate = new google.maps.Point(
      Math.floor(worldCoordinate.x * scale / TILE_SIZE),
      Math.floor(worldCoordinate.y * scale / TILE_SIZE));

  return [
    pLocationDescription
  ].join('<br>');
}

// The mapping between latitude, longitude and pixels is defined by the web
// mercator projection.
function project(latLng) {
  var siny = Math.sin(latLng.lat() * Math.PI / 180);

  // Truncating to 0.9999 effectively limits latitude to 89.189. This is
  // about a third of a tile past the edge of the world tile.
  siny = Math.min(Math.max(siny, -0.9999), 0.9999);

  return new google.maps.Point(
      TILE_SIZE * (0.5 + latLng.lng() / 360),
      TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)));
}
/**
 * end show map **
 */