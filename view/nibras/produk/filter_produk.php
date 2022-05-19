<div class="col-xs-9 hskonten">
  <div class="judul-konten"><h2><?php echo $namafilter ?></h2></div>
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
					
					   if($jenisfilter == 'ukuran') {
						  $gbrprod   = $dtProduk->getCover($datanya['idproduk'],$datanya['gbr_produk']);
					   } else {
					      $gbrprod   = $dtProduk->getCoverByWarna($datanya['idproduk'],$idwarna);
						  $gbrprod = $gbrprod['produk_gbr'];
					   }
					   $katprod   = $dtProduk->getKategoriProduk($datanya['idproduk']);
					   //$katalias  = array();
				       /*foreach($katprod as $k) {
						$kat[] = $k['kategori_nama'];
				        $katalias[] = $k['kategori_alias'];
			           }
			           $dkategori = implode(", ", $kat);
			           $dkategorialias = implode(", ", $katalias);
						*/
						$dkategori = $katprod[0]['kategori_nama'];
						$dkategorialias = $katprod[0]['kategori_alias'];
					   ?>
			   <?php $asid++; ?>
			   <div class="listproduk" rel="semiinfo-produk<?php echo $asid ?>">
			        <a class="imgproduk" href="<?php echo URL_PROGRAM.$dkategorialias.'/'.$datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>">
					<img src="<?php echo URL_IMAGE.'_thumb/thumbs_'.$gbrprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
					<div class="judul-produk"><?php echo $datanya['nama_produk']; ?></div>
					<div class="kategori-produk"><?php echo $dkategori; ?></div>
					<div class="harga-produk"><?php echo $dtFungsi->fFormatuang($datanya['hrg_jual'])?></div>
					<div id="semiinfo-produk<?php echo $asid ?>" style="display:none">
						<div class="stok-produk pull-right" title="stok <?php echo $datanya['nama_produk']; ?>"><img src="<?php echo URL_THEMES.'assets/img/stok.png'?>" title="stok <?php echo $datanya['nama_produk']; ?>"> Stok <?php echo $datanya['jml_stok']?></div>
					</div>
					<div class="clearfix"></div>
					</a>
			   </div>
			   <?php } ?>
			<?php } else { ?>
			Produk <?php echo ' <b>' .$namafilter.'</b>' ?> masih kosong, silahkan kembali beberapa hari lagi
			<?php } ?>
		   </div>
		</div>
   </div>
</div>