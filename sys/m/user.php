<?php
	//tolak akses langsung ke file
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {		
		die ("Akses Ditolak");
	} else {
		//ambil data koneksi
		require_once "../lib/config.php";
		require_once "../lib/array2XML.php";

		function pageFormat($format, $encode, $function){
			switch ($format) {
				case "json":
					header("Content-Type:application/json");	
					return json_encode($encode);
					break;

				case "xml":
					header("Content-Type: text/xml");
					// create new instance of simplexml
					$xml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><$function/>");						
					array2XML($xml, $encode);
					return $xml->asXML();
					break;
				
				default:
					header("Content-Type: text/plain");
					print_r($encode);
					break;
			}
		}

		// eksekusi usrModel
		class userModel {	
	
			public function all($method, $class, $function, $format){
				switch ($method) {
					case "GET":
						########## Mulai check apiKey ##########
						### Kalau apiKey kosong #####
						if (empty($_GET['key'])) {
							header("HTTP/1.1 401"); $encode = "key kosong";
						
						} else {
							# Query apiKey ke database
							$authKey = mysql_query("SELECT
								sirosys.app.appID
								FROM sirosys.app
								WHERE sirosys.app.appKey = '$_GET[key]'
								AND sirosys.app.appActive = true
							");
							
							if (!mysql_num_rows($authKey)) {# apabila Key tidak ditemukan
								header("HTTP/1.1 403"); $encode = "key tidak valid";
								
							} else { # Apabila key ditemukan
								//query data user		
								$query = mysql_query("SELECT
									sirosys.usr.usrID, sirosys.usr.usrEmail
									FROM sirosys.usr 
									ORDER BY sirosys.usr.usrID ASC
								");

								while ($array = mysql_fetch_assoc($query)) {$rows[] = $array;}

								$encode = array(
									"Code" => 200,
									"Stat" => true,
									"Rows" => count($rows),
									$class => $rows
								);
							}
						}
						
						break;
								
					default:
						header("HTTP/1.1 405"); $encode = "Method Tidak Valid";

						break;
				}

				return pageFormat($format, $encode, $function);

				
			}

			public function search($method, $class, $function, $format){
				switch ($method) {
					
					case "GET":
						########## Mulai check apiKey ##########
						### Kalau apiKey kosong #####
						if (empty($_GET['key'])) {
							header("HTTP/1.1 401"); $encode = "key kosong";
						
						} else {
							
							# Query apiKey ke database
							$authKey = mysql_query("SELECT
								sirosys.app.appID
								FROM sirosys.app
								WHERE sirosys.app.appKey = '$_GET[key]'
								AND sirosys.app.appActive = true
							");
							
							if (!mysql_num_rows($authKey)) {# apabila Key tidak ditemukan
								header("HTTP/1.1 403"); $encode = "key tidak valid";
								
							} else { # Apabila key ditemukan
								
								if (empty($_GET['usrID'])) { # Apabila usrID kosong
									header("HTTP/1.1 411"); $encode = "usrID kosong";
								
								} else { # Apabila usrID tidak kosong
									
									//query data user		
									$query = mysql_query("SELECT
										sirosys.usr.*
										FROM sirosys.usr
										WHERE sirosys.usr.usrID = $_GET[usrID]
									");

									$encode = array(
										"Code" => 200,
										"Stat" => "OK",
										"Rows" => mysql_num_rows($query),
										$class => mysql_fetch_assoc($query)
									);
								}
							
							}


						}

						break;
					
					default:
						header("HTTP/1.1 405"); $encode = "Method Tidak Valid";
						break;
				}
				
				return pageFormat($format, $encode, $function);

			}

			# Untuk authentikasi user dan password
			public function auth($method, $class, $function, $format){
				switch ($method) {
					
					case "POST":
						########## Mulai check apiKey ##########
						### Kalau apiKey kosong #####
						if (empty($_POST['key'])) {
							header("HTTP/1.1 401"); $encode = "key kosong";
						} else {
							# Query apiKey ke database
							$authKey = mysql_query("SELECT
								sirosys.app.appID
								FROM sirosys.app
								WHERE sirosys.app.appKey = '$_POST[key]'
								AND sirosys.app.appActive = true
							");
							
							if (!mysql_num_rows($authKey)) {# apabila Key tidak ditemukan
								header("HTTP/1.1 403"); $encode = "key tidak valid";
							} else { # Apabila key ditemukan

								$query = mysql_query("SELECT
									*
									FROM sirosys.usr
									WHERE sirosys.usr.usrEmail = '$_POST[usrEmail]'
									AND sirosys.usr.usrPass = md5('$_POST[usrPass]')
									AND usrStatus = 1
								");

								if (!mysql_num_rows($query)) {
									header("HTTP/1.1 401"); $encode = "user tidak ditemukan";
								} else {
									header("HTTP/1.1 302"); $encode = "User ditemukan";
								}
							}
						}

						break;
					
					default:
						header("HTTP/1.1 405"); $encode = "Method Tidak Valid";
						break;

					}
				return pageFormat($format, $encode, $function);
			}

		}
	}
?>