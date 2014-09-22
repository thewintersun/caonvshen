<?php

require_once 'Home/Common/DDlog.class.php';

// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
    	$pagenumber = C('PAGE_NUMBER');  // 每页的显示的个数
    	$hot= 0; // 是否是最热的排序
		if(isset($_GET['sort']) && $_GET['sort']=='hot'){
			$hot = 1;
		}
		
		// 第几页
		$p = 0;
		if(isset($_GET['p']) ){
			$p = $_GET['p'];
		}
		
		if($hot == 0){
			// 最新的排序
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by id desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		
		$nvshendata = M("nvshendata");
		$wb_id_list = $nvshendata->query($sql);
		if($wb_id_list){
			for($i=0;$i<count($wb_id_list); $i++){
				$wb_id = $wb_id_list[$i]['wb_id'];
				$sql = "select * from cns_nvshendata where wb_id=".$wb_id;
				$wb_detail = $nvshendata->query($sql);
				if($wb_detail){
					
				}
			}
			
		}
		
		
		
        $this->assign("url_caonvshen_nvshenpage", "http://lunvshen.com/u?name=绮里嘉ula");
		$this->assign("nvshen_head_img", "http://tp3.sinaimg.cn/3714607270/50/5700847258/0");
		$this->assign("nvshen_name", "绮里嘉ula");
		$this->assign("imgcount", 6);
		$this->assign("url_zipai", "http://ww1.sinaimg.cn/bmiddle/dd6868a6jw1ek3zaa7z6ij20hs0qo76i.jpg");
		 
    	$this->display("Index:index");
	}
	
	public function pp(){
		$this->display();
	}
  	
	public function video(){
		$this->display();
	}
	
	public function random(){
		$this->display();
	}
	
	// 单个的女神
	public function nvshen(){
		$name = $_GET['name'];
		
	}
}