<div class="col-xs-9 hskonten">
  <div class="judul-konten"><h2>Katalog</h2></div>
   <div class="isi-konten">
        <div class="row">
			<div class="col-xs-6 marginkiri">
				<span class="tulisan-label tulisan-kecil">Lihat Berdasarkan : </span>
				<select name="sort" class="selectbox" onchange="location = '<?php echo URL_PROGRAM.$linkpage?>?sort=' + this.value">
				    <option value="new" <?php if($sort=='new') echo "selected" ?>>Produk Terbaru</option>
					<option value="upd" <?php if($sort=='upd' || $sort=='') echo "selected" ?>>Update Terbaru</option>
					<option value="old" <?php if($sort=='old') echo "selected" ?>>Produk Terlama</option>
					<option value="hrgasc" <?php if($sort=='hrgasc') echo "selected" ?>>Harga Terendah</option>
					<option value="hrgdesc" <?php if($sort=='hrgdesc') echo "selected" ?>>Harga Tertinggi</option>
					<option value="namaasc" <?php if($sort=='namaasc') echo "selected" ?>>A - Z</option>
					<option value="namadesc" <?php if($sort=='namadesc') echo "selected" ?>>Z - A</option>
				</select>
					
			</div>
			<div class="col-xs-6 marginkanan">
			   <?php if($total>0) { ?>
				<ul class="pagination pagination-sm">
						<?php echo $dtPaging->GetPaging2($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?>
					</ul>
				<?php } ?>
			</div>
            <div class="clearfix"></div>
		   <div class="col-md-12  platproduk">
		    <?php if($ambildata) { ?>
			   <?php $asid=0; ?>
			   <?php 
			         foreach($ambildata as $datanya) {
					  
					  $katalogprod   = $datanya['produk_katalog'];
					  $katprod       = $dtProduk->getKategoriProduk($datanya['idproduk']);
					  if($tipemember == '0' || $tipemember=='') {
							$wheredisk = "idproduk='".$datanya['idproduk']."' AND idreseller_grup <> '".$reseller_bayar."' AND min_beli=1";
					  } else {
							$wheredisk = "idproduk='".$datanya['idproduk']."' AND idreseller_grup = '".$tipemember."' AND min_beli=1";
					  }
					  $carihargadisk = $dtFungsi->fcaridata2('_produk_diskon','harga',$wheredisk);
					  $dkategori = $katprod[0]['kategori_nama'];
					  $dkategorialias = $katprod[0]['kategori_alias'];
					  $asid++;
			  
					  if($datanya['sale'] == '1' ) {
						 $dthrglama = '<span class="hrglamaproduk"> '.$dtFungsi->fuang($datanya['hrg_jual']).'</span>';
						 $dthrgprod = 'Rp. '.$dtFungsi->fuang($hrgdiskon);
						 $kelas = 'harga-produk-lama';
						 $platdiskon = ' <a class="diskonproduk ">
						  		  <div class="hrgproduk"> SALE <br>'.$dthrgprod.'
								  </div>
								</a>';
					  } else {
						 $dthrgprod = 'Rp. '.$dtFungsi->fuang($datanya['hrg_jual']);
						 $dthrglama = '';
						 $kelas = 'harga-produk';
						 $platdiskon = '';
					  }
				?>
			   <div class="listkatalog" rel="semiinfo-produk<?php echo $asid ?>">
               <?php echo $platdiskon ?>
		   
			     <a class="imgkatalog" href="<?php echo URL_PROGRAM.$dkategorialias.'/'.$datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>">
				    <img src="<?php echo URL_IMAGE.'_thumb/thumbs_katalog'.$katalogprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
				    <div class="judul-produk"><?php echo $datanya['nama_produk']; ?></div>
				    <div class="kategori-produk"><?php echo $dkategori; ?></div>
				    <div class="<?php echo $kelas ?>"><?php echo $dtFungsi->fFormatuang($datanya['hrg_jual'])?></div>
				    <div id="semiinfo-produk<?php echo $asid ?>" style="display:none">
				    <div class="stok-produk" title="stok <?php echo $datanya['nama_produk']; ?>"><img src="<?php echo URL_THEMES.'assets/img/stok.png'?>" title="stok <?php echo $datanya['nama_produk']; ?>"> Stok <?php echo $datanya['jml_stok']?></div>
				    </div>
				    <div class="clearfix"></div>
			     </a>
		        </div>
			   <?php } ?>
			<?php } else { ?>
			Produk dari <?php echo $namaprodusen ?> masih kosong, silahkan kembali beberapa hari lagi
			<?php } ?>
		   </div>
		</div>
   </div>
</div>