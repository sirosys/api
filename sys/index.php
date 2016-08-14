<?php
//	ini_set("display_errors", 1);
//	header("Access-Control-Allow-Origin: *");

	if (empty($_GET['class'])) {	
	//	header('Location: ../');
		include "../index.html";
	} else {
		$method = $_SERVER['REQUEST_METHOD'];
		require_once "c/index.php";
		
		// Check if class exist
		if (class_exists($_GET['class'])) {
			$controller = new $_GET['class']($method, $_GET['class'], $_GET['function'], $_GET['format']);
		} else {
			header("HTTP/1.1 404"); echo "Halaman tidak ditemukan";
		}
	}

?>