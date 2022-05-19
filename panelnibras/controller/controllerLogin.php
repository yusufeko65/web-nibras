<?php
class controllerLogin
{
	private $Fungsi;
	private $model;
	private $data = array();

	function __construct()
	{
		$this->model = new modelLogin();
		$this->Fungsi	= new FungsiUmum();
	}
	function checkLogin()
	{
		$error = false;
		$this->data['username'] = isset($_POST['username']) ? htmlentities($_POST['username']) : '';
		$this->data['password'] =  isset($_POST['password']) ? $this->Fungsi->fEnkrip($_POST['password']) : '';

		if (strlen($this->data['username']) < 4) {
			$pesan = " Masukkan Username, minimal 4 karakter";
			$error = true;
		} elseif ($this->data['password'] == '') {
			$pesan = " Masukkan Password";
			$error = true;
		}

		if (!$error) {
			$datalogin = $this->model->getLogin($this->data);

			if (!empty($datalogin)) {
				$error = false;
				$_SESSION["idlogin"] 	= $datalogin['login_id'];
				$_SESSION["namalogin"] 	= $datalogin['login_nama'];
				$_SESSION["userlogin"] 	= $datalogin['login_username'];
				$_SESSION["grupnama"] 	= $datalogin['lg_nama'];
				$_SESSION["grupid"] 	= $datalogin['lg_id'];
				$_SESSION["lastlogin"] 	= $datalogin['last_login'];
				/* $_SESSION["masukadmin"] = "xjklmnJk1o~";*/
				$_SESSION['u_token']	= password_hash(uniqid(session_id() . $datalogin['login_id'] . $datalogin['login_username'], true), PASSWORD_DEFAULT);
				$this->model->updateLogin($_SESSION["userlogin"], date('Y-m-d H:i:s'));
				$hakakses = $this->model->hakAkses($datalogin['lg_id']);
				if (!empty($hakakses)) {

					foreach ($hakakses as $hak) {
						$namamenu = $hak['menu_folder'];
						$menuadd = $namamenu . "add";
						$menuedit = $namamenu . "edit";
						$menudel = $namamenu . "del";
						$menuview = $namamenu . "view";
						$_SESSION["$namamenu"] = 1;
						$_SESSION["$menuadd"]	= $hak['add'];
						$_SESSION["$menuedit"] 	= $hak['edit'];
						$_SESSION["$menuview"] 	= $hak['view'];
						$_SESSION["$menudel"] 	= $hak['del'];
					}
					return "sukses|<script>window.location='" . URL_PROGRAM_ADMIN . "home/?u_token=" . $_SESSION['u_token'] . "'</script>";
					//return "sukses";
					exit;
				} else {
					$error = true;
					$pesan = " Error Hak Akses";
				}
			} else {
				$error = true;
				$pesan = " Username dan Password Salah";
			}
		}

		//if($error) return "<script>hasildata('$pesan')</script>";
		if ($error) return 'gagal|' . $pesan;
	}
}
