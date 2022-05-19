<?php
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
    ob_start();
} elseif (strpos(' ' . $_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') == false) {
    if (strpos(' ' . $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') == false) {
        ob_start();
    } elseif (!ob_start("ob_gzhandler")) {
        ob_start();
    }
} elseif (!ob_start("ob_gzhandler")) {
    ob_start();
}

 //include "kompres.php";
date_default_timezone_set("Asia/Jakarta");
 
//setting foldernya untuk di hosting

//require_once 'databaseku.php';
require_once('phpmailer/class.phpmailer.php');
//include "_includes/classUmum.php";


define('ROOT_DOC', $_SERVER["DOCUMENT_ROOT"]);

//Url
if(!defined('URL_PROGRAM')) define("URL_PROGRAM", "/"); //ganti sesuai alamat
if(!defined('PATHBASE')) define("PATHBASE", "/"); //ganti sesuai alamat

if(!defined('URL_IMAGE')) define("URL_IMAGE", URL_PROGRAM.'assets/image/');

/* admin */
define("URL_PROGRAM_ADMIN", URL_PROGRAM."panelnibras/");
define("URL_PROGRAM_ADMIN_VIEW", URL_PROGRAM_ADMIN."view/");
define('DIR_INCLUDE', $_SERVER["DOCUMENT_ROOT"].URL_PROGRAM_ADMIN);
define("DATA_SIMPAN_SUKSES", "Berhasil Disimpan");
define("DATA_SIMPAN_GAGAL", "Maaf, Gagal Menyimpan");
define("DATA_HAPUS_SUKSES", "Berhasil Dihapus");
define("DATA_HAPUS_GAGAL", "Maaf, Gagal Menghapus");

//setting foldernya untuk di localhost
define('DIR_IMAGE', ROOT_DOC.URL_PROGRAM.'assets/image/');

if(!defined('DIR_CONTROLLER')) define("DIR_CONTROLLER", ROOT_DOC.URL_PROGRAM.'controller/');
if(!defined('DIR_MODEL')) define("DIR_MODEL", ROOT_DOC.URL_PROGRAM.'model/');

session_start();
?>