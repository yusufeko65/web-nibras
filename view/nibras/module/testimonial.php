<!-- testimonial -->
   <div class="col-md-12">
     <div class="title-module">
       <h3><span class="glyphicon glyphicon-bullhorn"></span> Testimonial</h3>
     </div>
     <div class="clearfix"></div>
	 <div class="text-right"><a href="<?php echo URL_PROGRAM ?>testimonial">Lihat Testimonial</a></div>
     <div class="testim-area">
      <div id="testimhead" class="carousel slide" data-ride="carousel">
         <div class="carousel-inner">
		 <?php $i = 0 ?>
		 <?php foreach($ambiltestim as $datanya) { ?>
		 <?php 
		        if($i < 1) $kelas = "item active";
				else $kelas='item';
		  ?>
			<div class="<?php echo $kelas ?>">
			  <p><?php echo $datanya['testim_komen'] ?></p>
			  <div class="text-right">&#8212; <?php echo $datanya['testim_nama'] ?></div>
		    </div>
		 <?php $i++ ?>
		 <?php } ?>
		 </div>
	   </div>
	 </div>
	 
   </div>
   
 <!-- /testimonial -->