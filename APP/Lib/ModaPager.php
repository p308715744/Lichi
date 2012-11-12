<?php

FLEA::loadClass('Lib_Pager');
class Lib_ModaPager extends Lib_Pager
{
    /**
     * ÏÔÊ¾ËùÓÐ
     */	 

	 function Moda_Pager()
	 {
	 }
	 
	 function display(){
		$html	=	'
		

		<STYLE type="text/css">	
			#pagertop{height:53px; width:620px; float:left; overflow:hidden;
			}
				.NavJump{ width:53px; height:20px; float:left; overflow:hidden; background-image:url(/imgs/pageIndexA.jpg); text-decoration:none; color:#FFFFFF; text-align:center; margin-right:10px; margin-left:5px;
				}
				.NavCurPage{ width:21px; height:20px;float:left; overflow:hidden; background-image:url(/imgs/pageIndexB.jpg);  text-decoration:none; color:#FFFFFF; text-align:center; margin-right:5px;
				}
				.NavPart{ width:21px; height:20px; float:left; overflow:hidden; background-image:url(/imgs/pageIndexC.jpg); text-decoration:none; color:#FFFFFF; text-align:center; margin-right:5px;
				}
					.spre{ width:25px; height:20px;float:left; overflow:hidden; color:#FFFFFF;
					}
					
					
					
		#butt{ width:980px; height:20px; text-align:center;}
			.butt_a{ width:51px; height:18px; border:#7e3f52 1px solid; background-color:#39303c; color:#edc5bd; margin-right:8px; line-height:19px; display:inline-block;}
			.butt_b{ width:21px; height:20px; background:url(../images/grzs_14.jpg) no-repeat; color:#edc5bd; margin-right:4px; line-height:19px; display:inline-block;}
			.butt_c{ width:21px; height:20px; background:url(../images/grzs_16.jpg) no-repeat; margin-right:4px; color:#edc5bd; line-height:19px; display:inline-block;}
			.butt_d{ width:21px; height:20px; color:#edc5bd; line-height:19px; display:inline-block;}
			.butt_e{ width:51px; height:18px; border:#7e3f52 1px solid; background-color:#39303c; color:#edc5bd; margin-left:4px; line-height:19px; display:inline-block;}		
					
					
			
		</STYLE>
		
		<div id="butt">


			<a href='.$this->_rebuildUrl($this->firstPage).' class="butt_a">&lt;&lt;&lt;</a>';
		
			/**/
			$lef = $this->currentPage -1;
			if($lef == 0)
				$i = 1;
			else
				$i = $lef;	
			$rig =	$this->pageCount - $this->currentPage;
			if($rig == 0 and $this->currentPage>2)	
			 	$i = $this->currentPage - 2;
			
			if($this->pageCount<4)	
				$lim = $this->pageCount;
			else
				$lim = 3;
							
				
			/**/		
			if($this->currentPage==3 and $this->pageCount!=3)
				$html	.=	'<a href="'.$this->_rebuildUrl(1).'" class="butt_c">1</a>';
			if($this->currentPage==4 and $this->pageCount==4)
				$html	.=	'<a href="'.$this->_rebuildUrl(1).'" class="butt_c">1</a>';
			if($this->currentPage>=4)
				if($this->pageCount>=2 and $this->pageCount>4)
				{
					$html	.=	'<a href="'.$this->_rebuildUrl(1).'" class="butt_c">1</a>';
					$html	.=	'<a href="'.$this->_rebuildUrl(2).'" class="butt_c">2</a>';
					$html	.=	'<span class="butt_d">...</span>';
					}
				
				
			/**/	
			for($i,$t=1;$t<=$lim;$i++,$t++){
				if($i==$this->currentPage){
					$html	.=	'<span class="butt_b">'.$i.'</span>';
				}else{
					$html	.=	'<a href="'.$this->_rebuildUrl($i).'" class="butt_c">'.$i.'</a>';
					
				}
			}
			
			/**/
			$xsd = $this->pageCount - $this->currentPage;
			if($xsd==2 and $this->pageCount!=3)
				$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount).'" class="butt_c">'.$this->pageCount.'</a>';
			if($xsd==3 and $this->pageCount==4)
				$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount).'" class="butt_c">'.$this->pageCount.'</a>';	
			if($xsd>=3 and $this->pageCount>4)
				if($this->pageCount>=2)
				{
					$html	.=	'<span class="butt_d">...</span>';
					$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount-1).'" class="butt_c">'.($this->pageCount-1).'</a>';
					$html	.=	'<a href="'.$this->_rebuildUrl($this->pageCount).'" class="butt_c">'.$this->pageCount.'</a>';
					
				}
			
			
			$html	.=	'<a href="'.$this->_rebuildUrl($this->lastPage).'" class="butt_e">&gt;&gt;&gt;</a>';
			
		$html	.=	'</div>';
		$this->pageHtml	=	 $html;

	
	}

	
}
?>
