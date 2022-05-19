<div class="modal fade" id="formfilter" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<form name="formfilters" id="formfilters" action="<?php echo URL_PROGRAM ?>" autocomplete="off">
						<div class="modal-header">
							<h5 class="modal-title"><i class="fa fa-filter" aria-hidden="true"></i> Filter</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							
							<h6>Kategori</h6>
							<hr>
							
							<?php if($kategori) { ?>
							<div class="form-group">
								<select class="custom-select" name="fcat" id="fcat">
									<option value="0">- Kategori -</option>
							<?php foreach($kategori as $kat) {?>
							
							<?php    if ($kat['children']) { ?>
									<?php if($kat['kategori_spesial'] == '0') { ?>
									<optgroup label="<?php echo $kat['kategori_nama'] ?>">
									<?php foreach ($kat['children'] as $child) { ?>
									<option value="<?php echo  $child['alias'] ?>" <?php echo isset($j) && $child['alias'] == $j ? 'selected' : '' ?>><?php echo $child['nama'] ?></option>
									<?php } // end looping  ?>
									 </optgroup>
									<?php } ?>
							<?php	 } else { ?>
							<option value="<?php echo  $kat['kategori_alias'] ?>" <?php echo isset($j) && $kat['kategori_alias'] == $j ? 'selected' : '' ?>><?php echo $kat['kategori_nama'] ?></option>
							<?php	 } // end if child ?>
							<?php } // end if looping kategori ?>
								</select>
							</div>
							<?php } ?>
					
							<h6>Warna</h6>
							<hr>
							<div class="form-group">
								<?php if($listwarna) { ?>
								
								<select class="custom-select" name="fwarna" id="fwarna">
									<option value="0">- Warna -</option>
									<?php foreach($listwarna as $warna) { ?>
									<option value="<?php echo $warna['alias'] ?>" <?php echo $warna['alias'] == $fwarna ? 'selected' : '' ?>><?php echo $warna['warna'] ?></option>
									<?php } ?>
								</select>
								<?php } ?>
								
							</div>
							<h6>Ukuran</h6>
							<hr>
							<div class="form-group">
								
								<?php if($listukuran) { ?>
							
								<select class="custom-select" name="fukuran" id="fukuran">
									<option value="0">- Ukuran -</option>
								<?php foreach($listukuran as $ukuran) { ?>
									<option value="<?php echo $ukuran['alias'] ?>" <?php echo $ukuran['alias'] == $fukuran ? 'selected' : '' ?>><?php echo $ukuran['ukuran'] ?></option>
								<?php } ?>
								</select>
								<?php } ?>
								
							</div>
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
						</div>
						</form>
					</div>
				</div>
			</div>
		