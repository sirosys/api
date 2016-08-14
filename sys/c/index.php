<?php
	//tolak akses langsung ke file
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {	
		die ("Akses Ditolak");
	} else {
		function sendModel($method, $class, $function, $format){
			require_once "m/$class.php"; // Include model

			$newMo = $class."Model";
			$model = new $newMo();
			
			if (method_exists($model, "$function")){ 
				return $model->$function($method, $class, $function, $format);
			} else {
				header("HTTP/1.1 404"); return "Halaman tidak ditemukan";
			}
			
		}

		# Mulai untuk hal yang akan dipanggil
		class user {
			public function __construct($method, $class, $function, $format){
				echo sendModel($method, $class, $function, $format);
			}
		}

		class app {
			public function __construct($method, $class, $function, $format){
				echo sendModel($method, $class, $function, $format);
			}
		}
	}
?>