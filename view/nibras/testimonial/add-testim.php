
  <div class="title-module">
      <h3><span class="glyphicon glyphicon-comment"></span> Testimonial</h3>
  </div>
  <div class="clearfix"></div>
  <div class="isi-konten">
      <div id="hasil" style="display: none;"></div> 
	   <div class="panel panel-default">
	    <div class="panel-body">
	     <form class="form-horizontal" method="POST" name="frmkomentar" id="frmkomentar" action="<?php echo URL_PROGRAM.$folder.'/'?>">
	       <div class="pull-right">
			  <a href="<?php echo URL_PROGRAM ?>testimonial/view">Lihat Testimonial</a>
			</div>
			<div class="clearfix"></div>
			<p></p>
           <div class="form-group">
			  <label class="col-md-2 control-label marginkiri">Nama</label> <span class="required">*</span>
			  <div class="col-md-8">
	          <input type="text" class="form-control forms" name="knama" id="knama" placeholder="Nama">
			  </div>
	       </div>
	
	      <div class="form-group">
	         <label class="col-md-2 control-label marginkiri">Email</label> <span class="required">*</span>
			 <div class="col-md-8">
		     <input type="email" class="form-control forms" name="kmail" id="kmail" placeholder="Email">
			 </div>
	      </div>
	     
		   <div class="form-group">
	         <label class="col-md-2 control-label marginkiri">Website</label> 
			 <div class="col-md-8">
		      <input type="text" class="form-control forms" name="kweb" id="kweb" placeholder="Alamat Website">
			 </div>
	      </div>
	   
	       <div class="form-group">
	         <label class="col-md-2 control-label marginkiri">Komentar</label>   <span class="required">*</span>
			 <div class="col-md-8">
		      <textarea name="kkomentar" id="kkomentar" class="form-control forms" placeholder="Komentar"></textarea>
			 </div>
	      </div>

          <div class="form-group">
		    <div class="col-sm-offset-2 col-md-8">
			<button type="button" name="btnok" id="btnok" class="btn btn-primary btn-sm">Kirim Komentar</button>
			</div>
		 </div>
		 <div class="clearfix"></div>
	  </form>
	  </div>
	 </div>
	</div>
</div>