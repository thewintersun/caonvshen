<?php

/**
 * 进行md5加密和验证
 */
class MD5Util  {
	
	private static  $auth_plus = "caonvshen";
	
	public static function GenAuthCode($str){
		$str_plus = $str.self::$auth_plus;
		return md5($str_plus);
	}
	
	public static function CheckAuthCode($str, $auth_code){
		$md5str = self::GenAuthCode($str);
		if($md5str == $auth_code){
			return true;
		}
		return false;
	}
}
