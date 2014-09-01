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
    
	//验证的附加字符串
	'AUTH_PLUS'=>'ddkf',
	
	//active_time的阈值
	'ACTIVE_TIME_MAX'=>30,
	
	// 手机循环请求消息的时间间隔
	'MOBILE_ASK_LOOP'=>20, 
	
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
		/*'__JS__' => '/Public/JS/', // 增加新的JS类库路径替换规则
	    '__UPLOAD__' => '/Uploads', // 增加新的上传路径替换规则
		*/
	),
	
	//连接会话服务器的配置项
	//'CHAT_DOMAIN'=>"http://".$domain,
	//'CHAT_POST'=>'8080',
	//主机服务器配置
	'DOMAIN_SERVER'=>array(
		'url'=>'http://'.$domain,
		'host'=>$domain,
		'port'=>'80',
	),
	//会话服务器列表
	'CHAT_SERVER'=>array(
	//第一个会话服务器配置项
	   '0'=>array(
	    	'web_server'=>array(
				'host'=>'http://127.0.0.1',
				'port'=>'8080',
			),
			'db_config'=>array(
				'db_type'=>'mysql',
				'db_user'=>'root',
				'db_pwd'=>'',
				'db_host'=>'127.0.0.1',
				'db_port'=>'3306',
				'db_name'=>'ddchat',
				'db_profix'=>'dd_',
			),
		),
	//可以添加其他的会话服务器信息
	
	),
	//{
	 //   chat:在线聊天,file:文件传输，image：图片发送，user_num:坐席个数，
	 //listen_more:接听多访客，max_listen:同时接听最大访客数,commen_lang:常用语设置，
	 //ban_guest:访客阻止，tran_guest:客服转接,theme_set：风格设置，
	 //price：套餐价格
	 
	//}
	'USER_MEAL'=>array(
	    //刚注册时的套餐情况
        '0'=>array(
        	'name'=>"注册初始默认套餐",
            'chat'=>TRUE,
            'file'=>FALSE,
            'image'=>FALSE,
            'user_num'=>1,
            'listen_more'=>TRUE,
            'max_listen'=>2,
            'commen_lang'=>FALSE,
            'ban_guest'=>FALSE,
            'tran_guest'=>FALSE,
            'theme_set'=>FALSE,
            'mobile'=>FALSE,
            'price'=>0,		 // 标出来的价格,一个月多少钱
            'price_discount'=>1, // 打折比
            /*++##++:数据统计配置项*/
            'data_statistic'=>FALSE,
            /*++###++*/
        ),
        //基本套餐配置
        '1'=>array(
        	'name'=>"基本套餐",
            'chat'=>TRUE,
            'file'=>FALSE,
            'image'=>FALSE,
            'user_num'=>3,
            'listen_more'=>TRUE,
            'max_listen'=>3,
            'commen_lang'=>FALSE,
            'ban_guest'=>FALSE,
            'tran_guest'=>FALSE,
            'theme_set'=>FALSE,
            'mobile'=>FALSE,
            'price'=>50,
            'price_discount'=>1,
            /*++##++:数据统计配置项*/
            'data_statistic'=>FALSE,
            /*++###++*/
            
        ),
        //标准套餐配置
        '2'=>array(
        	'name'=>"标准套餐",
            'chat'=>TRUE,
            'file'=>TRUE,
            'image'=>TRUE,
            'user_num'=>5,
            'listen_more'=>TRUE,
            'max_listen'=>10,
            'commen_lang'=>TRUE,
            'ban_guest'=>TRUE,
            'tran_guest'=>FALSE,
            'theme_set'=>TRUE,
            'mobile'=>FALSE,
            'price'=>60,
            'price_discount'=>1,
            /*++##++:数据统计配置项*/
            'data_statistic'=>FALSE,
            /*++###++*/
        ),
        //高级套餐配置
        '3'=>array(
        	'name'=>"高级套餐",
            'chat'=>TRUE,
            'file'=>TRUE,
            'image'=>TRUE,
            'user_num'=>20,
            'listen_more'=>TRUE,
            'max_listen'=>20,
            'commen_lang'=>TRUE,
            'ban_guest'=>TRUE,
            'tran_guest'=>TRUE,
            'theme_set'=>TRUE,
            'mobile'=>TRUE,
            'price'=>80,
            'price_discount'=>1,
            /*++##++:数据统计配置项*/
            'data_statistic'=>TRUE,
            /*++###++*/
        ),
    ),
	
	'WEB_LOG_RECORD' => true,
	'LOG_RECORD' => true,
	'LOG_LEVEL'                => 'EMERG,ALERT,CRIT,ERR,NOTIC',// 允许记录的日志级别
);
?>
