<?php
	
	class SQLite {

		var $handle;

		function __construct() {
			$this->handle = new SQLite3("CHAT"); 

			$this->handle->query("CREATE TABLE IF NOT EXISTS CHATS (id INTEGER PRIMARY KEY AUTOINCREMENT, ROOM TEXT, USERNAME TEXT, MESSAGE TEXT, AT TEXT)");
			$this->handle->query("CREATE TABLE IF NOT EXISTS ROOMS (id INTEGER PRIMARY KEY AUTOINCREMENT, NAME TEXT)");
			$this->handle->query("CREATE TABLE IF NOT EXISTS USERS (id INTEGER PRIMARY KEY AUTOINCREMENT, USERNAME TEXT, PASSWORD TEXT)");
		}

		function fetch($query) { 
		    $result = $this->handle->query($query); 
		    
		    $retArr = [];

		    while ($result && $row = $result->fetchArray()) {
			    $retArr[] = $row;
			}

			return $retArr;
		}

		function query($query) {
			return $this->handle->query($query);
		}

	}