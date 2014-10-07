<?php

require_once 'Home/Common/DDlog.class.php';

// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	private function check_session(){
		if(isset($_SESSION['username'])){
			$this->assign("user_login", 1);
			$this->assign("session_username", $_SESSION['username']);
			
			if($_SESSION['username'] == "admin"){
				$this->assign("admin", 1);
			}
		}
	}
	
	
    public function index(){
    	$this->check_session();
		
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
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by created_at desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".$pagenumber;
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
		$this->check_session();
		
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
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=2 group by wb_id order by created_at desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=2 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".$pagenumber;
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
		$this->check_session();
		
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
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=3 group by wb_id order by created_at desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 and type=3 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		
		$nvshendata = M("nvshendata");
		$wb_id_list = $nvshendata->query($sql);
		if($wb_id_list){
			$j = 0;
			
			for($i=0;$i<count($wb_id_list); $i++){
				$wb_id = $wb_id_list[$i]['wb_id'];
				$wb_result = $this->get_wb_detail_v($wb_id);
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

public function admincns(){
    	$this->check_session();
    	
    	// 是否已经收藏这个wb
		if(!isset($_SESSION['username']) || $_SESSION['username'] <> 'admin'){
			$this->redirect("/");
			return;
		}
				
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
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by created_at desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		else{
			// 最热排序
			$sql = "select wb_id from cns_nvshendata where isok=1 group by wb_id order by like_times desc limit ".$p*$pagenumber.",".$pagenumber;
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
		
    	$this->display("Index:admincns");
	}
	
	public function random(){
		$this->check_session();
		
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
		$this->check_session();
		
		$pagenumber = C('PAGE_NUMBER');  // 每页的显示的个数
		// 第几页
		$p = 0;
		
		if(isset($_GET['p']) ){
			$p = $_GET['p'];
		}
		$next_page = $p+1;
		
		$user_id = $_GET['id'];
		
		$type = $_GET['type'];
		
		$sql0 = "select wb_username, wb_user_description, avatar_large from caonvshen.cns_nvshenlist where wb_userid=".$user_id;
		
		$nvshenlist = M("nvshenlist");
		$nvshenlistresult = $nvshenlist->query($sql0);
		
		if($nvshenlistresult){
			$nvshenname = $nvshenlistresult[0]['wb_username'];
			$nvshen_description = $nvshenlistresult[0]['wb_user_description'];
			$nvshen_avatar_large = $nvshenlistresult[0]['avatar_large'];
			$this->assign("nvshenname", $nvshenname);
			$this->assign("nvshen_profile_image", $nvshen_avatar_large);
			$this->assign("nvshen_description", $nvshen_description);
		}
			
		$this->assign("nvshen_user_id", $user_id);
		$this->assign("type", $type);		
		$this->assign("next_page", $next_page);
		
		$sql = 
		"select nd.*, ndgp.pic_num, nl.wb_user_description from caonvshen.cns_nvshendata nd 
inner join 
(SELECT min(id) as id, count(1) as pic_num 
FROM caonvshen.cns_nvshendata 
where nvshen_user_id = ".$user_id."
group by wb_id) as ndgp on nd.id = ndgp.id 
left join caonvshen.cns_nvshenlist as nl on nd.nvshen_user_id = nl.wb_userid
where isok = 1 and ((type = 3 and video_image is not null and video_url is not null)
or type = 2) order by created_at desc
";
		
		$upper_limit = $pagenumber + 1;
		if (isset($_GET['type']) && $_GET['type']=='pp') {
			$sql = $sql."and type = 2 limit ".$p*$pagenumber.",".$upper_limit;
			$typeclass['pp'] ='active item';
			$typeclass['v'] = ' item';
			$typeclass['all'] = ' item';			
		} else if (isset($_GET['type']) && $_GET['type']=='video') {
			$sql = $sql."and type = 3 limit ".$p*$pagenumber.",".$upper_limit;
			$typeclass['pp'] =' item';
			$typeclass['v'] = 'active item';
			$typeclass['all'] = ' item';
		} else {
			$sql = $sql." limit ".$p*$pagenumber.",".$upper_limit;
			$typeclass['pp'] =' item';
			$typeclass['v'] = ' item';
			$typeclass['all'] = 'active item';
		}
		
		$this->assign("typeclass", $typeclass);
		
		$nvshendata = M("nvshendata");
		$wb_result = $nvshendata->query($sql);
		
		$j = 0;
		
		if($wb_result){
			$j = MIN(count($wb_result), $pagenumber);
						
			for($i=0; $i<$j; $i++){
				$result[$i]['wb_id'] = $wb_result[$i]['wb_id'];
				$result[$i]['type'] = $wb_result[$i]['type'];
				$result[$i]['video_image'] = $wb_result[$i]['video_image'];
				$result[$i]['wb_text'] = $wb_result[$i]['wb_text'];
				$result[$i]['pic_num'] = $wb_result[$i]['pic_num'];	
				$result[$i]['bmiddle_pic'] = $wb_result[$i]['bmiddle_pic'];	
				
				$result[$i]['isok']				= $wb_result[0]['isok'];
				$result[$i]['isok_backgroud']	="green";
				
				if($result[$i]['isok'] == 1){
					$result[$i]['isok_backgroud']="green";
				}
				else{
					$result[$i]['isok_backgroud']="red";
				}
					
			}		
			
			if (count($wb_result) <= $pagenumber) {
				$this->assign("show_next_page", "yes");	
			} else {
				$this->assign("show_next_page", "no");					
			}
			// 女神说明		
			$this->assign("ns_count", $j);
			$this->assign("ns_detail",$result);			
		}
		$this->display("Index:nvshen");
	}

	public function about() {
		$this->display();
	}
	
	public function advertise() {
		$this->display();
	}
	
	public function declaration() {
		$this->display();
	}
	
	public function linker() {
		$this->display();
	}
	
	public function Admin() {
		$this->display();
	}	
	
	
	// 点击一个图片或者视屏后， 进入到详细的这个微博的各种的网页
	public function wb(){
		$this->check_session();
		
		$wb_id = $_GET['id'];
		
		// 是否已经收藏这个wb
		if(isset($_SESSION['username'])){
			$is_shoucang = $this->is_shoucang($_SESSION['username'], $wb_id);
			$this->assign("is_shoucang", $is_shoucang);
		}
		
		$this->assign("nvshen_wb_id", $wb_id);
		$nvshendata = M("nvshendata");
		
		$getdata['wb_id'] = $wb_id;
		$wb_result = $nvshendata->where($getdata)->select();
		
		// 如果有
		if($wb_result){
			if(count($wb_result) >0 ){
				$type = $wb_result[0]['type'];
				$result['wb_id'] 				= $wb_id;
				$result['wb_text'] 				= $wb_result[0]['wb_text'];
				$result['nvshen_user_id'] 		= $wb_result[0]['nvshen_user_id'];
				$result['nvshen_screen_name'] 	= $wb_result[0]['nvshen_screen_name'];
				$result['nvshen_profile_image'] = $wb_result[0]['nvshen_profile_image'];
				$result['avatar_large'] 		= $wb_result[0]['avatar_large'];
				
				$result['nvshen_big_profile_image'] = $wb_result[0]['nvshen_big_profile_image'];
				
				$result['like_times'] 			= $wb_result[0]['like_times'];
				
				$result['isok']					= $wb_result[0]['isok'];
				$result['isok_backgroud']		="green";
				
				if($result['isok'] == 1){
					$result['isok_backgroud']="green";
				}
				else{
					$result['isok_backgroud']="red";
				}
				
				// 所有的喜欢的次数
				$result['all_like_times'] = 0;
				$sql = "select sum(like_times) as all_like_times from cns_nvshenlist where wb_userid=".$result['nvshen_user_id'];
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
				else{
					$this->assign("next_wb",-1);
				}
				
				if($last_wb_id != -1){
					$last_wb = $this->get_wb_detail($last_wb_id);
					$this->assign("last_wb",$last_wb);
				}
				else{
					$this->assign("last_wb",-1);
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
		$this->check_session();
		
		$sort= "caotimes"; // 是否是最热的排序
		if(isset($_GET['sort']) ){
			$sort = $_GET['sort'];
		}
		// 第几页
		$p = 0;
		if(isset($_GET['p']) ){
			$p = $_GET['p'];
		}
		
		$more_page = 1;
		
		
		
		$pagenumber = C('PAGE_NUMBER');  // 每页的显示的个数
		
		if($sort == "caotimes"){
			$sql = "select wb_username, wb_userid, like_times, album_num, video_num, profile_image_url from cns_nvshenlist order by like_times desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		if($sort == "ppnum"){
			$sql = "select wb_username, wb_userid, like_times, album_num, video_num, profile_image_url from cns_nvshenlist order by album_num desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		if($sort == "new"){
			$sql = "select wb_username, wb_userid, like_times, album_num, video_num, profile_image_url from cns_nvshenlist order by add_time desc limit ".$p*$pagenumber.",".$pagenumber;
		}
		
		// 结果数， 如果小于阈值，就不显示下一页
		$count_result = 0;
		$more_page = 1;
		
		$nslist = M('nvshenlist');
		$result = $nslist->query($sql);
		if($result){
			$count_result = count($result);
			
			if($count_result<$pagenumber){
				$more_page = 0;
			}
			$this->assign("more_page", $more_page);
			$this->assign("ns_count", count($result));
			$this->assign('nvshen_list',$result);
		}
		
		// 所有女神个数
		$sql = "select count(distinct wb_username) as all_nvshen_number from cns_nvshenlist";
		$all_number_result = $nslist->query($sql);
		if($all_number_result){
			$this->assign("all_nvshen_number", $all_number_result[0]['all_nvshen_number']);
		}
		
		
		$next_page = $p+1;
		$next_page_param = "sort=".$sort;
		$this->assign('next_page',$next_page);
		$this->assign("next_page_param", $next_page_param);
		
		
		$this->display();
	}
	
	
	// 个人页面
	public function me(){
		$this->check_session();
		
		if(!isset($_SESSION['username'])){
			$this->redirect("/");
			return;
		}
		
		$username = $_SESSION['username'];
		
		$j=0;
		
		$shoucang = M('shoucang');
		$getdata['username'] =$username;
		
		$wb_id_list = $shoucang->where($getdata)->select();
		if($wb_id_list){
			for( $i = 0; $i<count($wb_id_list); $i++){
				$wb_id = $wb_id_list[$i]['wb_id'];
				$wb_result = $this->get_wb_detail($wb_id);
				if($wb_result != -1){
					$result[$j] = $wb_result;
					$j++;
				}
			}
		}
		
		$this->assign("session_username",$username);
		$this->assign("ns_count", $j);
		$this->assign("ns_detail",$result);
		
		
		$this->display();
		
	}
	
	// 添加喜欢次数
	public function caoyixia(){
		$wb_id = $_POST['id'];
		$nvshendata = M("nvshendata");
		$sql = "update cns_nvshendata set like_times = like_times +1 where wb_id=".$wb_id;
		$result = $nvshendata->execute($sql);
		if(!$result){
			ddlog::warn("update caoyixia fail ".$wb_id);
		}
		return 0;
	}
	
	
	
	//看是否已经收藏了这个wb了
	private function is_shoucang($username, $wb_id){
		$shoucang = M("shoucang");
		$getdata['username'] = $username;
		$getdata['wb_id'] = $wb_id;
		$result = $shoucang->where($getdata)->find();
		if($result){
			return 1;
		}
		return 0;
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
				$result['isok']				= $wb_detail[0]['isok'];
				$result['isok_backgroud']	="green";
				
				if($result['isok'] == 1){
					$result['isok_backgroud']="green";
				}
				else{
					$result['isok_backgroud']="red";
				}
				
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

	private function get_wb_detail_v($wb_id){
		$nvshendata = M("nvshendata");
		$sql = "select * from cns_nvshendata where isok=1 and video_image is not null and video_url is not null and wb_id=".$wb_id;
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
				$result['isok']				= $wb_detail[0]['isok'];
				$result['isok_backgroud']	="green";
				
				if($result['isok'] == 1){
					$result['isok_backgroud']="green";
				}
				else{
					$result['isok_backgroud']="red";
				}
				
				$type = $wb_detail[0]['type'];
				
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
			ddlog::warn("next wb fail ". $wb_id);
			return -1;
		}
	
		$sql = "select wb_id from cns_nvshendata where isok=1 and created_at>".$created_at." order by created_at asc limit 20";
		$wb_id_result = $nvshendata->query($sql);
		if($wb_id_result){
			$result_wb_id = $wb_id_result[0]['wb_id'];
			return $result_wb_id;
		}
		ddlog::warn("next wb fail2 ". $wb_id);
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
	
		$sql = "select wb_id from cns_nvshendata where isok=1 and created_at<".$created_at." order by created_at desc limit 2";
		$wb_id_result = $nvshendata->query($sql);
		if($wb_id_result){
			$result_wb_id = $wb_id_result[0]['wb_id'];
			return $result_wb_id;
		}
		return -1;
	}
	
	// 获取微博主的说明
	private function get_nvshen_description($wb_userid){
		$nvshenlist = M("nvshenlist");
		$getdata['wb_userid'] = $wb_userid;
		
		$result = $nvshenlist->where($wb_userid)->find();
		if($result){
			return $result['wb_user_description'];
		}
		return -1;
		
	}
	
}