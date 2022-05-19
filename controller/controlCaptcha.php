<?php
class controller_Captcha {
   private $jmlkarakter;
   private $randomkarakter;
   private $kode;
      
   function __construct(){
		$this->jmlkarakter 		= 6;
		$this->randomkarakter 	= '012345678910abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		//$this->randomkarakter 	= '012345678910';
		$this->kode 			= '';
   }
	
   public function generateCaptcha(){
		$i = 0;
		while ($i < $this->jmlkarakter) { 
			$this->kode .= substr($this->randomkarakter, mt_rand(0, strlen($this->randomkarakter)-1), 1);
			$i++;
		}
		$_SESSION['kodesekuriti'] = $this->kode;
		return $this->kode;
   }
}
?>
