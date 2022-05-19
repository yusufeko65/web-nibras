<?php
class DataModelCustomerSupport {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_customer_support';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function getCustomerSupport($grup){
		$support = array();
		$strsql=mysql_query("select * from ".$this->tabelnya." INNER JOIN 
		                     _jenis_support ON _customer_support.cs_jsupport=_jenis_support.idjsupport where grup='".$grup."' AND cs_status='1'");
		
		while ($rs=mysql_fetch_array($strsql)){
            $support[] = array(
		       'id' => $rs['idsupport'],
		       'nama' => $rs['cs_nama'],
			   'akun' => $rs['cs_akun'],
			   'idjenis' => $rs['cs_jsupport'],
			   'jenis' => $rs['jenis_support']
	        );
	        
        }
        return $support;
		
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>