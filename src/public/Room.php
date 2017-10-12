<?php
	
	require_once('../vendor/autoload.php');
	require_once('SQLite.php');
	require_once('User.php');

	class Room {
		function __construct() {
		}

		function addRoom($jwt, $room) {
			$db = new SQLite();

			$user = new User();

			$retArr = [];

			if($user->verifyToken($jwt)){
				$date = date(DATE_ISO8601);

				$insert = $db->query("insert into ROOMS (NAME) VALUES ('$room')");

				if($insert) {
					$retArr = [
						'name' => $room
					];
				} else {
					$retArr = [
						'error' => 'Unable to add room',
						'statusCode' => 417
					];
				}
			} else {
				$retArr = [
					'error' => 'Unauthorized',
					'statusCode' => 401
				];
			}

			return $retArr;
		}
	}