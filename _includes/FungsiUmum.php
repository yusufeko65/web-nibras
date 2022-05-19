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
		$sql = $this->db->query("select count(*) as total from $tabel ".$where);
		
		//exit("select count(*) from $tabel ".$where);
		//$rs = $this->db->fetch_row($sql);
		return $sql->row['total'];
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
		$sql = $this->db->query("select $fieldambil from $tabel where $where");
		//echo "select $fieldambil from $tabel where $where";
		return $sql->row;
	}
	public function fcaridata3($tabel,$fieldambil,$where){
	    $data = array();
		$sql = $this->db->query("select $fieldambil from $tabel where $where");
		foreach($sql->rows as $rw){
		   $data[] = $rw["$fieldambil"];
		}
		//return $sql->rows;
		return $data;
	}
	public function cetakcombobox2($juduloption,$ukuran,$id=0,$idobject,$tabel,$fieldoption,$fieldisi,$kelascss,$where=Null){
	   $sql = 'select '.$fieldoption.','.$fieldisi.' from '.$tabel;
	   if($where != Null || $where != '') $sql .= ' WHERE '. $where;
	   // echo $sql;
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
	      if($id==$rs["$fieldoptnya"]) $selected=" selected ";
		  else $selected=' ';
		  
		  $fieldsisi = explode(" as ",$fieldisi);
		  $fieldisinya = isset($fieldsisi[1]) ? $fieldsisi[1] : $fieldisi;
		  
		  
		  $createcombo.='<option value="'.$rs["$fieldoptnya"].'"'.$selected.'>'.$rs["$fieldisinya"].'</option>';
		  
	   }
	   $createcombo.='</select>';
	   // echo $sql;
	   return $createcombo;
    }
    
      public function cetakcombobox3($juduloption,$ukuran,$id=0,$idobject,$tabel,$fieldoption,$fieldisi,$where=Null){
	   $sql = 'select '.$fieldoption.','.$fieldisi.' from '.$tabel;
	   if($where != Null || $where != '') $sql .= ' WHERE '. $where;
	   // echo $sql;
	   $query = $this->db->query($sql);
	   //$createcomborekening= '<select id="'.$idobject.'" name="'.$idobject.'" class="selectbox" style="width:'.$ukuran.'px">';
	   $createcomborekening='';
	   if($juduloption != '') $createcomborekening.= '<option value="0">'.$juduloption.'</option>';
	   while ($rs = $this->db->fetch_row($query)) {
	      if($id==$rs[0]) $selected=" selected ";
		  else $selected=' ';
		  $createcomborekening.='<option value="'.$rs[0].'"'.$selected.'>'.$rs[1].'</option>';
	   }
	   //$createcomborekening.='</select>';
	   // echo $sql;
	   return $createcomborekening;
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
	   while ($rs = $this->db->fetch_row($query)) {
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
	      echo 'ahhhh gagal' . $vfile_upload;
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
	   
	      //set ukuran gambar hasil perubahan untuk ukuran dimensi
          //if($src_width>$dimensi){
	      //   $dst_width = $dimensi;
	      //   $dst_height = ($dst_width/$src_width)*$src_height;
          //}else{
	      //   $dst_width=$src_width;
	      //   $dst_height=$src_height;
          //}
	   
	      /* tambahan untuk resize berdasarkan ukuran misalnya 80 x 80 */
		  $xpos = 0;
		  $ypos = 0;
		  
		  if(empty($dimensi2)) {
			  $dimensi2 = $dimensi;
		  }
		  
		  $width = (int)$dimensi;
		  $height = (int)$dimensi2;

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
	   
	      //proses perubahan ukuran gambar 
         // $im = imagecreatetruecolor($dst_width,$dst_height);
         // imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		  
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
	
	public function UploadImage($fotonya,$nmfileasli,$nmfilenya){
      $vfile_upload = DIR_IMAGE.$nmfileasli;
	  
	  //Simpan gambar dalam ukuran sebenarnya
      if(!move_uploaded_file($fotonya, $vfile_upload)) {
	      echo 'ahhhh gagal' . $vfile_upload;
		  exit;
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
		  
		  
          //set ukuran gambar hasil perubahan untuk ukuran thumb
          if($src_width>180){
	         $dst_width = 180;
	         $dst_height = ($dst_width/$src_width)*$src_height;
          }else{
	         $dst_width=$src_width;
	         $dst_height=$src_height;
          }
		 /*
		 $dst_width = 175;
	     $dst_height = 233; */
		 
		 //$dst_width = 220;
	     //$dst_height = 294;
	   
		 //proses perubahan ukuran gambar thumb
		 $im = imagecreatetruecolor($dst_width,$dst_height);
         imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

       //set ukuran gambar hasil perubahan ukuran detail(gambar besar)
       if($src_width > 280){
	      $dst_width2 = 280;
	      $dst_height2 = ($dst_width2/$src_width)*$src_height;
       } else {
	      $dst_width2 = $src_width;
	      $dst_height2 = $src_height;
       }

       //proses perubahan ukuran gambar besar
       $im2 = imagecreatetruecolor($dst_width2,$dst_height2);
       imagecopyresampled($im2, $im_src, 0, 0, 0, 0, $dst_width2, $dst_height2, $src_width, $src_height);
	   
	   
	   //set ukuran gambar hasil perubahan ukuran zoom(gambar zoom)
       if($src_width > 700){
	      $dst_width3 = 700;
	      $dst_height3 = ($dst_width3/$src_width)*$src_height;
       } else {
	      $dst_width3 = $src_width;
	      $dst_height3 = $src_height;
       }

       //proses perubahan ukuran gambar zoom
       $im3 = imagecreatetruecolor($dst_width3,$dst_height3);
       imagecopyresampled($im3, $im_src, 0, 0, 0, 0, $dst_width3, $dst_height3, $src_width, $src_height);
	   
	   //set ukuran gambar hasil perubahan untuk ukuran small
       if($src_width>60){
	       $dst_width4 = 60;
	       $dst_height4 = ($dst_width4/$src_width)*$src_height;
       }else{
	       $dst_width4=$src_width;
	       $dst_height4=$src_height;
       }

       //proses perubahan ukuran gambar SMALL
       $im4 = imagecreatetruecolor($dst_width4,$dst_height4);
       imagecopyresampled($im4, $im_src, 0, 0, 0, 0, $dst_width4, $dst_height4, $src_width, $src_height);
	   
	   //set ukuran gambar hasil perubahan untuk ukuran MIDDLE
       //if($src_width>100){
	   //    $dst_width5 = 100;
	       //$dst_height5 = ($dst_width5/$src_width)*$src_height;*/
		//   $dst_height5 = 100;
       //}else{
	   //    $dst_width5=$src_width;
	   //    $dst_height5=$src_height;
       //}
	   
	   /* if($src_height>100){
	       $dst_height5 = 100;
		   $dst_width5  = $dst_height5 * ($src_width/$src_height);
       }else{
	       $dst_width5=$src_width;
	       $dst_height5=$src_height;
       }

       //proses perubahan ukuran gambar MIDDLE
       $im5 = imagecreatetruecolor($dst_width5,$dst_height5);
       imagecopyresampled($im5, $im_src, 0, 0, 0, 0, $dst_width5, $dst_height5, $src_width, $src_height);*/
	   /* tambahan untuk resize berdasarkan ukuran misalnya 80 x 80 */
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
		    Imagegif($im,DIR_IMAGE . "_thumb/thumbs_".$nmfilenya);
		    Imagegif($im2,DIR_IMAGE . "_detail/detail_" . $nmfilenya);
			Imagegif($im3,DIR_IMAGE . "_zoom/zoom_" . $nmfilenya);
			Imagegif($im4,DIR_IMAGE . "_small/small_" . $nmfilenya);
			Imagegif($image_new,DIR_IMAGE . "_middle/middle_" . $nmfilenya);
			//Imagegif($im5,DIR_IMAGE . "_middle/middle_" . $nmfilenya);
          break;
          case 2:       //JPEG
		    imagejpeg($im,DIR_IMAGE."_thumb/thumbs_".$nmfilenya,100);           
		    imagejpeg($im2,DIR_IMAGE."_detail/detail_".$nmfilenya,100);
			imagejpeg($im3,DIR_IMAGE."_zoom/zoom_".$nmfilenya,100);
			imagejpeg($im4,DIR_IMAGE."_small/small_".$nmfilenya,100);
			imagejpeg($image_new,DIR_IMAGE."_middle/middle_".$nmfilenya,100);
			//imagejpeg($im5,DIR_IMAGE."_middle/middle_".$nmfilenya,100);
		  break;
          case 3:       //PNG
		    ImagePNG($im,DIR_IMAGE."_thumb/thumbs_".$nmfilenya); 
		    ImagePNG($im2,DIR_IMAGE . "_detail/detail_" . $nmfilenya);
			ImagePNG($im3,DIR_IMAGE . "_zoom/zoom_" . $nmfilenya);           
			imagePNG($im4,DIR_IMAGE."_small/small_".$nmfilenya);
			imagePNG($image_new,DIR_IMAGE."_middle/middle_".$nmfilenya);
			//imagejpeg($im5,DIR_IMAGE."_middle/middle_".$nmfilenya,100);
		  break;
          default:
           return false;
          break;
       }

       //imagedestroy($im_src);
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
	function __destruct() {
		$this->db->disconnect();
	}
} 