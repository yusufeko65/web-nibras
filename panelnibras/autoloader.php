<?php
function loadclass($class_name) {
  
	$dir = __DIR__ .'/';
	$dir = trim($dir);
	$dataclass = preg_replace('/(?<!^)([A-Z])/', '_\\1', $class_name);
	$tipeclass = explode("_",$dataclass);
	/*
	if($tipeclass[0] == 'controller' || $tipeclass[0] == 'model') {
		$folder =  "$dir$tipeclass[0]/";
	} else {
		$folder = ROOT_DOC.PATHBASE.'includes/';
	}
	*/
	switch($tipeclass[0]){
		case "controller":
		case "model":
			$folder =  "$dir$tipeclass[0]/";
		break;
		case "Fungsi":
		case "Database":
			$folder = ROOT_DOC.PATHBASE.'includes/';
		break;
		default:
			$folder = '';
		break;
	}
	
	if($folder != '' ){
		$path = trim($folder)."$class_name.php";
		
		if(file_exists($path)){
			require_once $path;
		}
		if (!class_exists($class_name, false)) {
			trigger_error("Unable to load class: $class_name", E_USER_WARNING);
		}
	}
}
spl_autoload_register('loadclass');