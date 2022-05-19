<?php
$dtFungsi = new FungsiUmum();

$themes = $dtFungsi->fcaridata('_themes','nama_themes','ststhemes','1');

if(!defined('THEMES')) define("THEMES", $themes);
if(!defined('URL_THEMES')) define("URL_THEMES",URL_PROGRAM.'view/'.THEMES.'/');
if(!defined('DIR_THEMES')) define("DIR_THEMES", ROOT_DOC.PATHBASE.'view/'.THEMES.'/');

if (!is_dir(DIR_THEMES)) {
	exit ('<div style="text-align:center;margin-top:200px"><br><b>Undercontruction!! </b><br>Maaf, untuk sementara website sedang dalam pengembangan</div>');
}



