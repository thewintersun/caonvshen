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
	
		// 添加喜欢次数
	public function cao17xia(){
		$wb_id = $_GET['id'];
		$nvshendata = M("nvshendata");
		$sql = "update cns_nvshendata set like_times = like_times +17 where wb_id=".$wb_id;
		$result = $nvshendata->execute($sql);
		if(!$result){
			ddlog::warn("update caoyixia fail ".$wb_id);
		}
		return 0;
	}
}