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
			$j = 0;
			
			for($i=0;$i<count($wb_id_list); $i++){
				$wb_id = $wb_id_list[$i]['wb_id'];
				$wb_result = $this->get_wb_detail($wb_id);
				if($wb_result != -1){
					$result[$j] = $wb_result;
					$j++;
				}
			}
			
			$this->assign("ns_count", $j);
			$this->assign("ns_detail",$result);
		}
		
		$next_page = $p+1;
		$next_page_param = "sort=new";
		if($hot ==1){
			$next_page_param = "sort=hot";
		}
		$this->assign('next_page',$next_page);
		$this->assign("next_page_param", $next_page_param);
		
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
	
	// 根据微博的id获取到这个微博的详细信息
	private function get_wb_detail($wb_id){
		$nvshendata = M("nvshendata");
		$sql = "select * from cns_nvshendata where wb_id=".$wb_id;
		$wb_detail = $nvshendata->query($sql);
		if($wb_detail){
			$wb_detail_count = count($wb_detail);
			if($wb_detail_count>0){
				$result['wb_text'] 			= $wb_detail[0]['wb_text'];
				$result['wb_id'] 			= $wb_detail[0]['wb_id'];
				$result['like_times'] 		= $wb_detail[0]['like_times'];
				$result['nvshen_user_id'] 	= $wb_detail[0]['nvshen_user_id'];
				$result['nvshen_screen_name']		= $wb_detail[0]['nvshen_screen_name'];
				$result['nvshen_profile_image']		= $wb_detail[0]['nvshen_profile_image'];
				$result['nvshen_big_profile_image']	= $wb_detail[0]['nvshen_big_profile_image'];

			
				$type = $wb_detail[0]['type'];
				
				// 图片微博
				if($type == 2){
					$result['type'] = 2;
					for($i=0; $i<$wb_detail_count; $i++){
						$result['pic'][$i]['thumbnail_pic'] = $wb_detail[$i]['thumbnail_pic'];
						$result['pic'][$i]['bmiddle_pic'] = $wb_detail[$i]['bmiddle_pic'];
						$result['pic'][$i]['large_pic'] = $wb_detail[$i]['large_pic'];
					}
					$result['pic_count'] = $i;
				}
				
				// 视频微博
				if($type == 3){
					$result['type'] = 3;
						$result['video_url'] 		= $wb_detail[0]['video_url'];
						$result['video_image'] 		= $wb_detail[0]['video_image'];
						$result['video_embed_code'] = $wb_detail[0]['video_embed_code'];
				}
				
				return $result;
			}
			else{
				return -1;
			}
		}
		else{
			return -1;
		}
	}
	
	
}