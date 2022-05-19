<?php
class FungsiUmum {
    function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
	}
   
	public function pesandata($pesan,$modulnya){
	   if($pesan!="") $pesan='gagal|'.$modulnya.'|'.DATA_SIMPAN_GAGAL.$pesan;
	   else $pesan='sukses|'.$modulnya.'|'.DATA_SIMPAN_SUKSES;
	   
	   return $pesan;
    }
	
	public function cariBulan($bulan){
		
		$nmbulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		return $nmbulan[$bulan];
		
	}
	
	public function urutkan($a,$subkey) {
	    foreach($a as $k=>$v) {
		   $b[$k] = strtolower($v[$subkey]);
	    }
	    asort($b);
	    foreach($b as $key=>$val) {
		   $c[] = $a[$key];
	    }
	    return $c;
    }
	
	public function fjumlahdata($tabel,$where=Null){
		$sql = "select count(*) as total from $tabel ".$where;
		//echo $sql;
		$sql = $this->db->query($sql);
		
		return isset($sql->row['total']) ? $sql->row['total'] : 0;
	}
	public function fcaridata($tabel,$fieldambil,$fieldkondisi,$data){
	    $where = '';
	    if($fieldkondisi != '' && $data != '') {
		    $where = " WHERE $fieldkondisi = '".$data."'";
		}
		
		$sql = $this->db->query("select $fieldambil from $tabel $where");
		//echo "select $fieldambil from $tabel $where";
		if ($sql->num_rows) {
			return $sql->row["$fieldambil"];
		} else {
			return 0;
		}
		
		
	}
	public function fcaridata2($tabel,$fieldambil,$where){
		$w = '';
		if($where != null || $where != '') {
			$w = ' where '.$where;
		}
		$sql = "select $fieldambil from $tabel".$w; 
		
		$strsql = $this->db->query($sql);
		
		return isset($strsql->row) ? $strsql->row : false;
	}
	public function fcaridata3($tabel,$fieldambil,$where){
	   
		$w = '';
		if($where != null || $where != '') {
			$w = ' where '.$where;
		}
		$sql = $this->db->query("select $fieldambil from $tabel".$w);
		if($sql){
			$data = array();
			foreach($sql->rows as $rw){
			   //$data[] = $rw["$fieldambil"];
			   $data[] = $rw;
			}
			return $data;
		} else {
			return false;
		}
		//return $sql->rows;
		
	}
	public function cetakcombobox2($juduloption,$ukuran,$id=0,$idobject,$tabel,$fieldoption,$fieldisi,$kelascss,$where=Null){
		$sql = 'select '.$fieldoption.' as opt,'.$fieldisi.' as value from '.$tabel;
		if($where != Null || $where != '') $sql .= ' WHERE '. $where;
	    
		$query = $this->db->query($sql);
	   
		if($kelascss == ''){
			$kelascss = " class=\"selectbox\"";
		} else {
			$kelascss = " class=\"$kelascss\"";
		}
		if($ukuran != ''){
			$style = ' style ="width:'.$ukuran.'px"';
		} else {
			$style = '';
		}
	   //echo $sql;
		$createcombo = '<select id="'.$idobject.'" name="'.$idobject.'"'.$kelascss.$style.'>';
		if($juduloption != '') $createcombo.= '<option value="0">'.$juduloption.'</option>';
		foreach($query->rows as $rs) {
			$fieldsopt = explode(" as ",$fieldoption);
			$fieldoptnya = isset($fieldsopt[1]) ? $fieldsopt[1] : $fieldoption;
			if($id==$rs["opt"]) $selected=" selected ";
			else $selected=' ';

			$fieldsisi = explode(" as ",$fieldisi);
			$fieldisinya = isset($fieldsisi[1]) ? $fieldsisi[1] : $fieldisi;


			$createcombo.='<option value="'.$rs["opt"].'"'.$selected.'>'.$rs["value"].'</option>';
		  
		}
		$createcombo.='</select>';
	   // echo $sql;
		return $createcombo;
    }
    
    public function cetakcombobox3($juduloption,$ukuran,$id=0,$idobject,$tabel,$fieldoption,$fieldisi,$where=Null){
		$sql = 'select '.$fieldoption.' as value,'.$fieldisi.' as caption from '.$tabel;
		if($where != Null || $where != '') $sql .= ' WHERE '. $where;
		
		$query = $this->db->query($sql);
	   
		$createcombo='';
		if($juduloption != '') $createcombo.= '<option value="0">'.$juduloption.'</option>';
		if($query) {
			foreach($query->rows as $rs) {
				if($id==$rs['value']) $selected=" selected ";
				else $selected=' ';
				$createcombo.='<option value="'.$rs['value'].'"'.$selected.'>'.trim($rs['caption']).'</option>';
			}
		}
	   
	   return $createcombo;
    } 
    
	public function cetakcombobox($juduloption,$ukuran,$id=0,$idobject,$tabel,$fieldoption,$fieldisi,$where=Null){
	   $sql = 'select '.$fieldoption.','.$fieldisi.' from '.$tabel;
	   if($where != Null || $where != '') $sql .= ' WHERE '. $where;
	   // echo $sql;
	   $query = $this->db->query($sql);
	   $createcomborekening= '<select id="'.$idobject.'" name="'.$idobject.'" class="selectbox" style="width:'.$ukuran.'px">';
	   if($juduloption != '') $createcomborekening.= '<option value="0">'.$juduloption.'</option>';
	   foreach($query->rows as $rs) {
	      if($id==$rs["$fieldoption"]) $selected=" selected ";
		  else $selected=' ';
		  $createcomborekening.='<option value="'.$rs[$fieldoption].'"'.$selected.'>'.$rs["$fieldisi"].'</option>';
	   }
	   $createcomborekening.='</select>';
	   // echo $sql;
	   return $createcomborekening;
    }
	public function cetakcomboboxmultiple($juduloption,$ukuran,$tinggi,$id=0,$idobject,$tabel,$fieldoption,$fieldisi,$where=Null){
	   $sql = 'select '.$fieldoption.','.$fieldisi.' from '.$tabel;
	   if($where != Null || $where != '') $sql .= ' WHERE '. $where;
	   // echo $sql;
	   $createcomborekening= '<select id="'.$idobject.'" name="'.$idobject.'[]" multiple class="selectbox" style="width:'.$ukuran.'px;height:'.$tinggi.'px">';
	   $query = $this->db->query($sql);
	   if($juduloption != '') $createcomborekening.= '<option value="0">'.$juduloption.'</option>';
	   foreach($query->rows as $rs) {
	      if($id==$rs[0]) $selected=" selected ";
		  else $selected=' ';
		  $createcomborekening.='<option value="'.$rs[0].'"'.$selected.'>'.$rs[1].'</option>';
	   }
	   $createcomborekening.='</select>';
	   // echo $sql;
	   return $createcomborekening;
    }
	public function freadmore($content,$jmldiambil,$batasbaru){
		$string = strip_tags($content);
		$tmp 	= explode(" ", stripslashes($string));
		$jmltemp = count($tmp);

		$tmpartikel[] = "";
		if($jmltemp>2){
			for($i=0;$i<=$jmldiambil;$i++){
				if($i==$batasbaru) $tmp[$i].="<br>";
				$tmpartikel[$i] =  $tmp[$i];			  
				
			}
			$bag_artikel = implode(" ",$tmpartikel);
		}
		return $bag_artikel."...";
	}
	public function fIdAkhir($namatabel,$field){
		$sql = $this->db->query("select MAX($field) as id from $namatabel");
		//echo "select MAX($field) from $namatabel";
		
		return $sql->row['id'];
	}
	public function fEnkrip( $target ) {
		$ret = '';
		for ( $i = 1; $i <= strlen($target); ++$i) {
			$x = substr($target,$i-1,1);
			$ret .= chr((ord($x) + 17) - $i);
		}
		return $ret;
	}

	public function fDekrip( $target ) {
		$ret = '';
		for ( $i = 1; $i <= strlen($target); ++$i) {
			$x = substr($target,$i-1,1);
			$ret .= chr((ord($x) + $i) - 17);
		}
		return $ret;
	}
	public function fFormatuang($rp){ //format uang rupiahs
		return 'Rp. '. number_format($rp, 2, ',', '.');
	}
	public function fuang($rp){ //format uang rupiahs
		return number_format($rp, 0, ',', '.');
	}
	public function array_to_json( $array ){
		if( !is_array( $array ) ){
			return false;
		}
		$associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
		if( $associative ){
			$construct = array();
			foreach( $array as $key => $value ){
				if( is_numeric($key) ){
					$key = "key_$key";
				}
				$key = "\"".addslashes($key)."\"";

				if( is_array( $value )){
					$value = array_to_json( $value );
				} else if( !is_numeric( $value ) || is_string( $value ) ){
					$value = "\"".addslashes($value)."\"";
				}
				$construct[] = "$key: $value";
			}
			$result = "{ " . implode( ", ", $construct ) . " }";

		} else { // If the array is a vector (not associative):
			$construct = array();
			foreach( $array as $value ){
				if( is_array( $value )){
					$value = array_to_json( $value );
				} else if( !is_numeric( $value ) || is_string( $value ) ){
					$value = "'".addslashes($value)."'";
				}
                $construct[] = $value;
			}
            $result = "[ " . implode( ", ", $construct ) . " ]";
		}
		return $result;
	}
	public function cekHak($folder,$aksi,$methodAjax){
		$error = false;
		$menu = $folder.$aksi;
		if(isset($_SESSION["masukadmin"]) != "xjklmnJk1o~") $error = true;
		if(isset($_SESSION[$menu]) != 1) $error = true;
		if($methodAjax != 1) {
			if($error) die('<div style="text-align:center;margin: 30px auto;font-size:14px">Anda tidak mempunyai akses</div>');
		} else {
			return $error;
		}
	}
	
	public function UploadImagebyUkuran($fotonya,$nmfileasli,$nmfilenya,$dimensi,$dimensi2 = Null){
		$vfile_upload = DIR_IMAGE.$nmfileasli;
	  
		//Simpan gambar dalam ukuran sebenarnya
		if(!move_uploaded_file($fotonya, $vfile_upload)) {
			echo 'upload image ukuran' . $vfile_upload;
			return false;
		} else {
			ini_set("memory_limit", "300M");
			ini_set("max_execution_time", "100");
			$size = getimagesize($vfile_upload);
			switch($size[2])
			{
				case 1:       //GIF
					$im_src = imagecreatefromgif($vfile_upload);
				break;
				case 2:       //JPEG
					$im_src = imagecreatefromjpeg($vfile_upload);
				break;
				case 3:       //PNG
					$im_src = imagecreatefrompng($vfile_upload);
				break;
				default:
					return false;
				break;
			}
			$src_width = imageSX($im_src);
			$src_height = imageSY($im_src);
	   
			/* tambahan untuk resize berdasarkan ukuran misalnya 80 x 80 */
			$xpos = 0;
			$ypos = 0;
		  
			if(empty($dimensi2)) {
				$dimensi2 = $dimensi;
			}
		  
			$width = (int)$dimensi;
			$height = (int)$dimensi2;

			$scale = min($width / $src_width, $height / $src_height);
			$new_width = (int)ceil($src_width * $scale);
			$new_height = (int)ceil($src_height * $scale);			
			$xpos = (int)(($width - $new_width) / 2);
			$ypos = (int)(($height - $new_height) / 2);
		  
			$image_new = imagecreatetruecolor($width, $height);
		  
			if($size[2]==3){
				imagealphablending($image_new, false);
				imagesavealpha($image_new, true);
				$background = imagecolorallocatealpha($image_new, 255, 255, 255, 127);
				imagecolortransparent($image_new, $background);
			} else {
				$background = imagecolorallocate($image_new, 255, 255, 255);
			}
		  
			imagefilledrectangle($image_new, 0, 0, $width, $height, $background);
	
			imagecopyresampled($image_new, $im_src, $xpos, $ypos, 0, 0, $new_width, $new_height, $src_width, $src_height);
			imagedestroy($im_src);
           
			$src_width  = $width;
			$src_height = $height;
		/* selesai tambahan resize dengan ukuran yang berubah2 */
	   
	    
			//Simpan gambar
			switch($size[2]){
				case 1:       //GIF
					//Imagegif($im,DIR_IMAGE . "_other/other_".$nmfilenya);
					Imagegif($image_new,DIR_IMAGE . "_other/other_".$nmfilenya);
				break;
				case 2:       //JPEG
					//imagejpeg($im,DIR_IMAGE."_other/other_".$nmfilenya,100);
					imagejpeg($image_new,DIR_IMAGE."_other/other_".$nmfilenya,100);
				break;
				case 3:       //PNG
					//ImagePNG($im,DIR_IMAGE."_other/other_".$nmfilenya); 
					ImagePNG($image_new,DIR_IMAGE."_other/other_".$nmfilenya); 
				break;
				default:
					return false;
				break;
			}

          //imagedestroy($im_src);
			if(is_file($vfile_upload)){
				$hapus=unlink($vfile_upload);
			}
			return true;
		}
	}
	
	public function UploadImagebyUkuranMulti($fotonya,$nmfileasli,$dataimage){
		$vfile_upload = DIR_IMAGE.$nmfileasli;
	  
		//Simpan gambar dalam ukuran sebenarnya
		if(!move_uploaded_file($fotonya, $vfile_upload)) {
			echo 'upload image ukuran' . $vfile_upload;
			return false;
		} else {
			ini_set("memory_limit", "300M");
			ini_set("max_execution_time", "100");
			$size = getimagesize($vfile_upload);
			switch($size[2])
			{
				case 1:       //GIF
					$im_src = imagecreatefromgif($vfile_upload);
				break;
				case 2:       //JPEG
					$im_src = imagecreatefromjpeg($vfile_upload);
				break;
				case 3:       //PNG
					$im_src = imagecreatefrompng($vfile_upload);
				break;
				default:
					return false;
				break;
			}
			
			$src_width = imageSX($im_src);
			$src_height = imageSY($im_src);
	   
			/* tambahan untuk resize berdasarkan ukuran misalnya 80 x 80 */
			$i = 0;
			
			foreach($dataimage as $image) {
				$xpos = 0;
				$ypos = 0;
				$scale = 1;
				
				$scale_w = $image['panjang'] / $src_width;
				$scale_h = $image['lebar'] / $src_height;
				
				$scale = min($scale_w, $scale_h);
				
				$new_width = (int)ceil($src_width * $scale);
				$new_height = (int)ceil($src_height * $scale);	
				
				$xpos = (int)(($image['panjang'] - $new_width) / 2);
				$ypos = (int)(($image['lebar'] - $new_height) / 2);
				
				$im = imagecreatetruecolor($image['panjang'],$image['lebar']);
				
				if($size[2]==3){
					imagealphablending($im, false);
					imagesavealpha($im, true);
					$background = imagecolorallocatealpha($im, 255, 255, 255, 127);
					imagecolortransparent($im, $background);
				} else {
					$background = imagecolorallocate($im, 255, 255, 255);
				}
				
				imagefilledrectangle($im, 0, 0, $image['panjang'], $image['lebar'], $background);
				imagecopyresampled($im, $im_src, $xpos, $ypos, 0, 0, $new_width, $new_height, $src_width, $src_height);
				
				//Simpan gambar
				switch($size[2]){
					case 1:       //GIF
						Imagegif($im,DIR_IMAGE . "_other/other_".$image['nama_image']);

					break;
					case 2:       //JPEG
						imagejpeg($im,DIR_IMAGE."_other/other_".$image['nama_image'],90);           
						
					
					break;
					case 3:       //PNG
						ImagePNG($im,DIR_IMAGE."_other/other_".$image['nama_image']); 
					
					break;
					default:
						return false;
					break;
				}
				imagedestroy($im);
				$i++;
			}
			
			imagedestroy($im_src);
			if(is_file($vfile_upload)) unlink($vfile_upload);
			
			return true;
		}
	}
	
	public function UploadProduk($fotonya,$nmfileasli,$nmfilenya){
		$vfile_upload = DIR_IMAGE.$nmfileasli;
		
		//Simpan gambar dalam ukuran sebenarnya
		if(!move_uploaded_file($fotonya, $vfile_upload)) {
	      
			return false;
		} else {
			ini_set("memory_limit", "300M");
			ini_set("max_execution_time", "100");
			// echo $vfile_upload;
		  
			$size = getimagesize($vfile_upload);
			switch($size[2])
			{
				case 1:       //GIF
					$im_src = imagecreatefromgif($vfile_upload);
				break;
				case 2:       //JPEG
				   $im_src = imagecreatefromjpeg($vfile_upload);
				break;
				case 3:       //PNG
				   $im_src = imagecreatefrompng($vfile_upload);
				break;
				default:
				  return false;
				break;
			}
			$src_width = imageSX($im_src);
			$src_height = imageSY($im_src);
			
			$modelsetting = new modelSetting();
			$fieldsetting = array('config_produkthumbnail_p',
								  'config_produkthumbnail_l',
								  'config_produkdetail_p',
								  'config_produkdetail_l',
								  'config_produkzoom_p',
								  'config_produkzoom_l',
								  'config_produksmall_p','config_produksmall_l'
							);
			$setting = $modelsetting->getSettingByKeys($fieldsetting);
			foreach($setting as $st){
				$key 	= $st['setting_key'];
				$value 	= $st['setting_value'];
				${$key} = $value;
			}
			
			/* gambar thumbnail */
			$xpos = 0;
			$ypos = 0;
			$scale = 1;
			
			
			$scale_w = $config_produkthumbnail_p / $src_width;
			$scale_h = $config_produkthumbnail_l / $src_height;
			
			$scale = min($scale_w, $scale_h);
			
			$new_width = (int)ceil($src_width * $scale);
			$new_height = (int)ceil($src_height * $scale);
			$xpos = (int)(($config_produkthumbnail_p - $new_width) / 2);
			$ypos = (int)(($config_produkthumbnail_l - $new_height) / 2);
		  
			
			$im = imagecreatetruecolor($config_produkthumbnail_p,$config_produkthumbnail_l);
			
			if($size[2]==3){
				imagealphablending($im, false);
				imagesavealpha($im, true);
				$background = imagecolorallocatealpha($im, 255, 255, 255, 127);
				imagecolortransparent($im, $background);
			} else {
				$background = imagecolorallocate($im, 255, 255, 255);
			}
			
			imagefilledrectangle($im, 0, 0, $config_produkthumbnail_p, $config_produkthumbnail_l, $background);
			imagecopyresampled($im, $im_src, $xpos, $ypos, 0, 0, $new_width, $new_height, $src_width, $src_height);
			
			/* Gambar Detail */
			$xpos_detil = 0;
			$ypos_detil = 0;
			$scale_detil = 1;
			
			$scale_detil_w = $config_produkdetail_p / $src_width;
			$scale_detil_h = $config_produkdetail_l / $src_height;
			
			$scale_detil = min($scale_detil_w, $scale_detil_h);
			
			$new_detil_width = (int)ceil($src_width * $scale_detil);
			$new_detil_height = (int)ceil($src_height * $scale_detil);
			$xpos_detil = (int)(($config_produkdetail_p - $new_detil_width) / 2);
			$ypos_detil = (int)(($config_produkdetail_l - $new_detil_height) / 2);
			
			$im_detil = imagecreatetruecolor($config_produkdetail_p,$config_produkdetail_l);
			
			if($size[2]==3){
				imagealphablending($im_detil, false);
				imagesavealpha($im_detil, true);
				$background = imagecolorallocatealpha($im_detil, 255, 255, 255, 127);
				imagecolortransparent($im_detil, $background);
			} else {
				$background = imagecolorallocate($im_detil, 255, 255, 255);
			}
			
			imagefilledrectangle($im_detil, 0, 0, $config_produkdetail_p, $config_produkdetail_l, $background);
			imagecopyresampled($im_detil, $im_src, $xpos_detil, $ypos_detil, 0, 0, $new_detil_width, $new_detil_height, $src_width, $src_height);
			
			
			/* Gambar Zoom */
			$xpos_zoom = 0;
			$ypos_zoom = 0;
			$scale_zoom = 1;
			
			$scale_zoom_w = $config_produkzoom_p / $src_width;
			$scale_zoom_h = $config_produkzoom_l / $src_height;
			
			$scale_zoom = min($scale_zoom_w, $scale_zoom_h);
			
			$new_zoom_width = (int)ceil($src_width * $scale_zoom);
			$new_zoom_height = (int)ceil($src_height * $scale_zoom);
			$xpos_zoom = (int)(($config_produkzoom_p - $new_zoom_width) / 2);
			$ypos_zoom = (int)(($config_produkzoom_l - $new_zoom_height) / 2);
			
			$im_zoom = imagecreatetruecolor($config_produkzoom_p,$config_produkzoom_l);
			
			if($size[2] == 3){
				imagealphablending($im_zoom, false);
				imagesavealpha($im_zoom, true);
				$background = imagecolorallocatealpha($im_zoom, 255, 255, 255, 127);
				imagecolortransparent($im_zoom, $background);
			} else {
				$background = imagecolorallocate($im_zoom, 255, 255, 255);
			}
			
			imagefilledrectangle($im_zoom, 0, 0, $config_produkzoom_p, $config_produkzoom_l, $background);
			imagecopyresampled($im_zoom, $im_src, $xpos_zoom, $ypos_zoom, 0, 0, $new_zoom_width, $new_zoom_height, $src_width, $src_height);
			
			/* gambar small */
			$xpos_small = 0;
			$ypos_small = 0;
			$scale_small = 1;
			
			$scale_small_w = $config_produkzoom_p / $src_width;
			$scale_small_h = $config_produksmall_l / $src_height;
			
			$scale_small = min($scale_small_w, $scale_small_h);
			
			$new_small_width = (int)ceil($src_width * $scale_small);
			$new_small_height = (int)ceil($src_height * $scale_small);
			$xpos_small = (int)(($config_produksmall_p - $new_small_width) / 2);
			$ypos_small = (int)(($config_produksmall_l - $new_small_height) / 2);
			
			$im_small = imagecreatetruecolor($config_produksmall_p,$config_produksmall_l);
			
			if($size[2] == 3){
				imagealphablending($im_small, false);
				imagesavealpha($im_small, true);
				$background = imagecolorallocatealpha($im_small, 255, 255, 255, 127);
				imagecolortransparent($im_small, $background);
			} else {
				$background = imagecolorallocate($im_small, 255, 255, 255);
			}
			
			imagefilledrectangle($im_small, 0, 0, $config_produksmall_p, $config_produksmall_l, $background);
			imagecopyresampled($im_small, $im_src, $xpos_small, $ypos_small, 0, 0, $new_small_width, $new_small_height, $src_width, $src_height);
			
			//Simpan gambar
			switch($size[2]){
				case 1:       //GIF
					Imagegif($im,DIR_IMAGE . "_thumb/thumbs_gproduk".$nmfilenya);
					Imagegif($im_detil,DIR_IMAGE . "_detail/detail_gproduk" . $nmfilenya);
					Imagegif($im_zoom,DIR_IMAGE . "_zoom/zoom_gproduk" . $nmfilenya);
					Imagegif($im_small,DIR_IMAGE . "_small/small_gproduk" . $nmfilenya);

				break;
				case 2:       //JPEG
					imagejpeg($im,DIR_IMAGE."_thumb/thumbs_gproduk".$nmfilenya,90);           
					imagejpeg($im_detil,DIR_IMAGE."_detail/detail_gproduk".$nmfilenya,90);
					imagejpeg($im_zoom,DIR_IMAGE."_zoom/zoom_gproduk".$nmfilenya,90);
					imagejpeg($im_small,DIR_IMAGE."_small/small_gproduk".$nmfilenya,90);
				
				break;
				case 3:       //PNG
					ImagePNG($im,DIR_IMAGE."_thumb/thumbs_gproduk".$nmfilenya); 
					ImagePNG($im_detil,DIR_IMAGE . "_detail/detail_gproduk" . $nmfilenya);
					ImagePNG($im_zoom,DIR_IMAGE . "_zoom/zoom_gproduk" . $nmfilenya);           
					imagePNG($im_small,DIR_IMAGE."_small/small_gproduk".$nmfilenya);
				
				break;
				default:
					return false;
				break;
			}
			
			
			imagedestroy($im_src);
			imagedestroy($im);
			imagedestroy($im_detil);
			imagedestroy($im_zoom);
			imagedestroy($im_small);
			
			if(is_file($vfile_upload)){
				$hapus = unlink($vfile_upload);
			}
			return true;
		}
    }
	
	/* Upload Katalog */
	public function UploadKatalog($fotonya,$nmfileasli,$nmfilenya){
      $vfile_upload = DIR_IMAGE.$nmfileasli;
	  
	  //Simpan gambar dalam ukuran sebenarnya
      if(!move_uploaded_file($fotonya, $vfile_upload)) {
	      echo 'ahhhh gagal' . $vfile_upload;
	      return false;
	  } else {
		  /*ini_set("memory_limit", "300M");
		  ini_set("max_execution_time", "100");
          */
		  $size = getimagesize($vfile_upload);
          switch($size[2])
          {
            case 1:       //GIF
               $im_src = imagecreatefromgif($vfile_upload);
            break;
            case 2:       //JPEG
               $im_src = imagecreatefromjpeg($vfile_upload);
            break;
            case 3:       //PNG
               $im_src = imagecreatefrompng($vfile_upload);
            break;
            default:
              return false;
            break;
          }
          $src_width = imageSX($im_src);
          $src_height = imageSY($im_src);
		 
		 $dst_width = 300;
	     $dst_height = 300;
	   
		 //proses perubahan ukuran gambar thumb
		 $scale1 = min($dst_width / $src_width, $dst_height / $src_height);
		 $new_width1 = (int)($src_width * $scale1);
		 $new_height1 = (int)($src_height * $scale1);			
    	 $xpos = (int)(($dst_width - $new_width1) / 2);
   		 $ypos = (int)(($dst_height - $new_height1) / 2);
		
		 $im = imagecreatetruecolor($dst_width,$dst_height);
		  
		  if($size[2]==3){
		     imagealphablending($im, false);
			 imagesavealpha($im, true);
			 $background = imagecolorallocatealpha($im, 255, 255, 255, 127);
			 imagecolortransparent($im, $background);
		  } else {
			 $background = imagecolorallocate($im, 255, 255, 255);
		  }
		  
		 imagefilledrectangle($im, 0, 0, $dst_width, $dst_height, $background);
	     imagecopyresampled($im, $im_src, $xpos, $ypos, 0, 0, $new_width1, $new_height1, $src_width, $src_height);

       //set ukuran gambar hasil perubahan ukuran detail(gambar besar)
       if($src_width > 350){
	      $dst_width2 = 350;
	      $dst_height2 = 350;
       } else {
	      $dst_width2 = $src_width;
	      $dst_height2 = $src_width;
       }

       //proses perubahan ukuran gambar besar
       $scale2 = min($dst_width2 / $src_width, $dst_height2 / $src_height);
	   $new_width2 = (int)($src_width * $scale2);
	   $new_height2 = (int)($src_height * $scale2);			
       $xpos2 = (int)(($dst_width2 - $new_width2) / 2);
   	   $ypos2 = (int)(($dst_height2 - $new_height2) / 2);
	
	   $im2 = imagecreatetruecolor($dst_width2,$dst_height2);
		  
	   if($size[2]==3){
		  imagealphablending($im2, false);
		  imagesavealpha($im2, true);
		  $background = imagecolorallocatealpha($im2, 255, 255, 255, 127);
		  imagecolortransparent($im2, $background);
	   } else {
		  $background = imagecolorallocate($im2, 255, 255, 255);
	   }
		  
	  imagefilledrectangle($im2, 0, 0, $dst_width2, $dst_height2, $background);
	  imagecopyresampled($im2, $im_src, $xpos2, $ypos2, 0, 0, $new_width2, $new_height2, $src_width, $src_height);
	   
	   
	   //set ukuran gambar hasil perubahan ukuran zoom(gambar zoom)
       if($src_width > 650){
	      $dst_width3 = 650;
	      $dst_height3 = 650;
       } else {
	      $dst_width3 = $src_width;
	      $dst_height3 = $src_width;
       }

       //proses perubahan ukuran gambar zoom
       $scale3 = min($dst_width3 / $src_width, $dst_height3 / $src_height);
	   $new_width3 = (int)($src_width * $scale3);
	   $new_height3 = (int)($src_height * $scale3);			
       $xpos3 = (int)(($dst_width3 - $new_width3) / 2);
   	   $ypos3 = (int)(($dst_height3 - $new_height3) / 2);
	
	   $im3 = imagecreatetruecolor($dst_width3,$dst_height3);
		  
	   if($size[2]==3){
		  imagealphablending($im3, false);
		  imagesavealpha($im3, true);
		  $background = imagecolorallocatealpha($im3, 255, 255, 255, 127);
		  imagecolortransparent($im3, $background);
	   } else {
		  $background = imagecolorallocate($im3, 255, 255, 255);
	   }
		  
	  imagefilledrectangle($im3, 0, 0, $dst_width3, $dst_height3, $background);
	  imagecopyresampled($im3, $im_src, $xpos3, $ypos3, 0, 0, $new_width3, $new_height3, $src_width, $src_height);
	   
	   //set ukuran gambar hasil perubahan untuk ukuran small
       if($src_width>60){
	       $dst_width4 = 60;
	       $dst_height4 = 60;
       }else{
	       $dst_width4=$src_width;
	       $dst_height4=$src_width;
       }

       //proses perubahan ukuran gambar SMALL
       $scale4 = min($dst_width4 / $src_width, $dst_height4 / $src_height);
	   $new_width4 = (int)($src_width * $scale4);
	   $new_height4 = (int)($src_height * $scale4);			
       $xpos4 = (int)(($dst_width4 - $new_width4) / 2);
   	   $ypos4 = (int)(($dst_height4 - $new_height4) / 2);
	
	   $im4 = imagecreatetruecolor($dst_width4,$dst_height4);
		  
	   if($size[2]==3){
		  imagealphablending($im4, false);
		  imagesavealpha($im4, true);
		  $background = imagecolorallocatealpha($im4, 255, 255, 255, 127);
		  imagecolortransparent($im4, $background);
	   } else {
		  $background = imagecolorallocate($im4, 255, 255, 255);
	   }
		  
	  imagefilledrectangle($im4, 0, 0, $dst_width4, $dst_height4, $background);
	  imagecopyresampled($im4, $im_src, $xpos4, $ypos4, 0, 0, $new_width4, $new_height4, $src_width, $src_height);
	   
		  $xpos = 0;
		  $ypos = 0;
		  
		  $width = 100;
		  $height = 100;

		  $scale = min($width / $src_width, $height / $src_height);
		  $new_width = (int)($src_width * $scale);
		  $new_height = (int)($src_height * $scale);			
    	  $xpos = (int)(($width - $new_width) / 2);
   		  $ypos = (int)(($height - $new_height) / 2);
		  
          $image_new = imagecreatetruecolor($width, $height);
		  
		  if($size[2]==3){
		     imagealphablending($image_new, false);
			 imagesavealpha($image_new, true);
			 $background = imagecolorallocatealpha($image_new, 255, 255, 255, 127);
			 imagecolortransparent($image_new, $background);
		  } else {
			 $background = imagecolorallocate($image_new, 255, 255, 255);
		  }
		  
		  imagefilledrectangle($image_new, 0, 0, $width, $height, $background);
	
		  imagecopyresampled($image_new, $im_src, $xpos, $ypos, 0, 0, $new_width, $new_height, $src_width, $src_height);
          imagedestroy($im_src);
           
          $src_width  = $width;
          $src_height = $height;
		/* selesai tambahan resize dengan ukuran yang berubah2 */

       //Simpan gambar
       switch($size[2]){
          case 1:       //GIF
		    Imagegif($im,DIR_IMAGE . "_thumb/thumbs_katalog".$nmfilenya);
		    Imagegif($im2,DIR_IMAGE . "_detail/detail_katalog" . $nmfilenya);
			Imagegif($im3,DIR_IMAGE . "_zoom/zoom_katalog" . $nmfilenya);
			Imagegif($im4,DIR_IMAGE . "_small/small_katalog" . $nmfilenya);
			Imagegif($image_new,DIR_IMAGE . "_middle/middle_katalog" . $nmfilenya);
			
          break;
          case 2:       //JPEG
		    imagejpeg($im,DIR_IMAGE."_thumb/thumbs_katalog".$nmfilenya,100);           
		    imagejpeg($im2,DIR_IMAGE."_detail/detail_katalog".$nmfilenya,100);
			imagejpeg($im3,DIR_IMAGE."_zoom/zoom_katalog".$nmfilenya,100);
			imagejpeg($im4,DIR_IMAGE."_small/small_katalog".$nmfilenya,100);
			imagejpeg($image_new,DIR_IMAGE."_middle/middle_katalog".$nmfilenya,100);
			
		  break;
          case 3:       //PNG
		    ImagePNG($im,DIR_IMAGE."_thumb/thumbs_katalog".$nmfilenya); 
		    ImagePNG($im2,DIR_IMAGE . "_detail/detail_katalog" . $nmfilenya);
			ImagePNG($im3,DIR_IMAGE . "_zoom/zoom_katalog" . $nmfilenya);           
			imagePNG($im4,DIR_IMAGE."_small/small_katalog".$nmfilenya);
			imagePNG($image_new,DIR_IMAGE."_middle/middle_katalog".$nmfilenya);
			
		  break;
          default:
           return false;
          break;
       }

       imagedestroy($im_src);
       imagedestroy($im);
       imagedestroy($im2);
	   imagedestroy($im3);
	   imagedestroy($im4);
	   imagedestroy($image_new);
  	   if(is_file($vfile_upload)){
		  $hapus=unlink($vfile_upload);
	   }
	   return true;
	   }
    }
	/* /Upload Katalog */
	
	function hapusfilegambar($namafile,$others=null)
	{
		
		if($others == null) {
			if(file_exists(DIR_IMAGE.'_thumb/thumbs_'.$namafile)) unlink(DIR_IMAGE.'_thumb/thumbs_'.$namafile);
			if(file_exists(DIR_IMAGE.'_detail/detail_'.$namafile)) unlink(DIR_IMAGE.'_detail/detail_'.$namafile);
			if(file_exists(DIR_IMAGE.'_zoom/zoom_'.$namafile)) unlink(DIR_IMAGE.'_zoom/zoom_'.$namafile);
			if(file_exists(DIR_IMAGE.'_small/small_'.$namafile)) unlink(DIR_IMAGE.'_small/small_'.$namafile);
		} else {
			
			if(file_exists(DIR_IMAGE.'_'.$others.'/'.$others.'_'.$namafile)) unlink(DIR_IMAGE.'_'.$others.'/'.$others.'_'.$namafile);
		}
	}
	
	public function friendlyURL($string){
	   $string = preg_replace("`\[.*\]`U","",$string);
	   $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
	   $string = htmlentities($string, ENT_COMPAT, 'utf-8');
	   $string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
	   $string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
	   return strtolower(trim($string, '-'));
    }
	
	public function kekata($x) {
		$x = abs($x);
		$angka = array("", "satu", "dua", "tiga", "empat", "lima",
					   "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($x <12) {
			$temp = " ". $angka[$x];
		} else if ($x <20) {
			$temp = $this->kekata($x - 10). " belas";
		} else if ($x <100) {
			$temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
		} else if ($x <200) {
			$temp = " seratus" . $this->kekata($x - 100);
		} else if ($x <1000) {
			$temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
		} else if ($x <2000) {
			$temp = " seribu" . $this->kekata($x - 1000);
		} else if ($x <1000000) {
			$temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
		} else if ($x <1000000000) {
			$temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
		} else if ($x <1000000000000) {
			$temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
		} else if ($x <1000000000000000) {
			$temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
		}      
		return $temp;
	}
	function terbilang($x, $style=4) {
		if($x<0) {
			$hasil = "minus ". trim($this->kekata($x));
		} else {
			$hasil = trim($this->kekata($x));
		}      
		switch ($style) {
			case 1:
				$hasil = strtoupper($hasil);
			break;
			case 2:
				$hasil = strtolower($hasil);
			break;
			case 3:
				$hasil = ucwords($hasil);
			break;
			default:
				$hasil = ucfirst($hasil);
			break;
		}      
		return $hasil;
	}
	function get_client_ip() {
        $ipaddress = '';
       if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
       else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
       else if(isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
       else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
       else if(isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
       else if(isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
       else
          $ipaddress = 'UNKNOWN';
       
	   return $ipaddress;
    }
	
	public function ftanggalFull2($tanggal){ //format tanggal dari yyyy-mm-dd H:i:s menjadi dd/mm/yyyy
		$xt="";
		$xt = explode(" ",$tanggal);
		$jam = $xt[1];
		$tgl = self::ftanggal2($xt[0]);
		//$full = $tgl;
		
		return $tgl;
	}
	public function ftanggal2($tanggal){ //format tanggal dari yyyy-mm-dd menjadi dd/mm/yyyy
		$xt = "";
		$xt = explode("-",$tanggal);
		$tgl = $xt[2];
		$bln = $xt[1];
		$thn = $xt[0];
		$zg = $tgl."/".$bln."/".$thn;
		return $zg;
	}
	public function ftanggal1($tanggal){ //format tanggal dari dd/mm/yyyy menjadi yyyy-mm-dd
		$xt = "";
		$xt = explode("/",$tanggal);
		$tgl = $xt[0];
		$bln = $xt[1];
		$thn = $xt[2];
		$zg = $thn."-".$bln."-".$tgl;
		return $zg;
	}
	public function ftanggal3($tanggal){ //format tanggal dari yyyy/mm/dd menjadi yyyy-mm-dd
		$tgl = str_replace("/","-",$tanggal);
		return $tgl;
	}
	public function ftanggalFull1($tanggal){ //format tanggal dari yyyy-mm-dd H:i:s menjadi dd/mm/yyyy H:i:s
		$xt="";
		$xt = explode(" ",$tanggal);
		$jam = $xt[1];
		$tgl = self::ftanggal2($xt[0]);
		$full = $tgl." ".$jam;
		
		return $full;
	}
	public function wilayah($j,$data){
	
	}
	
	public function hapusSession($session=array()){
		
		if(count($session) > 0){
			foreach($session as $s){
				unset($_SESSION["$s"]);
			}
		}
		
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
} 