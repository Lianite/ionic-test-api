<?php
	
	require_once('../vendor/autoload.php');
	require_once('SQLite.php');
	require_once('User.php');

	class Rooms {
		function __construct() {
		}

		function getRooms($jwt) {
			$db = new SQLite();

			$user = new User();

			$retArr = [];

			if($user->verifyToken($jwt)){
				$rooms = $db->fetch("select * from ROOMS order by NAME");

				if(count($rooms) > 0){
					foreach($rooms as $room) {
						$retArr[] = ['name' => $room['NAME']];
					}
				}
			} else {
				$retArr = [
					'error' => 'Unauthorized',
					'statusCode' => 401
				];
			}

			return $retArr;
		}

		function addChat($jwt, $room, $username, $text) {
			$db = new SQLite();

			$user = new User();

			$retArr = [];

			if($user->verifyToken($jwt)){
				$date = date(DATE_ISO8601);

				$insert = $db->query("insert into CHATS (ROOM, USERNAME, MESSAGE, AT) VALUES ('$room', '$username', '$text', '$date')");

				if($insert) {
					$retArr[] = [
						'at' => $date,
						'room' => $room,
						'username' => $username,
						'text' => $text
					];
				} else {
					$retArr = [
						'error' => 'Unable to add chat',
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

		function getChats($jwt, $room) {
			$db = new SQLite();

			$user = new User();

			$retArr = [];

			if($user->verifyToken($jwt)){
				$chats = $db->fetch("select * from CHATS where ROOM = '$room'");

				if(count($chats) > 0) {
					foreach($chats as $chat) {
						$retArr[] = [
							'at' => $chat['AT'],
							'room' => $chat['ROOM'],
							'username' => $chat['USERNAME'],
							'text' => $chat['MESSAGE']
						];
					}
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