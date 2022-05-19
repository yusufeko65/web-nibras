<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span><?php echo $detail['judul'] ?></span></h2>
	</section>
	<div class="col-sm-12">
		<?php echo trim(stripslashes(html_entity_decode($detail['content'])))?>
		<div class="clearfix"></div>
	</div>
</div>