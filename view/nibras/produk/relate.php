  <?php
    if(!isset($amenu) || $amenu != 'detail') {
	   echo "<script>location='".URL_PROGRAM."'</script>";
	}
  ?>
  <div class="col-md-12">
	<h3>Produk <?php echo $detail['name'] ?> Lainnya</h3>
  </div>
  <?php
	$asid=0;
	foreach($datarelate as $datanya) {
	  $gbrprod   = $datanya['gbr_produk'];
	  
	  $wheredisk = "product_id='".$datanya['idproduk']."' AND customer_group_id = '".$config_memberdefault."'";
	  $carihargadisk = $dtFungsi->fcaridata2('_produk_diskons','harga',$wheredisk);
	  if($carihargadisk) {
		 $hrgdiskon = $carihargadisk['harga'];
	  } else {
		 $hrgdiskon = 0;
	  }
			 
	  $cariharga = $dtFungsi->fcaridata2('_produk_harga','harga',$wheredisk);
	  if($cariharga) {
	     $hrgjual = $cariharga['harga'];
	  } else {
		 $hrgjual = 0;
	  }
	  $asid++;
			  
	  if($hrgdiskon == '' ) {
		 $dthrgprod = 'Rp. '.$dtFungsi->fuang($hrgjual);
		 $dthrglama = '';
      } else {
		 $dthrgprod = 'Rp. '.$dtFungsi->fuang($hrgdiskon);
		 $dthrglama = 'Rp. '.$dtFungsi->fuang($hrgjual);
      }
  ?>
		
		<div class="col-md-4">
		  
          <div class="thumbnail thumb-produk">
		    <div class="namaproduk"><?php echo $datanya['nama_produk']; ?></div>
            <div class="caption">
			 <a class="imgproduk" href="<?php echo URL_PROGRAM.$datanya['kat_alias'].'/'.$datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>">
             <img src="<?php echo URL_IMAGE.'_thumb/thumbs_'.$gbrprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
			 <div class="col-md-6">
			 <div class="jmlstok">Sisa Stok : <?php echo $datanya['jml_stok'] ?></div>
			 </div>
			 <div class="col-md-6">
			
			 <h4 class="text-right"><span class="label label-default"><?php echo $dthrgprod ?></span></h4>
			  <?php if($dthrglama != '') { ?>
			 <span class="hargalama text-right"><?php echo $dthrglama ?></span>
			 <?php } ?>
			 </div>
			 </a>
            </div>
          </div>
        </div>
		<?php } ?>
		
		 <div class="clearfix"></div>   

