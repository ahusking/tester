<?php
$http = json_decode(file_get_contents("http.json"),true);
#var_dump($http);
var_dump(PrepHTTPTest("husking.id.au"));

function PrepHTTPTest ($hostname = NULL, $uri = NULL, $query = NULL, $credentials = NULL, $port = NULL, $connType = NULL) {
	global $http;
	if (!isset($credentials)) { $credentials = $http["http"]["credentials"];}
	if (!isset($port)) { $port = $http["http"]["defBinding"];}
	if (!isset($connType)) { $connType = $http["http"]["defconnType"];}
	if (!isset($uri)) { $query = $http["http"]["defURI"];}
	return TSThttp($hostname,$credentials,$port,$connType, $uri, $query);
}

function TSThttp ($hostname, $credentials, $port, $connType, $uri, $query) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"$connType://$hostname:$port$uri");
	if (isset($credentials)){
		curl_setopt($ch, CURLOPT_USERPWD, $credentials);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	//var_dump($data);
	curl_close($ch);
	if (!isset($query)) {
		if ($httpcode == "200") {
			return $httpcode;
		}
	} elseif (strpos($data,$query) > 0) {
		return 0;
	} else { 
		return 1;
	}


}