<?php
class model_Kategori {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_category';
		$this->db 		= new Database();
		$this->db->connect();
	}
	function getQueryKategori($parent_id){
		
		
		$strsql1 = $this->db->query("SELECT c.category_id,cd.name,c.alias_url,c.spesial FROM " . $this->tabelnya . " c INNER JOIN _category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' ORDER BY c.sort_order, LCASE(cd.name) asc");
		if($strsql1) {
			$category_data = array();
			foreach ($strsql1->rows as $rs) {
				$category_data[] = array(
				   'kategori_id' => $rs['category_id'],
				   'kategori_nama' => $rs['name'],
				   'kategori_alias' => $rs['alias_url'],
				   'kategori_spesial' => $rs['spesial']
				);
			}
			return $category_data;
		}
		return false;
        
	}
	
	function getQueryProductHead($category){
		$sql = "select head_idproduk,nama_produk,url_alias from _produk_head where kategori_produk='".$category."' and status_produk='1' ORDER BY head_idproduk DESC";
		$strsql = $this->db->query($sql);
		if($strsql){
			$data = array();
			foreach($strsql->rows as $rs){
				$data[] = array(
					'id' 	=> $rs['head_idproduk'],
					'nama'	=> $rs['nama_produk'],
					'alias'	=> $rs['url_alias']
				);
			}
			return $data;
		}
		return false;
	}
	
	function getKategori($parent_id){
		
		$categories = $this->getQueryKategori($parent_id);
		if($categories){
			$category_data = array();
			foreach ($categories as $category) {
				$children_data = array();
				if($category['kategori_spesial'] == '1') {
					$children = $this->getQueryProductHead($category['kategori_id']);
					if($children){
						foreach($children as $child) {
							$children_data[] = $child;
						}
					}
				} else {
					$children = $this->getQueryKategori($category['kategori_id']);
					if($children){
						foreach($children as $child) {
							$children_data[] = array(
								'id' => $child['kategori_id'], 
								'nama' => $child['kategori_nama'], 
								'alias' => $child['kategori_alias']
							);
						}
					}
				}
				
				
				$category_data[] = array(
					'kategori_id' => $category['kategori_id'],
					'kategori_nama'   => $category['kategori_nama'],
					'children'    => $children_data,
					'kategori_alias' => $category['kategori_alias'],
					'kategori_spesial' => $category['kategori_spesial']
				);
			}
			return $category_data;
		}
		return false;
	}
	
	
	function getKategoriByID($iddata){
		$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _kategori_deskripsi ON _kategori.kategori_id = _kategori_deskripsi.idkategori where kategori_id='".$iddata."'");
		if ($strsql->num_rows) {
		   return $strsql->row;
		} else {
		   return false;
		}
	}
	function getKategoriByIDAlias($iddata,$alias){
		$strsql=$this->db->query("select _category.category_id,image,alias_url,name,description from ".$this->tabelnya." LEFT JOIN _category_description ON _category.category_id = _category_description.category_id where _category.category_id='".$iddata."' AND alias_url='".$alias."'");
		if ($strsql->num_rows) {
		   return $strsql->row;
		} else {
		   return false;
		}
	}
	function totalKategori($where){
	    if($where!='') $where = " where ".$where;
		
		$strsql = $this->db->query("select count(_kategori.kategori_id) as total from ".$this->tabelnya." INNER JOIN _kategori_deskripsi ON _kategori.kategori_id = _kategori_deskripsi.idkategori ".$where);
		return $strsql->row['total'];
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>