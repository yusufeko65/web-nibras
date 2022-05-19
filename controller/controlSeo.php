<?php
class controller_Seo {
  public function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
  }
	public function seourl(){
		$data = array();
		$amenu = isset($_GET['mn']) ? $_GET['mn']:'';
		if($amenu != '') { 
			$parts = explode('/', $amenu);
			$data['menu']	=	'';
			$data['folder']	=	'';
			foreach ($parts as $part) {
				if($part != '') {
					$sql = $this->db->query("SELECT * FROM _url_alias WHERE keyword_alias='".$this->db->escape($part)."'");
			  
					$rs = $sql->row;
					if($rs){
						$inisial = explode('=', $rs['inisial']);
						if($inisial[0] == 'produk') {
							$data['idproduk'] = $inisial[1];
							$data['folder'] = $rs['folder'];
							$data['menu'] = 'detail';					  
						}
				  
						if($inisial[0] == 'kategori') {
							$data['idkategori'] = $inisial[1];
							if($data['folder'] == '') $data['folder'] = $rs['folder'];
							if($data['menu'] == '') $data['menu'] = 'kategori';

						}
				  
						if($inisial[0] == 'produsen') {
							$data['idprodusen'] = $inisial[1];
							if($data['folder'] == '') $data['folder'] = $rs['folder'];
							if($data['menu'] == '') $data['menu'] = 'produsen';
						  
						}
				  
						if($inisial[0] == 'informasi') {
							$data['idinformasi'] = $inisial[1];
							if($data['folder'] == '') $data['folder'] = $rs['folder'];
							if($data['menu'] == '') $data['menu'] = 'informasi';

						}
				  
						if($inisial[0] == 'warna') {
							$data['idwarna'] = $inisial[1];
							if($data['folder'] == '') $data['folder'] = $rs['folder'];
							if($data['menu'] == '') $data['menu'] = 'warna';

						}
				  
						if($inisial[0] == 'ukuran') {
							$data['idukuran'] = $inisial[1];
							if($data['folder'] == '') $data['folder'] = $rs['folder'];
							if($data['menu'] == '') $data['menu'] = 'ukuran';
						}
						
						if($inisial[0] == 'produk-head') {
							$data['idprodukhead'] = $inisial[1];
							if($data['folder'] == '') $data['folder'] = $rs['folder'];
							if($data['menu'] == '') $data['menu'] = 'produkhead';
						}
						
						$data['alias']["$inisial[0]"] = $part;
					} else {
						if($data['menu'] == '' ) {
							$data['menu'] = $part;
						} else {
							$data['modul'] = $part; 
						}
						if($data['folder'] == '') $data['folder'] = $data['menu'];
					}
				}
			}
		}
	
		return $data;
	}
}
?>
