<?php
include "../../../autoloader.php";

if(isset($_GET['load'])) {
   $data = array();
   //$dtFungsi = new FungsiUmum();
   $db = new Database();
   $db->connect();
   switch($_GET['load']) {
	  case 'propinsi':
	    
	    $id 			= isset($_GET['negara']) ? $_GET['negara']:'';
		//echo $id;
	    $where		= 'negara_id='.$db->escape($id).' ORDER by provinsi_nama';
		$sql = "SELECT provinsi_id,provinsi_nama FROM _provinsi Where $where"; 
		
		$query = $db->query($sql);
		$data = array("wilayah"=>$query->rows);
	    //echo json_encode($dtFungsi->fcaridata3('_provinsi','provinsi_id,provinsi_nama',$where));
        echo json_encode($data);
	  break;
	  case 'kabupaten':
		 $id = isset($_GET['propinsi']) ? $_GET['propinsi']:'';
		 $where		= 'provinsi_id='.$db->escape($id). ' ORDER by kabupaten_nama';
		 $sql = "SELECT kabupaten_id,kabupaten_nama FROM _kabupaten Where $where"; 
		 $query = $db->query($sql);
		 echo json_encode($query->rows);
		 //echo json_encode($dtFungsi->fcaridata3('_kabupaten','kabupaten_id,kabupaten_nama',$where));
      break;
      case 'kecamatan':
		 $id = isset($_GET['kabupaten']) ? $_GET['kabupaten']:'';
		 $where		= 'kabupaten_id='.$db->escape($id).' ORDER by kecamatan_nama';
		 $sql = "SELECT kecamatan_id,kecamatan_nama FROM _kecamatan Where $where"; 
		 $query = $db->query($sql);
		 echo json_encode($query->rows);
	     //echo json_encode($dtFungsi->fcaridata3('_kecamatan','kecamatan_id,kecamatan_nama',$where));
	   break;
    }
	  
}
	 