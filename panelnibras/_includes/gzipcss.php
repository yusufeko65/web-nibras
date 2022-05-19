<?php
//PHP gzip method
//ob_start ("ob_gzhandler");
 require_once "kompres.php";
//Header to tell browser what kind of file it is.
header("Content-type: text/css; charset: UTF-8");
//Caching
header("Cache-Control: must-revalidate");
$expires = "Expires: " . gmdate("D, d M Y H:i:s", time() + 3600) . " GMT";
header($expires);
?>

