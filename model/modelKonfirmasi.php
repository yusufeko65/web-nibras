<?php
class model_Konfirmasi
{
	private $db;
	private $tabelnya;

	public function __construct()
	{
		$this->tabelnya = '_order_konfirmasi_bayar';
		$this->db 		= new Database();
		$this->db->connect();
		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : '';
	}

	public function simpanKonfirm($data)
	{
		$error = array();
		$this->db->autocommit(false);

		$sql = $this->db->query("INSERT INTO " . $this->tabelnya . " 
				values (null,
				'" . $this->db->escape($data['noorder']) . "',
				'" . $data['jmlbayar'] . "',
				'" . $data['bankto'] . "',
				'" . $this->db->escape($data['bankfrom']) . "',
				'" . $this->db->escape($data['norekfrom']) . "',
				'" . $this->db->escape($data['atasnamafrom']) . "',
				'" . $data['tglbayar'] . "',
				'" . $data['sts_now'] . "',
				'" . $data['tglinput'] . "',
				'" . $data['ipdata'] . "',
				'" . $data['buktitransfer'] . "')");
		if (!$sql) $error[] = "Error di table _order_konfirmasi_bayar";

		$sql = $this->db->query("update _order set status_id='" . $data['sts_now'] . "' WHERE pesanan_no='" . $this->db->escape($data['noorder']) . "'");
		if (!$sql) $error[] = "Error di table _order";

		$keterangan = 'Konfirmasi Pembayaran, tanggal transfer ' . $data['tglbayar'];
		$sql = $this->db->query("insert into _order_status values (null,'" . $this->db->escape($data['noorder']) . "','" . $data['tglinput'] . "','" . $data['sts_now'] . "','" . $keterangan . "','" . $this->idlogin . "')");
		if (!$sql) $error[] = "Error di table _order_status";

		if (count($error) > 0) {
			//print_r($error);
			$this->db->rollback();
			return false;
		} else {
			$this->db->commit();
			return true;
		}
	}

	public function checkOrder($data)
	{
		$check = $this->db->query("select pesanan_no from _order where pesanan_no='" . $this->db->escape($data['noorder']) . "' AND status_id = '" . $data['sts_pending'] . "' AND pelanggan_id='".$this->idlogin."'");

		if ($check->num_rows) {
			return true;
		} else {
			return false;
		}
	}

	public function getKonfirm($noorder)
	{
		$sql = $this->db->query("SELECT jml_bayar, bank_dari, bank_rek_dari, bank_atasnama_dari, tgl_transfer, 
								rekening_no, rekening_atasnama, rekening_cabang, bank_nama, buktitransfer
								FROM _order_konfirmasi_bayar INNER JOIN _bank_rekening
								ON _order_konfirmasi_bayar.bank_rek_tujuan = _bank_rekening.rekening_id INNER JOIN _bank 
								ON _bank_rekening.bank_id = _bank.bank_id
								WHERE order_pesan = '" . $noorder . "'");
		return isset($sql->row) ? $sql->row : false;
	}

	public function Simpan($data)
	{
		/*$sql = $this->db-query("INSERT INTO ".$this->tabelnya." values ('',
							'".$data['noorder']."',
							'".$data['jmlbayar']."',
							'".$data['bankto']."',
							'".$data['bankfrom']."',
							'".$data['norekfrom']."',
							'".$data['atasnamafrom']."',
							'".$data['tglbayar']."',
							'".$data['status']."',
							'".$data['tglinput']."',
							'".$data['ipdata']."')");
	    */
		/* if($sql) return $this->UpdateStatusOrder($data);
	    else return false;
		*/
		$sql = "INSERT INTO " . $this->tabelnya . " values ('',
							'" . $this->db->escape($data['noorder']) . "',
							'" . $data['jmlbayar'] . "',
							'" . $data['bankto'] . "',
							'" . $this->db->escape($data['bankfrom']) . "',
							'" . $this->db->escape($data['norekfrom']) . "',
							'" . $this->db->escape($data['atasnamafrom']) . "',
							'" . $data['tglbayar'] . "',
							'" . $data['sts_now'] . "',
							'" . $data['tglinput'] . "',
							'" . $data['ipdata'] . "',
							'" . $data['buktitransfer'] . "')";
		return $sql;
	}

	public function UpdateStatusOrder($data)
	{
		/*$sql = $this->db-query("update _order set status_id='".$data['status']."' WHERE pesanan_no='".$data['noorder']."'");*/
		/*
	   if( $sql ) return $this->SimpanStatusOrder($data);
	   else return false;
	   */
		$sql = "update _order set status_id='" . $data['sts_now'] . "' WHERE pesanan_no='" . $this->db->escape($data['noorder']) . "'";
		return $sql;
	}

	public function SimpanStatusOrder($data)
	{
		$keterangan = 'Konfirmasi Pembayaran, tanggal transfer ' . $data['tglbayar'];
		/*$sql = $this->db-query("insert into _order_status values ('','".$data['noorder']."','".$data['tglinput']."','".$data['status']."','".$keterangan."')"); */
		/*
	   if($sql) return true;
	   else return false;
	   */
		$sql = "insert into _order_status values ('','" . $this->db->escape($data['noorder']) . "','" . $data['tglinput'] . "','" . $data['sts_now'] . "','" . $keterangan . "')";
		return $sql;
	}


	public function prosesTransaksi($proses)
	{
		$jmlproses = count($proses);
		$this->db->autocommit(false);
		for ($i = 0; $i < $jmlproses; $i++) {
			try {

				if (!$this->db->query($proses[$i])) throw new Exception('Gagal Proses');
			} catch (Exception $e) {
				$this->db->rollback();
				return false;
				break;
			}
		}

		$this->db->commit();
		$this->db->autocommit(true);
		return true;
	}

	public function __destruct()
	{
		$this->db->disconnect();
	}
}
