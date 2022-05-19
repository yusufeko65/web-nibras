<?php
class Paging{
	private $first;
	private $last;
	private $next;
	private $prev;
	private $showPage;
	private $tlink;
	private $pages;
	
	public function GetPaging($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu){
		//$jmlpage = ceil(intval($total)/intval($baris));
		$this->first = 1;
		$this->last = $jmlpage;
		$this->showPage = 0;
		$this->tlink='';
		$this->pages = 1;
	
		
		//echo $jmlpage;
		if ($jmlpage>0){
			if ($page > 1){
				$this->pages = $page-1;
				$this->prev = "<a href=\"".URL_PROGRAM.$amenu.$linkpage."page/$this->pages/$linkcari \">Prev</a>";
				$this->first = "<a href=\"".URL_PROGRAM.$amenu.$linkpage."page/$this->first/$linkcari \">First</a>";
			} else {
				$this->prev = "<div class=\"disabled\">Prev</div>";
				$this->first = "<div class=\"disabled\">First</div>";
			}
		}
		for($this->pages = 1; $this->pages <= $jmlpage; $this->pages++){
			if ((($this->pages >= $page - 3) && ($this->pages <= $page + 3)) || ($this->pages == 1) || ($this->pages == $page)){   
				if (($this->showPage == 1) && ($this->pages != 2)) $this->tlink.="<span>...</span>"; 
				if (($this->showPage != ($jmlpage - 1)) && ($this->pages == $jmlpage)) $this->tlink.="<span>...</span>";
				if ($this->pages == $page) $this->tlink.="<b>$this->pages</b>";
				else $this->tlink.=" <a href=\"".URL_PROGRAM.$amenu.$linkpage."page/$this->pages/$linkcari \">".$this->pages."</a> ";
				$this->showPage = $this->pages;          
			}
		}
		if($jmlpage>0){
			if (($page < $jmlpage && $page >= 1)){
				$this->pages = $page+1;
				$this->last =  "<a href=\"".URL_PROGRAM.$amenu.$linkpage."page/$this->last/$linkcari \"> Last</a>";
				$this->next =  "<a href=\"".URL_PROGRAM.$amenu.$linkpage."page/$this->pages/$linkcari \"> Next</a>";
			} else {
				$this->last = "<div class=\"disabled\">Last</div>";
				$this->next = "<div class=\"disabled\">Next</div>";
			}
		}
		return $this->first.$this->prev.$this->tlink.$this->next.$this->last;
	}
	public function GetPaging2($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu){
		
		$this->first = 1;
		$this->last = $jmlpage;
		$this->showPage = 0;
		$this->tlink='';
		$this->pages = 1;
	
		
		//echo $jmlpage;
		if ($jmlpage>0){
			if ($page > 1){
				$this->pages = $page-1;
				$this->prev = "<li class=\"page-item\"><a class=\"page-link\" href=\"".URL_PROGRAM.$amenu.$linkpage."page_$this->pages/$linkcari \">Prev</a></li>";
				$this->first = "<li class=\"page-item\"><a class=\"page-link\" href=\"".URL_PROGRAM.$amenu.$linkpage."page_$this->first/$linkcari \">First</a></li>";
			} else {
				$this->prev = "<li class=\"page-item\"><a class=\"page-link\">Prev</a></li>";
				$this->first = "<li class=\"page-item\"><a class=\"page-link\">First</a></li>";
			}
		}
		for($this->pages = 1; $this->pages <= $jmlpage; $this->pages++){
			if ((($this->pages >= $page - 1) && ($this->pages <= $page + 1)) || ($this->pages == 1) || ($this->pages == $page)){   
				if (($this->showPage == 1) && ($this->pages != 2)) $this->tlink.="<li class=\"page-item\"><a>...</a></li>"; 
				if (($this->showPage != ($jmlpage - 1)) && ($this->pages == $jmlpage)) $this->tlink.="<li class=\"disabled\"><a>...</a></li>";
				if ($this->pages == $page) $this->tlink.="<li class=\"page-item\"><a class=\"page-link\">$this->pages</a></li>";
				else $this->tlink.=" <li class=\"page-item\"><a class=\"page-link\" href=\"".URL_PROGRAM.$amenu.$linkpage."page_$this->pages/$linkcari \">".$this->pages."</a></li> ";
				$this->showPage = $this->pages;          
			}
		}
		if($jmlpage>0){
			if (($page < $jmlpage && $page >= 1)){
				$this->pages = $page+1;
				$this->last =  "<li class=\"page-item\"><a class=\"page-link\" href=\"".URL_PROGRAM.$amenu.$linkpage."page_$this->last/$linkcari \"> Last</a></li>";
				$this->next =  "<li class=\"page-item\"><a class=\"page-link\" href=\"".URL_PROGRAM.$amenu.$linkpage."page_$this->pages/$linkcari \"> Next</a></li>";
			} else {
				$this->last = "<li class=\"page-item\"><a class=\"page-link\">Last</a></li>";
				$this->next = "<li class=\"page-item\"><a class=\"page-link\">Next</a></li>";
			}
		}
		return $this->first.$this->prev.$this->tlink.$this->next.$this->last;
	}
}
?>