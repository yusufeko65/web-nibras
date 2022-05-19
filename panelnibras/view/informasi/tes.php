<?php 
      $data = array();
      $data['infoid']     = isset($_POST['iddata']) ? $_POST['iddata']:'';
	  $data['judul']	    = isset($_POST['judul']) ? htmlentities($_POST['judul']):'';
	  $data['aliasurl']	= isset($_POST['aliasurl']) ? $_POST['aliasurl']:'';
	  $data['keterangan']	= isset($_POST['keterangan']) ? htmlentities($_POST['keterangan']):'';
	  
	  echo 'tes';
?>