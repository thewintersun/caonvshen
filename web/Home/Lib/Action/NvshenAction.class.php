<?php

require_once 'Home/Common/DDlog.class.php';
require_once 'Home/Common/weiboconfig.php';
require_once 'Home/Common/saetv2.ex.class.php';

// 本类由系统自动生成，仅供测试用途
class NvshenAction extends Action {
	
    public function addnvshen(){
    	if(!isset($_GET['weibo_name']) || trim($_GET['weibo_name'])==""){
    		ddlog::warn("weibo name param not set. weibo name is ". $_GET['weibo_name']);
			$this->ajaxReturn($data);
    	}
    	$wb_username = $_GET['weibo_name'];
		$wb_token = C('WEIBO_TOKEN');
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,'2.00BXxOZBTP2dPD8c527e22f90LgwAo');//这是我获取的token 创建微博操作类
		$newestweibo = $c->user_timeline_by_name('Bruven',1,1);
		echo json_encode($newestweibo);
		echo "<img src='".$newestweibo['statuses'][0]['pic_urls'][0]['thumbnail_pic']."'>";
		//echo json_encode($newestweibo['statuses'][0]['pic_urls'][0]['thumbnail_pic']	);
    	//$this->display("Index:index");
    	
	}
  
}