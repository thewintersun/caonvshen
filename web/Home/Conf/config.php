<?php
$domain='127.0.0.1';
return array(
	//'配置项'=>'配置值'
	 /* 数据库设置 */
    'DB_TYPE'               => 'mysql',     // 数据库类型
	'DB_HOST'               => '127.0.0.1', // 服务器地址
	'DB_NAME'               => 'ddkf',          // 数据库名
	'DB_USER'               => 'root',      // 用户名
	'DB_PWD'                => '',          // 密码
	'DB_PORT'               => '3306',        // 端口
	'DB_PREFIX'             => 'dd_',    // 数据库表前缀
	
	//cookie的域和路径配置
	'DD_COOKIE_PATH' =>'/',
	'DD_COOKIE_DOMAIN'=>$domain,
	
	//错误页面
	'TMPL_EXCEPTION_FILE'=>'./Home/Tpl/Public/error.html',
	//邮件配置项
	'SMTP_SERVER' =>'mail.ddkefu.com', //邮件服务器
	'SMTP_PORT' =>25, //邮件服务器端口
	'SMTP_USER_EMAIL' =>'service@ddkefu.com', //SMTP服务器的用户邮箱(一般发件人也得用这个邮箱)
	'SMTP_USER'=>'service', //SMTP服务器账户名
	'SMTP_PWD'=>'service2013', //SMTP服务器账户密码
	'SMTP_MAIL_TYPE'=>'HTML', //发送邮件类型:HTML,TXT(注意都是大写)
	'SMTP_TIME_OUT'=>30, //超时时间
	'SMTP_AUTH'=>true, //邮箱验证(一般都要开启)
	
	//模板替换路径配置项
	'TMPL_PARSE_STRING' =>array(
		'__PUBLIC__' => '/Public', // 更改默认的__PUBLIC__ 替换规则
		'__WEBSITE_URL__'=>"http://".$domain,
	),
	'WEIBO_TOKEN' => '2.00BXxOZBTP2dPD8c527e22f90LgwAo',

);
?>
