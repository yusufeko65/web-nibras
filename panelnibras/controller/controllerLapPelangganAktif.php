<?php
class controllerLapPelangganAktif {
   private $model;
   private $Fungsi;
   private $data=array();
      
   function __construct(){
		$this->model= new modelLapPelangganAktif();
		$this->Fungsi= new FungsiUmum();
   }
   
   public function tampilData(){
      $data = array();
	  $result=array();
	  $data['tglskrg']		= date('Y-m-d H:i:s');
	  $wsetting = "setting_key='config_targetaktif'";
	  $status = isset($_GET['status']) ? $_GET['status']:'1';
	  $grup   = isset($_GET['grup']) ? $_GET['grup']:'0';
	  $settoko 	= $this->Fungsi->fcaridata2("_setting","setting_value",$wsetting);
	  $waktu 	= $settoko[0];
	  $result = $this->model->getPelangganByWaktuBelanja($waktu,$data['tglskrg'],$grup,$status);
	 /* if($status < 1 ) {
	     $a = 1;
	  } else {
	     $a = 2;
	  }
	  */
	  /*foreach($pelanggan as $r) {
	    $jmlbelanja = $this->model->jumlahBelanja($r['id'],$waktu,$data['tglskrg']);
		
		if($jmlbelanja < $a) {
		   $result[] = array(
		     'id' => $r['id'],
			 'kode' => $r['kode'],
			 'nama' => $r['nama'],
			 'grup' => $r['grup']
		  );
		  
		}
	  }
	  */
	  return $result;
   }
   
}
?>
