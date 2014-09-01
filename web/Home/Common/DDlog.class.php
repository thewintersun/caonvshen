<?php

/**
 * 对log包装一下， 打印notice的log，写到date.ddkf.log的文件里
 */
class ddlog  {
	static function notice($str){
		$logfile = LOG_PATH.date('y_m_d').'.ddkf.log';
		Log::write($str,'NOTIC',3, $logfile);
	}
	static function warn($str){
		$logfile = LOG_PATH.date('y_m_d').'.ddkf.log';
		Log::write($str,'WARN',3, $logfile);
	}
}
