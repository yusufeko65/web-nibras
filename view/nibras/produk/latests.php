<div class="box">
  <div class="box-heading-2"><h2>Produk</h2></div>
   <div class="box-content-2">
        <div style="text-align:right">
		 <a href="<?php echo URL_PROGRAM.'produk/' ?>" title="Lihat Seluruh Produk">Seluruh Produk</a>
		</div>
	   <div class="box-products">
	  
	   <?php foreach($ambildata as $datanya) {
	         $gbrprod = $dtProduk->getCover($datanya['idproduk'],$datanya['gbr_produk']);
	   ?>
		<div onclick="location='<?php echo URL_PROGRAM.'detail/'.$datanya['idproduk'].'/'.$datanya['alias_url'] ?>'">
			<div class="product_box_frame">
  			  <div class="product_box_frame_2">
				<div class="product_box_frame_3">
					<div class="image">
						<a href="<?php echo URL_PROGRAM.'detail/'.$datanya['idproduk'].'/'.$datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>">
							<img src="<?php echo URL_IMAGE.'_thumb/thumbs_'.$gbrprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
						</a>
					</div>
					<div class="name"><?php echo $datanya['nama_produk']; ?></div>
					<div class="price"><!--<img src="<?php echo URL_THEMES.'images/price.png'?>">-->
						<span class="price-new">
							<?php echo $dtFungsi->fFormatuang($datanya['hrg_jual'])?>
						</span>
					</div>
				</div>
			 </div>
		   </div>
		</div>
        <?php } ?>
       </div>
	   <div style="text-align:right"><a href="<?php echo URL_PROGRAM.'produk/' ?>" title="Lihat Seluruh Produk">Seluruh Produk</a></div>
	</div>
</div>
