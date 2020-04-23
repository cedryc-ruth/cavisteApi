<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");

$wines = file_get_contents('wines.json');
$wines = json_decode($wines);
//var_dump($wines);

$baseLength = strlen($_SERVER['REDIRECT_BASE']);
$request = substr($_SERVER['REQUEST_URI'],$baseLength);
//var_dump($request);
//var_dump($_SERVER);

switch($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		echo json_encode(['error'=>'POST pas encore implémenté']);
		exit;
	case 'PUT':
		echo json_encode(['error'=>'PUT pas encore implémenté']);
		exit;
	case 'DELETE':
		echo json_encode(['error'=>'DELETE pas encore implémenté']);
		exit;
}

$matches = [];
$list = [];

if($request == "/api/wines") {
	$response = json_encode($wines);
} elseif(preg_match("/api\/wines\/(\d+)/",$request,$matches)==1) {
	//var_dump($matches);
	$response = json_encode($wines[$matches[1]-1]);
} elseif(preg_match("/api\/wines\/search\/(\w+)/",$request,$matches)==1) {
	//var_dump($matches);
	$search = strtolower($matches[1]);
	
	foreach($wines as $wine) {
		if(strpos(strtolower($wine->name),$search)!==false) {
			$list[] = $wine;
		}
	}
	
	$response = json_encode($list);
} elseif(preg_match("/api\/wines\/grapes/",$request)==1) {
	foreach($wines as $wine) {
		if(!in_array($wine->grapes,$list)) {
			$list[] = $wine->grapes;
		}
	}
	
	$response = json_encode($list);
} elseif(preg_match("/api\/wines\/regions/",$request)==1) {
	foreach($wines as $wine) {
		if(!in_array($wine->region,$list)) {
			$list[] = $wine->region;
		}
	}
	
	$response = json_encode($list);
} else {
	header("HTTP/1.0 404 Not Found");
	$response = '{"error":"Not found"}';
}
echo $response;