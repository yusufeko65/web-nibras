<?php
function loadclass($class_name)
{

  
   $prefix = '';
   $dataclass = explode("_", $class_name);

   if (count($dataclass) > 1) {
      
	  
      switch ($dataclass[0]) {
         case "controller":
            $prefix = 'control';
			$folder = DIR_CONTROLLER;
            break;
         case "model":
            $prefix = 'model';
			$folder = DIR_MODEL;
            break;
      }
      $class_name = $prefix . $dataclass[1];
   } else {
      $folder = ROOT_DOC.PATHBASE. 'includes/';
      $class_name = $dataclass[0];
   }
   $path = trim($folder) . "$class_name.php";

   if (file_exists($path)) {
      require_once $path;
   }
}
spl_autoload_register('loadclass');
