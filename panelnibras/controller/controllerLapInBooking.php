<?php
class controllerLapInBooking
{
 private $page;
 private $rows;
 private $offset;
 private $Fungsi;
 private $model;
 private $data = array();

 public function __construct()
 {
  $this->model  = new modelLapInBooking();
  $this->Fungsi = new FungsiUmum();
 }

 public function tampildata()
 {
  $this->page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $this->rows         = 10;
  $result             = array();
  $filter             = array();
  $where              = '';
  $data['caridata']   = isset($_GET['datacari']) ? $_GET['datacari'] : '';
  $data['status']     = isset($_GET['status']) ? $_GET['status'] : '';

  $result["total"] = 0;
  $result["rows"]  = '';
  $this->offset    = ($this->page - 1) * $this->rows;

  $result["total"]      = $this->model->getTotalOrder($data);
  $result["rows"]       = $this->model->getOrderLimit($this->offset, $this->rows, $data);
  $result["page"]       = $this->page;
  $result["baris"]      = $this->rows;
  $result["jmlpage"]    = ceil(intval($result["total"]) / intval($result["baris"]));
  return $result;
 }
}