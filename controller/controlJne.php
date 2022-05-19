<?php
//require_once DIR_MODEL."modelJne.php";
class controller_Jne {
  
   private $page;
   private $rows;
   private $offset;
   private $db;
   private $dataModel;
   private $dataFungsi;
   private $data=array();
      
   function __construct(){
		$this->dataModel= new model_Jne();
		$this->dataFungsi= new FungsiUmum();
	}
  
  public function tampildata(){
	$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
	$this->rows		= 10;
	$result 			= array();
	$filter				= array();
	$where = '';
	$caridata	= isset($_GET['datacari']) ? trim($_GET['datacari']):'';
	if($caridata!='') $filter[] = " kecamatan_nama like '%".trim(htmlentities($caridata))."%'";
	if(!empty($filter))	$where = implode(" and ",$filter);
	
	$result["total"] = 0;
	$result["rows"] = '';
	$this->offset = ($this->page-1)*$this->rows;

	$result["total"]   = $this->dataModel->totalJne($where);
	$result["rows"]    = $this->dataModel->getJneLimit($this->offset,$this->rows,$where);
	$result["page"]    = $this->page; 
	$result["baris"]   = $this->rows;
	$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
	
	return $result;
  }
  
  function getServisJne(){
	$jne = $this->dataModel->getServisJne();
	return $jne;
  }
  
  function dataJneByID($iddata){
	$jne = $this->dataModel->getJneByID($iddata);
	return $jne;
  }
  
  
}
?>
