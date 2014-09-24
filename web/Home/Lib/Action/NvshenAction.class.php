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
		$sql = "select wb_id from cns_nvshendata order by wb_id desc limit 1";
		$result = $nvshendata->query($sql);
		if($result){
			// get lastest weibo id
			$since_id = $result[0]['wb_id'];
		}
		
		
		$wb_token = C('WEIBO_TOKEN');
		$weibo = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);
		
		// 视频微博，3
		$video_weibo = $weibo->home_timeline(1, 100, $since_id, 0, 0, 3);
		// 图片微博，3
		$pic_weibo = $weibo->home_timeline(1, 100, $since_id, 0, 0, 2);
		
		
		if(isset($video_weibo) && isset($video_weibo['statuses']) && count($video_weibo['statuses'])>0 )
		{
			$wblist = $video_weibo['statuses'];
			for( $i=count($wblist)-1; $i>=0; $i--){
				$add_data['wb_text'] = $wblist[$i]['text'];
				$add_data['wb_id'] = $wblist[$i]['id'];
				
				//  匹配短网址
				if(preg_match('/(http:\/\/t\.cn)+[\w\/\.\-]*/', $add_data['wb_text'], $matched)){
					$short_url = 	$matched[0];
					$short_detail = $weibo->get_info_by_shorturl($short_url);
					$detail_object = $short_detail['urls'][0]['annotations'][0]['object'];
					$video_url = $detail_object['stream']['hd_url'];
					$video_image = $detail_object['image']['url'];
					$video_embed_code  = $detail_object['embed_code'];
					
					//  可能存在获取视频不对的情况
					if($video_url==null || $video_url==""){
						$annotations 	= $short_detail['urls'][0]['annotations'][0];
						$video_url 		= $annotations['url'];
						$video_image	= $annotations['pic'];
					}
					
					
					
					$add_data['type'] = 3;
					$add_data['nvshen_user_id'] 			= $wblist[$i]['user']['id'];
					$add_data['nvshen_screen_name'] 		= $wblist[$i]['user']['screen_name'];
					$add_data['nvshen_profile_image'] 		= $wblist[$i]['user']['profile_image_url'];
					$add_data['nvshen_big_profile_image'] 	= $wblist[$i]['user']['cover_image_phone'];
					
					$add_data['video_url'] = $video_url;
					$add_data['video_image'] = $video_image;
					$add_data['video_embed_code'] = $video_embed_code;
					$add_data['like_times'] = 0;
					$add_data['isok'] = 1;
					
					
					
					$result = $nvshendata->add($add_data);
					if(!$result){
						ddlog::warn("add nvshen video data fail. wb_id is  ". $add_data['wb_id']);
					}
					unset($add_data);
				}
				else{
					continue;
				}
			}
		}
		
		
		// 图片
		if(isset($pic_weibo) && isset($pic_weibo['statuses']) && count($pic_weibo['statuses'])>0 )
		{
			$wblist = $pic_weibo['statuses'];
			for( $i=count($wblist)-1; $i>=0; $i--){
				unset($add_data);
			//for( $i=0;$i<count($wblist);  $i++){
				$pic_array 	= $wblist[$i]['pic_urls'];
				$pic_number =  count($pic_array);
				// 没有图片
				if($pic_number==0){
					continue;
				}
				
				$add_data['wb_text'] = $wblist[$i]['text'];
				$add_data['wb_id'] = $wblist[$i]['id'];
				
				$add_data['type'] = 2;
				$add_data['nvshen_user_id'] 			= $wblist[$i]['user']['id'];
				$add_data['nvshen_screen_name'] 		= $wblist[$i]['user']['screen_name'];
				$add_data['nvshen_profile_image'] 		= $wblist[$i]['user']['profile_image_url'];
				$add_data['nvshen_big_profile_image'] 	= $wblist[$i]['user']['cover_image_phone'];
				
				$add_data['like_times'] = 0;
				$add_data['isok'] = 0;
				
				for($j=0;$j<count($pic_array);$j++){
					$thumbnail_pic = $pic_array[$j]['thumbnail_pic'];
					$bmiddle_pic = str_replace("thumbnail", "bmiddle", $thumbnail_pic);
					$large_pic 	= str_replace("thumbnail", "large", $thumbnail_pic);
					
					$add_data['pic_url'] 		= $large_pic;
					$add_data['thumbnail_pic'] 	= $thumbnail_pic;
					$add_data['bmiddle_pic'] 	= $bmiddle_pic;
					$add_data['large_pic'] 		= $large_pic;
					
					$result = $nvshendata->add($add_data);
					if(!$result){
						ddlog::warn("add nvshen video data fail. wb_id is  ". $add_data['wb_id']);
					}
				}
			}
		}
		$this->ajaxReturn('ok','ok',0);
	}
  
	public function test(){
		$wb_token = C('WEIBO_TOKEN');
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);//这是我获取的token 创建微博操作类
		
		$a = $c->get_info_by_shorturl('http://t.cn/RvTbPbi');
		echo json_encode($a);
	}
}