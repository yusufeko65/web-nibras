<?php
if(isset($_SESSION['hscart'])) {
  $jmlcart = count($_SESSION['hscart']);
  $miniCarts = $dtCart->showminiCart($_SESSION['hscart'],$tipemember);
  $totalitems = $miniCarts['items'];
  $hcart = $miniCarts['carts'];
  $jmlitem = 0;
  $zsubtotal = 0;
  $jmlerror = $miniCarts['jmlerror'];
  foreach($hcart as $hc){
    $jmlitem += (int)$hc['qty'];
	$zsubtotal += (int)$hc['total'];
  }
} else {
  $jmlcart = 0;
  $jmlitem = 0;
  $zsubtotal = 0;
  $hcart = array();
  $jmlerror = 0;
}
