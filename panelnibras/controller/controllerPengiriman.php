<?php
class controllerPengiriman
{
 private $page;
 private $rows;
 private $offset;
 private $Fungsi;
 private $model;
 private $data = array();

 public function __construct()
 {
  $this->model  = new modelPengiriman();
  $this->Fungsi = new FungsiUmum();
 }

 public function editdata($order_id)
 {
  $modulnya = "update";
  $pesan    = "";
  $cek      = $this->Fungsi->cekHak(folder, "edit", 1);
  if ($cek) {
   $pesan  = ' Anda tidak mempunyai Akses untuk mengubah data ';
   $status = 'error';
  } else {
   $result = $this->model->checkSentOrder($order_id);
   if ($result["status_id"] == 16) {
    $simpan = $this->model->editStatus($order_id);
    if ($simpan) {
     $this->model->insertStatusHistory($order_id);
     $status = 'success';
     $pesan  = 'Berhasil input pengiriman';
    } else {
     $status = 'error';
     $pesan  = 'Gagal input pengiriman';
    }
   } elseif ($result["status_id"] == 11) {
    $pesan  = "Status order sudah terkirim";
    $status = 'error';
   } else {
    $pesan  = " Hanya order dengan status sedang packing";
    $status = 'error';
   }
  }
  return array("status" => $status, "message" => $pesan);
 }

 public function tampildata()
 {
  $this->page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $this->rows         = 10;
  $result             = array();
  $filter             = array();
  $where              = '';
  $data['caridata']   = isset($_GET['datacari']) ? $_GET['datacari'] : '';
  $get_kurir          = isset($_GET['kurir']) ? explode('#', $_GET['kurir']) : '';
  $data["nama_kurir"] = !empty($get_kurir) ? $get_kurir[0] : '';
  $data["servis"]     = !empty($get_kurir) ? $get_kurir[1] : '';

  $result["total"] = 0;
  $result["rows"]  = '';
  $this->offset    = ($this->page - 1) * $this->rows;

  $result["list_kurir"] = $this->model->getKurir();
  $result["total"]      = $this->model->getTotalOrder($data);
  $result["rows"]       = $this->model->getOrderLimit($this->offset, $this->rows, $data);
  $result["page"]       = $this->page;
  $result["baris"]      = $this->rows;
  $result["jmlpage"]    = ceil(intval($result["total"]) / intval($result["baris"]));
  return $result;
 }
}
