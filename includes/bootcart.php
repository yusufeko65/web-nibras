<?php
if(isset($_SESSION['hscart'])) {
	$jmlcart = count($_SESSION['hscart']);
	$data['min_beli'] = $grup_min_beli;
	$data['min_beli_syarat'] = $grup_min_beli_syarat;
	$data['diskon_grup'] = $grup_diskon;
	$miniCarts = $dtCart->showminiCart($_SESSION['hscart'],$data);
	
	$totalitems = $miniCarts['items'];
	$hcart = $miniCarts['carts'];
	$jmlitem = 0;
	$zsubtotal = 0;
	$jmlerror = $miniCarts['jmlerror'];
	//$ztotberat = 0;
	foreach($hcart as $hc){
		$jmlitem += (int)$hc['qty'];
		$zsubtotal += (int)$hc['total'];
		//$ztotberat = $ztotberat + $hc['berat'];
	}
} else {
	$jmlcart = 0;
	$jmlitem = 0;
	$zsubtotal = 0;
	$hcart = false;
	$jmlerror = 0;
	//$ztotberat = 0;
	
}
