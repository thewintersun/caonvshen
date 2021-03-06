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
		$wb_token = C('ONE_WEIBO_TOKEN'); //  没有关注任何人的微博账号
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);//这是我获取的token 创建微博操作类
		
		//  先查找是否在nvshenlist里面
		$nvshenlist = M("nvshenlist");
		$getdata['wb_username'] = $wb_username;
		$ns_list_result = $nvshenlist->where($getdata)->find();
		if($ns_list_result){
			echo 3;
			return;
		}
		
		//  再查找是否在推荐列表里
		$tuijian = M("tuijian");
		$getdata2['nvshen_name'] = $wb_username;
		$tuijian_list_result = $tuijian->where($getdata2)->find();
		if($tuijian_list_result){
			echo 3;
			return;
		}
		
		
		// 测试关注微博是否成功
		$follow_result = $c->follow_by_name($wb_username);
		
		// 添加到数据库中
		$adddata['nvshen_name'] = $wb_username;
		$tuijian->add($adddata);
			
		if(isset($follow_result['screen_name']) && $follow_result['screen_name'] == $wb_username){
			// 成功, 取消关注
			$unfollow_result = $c->unfollow_by_name($wb_username);
			
			
			
			echo 0;
			return;
		}else{
			// 不成功，没有这个微博
			echo 2;
			return;
		}
		
    	return;
	}


	// 用另外一个微博账号， 获取某个单独的女神的微博信息
	public function scan_one_nvshen(){
		if(!isset($_GET['weibo_name']) || trim($_GET['weibo_name'])==""){
    		ddlog::warn("weibo name param not set. weibo name is ". $_GET['weibo_name']);
			$this->ajaxReturn('param not set', 'param not set', 1);
			return;
    	}
		
		$nvshendata = M('nvshendata');
		
		
    	$wb_username = $_GET['weibo_name'];
		$wb_token = C('ONE_WEIBO_TOKEN');
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);//这是我获取的token 创建微博操作类
		
		$follow_result = $c->follow_by_name($wb_username);
		
		// 添加到数据库
		if(isset($follow_result['screen_name']) && $follow_result['screen_name'] == $wb_username){
			//echo "follow ". $wb_username." success <br>";
			
			// add to database
			$nslist = M('nvshenlist');
			$adddata['wb_userid'] = $follow_result['id'];
			$adddata['wb_username'] = $wb_username;
			$adddata['like_times'] = 0;
			$adddata['wb_user_description'] = $follow_result['description'];
			$adddata['profile_image_url'] 	= $follow_result['profile_image_url'];
			$adddata['avatar_large'] 		= $follow_result['avatar_large'];
			$adddata['big_profile_image_url'] = $follow_result['cover_image_phone'];
			$adddata['profile_url']	= $follow_result['profile_url'];
			$adddata['wb_address'] = "http://www.weibo.com/".$follow_result['profile_url'];
			$adddata['add_time'] = time();
			
			$result = $nslist->add($adddata);
			if($result === false){
				//echo 'add db fail '.$wb_username."<br>";
				//return;
				echo $wb_username.' add to db fail';
			}
			//echo "add db success"." <br>";
		}
		else {
			$this->ajaxReturn($follow_result, 'fail', 2);	
		}
		
		// 获取微博信息， 然后存到数据库
		// 视频微博，3
		$video_weibo = $c->home_timeline(1, 100, 0, 0, 0, 3);
		// 图片微博，3
		$pic_weibo 	= $c->home_timeline(1, 100, 0, 0, 0, 2);
		
		if(isset($video_weibo) && isset($video_weibo['statuses']) && count($video_weibo['statuses'])>0 )
		{
			$wblist = $video_weibo['statuses'];
			for( $i=count($wblist)-1; $i>=0; $i--){
				
				// 先判断这个是否已经存在数据库中
				$checkdata['wb_id'] = $wblist[$i]['id'];;
				$result = $nvshendata->where($checkdata)->find();
				if($result){
					// 已经存在
					continue;
				}
				
				
				
				$add_data['wb_text'] = $wblist[$i]['text'];
				$add_data['wb_id'] = $wblist[$i]['id'];
				$add_data['created_at'] = strtotime($wblist[$i]['created_at']);
				
				//  匹配短网址
				if(preg_match('/(http:\/\/t\.cn)+[\w\/\.\-]*/', $add_data['wb_text'], $matched)){
					$short_url = 	$matched[0];
					$short_detail = $c->get_info_by_shorturl($short_url);
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
					$add_data['avatar_large']				= $wblist[$i]['user']['avatar_large'];
					$add_data['nvshen_big_profile_image'] 	= $wblist[$i]['user']['cover_image_phone'];
					
					$add_data['video_url'] = $video_url;
					$add_data['video_image'] = $video_image;
					$add_data['video_embed_code'] = $video_embed_code;
					$add_data['like_times'] = 0;
					$add_data['isok'] = 1;
					
					
					
					$result = $nvshendata->add($add_data);
					if(!$result){
						//echo "add nvshen video data fail. wb_id is  ". $add_data['wb_id']. " <br>";
						$this->ajaxReturn($add_data['wb_id'].' scan to db fail', 'fail', 1);
					}else{
						//echo "add nvshen video data success. wb_id is  ". $add_data['wb_id']. " <br>";
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
				
				// 先判断这个是否已经存在数据库中
				$checkdata['wb_id'] = $wblist[$i]['id'];;
				$result = $nvshendata->where($checkdata)->find();
				if($result){
					// 已经存在
					continue;
				}
				
				
				unset($add_data);
				$pic_array 	= $wblist[$i]['pic_urls'];
				$pic_number =  count($pic_array);
				// 没有图片
				if($pic_number==0){
					continue;
				}
				
				$add_data['wb_text'] = $wblist[$i]['text'];
				$add_data['wb_id'] = $wblist[$i]['id'];
				$add_data['created_at'] = strtotime($wblist[$i]['created_at']);
				
				$add_data['type'] = 2;
				$add_data['nvshen_user_id'] 			= $wblist[$i]['user']['id'];
				$add_data['nvshen_screen_name'] 		= $wblist[$i]['user']['screen_name'];
				$add_data['nvshen_profile_image'] 		= $wblist[$i]['user']['profile_image_url'];
				$add_data['avatar_large']				= $wblist[$i]['user']['avatar_large'];
				$add_data['nvshen_big_profile_image'] 	= $wblist[$i]['user']['cover_image_phone'];
				
				$add_data['like_times'] = 0;
				$add_data['isok'] = 1;
				
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
						//echo "add nvshen video data fail. wb_id is  ". $add_data['wb_id']." <br>";
						$this->ajaxReturn($add_data['wb_id'].' scan to db fail', 'fail', 1);
					}else{
						//echo "add nvshen video data success. wb_id is  ". $add_data['wb_id']." <br>";
					}
				}
			}
		}
		
		
		$unfollow_result = $c->unfollow_by_name($wb_username);
		
		
		if($this->main_follow_one_nvshen($wb_username) == -1){
			$this->ajaxReturn('main follow nvshen fail', 'fail', 1);
		}
		
		$this->ajaxReturn('success', 'ok', 0);
	}

	// 用主账号的微博关注一个女神
	private function main_follow_one_nvshen($nvshen_name){
		$wb_token = C('WEIBO_TOKEN');
		$weibo = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);
		$follow_result = $weibo->follow_by_name($nvshen_name);
		if(isset($follow_result['screen_name']) && $follow_result['screen_name'] == $nvshen_name){
			return 0;
		}
		return -1;
	}
	
	
	// 对外使用的
	public function follow_one_nvshen(){
            $wb_name = $_GET['weibo_name'];
            $ret = $this->main_follow_one_nvshen($wb_name);
            if($ret == 0){
                    echo "follow success";  
                    return;
            }
            echo "fail ".$wb_name;
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
				unset($add_data);
				
				$add_data['wb_text'] = $wblist[$i]['text'];
				$add_data['wb_id'] = $wblist[$i]['id'];
				$add_data['created_at'] = strtotime($wblist[$i]['created_at']);
				
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
					$add_data['avatar_large']				= $wblist[$i]['user']['avatar_large'];
					$add_data['nvshen_big_profile_image'] 	= $wblist[$i]['user']['cover_image_phone'];
					
					$add_data['video_url'] = $video_url;
					$add_data['video_image'] = $video_image;
					$add_data['video_embed_code'] = $video_embed_code;
					$add_data['like_times'] = 0;
					$add_data['isok'] = 1;
					
					// 是自己就跳过
					if($add_data['nvshen_user_id'] == 1436870027){
						continue;
					}
					
					$result = $nvshendata->add($add_data);
					if(!$result){
						ddlog::warn("add nvshen video data fail. wb_id is  ". $add_data['wb_id']);
					}
					
					// 评论
					$comment = "您的微博已经被操女神（caonvshen.com）收录.更多女神都在 @caonvshen";
					if($i<1){
						// 不能发多了， 不然提示账号异常
						$weibo->send_comment($add_data['wb_id'] , $comment , 1);
						sleep(11);
					}
						
					
					
					
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
				$add_data['created_at'] = strtotime($wblist[$i]['created_at']);
				
				$add_data['type'] = 2;
				$add_data['nvshen_user_id'] 			= $wblist[$i]['user']['id'];
				$add_data['nvshen_screen_name'] 		= $wblist[$i]['user']['screen_name'];
				$add_data['nvshen_profile_image'] 		= $wblist[$i]['user']['profile_image_url'];
				$add_data['avatar_large']				= $wblist[$i]['user']['avatar_large'];
				$add_data['nvshen_big_profile_image'] 	= $wblist[$i]['user']['cover_image_phone'];
				
				$add_data['like_times'] = 0;
				$add_data['isok'] = 1;
				
				// 是自己就跳过
				if($add_data['nvshen_user_id'] == 1436870027){
					continue;
				}
				
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
				
				//  自己的账号发微博
				$url = "http://www.caonvshen.com/index.php/Index/wb?id=".$add_data['wb_id'];
				$short_text = substr($add_data['wb_text'], 0,300);
				$self_wb_content = "女神@".$add_data['nvshen_screen_name']." 的自拍照[".$pic_number."P]: ".$short_text."....   ".$url;
				$self_wb_pic     = str_replace("thumbnail", "large", $pic_array[0]['thumbnail_pic']);
				if($i<5){
					$weibo->upload($self_wb_content, $self_wb_pic);
					sleep(10);
				}
				
				// 评论
				$comment = "您的微博已经被操女神（caonvshen.com）收录.更多女神都在 @caonvshen";
				if($i<3){
					// 不能发多了， 不然提示账号异常
					$weibo->send_comment($add_data['wb_id'] , $comment , 1);
					sleep(11);
				}


			}
		}
		$this->ajaxReturn('ok','ok',0);
	}
  
  	// 更新nvshenlist表， 主要是计算相册个数， 视频个数， like次数
  	public function update_nvshen(){
  		$album_num = 0;
		$video_num = 0;
  		$nslist = M('nvshenlist');
		$nvshendata = M('nvshendata');
		
		$wb_user_list = $nslist->query('select id,wb_userid from cns_nvshenlist');
		if($wb_user_list){
			for($i=0; $i<count($wb_user_list); $i++){
				$wb_user_id = $wb_user_list[$i]['wb_userid'];
				
				
				// album 
				$album_num = 0;
				$sql = "select count(distinct wb_id) as album_num from cns_nvshendata where isok=1 and type=2 and nvshen_user_id=".$wb_user_id;
				$album_result = $nvshendata->query($sql);
				if($album_result){
					$album_num = $album_result[0]['album_num'];
				}
				
				
				// video num 
				$video_num = 0;
				$sql = "select count(distinct wb_id) as video_num from cns_nvshendata where  isok=1 and type=3 and nvshen_user_id=".$wb_user_id;
				$video_result = $nvshendata->query($sql);
				if($video_result){
					$video_num = $video_result[0]['video_num'];
				}
				
				
				// all like times
				$total_like = 0;
				$sql = "select distinct wb_id from cns_nvshendata where  isok=1 and nvshen_user_id=".$wb_user_id;
				$wblist=$nvshendata->query($sql);
				if($wblist){
					for($j=0; $j<count($wblist); $j++){
						$sql = "select like_times from cns_nvshendata where wb_id=".$wblist[$j]['wb_id'];
						$like_result = $nvshendata->query($sql);
						if($like_result && count($like_result)>0){
							$total_like += $like_result[0]['like_times'];
						}
					}
				}
				
				$savedata['id'] 		= $wb_user_list[$i]['id'];
				$savedata['like_times'] = $total_like;
				$savedata['album_num'] 	= $album_num;
				$savedata['video_num'] 	= $video_num;
				
				
				
				
				$r = $nslist->save($savedata);
				if($r===FALSE){
					ddlog::warn("save liketimes fail");
					echo "fail<br>";
					echo json_encode($savedata)."<br>";
				}
			}
		}
  	}
	public function test(){
		
		
		$wb_token = C('WEIBO_TOKEN');
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,$wb_token);//这是我获取的token 创建微博操作类

		$comment = "您的微博已经被操女神（caonvshen.com）收录.更多女神都在 @caonvshen";
		$a = $c->send_comment(3763489095379183 , $comment , 1);
		echo json_encode($a);

	}
}