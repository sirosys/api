<?php
	//tolak akses langsung ke file
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {		
		die ("Akses Ditolak");
	} else {
		function array2XML($obj, $array){
			foreach ($array as $key => $value) {
				if(is_numeric($key))
				//	$key = "data" . $key;
					$key = "array";
				if (is_array($value)) {
					$node = $obj->addChild($key);
					array2XML($node, $value);
				} else {
					$obj->addChild($key, htmlspecialchars($value));
				}
			}
		}
	}

?>