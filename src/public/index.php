<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	require_once ('../vendor/autoload.php');
	require_once ('User.php');
	require_once ('Room.php');
	require_once ('Rooms.php');

	$app = new \Slim\App;

	$app->add(function($request, $response, $next) {
	    $response = $next($request, $response);


	    return $response->withHeader("Access-Control-Allow-Methods", 'GET, POST, OPTIONS')
	    	->withHeader('Access-Control-Allow-Origin', '*')
	    	->withHeader('Access-Control-Allow-Headers', 'content-type, authorization');
	});

	$app->post('/api/{version}/user/{method}', function (Request $request, Response $response) {
	    $version = $request->getAttribute('version');
	    $method = $request->getAttribute('method');

	    $requestObj = $request->getParsedBody();

	    $user = new User();

	    $tmp = $user->$method($requestObj['username'], $requestObj['password']);

	    if($tmp['error']) {
	    	$newResponse = $response->withStatus($tmp['statusCode']);
	    } else {
			$newResponse = $response->withJSON($tmp, 200);
		}

	    return $newResponse;
	});

	$app->post('/api/{version}/room', function (Request $request, Response $response) {
	    $version = $request->getAttribute('version');

	    $headers = $request->getHeaders();
		$jwt = str_replace('Bearer ', '', $headers['HTTP_AUTHORIZATION'][0]);

	    $requestObj = $request->getParsedBody();

	    $roomController = new Room();

	    $retArr = $roomController->addRoom($jwt, $requestObj['name']);

	    $newResponse = $response;

	    if(array_key_exists('error', $retArr)){
	    	$newResponse = $newResponse->withStatus($retArr['statusCode']);
	    } else {
	    	$newResponse = $newResponse->withJSON($retArr, 200);
	    }

	    return $newResponse;
	});

	$app->get('/api/{version}/rooms', function (Request $request, Response $response) {
	    $version = $request->getAttribute('version');

		$headers = $request->getHeaders();
		$jwt = str_replace('Bearer ', '', $headers['HTTP_AUTHORIZATION'])[0];

	    $roomsController = new Rooms();

	    $retArr = $roomsController->getRooms($jwt);

	    $newResponse = $response;

	    if(array_key_exists('error', $retArr)){
	    	$newResponse = $newResponse->withStatus($retArr['statusCode']);
	    } else {
	    	$newResponse = $newResponse->withJSON($retArr, 200);
	    }

	    return $newResponse;
	});

	$app->get('/api/{version}/rooms/{roomName}/chats', function (Request $request, Response $response) {
	    $version = $request->getAttribute('version');
	    $roomName = $request->getAttribute('roomName');

	    $headers = $request->getHeaders();
		$jwt = str_replace('Bearer ', '', $headers['HTTP_AUTHORIZATION'])[0];

	    $roomsController = new Rooms();

	    $retArr = $roomsController->getChats($jwt, $roomName);

	    $newResponse = $response;

	    if(array_key_exists('error', $retArr)){
	    	$newResponse = $newResponse->withStatus($retArr['statusCode']);
	    } else {
	    	$newResponse = $newResponse->withJSON($retArr, 200);
	    }

	    return $newResponse;
	});

	$app->post('/api/{version}/rooms/{roomName}/chat', function (Request $request, Response $response) {
	    $version = $request->getAttribute('version');
	    $roomName = $request->getAttribute('roomName');

	    $headers = $request->getHeaders();
		$jwt = str_replace('Bearer ', '', $headers['HTTP_AUTHORIZATION'])[0];

	    $requestObj = $request->getParsedBody();

	    $roomsController = new Rooms();

	    $retArr = $roomsController->addChat($jwt, $requestObj['room'], $requestObj['username'], $requestObj['text']);

	    $newResponse = $response;

	    if(array_key_exists('error', $retArr)){
	    	$newResponse = $newResponse->withStatus($retArr['statusCode']);
	    } else {
	    	$newResponse = $newResponse->withJSON($retArr, 200);
	    }

	    return $newResponse;
	});

	$app->run();