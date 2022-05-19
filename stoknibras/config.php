<?php
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
/*
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler");
else ob_start();
*/

//include "kompres.php";
date_default_timezone_set("Asia/Jakarta");

//setting foldernya untuk di hosting
//require_once 'databaseku.php';
//include "_includes/classUmum.php";

//$dtFungsi = new FungsiUmum();
//if(!defined('THEMES')) define("THEMES", $dtFungsi->fcaridata('_themes','nama_themes','ststhemes','1'));

//define('ROOT_DOC', '/home/goetnik1/public_html/nibras.co.id/');
define('ROOT_DOC', '/home/susanto/public_html/nibras_co_id/');

//Url
//if (!defined('URL_PROGRAM')) define("URL_PROGRAM", "/nibrascoid/"); //ganti sesuai alamat
//if (!defined('PATHBASE')) define("PATHBASE", "/nibrascoid/"); //ganti sesuai alamat
if (!defined('PATHBASE')) define("PATHBASE", "/"); //ganti sesuai alamat
if(!defined('URL_PROGRAM')) define("URL_PROGRAM", "/"); //ganti sesuai alamat
if (!defined('URL_IMAGE')) define("URL_IMAGE", URL_PROGRAM . 'assets/image/');


//setting foldernya untuk di localhost
define('DIR_IMAGE', ROOT_DOC . URL_PROGRAM . 'assets/image/');
//if(!defined('DIR_THEMES')) define("DIR_THEMES", ROOT_DOC.URL_PROGRAM.'view/'.THEMES.'/');
if (!defined('DIR_CONTROLLER')) define("DIR_CONTROLLER", ROOT_DOC . URL_PROGRAM . 'controller/');
if (!defined('DIR_MODEL')) define("DIR_MODEL", ROOT_DOC . URL_PROGRAM . 'model/');
//if (!is_dir(DIR_THEMES)) exit ('<div style="text-align:center;margin-top:200px"><br><b>Undercontruction!! </b><br>Maaf, untuk sementara website sedang dalam pengembangan</div>');
//if(!file_exists(DIR_THEMES.'home/index.php')) exit("Tidak ditemukan");
session_start();
