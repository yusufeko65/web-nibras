<?php
class controllerLapStok {
   private $model;
   private $Fungsi;
   private $data=array();
      
   function __construct(){
		$this->model= new modelLapStok();
		$this->Fungsi= new FungsiUmum();
   }
   
   public function tampilData(){

	$result 			= array();
	$filter				= array();
	$where 				= '';

	$settoko 	= $this->Fungsi->fcaridata2("_setting_toko","status_print","setid <> ''");
	$status 	= $settoko[0];
	
	$caridata	= isset($_GET['datacari']) ? $_GET['datacari']:'';
	if($caridata!='') $filter[] = " nama_produk like '%".trim(strip_tags($caridata))."%'";
	
	if(!empty($filter))	$where = implode(" and ",$filter);

	return $this->model->getLapStok($where,$status);
  }
  
   public function tampilDataDetail($iddata){
    $settoko 	= $this->Fungsi->fcaridata2("_setting_toko","status_print","setid <> ''");
	$status 	= $settoko[0];
	return $this->model->getLapStokDetail($iddata,$status);
  }
   
}
?>
