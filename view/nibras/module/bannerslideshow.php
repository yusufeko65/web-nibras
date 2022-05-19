<!-- Banner SlideShow -->
<?php
if ($config_slideshow == '1') {
	$dtSlideshow 	= new controller_SlideShow();
	$slideshow    = $dtSlideshow->getSlideShow();
} else {
	$slideshow = false;
}
if ($slideshow) { ?>
	<div class="container" id="slideshow">
		<div class="carousel slide" data-ride="carousel" id="slide">
			<div class="carousel-inner">

				<?php if ($slideshow) { ?>
					<?php $i = 0 ?>


					<?php foreach ($slideshow as $slides) { ?>
						<?php $class = $i == 0 ? 'active' : '' ?>
						<div class="carousel-item <?php echo $class ?>">
							<a href="<?php echo $slides['url'] ?>"><img class="d-block w-100" src="<?php echo URL_IMAGE . '_other/other_' . $slides['gbr'] ?>" alt="First slide"></a>
						</div>
						<?php $i++ ?>
					<?php } ?>
				<?php } ?>
			</div>
			<a class="carousel-control-prev" href="#slide" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#slide" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	<!-- end Banner SlideShow -->
<?php } ?>