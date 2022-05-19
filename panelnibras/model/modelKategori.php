<?php
class modelKategori
{
	private $db;
	private $tabelnya;

	function __construct()
	{
		$this->tabelnya = '_category';
		$this->db 		= new Database();
		$this->db->connect();
	}

	function checkDataKategori($kategori_nama)
	{
		$check = $this->db->query("select name from _category_description where name='" . $this->db->escape($kategori_nama) . "'");

		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataKategoriByID($kategori_id)
	{
		$check = $this->db->query("select category_id from " . $this->tabelnya . " where category_id='$kategori_id'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function simpanKategori($data = array())
	{
		$error = array();
		$idkategori = '';
		$insert = '';
		$this->db->autocommit(false);

		/* table _Category */
		$sql = "INSERT INTO `_category` 
				SET `parent_id` = '" . (int) $data['kategori_induk'] . "',
				`sort_order` = '" . (int) $data['kategori_urutan'] . "',
                `image`='" . $data['kategori_logo'] . "',
				`date_modified` = '" . $data['tgl'] . "', 
				`date_added` = '" . $data['tgl'] . "',
				`alias_url` = '" . $data['kategori_alias'] . "',
				`spesial`='" . $data['spesial'] . "'";

		$sql = $this->db->query($sql);
		if (!$sql) {
			$error[] = "Error di table _Category";
		} else {
			/* mengambil idkategori yang terakhir diinput */
			$idkategori = $this->db->lastid();
		}

		/* table _category_description */
		$sql = $this->db->query("INSERT INTO `_category_description` SET `category_id` = '" . (int) $idkategori . "', `name` = '" . $data['kategori_nama'] . "',`description`='" . $data['keterangan'] . "',`language_id`=0");
		if (!$sql) $error[] = "Error di table _category_description";

		/* table _url_alias */
		$inisial = 'kategori=' . $idkategori;

		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['kategori_alias'] . "','produk')");
		if (!$sql) $error[] = "Error di table _url_alias";

		/* category _path */
		$level = 0;
		if ($data['pathcategory']) {

			$datavalue = [];
			foreach ($data['pathcategory'] as $cat) {
				$datavalue[] = "('" . $idkategori . "','" . $cat['path_id'] . "','" . $level . "')";
				$level++;
			}
		}
		$datavalue[] = "('" . $idkategori . "','" . $idkategori . "','" . $level . "')";
		if (count($datavalue) > 0) {
			$insert = implode(",", $datavalue);
		}
		if ($insert != '') {
			$sql = $this->db->query("INSERT INTO _category_path values " . $insert);
			if (!$sql) $error[] = "Error di table produk kategori path";
		}

		/* simpan ke table kategori ukuran */
		if (isset($data['idukuran'])) {
			$valueukuran = [];
			$insertukuran = '';
			foreach ($data['idukuran'] as $idukuran) {
				$valueukuran[] = "('" . $idkategori . "','" . $idukuran . "')";
			}
			if (count($valueukuran) > 0) {
				$insertukuran = implode(",", $valueukuran);
			}

			if ($insertukuran != '') {
				$sql = $this->db->query("insert into _category_ukuran values $insertukuran");
				if (!$sql) $error[] = "Error di table _category_ukuran";
			}
		}
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status);
	}
	function simpanDeskripsiKategori($data = array())
	{
		//$sql=$this->db->query("insert into _kategori_deskripsi values ('','".$data['kategori_id']."','".$data['kategori_nama']."')");
		return $this->db->query("INSERT INTO `_category_description` SET `category_id` = '" . (int) $data['kategori_id'] . "', `name` = '" . $data['kategori_nama'] . "',`description`='" . $data['keterangan'] . "',`language_id`=0");
	}
	function simpanPathKategori($data)
	{
		$level = 0;

		$query = $this->db->query("SELECT * FROM _category_path WHERE category_id = '" . (int) $data['kategori_induk'] . "' ORDER BY level ASC");
		//        $result = mysql_fetch_array($query);
		foreach ($query->rows as $result) {

			$this->db->query("INSERT INTO `_category_path` SET `category_id` = '" . (int) $data['kategori_id'] . "', `path_id` = '" . (int) $result['path_id'] . "', `level` = '" . (int) $level . "'");

			$level++;
		}

		$s = $this->db->query("INSERT INTO `_category_path` SET `category_id` = '" . (int) $data['kategori_id'] . "', `path_id` = '" . (int) $data['kategori_id'] . "', `level` = '" . (int) $level . "'");
		if ($s) return true;
		else return false;
	}
	function editKategori($data = array())
	{
		$error = array();
		$this->db->autocommit(false);
		$idkategori = $data['iddata'];
		/* update category */
		$sql = "UPDATE _category SET 
				alias_url = '" . $data['kategori_alias'] . "', 
				date_modified = '" . $data['tgl'] . "', 
				parent_id = '" . $data['kategori_induk'] . "',
				image='" . $data['kategori_logo'] . "', 
				sort_order='" . $data['kategori_urutan'] . "',
				spesial = '" . $data['spesial'] . "' WHERE category_id = '" . (int) $idkategori . "'";
		$sql = $this->db->query($sql);
		if (!$sql) $error[] = "Error di table _category";

		/* update category description */
		$sql = "UPDATE _category_description set name='" . $this->db->escape($data['kategori_nama']) . "',description='" . $data['keterangan'] . "' WHERE category_id='" . $idkategori . "'";
		$sql = $this->db->query($sql);
		if (!$sql) $error[] = "Error di table _category";

		/* update url alias */
		$inisial = 'kategori=' . $idkategori;
		$this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['kategori_alias'] . "','produk')");
		if (!$sql) $error[] = "Error di table _url_alias";

		/* category _path */
		$level = 0;
		if ($data['pathcategory']) {

			$datavalue = [];
			foreach ($data['pathcategory'] as $cat) {
				$datavalue[] = "('" . $idkategori . "','" . $cat['path_id'] . "','" . $level . "')";
				$level++;
			}
		}
		$datavalue[] = "('" . $idkategori . "','" . $idkategori . "','" . $level . "')";
		if (count($datavalue) > 0) {
			$insert = implode(",", $datavalue);
		}

		if ($insert != '') {
			$sql = $this->db->query("delete from _category_path where category_id='" . $idkategori . "'");
			$sql = $this->db->query("INSERT INTO _category_path values " . $insert);
			if (!$sql) $error[] = "Error di table produk kategori path";
		}

		/* simpan ke table _category_ukuran */
		if (isset($data['idukuran'])) {
			$valueukuran = [];
			$insertukuran = '';
			foreach ($data['idukuran'] as $idukuran) {
				$valueukuran[] = "('" . $idkategori . "','" . $idukuran . "')";
			}
			if (count($valueukuran) > 0) {
				$insertukuran = implode(",", $valueukuran);
			}

			if ($insertukuran != '') {

				$sql = $this->db->query("delete from _category_ukuran WHERE category_id='" . $idkategori . "'");

				if (!$sql) $error[] = "Error di table url alias delete";

				$sql = $this->db->query("insert into _category_ukuran values $insertukuran");
				if (!$sql) $error[] = "Error di table _category_ukuran";
			}
		}

		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status);
	}

	function editDeskripsiKategori($data = array())
	{

		$sql = "UPDATE _category_description set name='" . $this->db->escape($data['kategori_nama']) . "',description='" . $data['keterangan'] . "' WHERE category_id='" . $data['kategori_id'] . "'";
		$sql = $this->db->query($sql);
		if ($sql) return true;
		else return false;
	}
	function simpanAliasKategori($data)
	{
		$inisial = 'kategori=' . $data['kategori_id'];

		$del = $this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['kategori_alias'] . "','produk')");
		if ($sql) return true;
		else return false;
	}

	function getPath($category_id)
	{
		$query = $this->db->query("SELECT kategori_nama, kategori_induk FROM " . $this->tabelnya . " INNER JOIN _kategori_deskripsi ON _kategori.kategori_id = _kategori_deskripsi.idkategori WHERE kategori_id = '" . (int) $category_id . "' ORDER BY kategori_nama ASC");

		if ($query->row['kategori_induk'] != 0) {
			return $this->getPath($query->row['kategori_induk']) . " >> " . $query->row['kategori_nama'];
		} else {
			return $query->row['kategori_nama'];
		}
	}
	function getPathInduk($category_id)
	{
		$query = $this->db->query("SELECT * FROM _category_path WHERE category_id = '" . (int) $category_id . "' ORDER BY level ASC");
		return (!$query) ? false  : $query->rows;
	}
	function getKategoriLimit($data, $parent_id = 0)
	{

		$category_data = array();
		$where = '';
		$filter = array();

		if ($data['datacari'] != '') {
			$filter[] = " cd2.name like '%" . trim($this->db->escape($data['datacari'])) . "%'";
		}
		if (isset($data['id'])) {
			if ($data['id'] != '0') {
				$filter[] = " cp.category_id <> '" . $data['id'] . "'";
			}
		}

		if ($data['spesial'] != '') $filter[] = " c1.spesial = '" . trim(strip_tags($data['spesial'])) . "'";

		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where) $where = " WHERE " . $where;

		$strsql  = "SELECT cp.category_id AS category_id,
		        GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, 
				c1.parent_id, c1.sort_order FROM _category_path cp 
				LEFT JOIN _category c1 ON (cp.category_id = c1.category_id) 
				LEFT JOIN _category c2 ON (cp.path_id = c2.category_id) 
				LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id) 
				LEFT JOIN _category_description cd2 ON (cp.category_id = cd2.category_id) " . $where;
		$strsql .= " GROUP BY cp.category_id ORDER BY name ASC";
		//echo $strsql;
		$sql = $this->db->query($strsql);
		foreach ($sql->rows as $rs) {

			$category_data[] = array(
				'kategori_id' => $rs['category_id'],
				'kategori_nama' => $rs['name'],
				'kategori_urutan' => $rs['sort_order']
			);
		}
		return $category_data;
		//return $hasil;
	}

	function getKategoriByIDs($iddata)
	{
		$sql = "SELECT cp.category_id AS category_id,
		        GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, 
				c1.parent_id, c1.sort_order,c1.image,cd2.description,c1.spesial FROM _category_path cp 
				LEFT JOIN _category c1 ON (cp.category_id = c1.category_id) 
				LEFT JOIN _category c2 ON (cp.path_id = c2.category_id) 
				LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id) 
				LEFT JOIN _category_description cd2 ON (cp.category_id = cd2.category_id)";

		$sql .= " WHERE cd2.category_id = '" . $iddata . "'";
		$sql .= " GROUP BY cp.category_id";

		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getKategoriByID($iddata)
	{

		$sql = "SELECT DISTINCT *, 
					 (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM _category_path cp LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id GROUP BY cp.category_id) AS path FROM _category c LEFT JOIN _category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int) $iddata . "'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}

	function checkRelasi($data)
	{
		$check = $this->db->query("select kategori_id from _produk_kategori where idkategori='$data'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}
	function hapusKategori($data)
	{
		$check = $this->db->query("delete from " . $this->tabelnya . " where category_id='$data'");
		if ($check) {
			$inisial = 'kategori=' . $data;
			$checks = $this->db->query("delete from _category_description where category_id = '$data'");
			$checks = $this->db->query("delete from _category_path where category_id = '$data'");
			$checks = $this->db->query("delete from _url_alias where inisial = '" . $inisial . "'");
			if ($checks) return true;
			else return false;
		} else {
			return false;
		}
	}
	function getWarnaKategoriByKategori($id)
	{

		$sql = "SELECT cw.cat_id,cw.cat_warna,w.warna,cw.cat_foto 
			    FROM _category_warna cw 
				INNER JOIN _warna w ON cw.cat_warna = w.idwarna 
				WHERE cw.cat_id='" . $id . "'";

		$query = $this->db->query($sql);
		if ($query) {
			$data = array();
			foreach ($query->rows as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getKategoriUkuran($id)
	{
		$sql = "select u.ukuran,cu.idukuran,cu.category_id
						from _category_ukuran cu
						left join _ukuran u on cu.idukuran = u.idukuran 
						where cu.category_id='" . $id . "'";
		$query = $this->db->query($sql);
		if ($query) {
			$data = array();
			foreach ($query->rows as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	function getListKategori($parent_id)
	{

		$categories = $this->getQueryKategori($parent_id);
		if ($categories) {
			$category_data = array();
			foreach ($categories as $category) {
				$children_data = array();
				if ($category['kategori_spesial'] == '1') {
					$children = $this->getQueryProductHead($category['kategori_id']);
					if ($children) {
						foreach ($children as $child) {
							$children_data[] = $child;
						}
					}
				} else {
					$children = $this->getQueryKategori($category['kategori_id']);
					if ($children) {
						foreach ($children as $child) {
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

	function getQueryKategori($parent_id)
	{

		$sql = "SELECT c.category_id,cd.name,c.alias_url,c.spesial FROM " . $this->tabelnya . " c INNER JOIN _category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int) $parent_id . "' ORDER BY c.sort_order, LCASE(cd.name) asc";
		$strsql1 = $this->db->query($sql);
		if ($strsql1) {
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

	function getQueryProductHead($category)
	{
		$sql = "select head_idproduk,nama_produk,url_alias from _produk_head where kategori_produk='" . $category . "' and status_produk='1' ORDER BY nama_produk asc";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = array();
			foreach ($strsql->rows as $rs) {
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
}
