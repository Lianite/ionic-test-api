<?php
	
	require_once('../vendor/autoload.php');
	require_once('SQLite.php');

	use \Firebase\JWT\JWT;

	class User {
		var $key = "chat_secret_key";

		function __construct() {
		}

		function signup($username, $password) {
			$db = new SQLite();

			$existingUsers = $db->fetch("select count(id) as ct from USERS where username = '$username'");

			if($existingUsers[0]['ct'] == 0){
				$db->query("insert into USERS (username, password) VALUES ('$username', '" . password_hash($password, PASSWORD_BCRYPT ) . "')");

				return [
					'username' => $username,
					'token' => $this->generateToken($username)
				];
			} else {
				return [
					'error' => 'User already exists',
					'statusCode' => 401
				];
			}
		}

		function login($username, $password) {
			$db = new SQLite();

			$existingUsers = $db->fetch("select count(id) as ct from USERS where username = '$username'");

			if($existingUsers[0]['ct'] == 1){
				return [
					'username' => $username,
					'token' => $this->generateToken($username)
				];
			} else {
				return [
					'error' => 'Username or password is incorrect',
					'statusCode' => 401
				];
			}
		}

		function generateToken($name) {
			$token = array(
			    "iss" => "http://lianite.ddns.net",
			    "aud" => "http://lianite.ddns.net",
			    "iat" => microtime(),
			    "nbf" => microtime(),
			    "username" => $name
			);

			$jwt = JWT::encode($token, $this->key);

			return $jwt;
		}

		function verifyToken($jwt) {
			try {
				$decoded = JWT::decode($jwt, $this->key, array('HS256'));
			} catch (Exception $e) {
				// print_r($e);
				return false;
			}

			return true;
		}
	}