<?php
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,'2.00BXxOZBTP2dPD8c527e22f90LgwAo');//这是我获取的token 创建微博操作类
echo "<html>";
echo "<meta http-equiv='content-type' content='text/html; charset=gb2312' />";
$newestweibo = $c->home_timeline();
echo $newestweibo['statuses'][0]['text'];
echo json_encode($newestweibo['statuses'][0]['text']);
echo "<img src='".$newestweibo['statuses'][0]['pic_urls'][0]['thumbnail_pic']."'>";
//echo json_encode($newestweibo['statuses'][0]['pic_urls'][0]['thumbnail_pic']	);
echo "</html>";
?>