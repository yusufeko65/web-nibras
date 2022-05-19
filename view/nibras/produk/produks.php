<div class="box">
  <div class="box-heading-2"><h2>Semua Produk</div>
   <div class="box-content-2">  
        <?php if($total>0) { ?>
	    <!-- Pagging -->
		<div class="subtitlecontent">
			<div class="urutkan">
				<div class="urutkankanan">Tampilkan berdasarkan
				   <select name="sort" class="selectboxs" style="width:180px" onchange="location = '<?php echo URL_PROGRAM.$amenu.$linkpage?>?sort=' + this.value">
				       <option value="new" <?php if($sort=='new' || $sort=='') echo "selected" ?>>Produk Terbaru</option>
					   <option value="old" <?php if($sort=='old') echo "selected" ?>>Produk Terlama</option>
					   <option value="hrgasc" <?php if($sort=='hrgasc') echo "selected" ?>>Harga Terendah</option>
					   <option value="hrgdesc" <?php if($sort=='hrgdesc') echo "selected" ?>>Harga Tertinggi</option>
					   <option value="namaasc" <?php if($sort=='namaasc') echo "selected" ?>>A - Z</option>
					   <option value="namadesc" <?php if($sort=='namadesc') echo "selected" ?>>Z - A</option>
				   </select>
				</div>
			</div>
		</div>
		<div class="pagging">
			<div class="pagingkiri">Hal <?php echo $page ?> dari <?php echo $jmlpage ?></div>
			<div class="pagingkanan"><?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?></div>

		</div>
		<!-- End Pagging -->
	    <?php } ?>
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
	  <?php if($total>0) { ?>
	   <!-- Pagging -->
		<div class="pagging">
			<div class="pagingkiri">Hal <?php echo $page ?> dari <?php echo $jmlpage ?></div>
			<div class="pagingkanan"><?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?></div>

		</div>
		<!-- End Pagging -->
	 <?php } ?>
	</div>
</div>
