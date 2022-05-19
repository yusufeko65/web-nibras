<?php
function loadclass($class_name) {
  
   $dir = __DIR__ .'/';
   $dir = trim($dir);
   $dataclass = explode("_",$class_name);
   if(count($dataclass) > 1){
      $folder     =  "$dir$dataclass[0]/";
	  switch($dataclass[0]){
	     case "controller":
		    $prefix = 'control';
		 break;
		 case "model":
		    $prefix = 'model';
		 break;
	  }
      $class_name = $prefix.$dataclass[1];
   } else {
	  $folder = __DIR__ .'/includes/';
	  $class_name = $dataclass[0];
	  
   }
   $path = trim($folder)."$class_name.php";
   
   if(file_exists($path)){
     require_once $path;
   }
}
spl_autoload_register('loadclass');