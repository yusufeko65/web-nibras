<?php
class modelUkuran
{
	private $db;
	private $tabelnya;

	function __construct()
	{
		$this->tabelnya = '_ukuran';
		$this->db 		= new Database();
		$this->db->connect();
	}

	function checkDataUkuran($ukuran)
	{
		$check = $this->db->query("select ukuran from " . $this->tabelnya . " where ukuran='$ukuran'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataUkuranByID($id)
	{
		$check = $this->db->query("select ukuran from " . $this->tabelnya . " where idukuran='$id'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function simpanUkuran($data)
	{
		$sql = $this->db->query("insert into " . $this->tabelnya . " values (null,'" . $data['ukuran_nama'] . "','" . $data['ukuran_alias'] . "','".$data['urutan']."')");
		$inisial = 'ukuran=' . $data['ukuran_id'];

		$del = $this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['ukuran_alias'] . "','produk')");
		if ($sql) return true;
		else return false;
	}

	function editUkuran($data)
	{
		
		$sql = $this->db->query("update " . $this->tabelnya . " 
								set ukuran='" . $data['ukuran_nama'] . "', 
								alias='" . $data['ukuran_alias'] . "',
								order_by='".$data['urutan']."' where idukuran='" . $data['ukuran_id'] . "'");
		$inisial = 'ukuran=' . $data['ukuran_id'];

		$del = $this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['ukuran_alias'] . "','produk')");
		if ($sql) return true;
		else return false;
	}
	function simpanAliasUkuran($data)
	{
		$inisial = 'ukuran=' . $data['ukuran_id'];

		$del = $this->db->query("delete from _url_alias WHERE inisial='" . $inisial . "'");
		$sql = $this->db->query("insert into _url_alias values ('" . $inisial . "','" . $data['ukuran_alias'] . "','produk')");
		if ($sql) return true;
		else return false;
	}
	function getUkuran()
	{
		$arstatus_order = array();
		$strsql = $this->db->query("select * from " . $this->tabelnya . " order by ukuran asc ");
		$rsa = mysql_fetch_array($strsql);
		return $rsa;
	}
	function getUkuranLimit($batas, $baris, $data)
	{

		$hasil = array();
		$where = '';
		$filter = array();

		if ($data['caridata'] != '') $filter[] = " ukuran like '%" . trim($this->db->escape($data['caridata'])) . "%'";
		if (!empty($filter))	$where = implode(" and ", $filter);


		if ($where) $where = " WHERE " . $where;
		$sql = "SELECT idukuran, ukuran, order_by
				FROM _ukuran " . $where . " 
				ORDER BY order_by ASC ";
		if($baris > 0)	{
			$sql .= "limit $batas,$baris";
		}
		

		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}

	function totalUkuran($data)
	{
		$where = '';
		$filter = array();


		if ($data['caridata'] != '') $filter[] = " ukuran like '%" . trim($this->db->escape($data['caridata'])) . "%'";
		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where != '') $where = " where " . $where;

		$strsql = $this->db->query("select count(*) as total from " . $this->tabelnya . $where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}

	function getUkuranByID($iddata)
	{
		$strsql = $this->db->query("select * from " . $this->tabelnya . " where idukuran='" . $iddata . "'");
		return isset($strsql->row) ? $strsql->row : array();
	}

	function checkRelasi($data)
	{
		$check = $this->db->query("select idukuran from _provinsi where provinsi_id='$data'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}
	function hapusUkuran($data)
	{
		return $this->db->query("delete from " . $this->tabelnya . " where idukuran='$data'");
	}
	function __destruct()
	{
		$this->db->disconnect();
	}
}
 