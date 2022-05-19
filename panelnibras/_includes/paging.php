<?php
class Paging{
	private $first;
	private $last;
	private $next;
	private $prev;
	private $showPage;
	private $tlink;
	private $pages;
	
	public function GetPaging($total,$baris,$page,$jmlpage,$linkpage){
		//$jmlpage = ceil(intval($total)/intval($baris));
		$this->first = 1;
		$this->last = $jmlpage;
		$this->showPage = 0;
		$this->tlink='';
		$this->pages = 1;
	
		$op = isset($_GET['op']) ? $_GET['op']:'view';
		$pid = isset($_GET['pid']) ? "&pid=".$_GET['pid']:'';
		//echo $jmlpage;
		if ($jmlpage>0){
			if ($page > 1){
				$this->pages = $page-1;
				$this->prev = "<li><a href=\"".URL_PROGRAM_ADMIN.folder."/?op=$op$pid&page=$this->pages$linkpage \">Prev</a></li>";
				$this->first = "<li><a href=\"".URL_PROGRAM_ADMIN.folder."/?op=$op$pid&page=$this->first$linkpage \">First</a></li>";
			} else {
				$this->prev = "<li class=\"disabled\"><a href=\"#\">Prev</a></li>";
				$this->first = "<li class=\"disabled\"><a href=\"#\">First</a></li>";
			}
		}
		for($this->pages = 1; $this->pages <= $jmlpage; $this->pages++){
			if ((($this->pages >= $page - 3) && ($this->pages <= $page + 3)) || ($this->pages == 1) || ($this->pages == $page)){   
				if (($this->showPage == 1) && ($this->pages != 2)) $this->tlink.="<span>...</span>"; 
				if (($this->showPage != ($jmlpage - 1)) && ($this->pages == $jmlpage)) $this->tlink.="<span>...</span>";
				if ($this->pages == $page) $this->tlink.="<li class=\"active\"><a href=\"#\">$this->pages</a></li>";
				else $this->tlink.="<li><a href=\"".URL_PROGRAM_ADMIN.folder."/?op=$op$pid&page=$this->pages$linkpage\">".$this->pages."</a></li>";
				$this->showPage = $this->pages;          
			}
		}
		if($jmlpage>0){
			if (($page < $jmlpage && $page >= 1)){
				$this->pages = $page+1;
				$this->last =  "<li><a href=\"".URL_PROGRAM_ADMIN.folder."/?op=$op$pid&page=$this->last$linkpage \"> Last</a></li>";
				$this->next =  "<li><a href=\"".URL_PROGRAM_ADMIN.folder."/?op=$op$pid&page=$this->pages$linkpage \"> Next</a></li>";
			} else {
				$this->last = "<li class=\"disabled\"><a href=\"#\">Last</a></li>";
				$this->next = "<li class=\"disabled\"><a href=\"#\">Next</a></li>";
			}
		}
		return $this->first.$this->prev.$this->tlink.$this->next.$this->last;
	}
}
?>