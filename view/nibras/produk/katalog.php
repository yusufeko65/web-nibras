		<div class="col-md-12">
			<div class="row" style="margin-top:20px">
				<div class="col-md-6 marginkiri">
					<span class="tulisan-label">Lihat Berdasarkan : </span>
					 <select name="sort" class="selectbox" onchange="location = '<?php echo URL_PROGRAM.$amenu.$linkpage?>?sort=' + this.value">
				       <option value="new" <?php if($sort=='new' || $sort=='') echo "selected" ?>>Produk Terbaru</option>
					   <option value="old" <?php if($sort=='old') echo "selected" ?>>Produk Terlama</option>
					   <option value="hrgasc" <?php if($sort=='hrgasc') echo "selected" ?>>Harga Terendah</option>
					   <option value="hrgdesc" <?php if($sort=='hrgdesc') echo "selected" ?>>Harga Tertinggi</option>
					   <option value="namaasc" <?php if($sort=='namaasc') echo "selected" ?>>A - Z</option>
					   <option value="namadesc" <?php if($sort=='namadesc') echo "selected" ?>>Z - A</option>
					</select>
					
				</div>
				<div class="col-md-6 sisi-pagekanan">
				<?php 
				  $currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
				  //$cureentUrl = 'http://www.hijabsupplier.com';
				?>

				  <div class="pull-left share-twitter">
			         <!--<div class="fb-share-button" data-href="<?php echo $currentUrl ?>" data-type="button_count"></div>-->
					 <div class="fb-like" data-href=""<?php echo $currentUrl ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
				   </div>
				  <div class="pull-left share-twitter">
				     <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $currentUrl ?>" data-counturl="<?php echo $currentUrl ?>" data-lang="en" data-count="horizontal">Tweet</a>
				     <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				   </div>
				   
			
				<?php if($total>0) { ?>
					<ul class="pagination pagination-sm">
						<?php echo $dtPaging->GetPaging2($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?>
					</ul>
				<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-md-12">
		  <div class="row">
		<?php
		    $asid=0;
			foreach($ambildata as $datanya) {
			  $katalogprod   = $datanya['produk_katalog'];
			  $katprod       = $dtProduk->getKategoriProduk($datanya['idproduk']);
			  if($tipemember == '0' || $tipemember=='') {
			    $wheredisk = "idproduk='".$datanya['idproduk']."' AND idreseller_grup <> '".$reseller_bayar."' AND min_beli=1";
			  } else {
			     $wheredisk = "idproduk='".$datanya['idproduk']."' AND idreseller_grup = '".$tipemember."' AND min_beli=1";
			  }
			  $carihargadisk = $dtFungsi->fcaridata2('_produk_diskon','harga',$wheredisk);
			  $hrgdiskon = $carihargadisk[0];
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
			  //echo 'tessssssss '.DIR_IMAGE.'_thumb/thumbs_katalog'.$katalogprod;
		?>
		<div class="listkatalog" rel="semiinfo-produk<?php echo $asid ?>">
            <?php echo $platdiskon ?>
		   
			<a class="imgkatalog" href="<?php echo URL_PROGRAM.$dkategorialias.'/'.$datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>">
				<?php 
				$dirimagekatalog = str_replace("//","/",DIR_IMAGE.'_thumb/thumbs_katalog'.$katalogprod);
				
				if(file_exists($dirimagekatalog)) { ?>
				<img src="<?php echo URL_IMAGE.'_thumb/thumbs_katalog'.$katalogprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
				<?php } else { ?>
				<img src="<?php echo URL_IMAGE.'lain/noimage300.jpg'; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
				<?php } ?>
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
		  </div>
		</div>
	 