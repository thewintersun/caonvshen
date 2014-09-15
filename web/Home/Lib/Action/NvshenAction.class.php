<?php

require_once 'Home/Common/DDlog.class.php';
require_once 'Home/Common/weiboconfig.php';
require_once 'Home/Common/saetv2.ex.class.php';

// 本类由系统自动生成，仅供测试用途
class NvshenAction extends Action {
	
	// 添加一个女神的接口
    public function addnvshen(){
    	if(!isset($_GET['weibo_name']) || trim($_GET['weibo_name'])==""){
    		ddlog::warn("weibo name param not set. weibo name is ". $_GET['weibo_name']);
			$this->ajaxReturn('param not set', 'param not set', 1);
			return;
    	}
    	$wb_username = $_GET['weibo_name'];
		$wb_token = C('WEIBO_TOKEN');
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);//这是我获取的token 创建微博操作类
		
		
		$follow_result = $c->follow_by_name($wb_username);
		if(isset($follow_result['screen_name']) && $follow_result['screen_name'] == $wb_username){
			ddlog::notice("follow ". $wb_username." success");
			
			// add to database
			$nslist = M('nvshenlist');
			$adddata['wb_userid'] = $follow_result['id'];
			$adddata['wb_username'] = $wb_username;
			$adddata['like_times'] = 0;
			$adddata['wb_user_description'] = $follow_result['description'];
			$adddata['profile_image_url'] 	= $follow_result['profile_image_url'];
			$adddata['big_profile_image_url'] = $follow_result['cover_image_phone'];
			$adddata['profile_url']	= $follow_result['profile_url'];
			$adddata['wb_address'] = "http://www.weibo.com/".$follow_result['profile_url'];
			
			$result = $nslist->add($adddata);
			if(!$result){
				$this->ajaxReturn('add db fail'.$wb_username, 'fail', 1);
				return;
			}
			$this->ajaxReturn($wb_username, 'success', 0);
			return;
		}
		
		ddlog::notice($wb_username.'not exist');
		$this->ajaxReturn($wb_username.'not exist', 'fail', 1);
    	return;
	}


	//  定期扫描女神的微博的更新
	public function scan_nvshen(){
		// read newest weibo
		$since_id = 0;
		
		$nvshendata = M('nvshendata');
		$sql = "select wb_id from cns_nvshendata order by id desc limit 1";
		$result = $nvshendata->query($sql);
		if($result){
			$since_id = $result[0]['wb_id'];
		}
		
		
		$wb_token = C('WEIBO_TOKEN');
		$weibo = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);
		
		
		$newestweibo = $weibo->home_timeline(1, 50, $since_id, 0, 0, 1);
		if(isset($newestweibo) && isset($newestweibo['statuses']) && count($newestweibo['statuses'])>0 )
		{
			$wblist = $newestweibo['statuses'];
			for($i=0; $i<count($wblist); $i++){
				$add_data['wb_text'] = $wblist[$i]['text'];
				$add_data['wb_id'] = $wblist[$i]['id'];
				
				echo count($wblist[$i]['pic_urls']);
				echo "<br>";
				for($j=0;$j<count($wblist[$i]['pic_urls']); $j++){
					echo $wblist[$i]['pic_urls'][$j]['thumbnail_pic'];
					echo "<br>";
					echo "<br>";
				}
				echo json_encode($wblist[$i]['pic_urls']);
				
				
			}
		}
		echo json_encode($newestweibo);
		
		
	}
  
}