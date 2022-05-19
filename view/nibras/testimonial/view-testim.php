
  <div class="title-module">
      <h3><span class="glyphicon glyphicon-comment"></span> Testimonial</h3>
  </div>
  <div class="clearfix"></div>
  <div class="isi-konten">
      <div id="hasil" style="display: none;"></div> 
      <div class="panel panel-default">
	    <div class="panel-body">
		    <div class="pull-right">
			  <a href="<?php echo URL_PROGRAM ?>testimonial/form" class="btn btn-sm btn-primary">Kirim Testimonial</a>
			</div>
			<div class="clearfix"></div>
			<p>
	         <?php foreach($ambildata as $datanya) {?>
			<div class="well well-small komentar">
				<h4><?php echo $datanya['testim_nama'] ?></h4>
				 <div class="tulisan-kecil"><span class="glyphicon glyphicon-time"></span> <?php echo date('d-m-Y H:i', strtotime($datanya['testim_tgl']))?></div>
				 <?php echo trim(htmlentities($datanya['testim_komen'])) ?>  
			 </div>
			 <?php } ?>
			 <?php if($total>0) { ?>
			<!-- Pagging -->
			<div class="pull-left info-paging">
				Hal <?php echo $page ?> dari <?php echo $jmlpage ?>, <?php echo $total ?> Data
			</div>
			<div class="pull-right">
				<ul class="pagination pagination-hs pagination-sm">
					<?php echo $dtPaging->GetPaging2($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?>
				</ul>
			</div>
			<!-- End Pagging -->
			<?php } ?>
	    </div>
	  </div>
  </div>

