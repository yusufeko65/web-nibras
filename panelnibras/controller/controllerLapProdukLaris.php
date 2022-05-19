<?php
class controllerLapProdukLaris {
	private $model;
	private $Fungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelLapProdukLaris();
		$this->Fungsi= new FungsiUmum();
	}
   
    public function tampilData(){

		$result 			= array();
		$filter				= array();
		$where 				= '';
			
		$data['bulan'] 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
		$data['tahun'] 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
		$wsetting = "setting_key='config_shippingstatus'";
		$settoko 	= $this->Fungsi->fcaridata2("_setting","setting_value",$wsetting);
		$data['status']		= $settoko['setting_value'];
		

		return $this->model->getResult($data,'10');
	}
   
}
?>
