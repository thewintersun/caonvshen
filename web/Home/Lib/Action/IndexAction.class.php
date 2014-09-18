<?php

require_once 'Home/Common/DDlog.class.php';

// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        //echo "bbbb";
        //$this->assign("oo", "wangqi");
        $this->assign("url_caonvshen_nvshenpage", "http://lunvshen.com/u?name=绮里嘉ula");
		$this->assign("nvshen_head_img", "http://tp3.sinaimg.cn/3714607270/50/5700847258/0");
		$this->assign("nvshen_name", "绮里嘉ula");
		$this->assign("imgcount", 6);
		$this->assign("url_zipai", "http://ww1.sinaimg.cn/bmiddle/dd6868a6jw1ek3zaa7z6ij20hs0qo76i.jpg");
		 
		/*
		$nvshenInfo = array(
	        'url_caonvshen_nvshenpage' => 'http://lunvshen.com/u?name=绮里嘉ula',
	        'nvshen_head_img' => 'http://tp3.sinaimg.cn/3714607270/50/5700847258/0',
	        'nvshen_name' => '绮里嘉ula',
	        'imgcount' => 6,
	        'url_zipai' => "http://ww1.sinaimg.cn/bmiddle/dd6868a6jw1ek3zaa7z6ij20hs0qo76i.jpg"
	    );		
		*/
    	$this->display("Index:index");
	}
  
}