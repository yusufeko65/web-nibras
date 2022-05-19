<?php
class modelPropinsi
{
	private $db;
	private $tabelnya;

	function __construct()
	{
		$this->tabelnya = '_provinsi';
		$this->db 		= new Database();
		$this->db->connect();
	}

	function checkDataPropinsi($provinsi_nama)
	{
		$check = $this->db->query("select provinsi_nama from " . $this->tabelnya . " where provinsi_nama='$provinsi_nama'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function checkDataPropinsiByID($provinsi_id)
	{
		$check = $this->db->query("select provinsi_nama from " . $this->tabelnya . " where provinsi_id='$provinsi_id'");
		$jml = $check->num_rows;
		if ($jml > 0) return true;
		else return false;
	}

	function simpanPropinsi($data)
	{
		return $this->db->query("insert into " . $this->tabelnya . " values (null,'" . $data['propinsi_nama'] . "','" . $data['propinsi_negara'] . "')");
	}

	function editPropinsi($data)
	{
		return $this->db->query("update " . $this->tabelnya . " set provinsi_nama='" . $data['propinsi_nama'] . "',negara_id='" . $data['propinsi_negara'] . "' where provinsi_id='" . $data['propinsi_id'] . "'");
	}

	function getPropinsi()
	{
		$arprovinsi = array();
		$strsql = $this->db->query("select * from " . $this->tabelnya . " order by provinsi_nama asc ");
		foreach ($strsql->rows as $rsa) {
			$arprovinsi[] = array(
				'idp' => $rsa['provinsi_id'],
				'nmp' => $rsa['provinsi_nama']
			);
		}
		return $arprovinsi;
	}
	function getPropinsiLimit($batas, $baris, $data)
	{

		$hasil = array();
		$where = '';
		$filter = array();

		if ($data['caridata'] != '') $filter[] = " provinsi_nama like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'";

		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where) $where = " WHERE " . $where;
		$sql = "SELECT provinsi_id, provinsi_nama, _negara.negara_nama FROM _provinsi INNER JOIN _negara ON _provinsi.negara_id=_negara.negara_id " . $where . " ORDER BY _provinsi.provinsi_id desc limit $batas,$baris";

		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}
	function totalPropinsi($data)
	{

		$where = '';
		if ($data['caridata'] != '') $filter[] = " provinsi_nama like '%" . trim(strip_tags($this->db->escape($data['caridata']))) . "%'";

		if (!empty($filter))	$where = implode(" and ", $filter);

		if ($where != '') $where = " where " . $where;
		$strsql = $this->db->query("select count(*) as total from _provinsi INNER JOIN _negara ON _provinsi.negara_id=_negara.negara_id " . $where);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	function getPropinsiByID($iddata)
	{
		$strsql = $this->db->query("select * from " . $this->tabelnya . " where provinsi_id='" . $iddata . "'");
		return isset($strsql->row) ? $strsql->row : array();
	}

	function checkRelasi($data)
	{
		$check = $this->db->query("select provinsi_id from _kabupaten where provinsi_id='$data'");
		$jml	= $check->num_rows;

		if ($jml > 0) {
			$chtarif = $this->db->query("select provinsi_id from _tarif_jne where provinsi_id='$data'");
			$jmltarif	= $chtarif->num_rows;
			if ($jmltarif > 0) return true;
			else return false;
		} else {
			return false;
		}
	}
	function hapusPropinsi($data)
	{
		return $this->db->query("delete from " . $this->tabelnya . " where provinsi_id='$data'");
	}
}
