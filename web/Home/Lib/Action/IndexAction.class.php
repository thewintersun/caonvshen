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
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by created_at desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
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
		$this->assign("ishot", $hot);
		$this->assign('next_page',$next_page);
		$this->assign("next_page_param", $next_page_param);
		
    	$this->display("Index:index");
	}
	
	public function pp(){
		
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
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=2 group by wb_id order by created_at desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=2 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
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
		$this->assign("ishot", $hot);
		$this->assign('next_page',$next_page);
		$this->assign("next_page_param", $next_page_param);
		
		$this->display();
	}
  	
	public function video(){
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
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=3 group by wb_id order by id desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=3 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
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
		
    	$this->display("Index:video");
	}

	
	public function random(){
		
		$pagenumber = C('PAGE_NUMBER');  // 每页的显示的个数
		
		$sql = "select distinct wb_id from cns_nvshendata where isok=1 order by created_at desc limit 1000";
		
		$nvshendata = M("nvshendata");
		$wb_id_list = $nvshendata->query($sql);
		if($wb_id_list){
			$rand_key = array_rand($wb_id_list, $pagenumber);
			for($i=0;$i<count($rand_key); $i++){
				$rand_wb_list[$i]['wb_id'] = $wb_id_list[$rand_key[$i]]['wb_id'];
				ddlog::notice("\n".$i.": ".$rand_wb_list[$i]['wb_id']);
			}
			
			$j=0;
			for($i=0;$i<count($rand_wb_list); $i++){
				$wb_id = $rand_wb_list[$i]['wb_id'];
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
		
		
		
		$this->display();
	}
	
	// 单个的女神
	public function nvshen(){
		$pagenumber = C('PAGE_NUMBER');  // 每页的显示的个数
		// 第几页
		$p = 0;
		$next_page = $p+1;
		if(isset($_GET['p']) ){
			$p = $_GET['p'];
		}		
		
		$wb_id = $_GET['id'];
		
		$type = $_GET['type'];
		
		$sql = 
		"select nd.*, ndgp.pic_num, nl.wb_user_description from caonvshen.cns_nvshendata nd 
inner join 
(SELECT min(id) as id, count(1) as pic_num 
FROM caonvshen.cns_nvshendata 
where nvshen_user_id = ".$wb_id."
group by wb_id) as ndgp on nd.id = ndgp.id 
left join caonvshen.cns_nvshenlist as nl on nd.nvshen_user_id = nl.wb_userid
";
		if (isset($_GET['type']) && $_GET['type']=='pp') {
			$sql = $sql."where type = 2 limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
			$typeclass['pp'] ='active item';
			$typeclass['v'] = ' item';
			$typeclass['all'] = ' item';
		} else if (isset($_GET['type']) && $_GET['type']=='video') {
			$sql = $sql."where type = 3 limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
			$typeclass['pp'] =' item';
			$typeclass['v'] = 'active item';
			$typeclass['all'] = ' item';
		} else {
			$sql = $sql." limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
			$typeclass['pp'] =' item';
			$typeclass['v'] = ' item';
			$typeclass['all'] = 'active item';
		}
		
		$nvshendata = M("nvshendata");
		$wb_result = $nvshendata->query($sql);
		
		if($wb_result){
			$j = MAX(count($wb_result), $pagenumber);
			
			$result['nvshen_screen_name'] = $wb_result[0]['nvshen_screen_name'];
			$result['nvshen_user_id'] = $wb_result[0]['nvshen_user_id'];
			$result['avatar_large'] = $wb_result[0]['avatar_large'];
			
			for($i=0; $i<$j; $i++){				
				$result[$i]['type'] = $wb_result[$i]['type'];
				$result[$i]['wb_user_description'] = $wb_result[$i]['wb_user_description'];
				$result[$i]['wb_id'] = $wb_result[$i]['wb_id'];
				$result[$i]['video_image'] = $wb_result[$i]['video_image'];
				$result[$i]['wb_text'] = $wb_result[$i]['wb_text'];
				$result[$i]['pic_num'] = $wb_result[$i]['pic_num'];	
				$result[$i]['bmiddle_pic'] = $wb_result[$i]['bmiddle_pic'];			
			}
			
			$this->assign("type", $type);
			$this->assign("typeclass", $typeclass);
			$this->assign("next_page", $next_page);
			$this->assign("ns_count", $j);
			$this->assign("ns_detail",$result);
		}
		
    	$this->display("Index:nvshen");
	}
	
	
	// 点击一个图片或者视屏后， 进入到详细的这个微博的各种的网页
	public function wb(){
		$wb_id = $_GET['id'];
		$this->assign("nvshen_wb_id", $wb_id);
		$nvshendata = M("nvshendata");
		
		$getdata['wb_id'] = $wb_id;
		$wb_result = $nvshendata->where($getdata)->select();
		
		// 如果有
		if($wb_result){
			if(count($wb_result) >0 ){
				$type = $wb_result[0]['type'];
				
				$result['wb_text'] 				= $wb_result[0]['wb_text'];
				$result['nvshen_user_id'] 		= $wb_result[0]['nvshen_user_id'];
				$result['nvshen_screen_name'] 	= $wb_result[0]['nvshen_screen_name'];
				$result['nvshen_profile_image'] = $wb_result[0]['nvshen_profile_image'];
				$result['avatar_large'] 		= $wb_result[0]['avatar_large'];
				
				$result['nvshen_big_profile_image'] = $wb_result[0]['nvshen_big_profile_image'];
				
				$result['like_times'] 			= $wb_result[0]['like_times'];
				
				
				// 所有的喜欢的次数
				$result['all_like_times'] = 0;
				$sql = "select sum(like_times) as all_like_times from cns_nvshendata where wb_id=".$wb_id;
				$like_times_result = $nvshendata->query($sql);
				if($like_times_result){
					$result['all_like_times'] = $like_times_result[0]['all_like_times'];
				}
				
				// 获取上一个下一个的数据
				$next_wb_id = $this->get_next_wb($wb_id);
				$last_wb_id = $this->get_last_wb($wb_id);
				if($next_wb_id != -1){
					$next_wb = $this->get_wb_detail($next_wb_id);
					$this->assign("next_wb",$next_wb);
				}
				if($last_wb_id != -1){
					$last_wb = $this->get_wb_detail($last_wb_id);
					$this->assign("last_wb",$last_wb);
				}
				
				
				// 总共的相册个数
				$result['album_num'] = 0;
				$sql = "select count(distinct wb_id) as album_num from cns_nvshendata where type=2 and nvshen_user_id=".$result['nvshen_user_id'];
				$album_result = $nvshendata->query($sql);
				if($album_result){
					$result['album_num'] = $album_result[0]['album_num'];
				}
				
				
				// 总共的视频个数
				$result['video_num'] = 0;
				$sql = "select count(distinct wb_id) as video_num from cns_nvshendata where type=3 and nvshen_user_id=".$result['nvshen_user_id'];
				$video_result = $nvshendata->query($sql);
				if($video_result){
					$result['video_num'] = $video_result[0]['video_num'];
				}
				
				// pic weibo
				if($type == 2){
					$result['pic_number'] 					= count($wb_result);
					$result['nvshen_screen_name_title'] 	= $wb_result[0]['nvshen_screen_name'].'的自拍照['.$result['pic_number'].'P]';
					
					for($i=0; $i<count($wb_result); $i++){
						$result['pic'][$i]['thumbnail_pic'] = $wb_result[$i]['thumbnail_pic'];
						$result['pic'][$i]['bmiddle_pic'] 	= $wb_result[$i]['bmiddle_pic'];
						$result['pic'][$i]['large_pic'] 	= $wb_result[$i]['large_pic'];
					}
					
					$this->assign("nvshen_detail", $result);
					$hotest_wb = $this->hotest_wb(0,5);
					$this->assign("hotest_wb_number", count($hotest_wb));
					$this->assign("hotest_wb", $hotest_wb);
		
					$this->display("Index:wb_pic");
				}
								
				// video weibo 
				if($type == 3){					
					$result['v_number'] 					= count($wb_result);
					$result['nvshen_screen_name_title'] 	= $wb_result[0]['nvshen_screen_name'].'的自拍视频['.$result['pic_number'].'P]';
					$result['v']['video_url'] = $wb_result[0]['video_url'];
					$result['v']['video_image'] 	= $wb_result[0]['video_image'];
										
					$this->assign("nvshen_detail", $result);					
					$hotest_wb = $this->hotest_wb(0,5);
					$this->assign("hotest_wb_number", count($hotest_wb));
					$this->assign("hotest_wb", $hotest_wb);
					$this->display("Index:v");
				}
			}
		}
	}

	//  女神排行榜
	public function users(){
		$sort= "caotimes"; // 是否是最热的排序
		if(isset($_GET['sort']) ){
			$sort = $_GET['sort'];
		}
		
		$pagenumber = C('PAGE_NUMBER');  // 每页的显示的个数
		
		if($sort == "caotimes"){
			$sql = "select wb_id, sum(like_times) as like_times from cns_nvshendata group by wb_id order by like_times desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		if($sort == "caotimes"){
			$sql = "select wb_id, sum(like_times) as like_times from cns_nvshendata group by wb_id order by like_times desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		if($sort == "caotimes"){
			$sql = "select wb_id, sum(like_times) as like_times from cns_nvshendata group by wb_id order by like_times desc limit ".$p*$pagenumber.",".($p+1)*$pagenumber;
		}
		
		
		// 第几页
		$p = 0;
		if(isset($_GET['p']) ){
			$p = $_GET['p'];
		}
		
		
		$next_page = $p+1;
		$next_page_param = "sort=".$sort;
		$this->assign('next_page',$next_page);
		$this->assign("next_page_param", $next_page_param);
		
		
		$this->display();
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

	
	// 获取最热微博， 就是like_times最多的吧
	private function hotest_wb($start, $end){
		$nvshendata = M("nvshendata");
		$sql = "select distinct wb_id from cns_nvshendata where isok=1 order by like_times desc limit  ".$start.",".$end;
		$hot_result = $nvshendata->query($sql);
		
		if($hot_result){
			$j = 0;
			for($i=0; $i<count($hot_result); $i++){
				$wb_id = $hot_result[$i]['wb_id'];
				$detail_result = $this->get_wb_detail($wb_id);
				if($detail_result != -1){
					$final_result[$j] = $detail_result;
					$j++;
				}
			}
			return $final_result;
		}
		return -1;
	}
	
	// 获取比输入的微博id更新的一个微博的id
	private function get_next_wb($wb_id){
		$nvshendata = M("nvshendata");
		$sql = "select created_at from cns_nvshendata where wb_id=".$wb_id;
		$created_at_result = $nvshendata->query($sql);
		if($created_at_result ){
			$created_at = $created_at_result[0]['created_at'];
		}else{
			return -1;
		}
	
		$sql = "select wb_id from cns_nvshendata where created_at>".$created_at." limit 2";
		$wb_id_result = $nvshendata->query($sql);
		if($wb_id_result){
			$result_wb_id = $wb_id_result[0]['wb_id'];
			return $result_wb_id;
		}
		return -1;
	}
	
	
	//  获取更加老的微博的id
	private function get_last_wb($wb_id){
		$nvshendata = M("nvshendata");
		$sql = "select created_at from cns_nvshendata where wb_id=".$wb_id;
		$created_at_result = $nvshendata->query($sql);
		if($created_at_result ){
			$created_at = $created_at_result[0]['created_at'];
		}else{
			return -1;
		}
	
		$sql = "select wb_id from cns_nvshendata where created_at<".$created_at." limit 2";
		$wb_id_result = $nvshendata->query($sql);
		if($wb_id_result){
			$result_wb_id = $wb_id_result[0]['wb_id'];
			return $result_wb_id;
		}
		return -1;
	}	
	
}