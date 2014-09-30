<?php

require_once 'Home/Common/DDlog.class.php';

// 本类由系统自动生成，仅供测试用途
class AdminAction extends Action {
	public function SwapISOK() {
		$wb_id = $_GET['id'];
		
		if(!isset($_SESSION['username']) || $_SESSION['username'] <> 'admin'){
			echo 1;
			return;
		}

		$sql = "update caonvshen.cns_nvshendata set isok = 1 - isok where wb_id = ".$wb_id;		
		$swapisok = M('swapisok');
		
		$result = $swapisok->execute($sql);
		if($result === false){
			echo 1;
			return;
		}
		
		echo 0;
		return;
	}
}