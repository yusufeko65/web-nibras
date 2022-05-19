<?php
class modelProduk
{
	private $db;
	private $tabelnya;
	private $sessionuser;

	function __construct()
	{
		$this->tabelnya = '_produk';
		$this->db 		= new Database();
		$this->db->connect();
		$this->sessionuser = isset($_SESSION['userlogin']) ? $_SESSION['userlogin'] : '';
	}

	function checkDataProduk($kode_produk)
	{
		$check = $this->db->query("select kode_produk from _produk where kode_produk='$kode_produk'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataProdukHead($kode_produk)
	{
		$check = $this->db->query("select kode_produk from _produk_head where kode_produk='" . $this->db->escape($kode_produk) . "'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataWarnaProdukOption($produk, $warna)
	{
		$check = $this->db->query("select idproduk,warna from _produk_option where idproduk='$produk' AND warna='$warna'");

		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataProdukByID($idproduk)
	{
		$check = $this->db->query("select kode_produk from " . $this->tabelnya . " where idproduk='$idproduk'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataProdukHeadByID($idproduk)
	{
		$check = $this->db->query("select kode_produk from _produk_head where head_idproduk='" . $idproduk . "'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataGambarProdukByID($id)
	{
		$check = $this->db->query("select gbr from _produk_img where idimg='" . $id . "'");

		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataWarnaProdukByID($id)
	{
		$check = $this->db->query("select idpwarna from _produk_warna where idpwarna='$id'");

		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}
	function simpanHeadProduk($data)
	{
		$error = array();
		$status = '';
		$idproduk = '';
		$this->db->autocommit(false);
		/* simpan ke table _produk_head */
		$sql = $this->db->query("insert into _produk_head values 
							     (null,'" . $data['kode_produk'] . "',
								 '" . $this->db->escape($data['nama_produk']) . "',
								 '" . $data['idkategori'] . "',
								 '" . $this->db->escape(trim($data['keterangan_produk'])) . "',
								 '" . $this->db->escape(trim($data['metatag_deskripsi'])) . "',
								 '" . $this->db->escape(trim($data['metatag_keyword'])) . "',
								 '" . $data['alias_url'] . "','" . $data['produk_logo'] . "',
								 '" . $data['tglupdate'] . "','" . $data['tglupdate'] . "',
								 '" . $this->sessionuser . "','" . $this->sessionuser . "',
								 '" . $data['status_produk'] . "')");

		if (!$sql) {
			$error[] = "Error di table produk";
		} else {
			$idproduk = $this->db->lastid();
		}

		/* end simpan ke table _produk_head */

		/* simpan ke table url_alias */
		$inisial = 'produk-head=' . $idproduk;
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['alias_url'] . "','produk')");

		if (!$sql) $error[] = "Error di table url_alias";
		/* end simpan ke table url_alias */

		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status, "idproduk" => $idproduk);
	}
	function simpanProduk($data)
	{
		$error = array();
		$status = '';
		$idproduk = '';
		$this->db->autocommit(false);
		/* simpan ke table _produk */
		$data['producthead_id'] = empty($data['producthead_id']) || $data['producthead_id'] == '' ? 0 : $data['producthead_id'];
		$sql =  $this->db->query("insert into " . $this->tabelnya . " values (null,
								 '" . $data['kode_produk'] . "','" . $data['jml_stok'] . "',
								 '" . $data['produk_logo'] . "','" . $data['berat_produk'] . "',
								 '" . $data['hrg_jual'] . "','0','" . $data['tglupdate'] . "',
								 '" . $data['tglupdate'] . "',
								 '" . $this->sessionuser . "','" . $this->sessionuser . "',
								 '" . $data['status_produk'] . "',
								 '0','0','0','" . $data['hrg_diskon'] . "','" . $data['persen_diskon'] . "','0',
								 '" . $data['poin'] . "','" . $data['producthead_id'] . "')");

		if (!$sql) {
			$error[] = "Error di table produk";
		} else {
			$idproduk = $this->db->lastid();
		}
		/* simpan ke table produk deskripsi */
		$sql = $this->db->query("insert into _produk_deskripsi values (null,'" . $idproduk . "',
								 '" . $this->db->escape(trim($data['nama_produk'])) . "',
								 '" . $this->db->escape(trim($data['keterangan_produk'])) . "',
								 '" . $this->db->escape(trim($data['metatag_deskripsi'])) . "',
								 '" . $this->db->escape(trim($data['metatag_keyword'])) . "',
								 '" . $data['alias_url'] . "','-')");

		if (!$sql) $error[] = "Error di table produk deskripsi";

		/* simpan ke table url_alias */
		$inisial = 'produk=' . $idproduk;
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['alias_url'] . "','produk')");

		if (!$sql) $error[] = "Error di table url_alias";

		/* simpan ke table produk kategori */
		if (isset($data['idkategori'])) {
			$valuekategori = [];
			$insertkategori = '';
			foreach ($data['idkategori'] as $idkategori) {
				$valuekategori[] = "('" . $idproduk . "','" . $idkategori . "')";
			}
			if (count($valuekategori) > 0) {
				$insertkategori = implode(",", $valuekategori);
			}
			//$sql = $this->db->query("insert into _produk_kategori values ('".$idproduk."','".$data['idkategori']."')");
			if ($insertkategori != '') {
				$sql = $this->db->query("insert into _produk_kategori values $insertkategori");
				if (!$sql) $error[] = "Error di table produk kategori";
			}
		}
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status, "idproduk" => $idproduk);
	}
	function editProdukHead($data)
	{
		$error = array();
		$status = '';
		$this->db->autocommit(false);
		/* table produk head*/
		$sql = "update _produk_head set 
		        kode_produk='" . $data['kode_produk'] . "',
				nama_produk='" . $this->db->escape(trim($data['nama_produk'])) . "',
				kategori_produk='" . $data['idkategori'] . "',
				deskripsi_head='" . $this->db->escape(trim($data['keterangan_produk'])) . "',
				tag_deskripsi='" . $this->db->escape(trim($data['metatag_deskripsi'])) . "',
				tag_keyword='" . $this->db->escape(trim($data['metatag_keyword'])) . "',
				url_alias='" . $this->db->escape($data['alias_url']) . "',
				gbr_produk='" . $data['produk_logo'] . "',
				date_updated='" . $data['tglupdate'] . "',
				updatedby='" . $this->sessionuser . "',
				status_produk='" . $data['status_produk'] . "'
				where head_idproduk='" . $data['idproduk'] . "'";
		$strsql = $this->db->query($sql);
		if (!$sql) $error[] = "Error di table produk head";
		/* end table produk head */

		/* table url alias */
		$inisial = 'produk-head=' . $data['idproduk'];

		$sql = $this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");

		if (!$sql) $error[] = "Error di table url alias delete";

		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['alias_url'] . "','produk')");

		if (!$sql) $error[] = "Error di table url alias insert";
		/* end table url alias */

		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status, "idproduk" => $data['idproduk']);
	}
	function editProduk($data)
	{
		$error = array();
		$status = '';
		$this->db->autocommit(false);

		$sale = $data['hrg_diskon'] > 0 && $data['persen_diskon'] > 0 ? 1 : 0;
		/* sql table _produk */
		$sql = $this->db->query("update " . $this->tabelnya . " set 
								 kode_produk = '" . $data['kode_produk'] . "',
								 jml_stok='" . $data['jml_stok'] . "',
								 gbr_produk='" . $data['produk_logo'] . "',
								 berat_produk='" . $data['berat_produk'] . "',
								 hrg_jual = '" . $data['hrg_jual'] . "',
								 date_updated = '" . $data['tglupdate'] . "',
								 status_produk = '" . $data['status_produk'] . "',
								 hrg_diskon = '" . $data['hrg_diskon'] . "',
								 persen_diskon = '" . $data['persen_diskon'] . "',
								 poin = '" . $data['poin'] . "',
								 dilihat = 0,
								 sale = '" . $sale . "',
								 updatedby = '" . $this->sessionuser . "',
								 head_produk='" . $data['producthead_id'] . "'
								 where idproduk='" . $data['idproduk'] . "'");

		if (!$sql) $error[] = "Error di table produk";

		/* sql table _produk_deskripsi */
		$sql = $this->db->query("update _produk_deskripsi set 
								 nama_produk='" . $this->db->escape(trim($data['nama_produk'])) . "',
								 keterangan_produk='" . $this->db->escape(trim($data['keterangan_produk'])) . "',
								 metatag_deskripsi='" . $this->db->escape(trim($data['metatag_deskripsi'])) . "',
								 metatag_keyword = '" . $this->db->escape(trim($data['metatag_keyword'])) . "',
								 alias_url = '" . $data['alias_url'] . "',tag='-' 
								 where idproduk='" . $data['idproduk'] . "'");

		if (!$sql) $error[] = "Error di table produk deskripsi";

		/* sql url_alias */
		$inisial = 'produk=' . $data['idproduk'];

		$sql = $this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");

		if (!$sql) $error[] = "Error di table url alias delete";

		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['alias_url'] . "','produk')");

		if (!$sql) $error[] = "Error di table url alias insert";

		/* sql kategori */
		/*
		$sql = $this->db->query("UPDATE _produk_kategori SET idkategori = '".$data['idkategori']."' WHERE idproduk = '".$data['idproduk']."'");
		
		if(!$sql) $error[] = "Error di table update _produk_kategori";
		*/
		/* simpan ke table produk kategori */
		if (isset($data['idkategori'])) {
			$valuekategori = [];
			$insertkategori = '';
			foreach ($data['idkategori'] as $idkategori) {
				$valuekategori[] = "('" . $data['idproduk'] . "','" . $idkategori . "')";
			}
			if (count($valuekategori) > 0) {
				$insertkategori = implode(",", $valuekategori);
			}

			if ($insertkategori != '') {

				$sql = $this->db->query("delete from _produk_kategori WHERE idproduk='" . $data['idproduk'] . "'");

				if (!$sql) $error[] = "Error di table url alias delete";

				$sql = $this->db->query("insert into _produk_kategori values $insertkategori");
				if (!$sql) $error[] = "Error di table produk kategori";
			}
		}
		if (count($error) > 0) {
			$this->db->rollback();
			$status = "error";
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status" => $status, "idproduk" => $data['idproduk']);
	}

	function simpanWarnaGambarHeadProduk($data)
	{
		$sql = "select count(*) as jml from _produk_head_warna 
				where idhead_produk='" . $data['idproduk'] . "' and idwarna='" . $data['idwarna'] . "'";
		$check = $this->db->query($sql);
		$result = isset($check->row['jml']) ? $check->row['jml'] : 0;

		if ($result > 0) {
			$sqls = $this->db->query("UPDATE _produk_head_warna	SET
							  image_head='" . $data['imagewarna'] . "'
							  WHERE idhead_produk = '" . $data['idproduk'] . "' AND idwarna='" . $data['idwarna'] . "'");
		} else {

			$sqls = $this->db->query("INSERT INTO _produk_head_warna value (null,'" . $data['idproduk'] . "','" . $data['idwarna'] . "','" . $data['imagewarna'] . "')");
		}

		if ($sqls) {
			$status = 'success';
		} else {
			$status = 'error';
		}

		return array("status" => $status);
	}

	function simpanWarnaGambar($data)
	{
		$this->db->query("UPDATE _produk_img	SET
								 gbr='" . $data['imagewarna'] . "'
								 WHERE idproduk = '" . $data['idproduk'] . "' AND idwarna='" . $data['idwarna'] . "'");
		if (!$this->db->affected_rows()) {
			$sql = $this->db->query("INSERT INTO _produk_img value (null,'" . $data['idproduk'] . "','" . $data['idwarna'] . "','" . $data['imagewarna'] . "')");
			if ($sql) {
				$status = 'success';
			} else {
				$status = 'error';
			}
		} else {
			$status = 'success';
		}
		return array("status" => $status);
	}

	function simpanGambarDetail($data)
	{

		$sql = $this->db->query("INSERT INTO _produk_gbr_detail value ('" . $data['idproduk'] . "','" . $data['imagedetail'] . "')");
		if ($sql) {
			$status = 'success';
		} else {
			$status = 'error';
		}

		return array("status" => $status);
	}

	function getWarnaProdukByProdukWarna($data)
	{
		$sql = "SELECT idproduk,idwarna,gbr FROM _produk_img WHERE idproduk='" . $data['idproduk'] . "' AND idwarna='" . $data['idwarna'] . "'";
		$query = $this->db->query($sql);
		return isset($query->row) ? $query->row : false;
	}
	function getWarnaProdukHeadByProdukWarna($data)
	{
		$sql = "SELECT idhead_produk,idwarna,image_head 
				FROM _produk_head_warna 
				WHERE idhead_produk='" . $data['idproduk'] . "' AND idwarna='" . $data['idwarna'] . "'";
		$query = $this->db->query($sql);
		return isset($query->row) ? $query->row : false;
	}
	function getWarnaProdukByProduk($idproduk)
	{

		$sql = "SELECT idproduk,pi.idwarna,w.warna,gbr FROM _produk_img pi INNER JOIN _warna w ON pi.idwarna = w.idwarna WHERE idproduk='" . $idproduk . "'";

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
	function getWarnaProdukByProdukAndUkuran($idproduk, $idukuran)
	{
		$sql = "select idproduk,po.warna as idwarna,w.warna,po.stok 
				from _produk_options po 
				inner join _warna w
				on po.warna = w.idwarna
				where idproduk='" . $idproduk . "' and ukuran='" . $idukuran . "' and stok > 0";

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	function getWarnaProdukHeadByProduk($idproduk)
	{

		$sql = "SELECT idhead_produk,pi.idwarna,w.warna,image_head 
				FROM _produk_head_warna pi 
				INNER JOIN _warna w ON pi.idwarna = w.idwarna 
				WHERE idhead_produk='" . $idproduk . "'";

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
	function getGambarDetailByProduk($idproduk)
	{
		$data = array();
		$sql = "SELECT idproduk,gbr_detail FROM _produk_gbr_detail WHERE idproduk = '" . $idproduk . "'";
		$query = $this->db->query($sql);
		if ($query) {
			foreach ($query->rows as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function checkStokOption($data)
	{
		$sql = "select count(idproduk) as total from _produk_options where idproduk='" . $data['idproduk'] . "' and ukuran='" . $data['idukuran'] . "' and warna='" . $data['idwarna'] . "'";
		$strsql = $this->db->query($sql);
		$jml = $strsql->row['total'];
		if ($jml > 0) return true;
		else return false;
	}

	function saveStokOption($data)
	{
		$error = array();
		$status = '';
		$user = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"] : 0;
		$this->db->autocommit(false);
		/*
		$sql = $this->db->query("UPDATE _produk_options	SET
								 stok = stok + ".$data['stok_option']."
								 WHERE idproduk = '".$data['idproduk']."' AND warna='".$data['idwarna']."' AND ukuran = '".$data['idukuran']."'");
		*/
		//if(!$this->db->affected_rows()) {
		$sql2 = $this->db->query("INSERT INTO _produk_options values (null,'" . $data['idproduk'] . "','" . $data['stok_option'] . "','" . $data['idukuran'] . "','" . $data['idwarna'] . "','" . $data['tambahan_harga'] . "')");
		if (!$sql2) {
			$error[] = "Error di table produk options";
		}
		//}
		/*
		$sql = $this->db->query("INSERT INTO _produk_option_history 
								 values (null,'".$data['idproduk']."','".$data['idukuran']."',
								 '".$data['idwarna']."','".$data['stok_option']."',
								 '".$data['tglinsert']."','".$user."')");
		if(!$sql) {
			$error[] = "Error di table produk options history";
		}
		*/
		$sql = $this->db->query("UPDATE _produk SET jml_stok = jml_stok + " . $data['stok_option'] . ",
								date_updated='".Date('Y-m-d H:i:s')."',updatedby='".$this->sessionuser."'
								 WHERE idproduk='" . $data['idproduk'] . "'");

		if (!$sql) {
			$error[] = "Error di table produk";
		}
		if (count($error) > 0) {
			$this->db->rollback();
			$status = 'error';
		} else {
			$this->db->commit();
			$status = 'success';
		}
		return array("status" => $status);
	}
	function updateStokOption($data)
	{
		$error = array();
		$this->db->autocommit(false);
		$sql = "update _produk_options set stok = stok + " . $data['stok'] . " 
				where idproduk='" . $data['idproduk'] . "' 
				and warna='" . $data['idwarna'] . "' and ukuran='" . $data['idukuran'] . "'";

		$strsql = $this->db->query($sql);
		if (!$strsql) $error[] = 'Error di table _produk_options';

		$sql = "update _produk set jml_stok = " . $data['stok_option'] . ",
				date_updated='".Date('Y-m-d H:i:s')."',updatedby='".$this->sessionuser."' where idproduk='" . $data['idproduk'] . "'";

		$strsql = $this->db->query($sql);
		if (!$strsql) $error[] = 'Error di table _produk';

		if (count($error) > 0) {
			$this->db->rollback();
			$status = 'error';
		} else {
			$this->db->commit();
			$status = 'success';
		}
		return array("status" => $status);
	}
	/*
	function getStokOptionByWarnaUkuran($idproduk,$idwarna,$idukuran)
	{
		$sql = "SELECT stok FROM _produk_options WHERE idproduk='".$idproduk."' AND warna='".$idwarna."' AND ukuran='".$idukuran."'";
		$query = $this->db->query($sql);
		return isset($query->row['stok']) ? $query->row['stok'] : 0;
	}
	*/
	function getAllStokOptionByProduk($idproduk)
	{
		$data = array();

		$sql = "SELECT po.stok,po.ukuran as idukuran,u.ukuran,po.warna as idwarna,w.warna,po.tambahan_harga 
				FROM _produk_options po 
				LEFT JOIN _ukuran u ON po.ukuran = u.idukuran
				LEFT JOIN _warna w ON po.warna = w.idwarna
				WHERE idproduk = '" . $idproduk . "'";

		$query = $this->db->query($sql);
		if ($query) {
			foreach ($query->rows as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}
	function getStokOptionByProduk($idproduk)
	{
		$sql 	= " select sum(stok) as stok from _produk_options
					where idproduk='" . $idproduk . "'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['stok']) ? $strsql->row['stok'] : 0;
	}
	function hapusStokOption($data)
	{
		$error = array();
		$status = '';
		$user = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"] : 0;
		$this->db->autocommit(false);

		$sql2 = $this->db->query("delete from _produk_options where idproduk='" . $data['idproduk'] . "' and warna='" . $data['idwarna'] . "' and ukuran='" . $data['idukuran'] . "'");
		if (!$sql2) {
			$error[] = "Error di table produk options";
		}
		$sql = $this->db->query("UPDATE _produk SET jml_stok = jml_stok - " . $data['stok'] . " 
								 WHERE idproduk='" . $data['idproduk'] . "'");
		if (!$sql) {
			$error[] = "Error di table produk";
		}
		if (count($error) > 0) {
			$this->db->rollback();
			$status = 'error';
		} else {
			$this->db->commit();
			$status = 'success';
		}
		return array("status" => $status);
	}

	function savehargatambahan($data)
	{
		$sql = "INSERT INTO _produk_item_harga 
				values (null,'" . $data['idproduk'] . "',
				'" . $data['idukuran'] . "','" . $data['idwarna'] . "',
				'" . $data['tambahan_harga'] . "')";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$status = 'success';
		} else {
			$status = 'error';
		}
		return array("status" => $status);
	}

	function getAllHargaTambahanByProduk($idproduk)
	{
		$data = array();

		$sql = "SELECT pih.harga,pih.idukuran as idukuran,u.ukuran,pih.idwarna as idwarna,w.warna 
				FROM _produk_item_harga pih
				LEFT JOIN _ukuran u ON pih.idukuran = u.idukuran
				LEFT JOIN _warna w ON pih.idwarna = w.idwarna
				WHERE idproduk = '" . $idproduk . "'";

		$query = $this->db->query($sql);
		if ($query) {
			foreach ($query->rows as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	function hapustambahharga($data)
	{
		$sql = "DELETE FROM _produk_item_harga 
				WHERE idproduk='" . $data['idproduk'] . "' 
				AND idukuran='" . $data['idukuran'] . "' 
				AND idwarna='" . $data['idwarna'] . "'";
		return $this->db->query($sql);
	}

	function hapustambahhargaperproduk($idproduk)
	{
		$sql = "DELETE FROM _produk_item_harga 
				WHERE idproduk='" . $idproduk . "'";
		return $this->db->query($sql);
	}


	function getProdukByID($iddata)
	{
		$strsql = $this->db->query("select p.idproduk,p.kode_produk,pd.nama_produk,
		                     pd.keterangan_produk,pd.metatag_deskripsi,
							 pd.metatag_keyword,pd.alias_url,pk.idkategori,
                             p.jml_stok,p.gbr_produk,p.berat_produk,
							 p.hrg_jual,p.status_produk,p.hrg_diskon,
							 p.persen_diskon,p.sale,
							 p.poin, p.head_produk as producthead_id,
							 ph.nama_produk as producthead_nama
							 from " . $this->tabelnya . " p 
							 left JOIN _produk_deskripsi pd ON p.idproduk = pd.idproduk
							 left JOIN _produk_kategori pk ON p.idproduk = pk.idproduk
							 LEFT JOIN _produk_head ph ON p.head_produk = ph.head_idproduk
                             where p.idproduk='" . $iddata . "'");

		return isset($strsql->row) ? $strsql->row : false;
	}
	function getProdukHeadByID($iddata)
	{
		$strsql = $this->db->query("select head_idproduk,
								  kode_produk,nama_produk,
								  kategori_produk,cd.name as kategori_nama,
								  deskripsi_head,tag_deskripsi,
								  tag_keyword,url_alias,gbr_produk,status_produk
								  from _produk_head 
								  left join _category_description cd
								  on _produk_head.kategori_produk = cd.category_id
								  where head_idproduk='" . $iddata . "'");

		return isset($strsql->row) ? $strsql->row : false;
	}
	function getProdukLimit($batas, $baris, $data)
	{

		$hasil = array();
		$where = '';
		$filter = array();

		$multifilter = [];
		if ($data['caridata'] != '') {
			/*
			$multisearch = explode(' ',$data['caridata']);
			foreach($multisearch as $kw){
				$multifilter[] = " _produk_deskripsi.nama_produk like '%".trim(strip_tags($kw))."%'";
			}
			if(count($multifilter) > 0) {
				$filter[] = '('.implode(" OR ",$multifilter).')';
			}
			*/
			$kw1 = str_replace(' ', '', $data['caridata']);
			$kw2 = $data['caridata'];

			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim(strip_tags($kw1)) . "%'";
			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim(strip_tags($kw2)) . "%'";
			$multifilter[] = " _produk.kode_produk like '%" . trim(strip_tags($kw1)) . "%'";
			$multifilter[] = " _produk.kode_produk like '%" . trim(strip_tags($kw2)) . "%'";
			if (count($multifilter) > 0) {
				$filter[] = '(' . implode(" OR ", $multifilter) . ')';
			}
			//$filter[] = " _produk_deskripsi.nama_produk like '%".trim(strip_tags($data['caridata']))."%'";
		}
		if ($data['kat'] != '') $filter[] = " _produk_kategori.idkategori = '" . trim(strip_tags($data['kat'])) . "'";
		if ($data['sts'] != '') $filter[] = " _produk.status_produk = '" . trim(strip_tags($data['sts'])) . "'";
		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where) $where = " WHERE " . $where;
		$sql = "SELECT _produk.idproduk,_produk.kode_produk,_produk.status_produk,_produk_deskripsi.nama_produk,_produk.jml_stok,_produk.gbr_produk,_produk.hrg_jual 
				FROM _produk INNER JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk " . $where . " 
				group by _produk.idproduk ORDER BY _produk.idproduk desc limit $batas,$baris";

		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	function totalProduk($data)
	{

		$hasil = array();
		$where = '';
		$filter = array();
		$multifilter = [];
		//if($data['caridata']!='') $filter[] = " _produk_deskripsi.nama_produk like '%".trim(strip_tags($data['caridata']))."%'";
		if ($data['caridata'] != '') {
			$kw1 = str_replace(' ', '', $data['caridata']);
			$kw2 = $data['caridata'];

			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim($this->db->escape($kw1)) . "%'";
			$multifilter[] = " _produk_deskripsi.nama_produk like '%" . trim($this->db->escape($kw2)) . "%'";
			if (count($multifilter) > 0) {
				$filter[] = '(' . implode(" OR ", $multifilter) . ')';
			}
		}
		if ($data['kat'] != '') $filter[] = " _produk_kategori.idkategori = '" . trim(strip_tags($data['kat'])) . "'";
		if ($data['sts'] != '') $filter[] = " _produk.status_produk = '" . trim(strip_tags($data['sts'])) . "'";
		if (!empty($filter))	$where = implode(" and ", $filter);


		if ($where != '') $where = " where " . $where;
		$sql = "select _produk.idproduk from " . $this->tabelnya . " INNER JOIN _produk_deskripsi ON _produk.idproduk = _produk_deskripsi.idproduk LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk " . $where . " group by _produk.idproduk";
		//echo $sql;
		$strsql = $this->db->query($sql);
		$jml = $strsql->num_rows;
		//return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
		return isset($jml) ? $jml : 0;
	}
	function getHeadProdukListAll($batas, $baris, $data, $autocomplete = 0)
	{


		$where = '';
		$filter = array();

		if ($data['caridata'] != '') $filter[] = " _produk_head.nama_produk like '%" . trim(strip_tags($data['caridata'])) . "%'";
		if ($data['kat'] != '') $filter[] = " _produk_head.kategori_produk = '" . trim(strip_tags($data['kat'])) . "'";
		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where) $where = " WHERE " . $where;
		$sql = "SELECT head_idproduk,kode_produk,
				nama_produk,kategori_produk,
				gbr_produk
				FROM _produk_head " . $where . " 
				ORDER BY head_idproduk desc ";
		if ($autocomplete == '0') {
			$sql .= "limit $batas,$baris";
		}

		$strsql = $this->db->query($sql);
		if ($strsql) {
			$hasil = array();
			foreach ($strsql->rows as $rsa) {
				$hasil[] = $rsa;
			}
			return $hasil;
		}
		return false;
	}
	function totalHeadProduk($data)
	{

		$hasil = array();
		$where = '';
		$filter = array();

		if ($data['caridata'] != '') $filter[] = " _produk_head.nama_produk like '%" . trim(strip_tags($data['caridata'])) . "%'";
		if ($data['kat'] != '') $filter[] = " _produk_head.kategori_produk = '" . trim(strip_tags($data['kat'])) . "'";
		if (!empty($filter))	$where = implode(" and ", $filter);


		if ($where != '') $where = " where " . $where;
		$strsql = $this->db->query("select count(*) as total from _produk_head " . $where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function hapusWarnaHeadProduk($data)
	{
		return $this->db->query("delete from _produk_head_warna where idhead_produk='" . $data['idproduk'] . "' AND idwarna='" . $data['idwarna'] . "'");
	}
	function hapusWarna($data)
	{
		return $this->db->query("delete from _produk_img where idproduk='" . $data['idproduk'] . "' AND idwarna='" . $data['idwarna'] . "'");
	}

	function hapusWarnaPerProduk($idproduk)
	{
		return $this->db->query("delete from _produk_img where idproduk='" . $idproduk . "'");
	}

	function hapusGambarDetail($data)
	{
		return $this->db->query("delete from _produk_gbr_detail where idproduk='" . $data['idproduk'] . "' AND gbr_detail='" . $data['image_detail'] . "'");
	}

	function hapusGambarDetailPerProduk($idproduk)
	{
		return $this->db->query("delete from _produk_gbr_detail where idproduk='" . $idproduk . "'");
	}

	function hapusProduk($idproduk)
	{
		/*
		$sql = "delete t1, t2 from ".$this->tabelnya." as t1 INNER JOIN _produk_deskripsi as t2
                              where t1.idproduk = t2.idproduk AND t1.idproduk='$data'";
		*/
		$sql = "delete t1, t2, t3, t4, t5, t6, t8 from " . $this->tabelnya . " as t1 
				LEFT JOIN _produk_deskripsi as t2 ON t1.idproduk = t2.idproduk 
				LEFT JOIN _produk_gbr_detail t3 ON t1.idproduk = t3.idproduk
				LEFT JOIN _produk_img t4 ON t1.idproduk = t4.idproduk
				LEFT JOIN _produk_item_harga t5 ON t1.idproduk = t5.idproduk
				LEFT JOIN _produk_kategori t6 ON t1.idproduk = t6.idproduk
				LEFT JOIN _produk_options t8 ON t1.idproduk = t8.idproduk
                where t1.idproduk='" . $idproduk . "'";

		$check = $this->db->query($sql);

		//if($check) return $this->hapusDeskripsiProduk($data);
		if ($check) return true;
		else return false;
	}

	function hapusKategoriProduk($idproduk)
	{
		$sql = "delete from _produk_kategori where idproduk='" . $idproduk . "'";
		$this->db->query($sql);
	}


	function checkRelasi($id)
	{
		$sql = "SELECT produk_id FROM _order_detail WHERE produk_id='" . $id . "'";
		$check = $this->db->query($sql);
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function getProdukKategori($idproduk)
	{
		$sql = "select idproduk,idkategori from _produk_kategori where idproduk='" . $idproduk . "'";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	function getStokWarnaUkuran($idproduk, $idukuran, $idwarna)
	{
		$sql = "select stok 
				from _produk_options 
				where idproduk='" . $idproduk . "' 
				and ukuran='" . $idukuran . "'
				and warna='" . $idwarna . "'";
		//echo $sql;
		$strsql = $this->db->query($sql);
		return isset($strsql->row['stok']) ? $strsql->row['stok'] : 0;
	}
	public function getOption($id, $warna, $ukuran)
	{
		$sql = "select * from _produk_options where idproduk='" . $id . "' AND warna='" . $warna . "' AND ukuran='" . $ukuran . "'";

		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	public function getProduksBy($cari)
	{
		$sql = "select * from _produk
				INNER JOIN _produk_deskripsi ON 
				_produk.idproduk = _produk_deskripsi.idproduk
				WHERE 
				(_produk_deskripsi.nama_produk like '%" . $cari . "%' OR _produk.kode_produk like '%" . $cari . "%') 
				AND _produk.jml_stok > 0";
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	public function getProdukOption($idproduk, $tipe)
	{
		if ($tipe == 'ukuran') {
			$sql = "select po.ukuran as id,u.ukuran as alias 
					from _produk_options po left join _ukuran u
					on po.ukuran = u.idukuran 
					where po.idproduk='" . $idproduk . "' and po.stok > 0 group by po.ukuran order by u.ukuran asc";
		}
		$strsql = $this->db->query($sql);
		if ($strsql) {
			$data = [];
			foreach ($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
}
