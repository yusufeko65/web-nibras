<div class="container" id="bannerpayment">
	
	<!--
	<section class="product-section">
		<h2 class="section-title"><span></span></h2>
	</section>
	-->
	<hr>
	
	<ul>
		<?php if($bank) { ?>
		
		<?php foreach($bank as $b) { ?>
		<!--<div class="col-sm-2">-->
			<li>
			<img src="<?php echo URL_IMAGE.'_other/other_'.$b['lgs']?>" alt="<?php echo $b['nms'] ?>" title="<?php echo $b['nms'] ?>" class="rounded d-block img-fluid">
			</li>
		<!--</div>-->
		<?php } // end foreach bank ?>
	
		<?php } // end if bank ?>
		<?php if($shipping) { ?>
		
		<?php foreach($shipping as $ship) { ?>
		<!--<div class="col-sm-2">-->
			<li>
			<img src="<?php echo URL_IMAGE.$ship['logo'] ?>" class="rounded d-block img-fluid">
			</li>
		<!--</div>-->
		
		<?php } // end foreach shipping ?>
		
	<?php } // end if shipping ?>
	</ul>
	<div class="clearfix"></div>
</div>