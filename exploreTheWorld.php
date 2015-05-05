<?


$api_key = "";  // get this from flickr
$flickr_userid = "";   // get this from flickr

$ch = curl_init(); /// initialize a cURL session
$page = rand(0,600);
$api_url="https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$api_key&tags=landscape&has_geo=true&format=rest&per_page=500&page=$page";
curl_setopt ($ch, CURLOPT_URL, $api_url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$flickrreturn = curl_exec ($ch);
$doc = new SimpleXmlElement($flickrreturn, LIBXML_NOCDATA);
curl_close ($ch);
$photocount = $doc->photos[total];
$random = rand (0,499);

$picid = $doc->photos->photo[$random][id];
$picsecret = $doc->photos->photo[$random][secret];
$farm = $doc->photos->photo[$random][farm];
$server = $doc->photos->photo[$random][server];


$block1 = $block2 = 0;
while ($block1 == $block2) {
	$block1=rand(0,499);
	$block2=rand(0,499);
	if ($block1 == $random || $block1 == $block2 || $block2 == $random) {
		$block1 = $block2 = 0;
	}
}

$ch = curl_init(); /// initialize a cURL session
$api_url="https://api.flickr.com/services/rest/?method=flickr.photos.geo.getLocation&api_key=$api_key&photo_id=$picid&format=rest";
curl_setopt ($ch, CURLOPT_URL, $api_url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$flickrreturn = curl_exec ($ch);
$doc = new SimpleXmlElement($flickrreturn, LIBXML_NOCDATA);
curl_close ($ch);
$lat = $doc->photo->location[0][latitude];
$long = $doc->photo->location[0][longitude];




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Explore The World</title>

    <style>
      html, body, #map-canvas {
        height: 100vh;
        margin: 0px;
        padding: 0px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>
	 var map;
	 var marker = [] ;
	 var m = 0;
function initialize() {
	var myLatlng = new google.maps.LatLng(<?=$lat?>,<?=$long?>);

  var mapOptions = {
    zoom: 6,
    center: myLatlng
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	addMarker(<?=$lat?>,<?=$long?>);
}

function addMarker(lat,long) {
	var myLatlng = new google.maps.LatLng(lat,long);
	marker[m] = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });
  //if (m > 0) { addline(marker[m-1].position,marker[m].position); }
  m++;
  
  map.panTo(myLatlng);
}
google.maps.event.addDomListener(window, 'load', initialize);

    </script>
</head>

<body>
<div align="center">
<div id="map-canvas"></div>
<div style="position:absolute;top:20px;left:20px;color:#fff;">
<img src="https://farm<?=$farm?>.static.flickr.com/<?=$server?>/<?=$picid?>_<?=$picsecret?>_z.jpg" />
</div>
</div>
</body>
</html>
