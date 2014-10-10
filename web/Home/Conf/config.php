<?php
$domain='www.caonvshen.com';
return array(
	//'配置项'=>'配置值'
	 /* 数据库设置 */
    'DB_TYPE'               => 'mysql',     // 数据库类型
	'DB_HOST'               => 'www.caonvshen.com', // 服务器地址
	'DB_NAME'               => 'caonvshen',          // 数据库名
	'DB_USER'               => 'root',      // 用户名
	'DB_PWD'                => '7788521',          // 密码
	'DB_PORT'               => '3306',        // 端口
	'DB_PREFIX'             => 'cns_',    // 数据库表前缀
	
	//cookie的域和路径配置
	'DD_COOKIE_PATH' =>'/',
	'DD_COOKIE_DOMAIN'=>$domain,
	
	//错误页面
	'TMPL_EXCEPTION_FILE'=>'./Home/Tpl/Public/error.html',
	
	//模板替换路径配置项
	'TMPL_PARSE_STRING' =>array(
		'__PUBLIC__' => '/Public', // 更改默认的__PUBLIC__ 替换规则
		'__WEBSITE_URL__'=>"http://".$domain,
	),
	'DOMAIN' => $domain,
	'WEIBO_TOKEN' => '2.00BXxOZBTP2dPDb7ba7fc1b56wmMRE',
	'ONE_WEIBO_TOKEN' => '2.00TdHFfFTP2dPD3cf1dccf55sVs_iB',
	'PAGE_NUMBER' => 24,
);
?>
