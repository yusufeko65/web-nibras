<?php
class modelSetting
{
	private $db;
	private $tabelnya;

	function __construct()
	{
		$this->tabelnya = '_setting';
		$this->db 		= new Database();
		$this->db->connect();
	}


	function simpanSetting($data = array())
	{
		$sqlDel 		= $this->db->query("DELETE from " . $this->tabelnya);
		$values			= array();
		$dataconfig 	= '';
		$hasil			= false;

		$i	= 0;

		foreach ($data as $key => $value) {
			if ($key != 'aksi') {

				if (is_array($value)) {

					$value = implode("::", $value);
				} else {
					$value = $this->db->escape($value);
				}
				$values[$i] = "('config','$key','$value')";
				$i++;
			}
		}
		if (count($values) > 0)
			$dataconfig = implode(",", $values);


		if ($dataconfig != '') {
			$sql = "INSERT INTO " . $this->tabelnya . " values " . $dataconfig;

			return $this->db->query($sql);
		}
		return $hasil;
	}

	function getSetting()
	{

		$sql = $this->db->query("SELECT * from " . $this->tabelnya . " WHERE setting_grup = 'config' ");
		if ($sql) {
			$data = array();
			foreach ($sql->rows as $rsa) {
				$data[] = $rsa;
			}
			return $data;
		} else {
			return false;
		}
	}

	function getSettingByKey($key)
	{
		$str = "SELECT * from " . $this->tabelnya . " WHERE setting_key = '" . $key . "'";
		$sql = $this->db->query($str);
		return isset($sql->row) ? $sql->row : false;
	}
	function getSettingByKeys($keys)
	{
		$data = array();
		$key = '';

		$jmlkey = count($keys);
		for ($i = 0; $i < $jmlkey; $i++) {
			$key .= "'" . $keys[$i] . "'";
			if ($i < $jmlkey - 1) {
				$key .= ",";
			}
		}
		$where = ' WHERE setting_key IN (' . $key . ')';
		$sql = "SELECT setting_grup,setting_key,setting_value from " . $this->tabelnya . " $where ";

		$sql = $this->db->query($sql);

		foreach ($sql->rows as $rs) {
			$data[] = $rs;
		}
		return $data;
	}

	public function setSettingByKey($key, $value){
		$sql = "UPDATE _setting SET setting_value = $value WHERE setting_key = '$key'";

		$result = $this->db->query($sql);
		if($result){
			return true;
		} else {
			return false;
		}
	}
}
