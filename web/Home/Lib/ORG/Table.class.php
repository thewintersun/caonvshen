<?php

class Table{
	var $tableId;
	var $tableClassName;
	var $trClassName='';
	var $tdClassNameArr=array();
	var $theadColumNumber;
	var $tbodyColumNumber;
	var $tbodyRowNumber;
	var $thDataArray;
	var $tbDataArray;
	public function Table($tableId,$tableClassName){
		$this->tableId=$tableId;
		$this->tableClassName=$tableClassName;
	}
	public function setThead($thDataArray){
		$this->theadColumNumber=count($thDataArray);
		$this->thDataArray=$thDataArray;	
	}
	
	public function setTbody($tbDataArray){
		$this->tbodyColumNumber=count($tbDataArray[0]);
		$this->tbodyRowNumber=count($tbDataArray);
		$this->tbDataArray=$tbDataArray;
	}
	public function getThead(){
		$theadPre="<thead><tr>";
		$theadPro="	</tr></thead>";
		$ths="";
		for($i=0;$i<$this->theadColumNumber;$i++){
			$ths=$ths."<th>".$this->thDataArray[$i]."</th>";
		}
		$thead=$theadPre.$ths.$theadPro;
		return $thead;
					
	}
	public function setTrClass($trclass){
		$this->trClassName=$trclass;
	}
	public function setTdClass($tdClassArr){
		$this->tdClassNameArr=$tdClassArr;
	}
	public function getTbody(){
		$tbodyPre="<tbody>";
		$tbodyPro="	</tbody>";
		$trs="";
		for($i=0;$i<$this->tbodyRowNumber;$i++){
			$trPre="<tr class='".$this->trClassName."'>";
			//$trPre="<tr>";
			$trPro="</tr>";
			$tds="";
			$flag=0;
			foreach ($this->tbDataArray[$i] as $key => $value) {
				//$tds=$tds."<td class='".$this->$tdClassNameArr[$flag]."'>".$value."</td>";
				$tds=$tds."<td class='".$this->tdClassNameArr[$flag]."'>".$value."</td>";
				$flag++;
			}
			$trs=$trs.$trPre.$tds.$trPro;
		}
		$tbody=$tbodyPre.$trs.$tbodyPro;
		return $tbody;
	}
	public function getTable(){
		$tablePre="<table id='".$this->tableId."' width='100%' cellspacing='0' cellpadding='0' border='0'>";
		$tablePro="</table>";
		
		$table=$tablePre.$this->getThead().$this->getTbody().$tablePro;
		return $table;
	}
}

?>

