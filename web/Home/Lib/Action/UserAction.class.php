<?php

require_once 'Home/Common/DDlog.class.php';

// 本类由系统自动生成，仅供测试用途
class UserAction extends Action {
    public function index(){
    	
	}
	
	public function login(){
		$passwd 	= $_GET['password'];
		$username 	= $_GET['username'];
		
		$user = M("user");
		$getdata['user_name'] = $username;
		
		$result = $user->where($getdata)->find();
		if($result ){
			if($result['passwd'] == $passwd ){
				$_SESSION['username'] = $username;
				
				$expire = time() + 86400*365;
				setcookie ("name", $username, $expire);
				setcookie ("pwd", $passwd, $expire);
				
				echo  1;
				return;
			}
		}
		echo  0;
		return;
	}
	
	public function logout(){
		unset($_SESSION['username']);
		setcookie("name","1",time()-1);
		setcookie("pwd","1",time()-1);
		$this->redirect("/");
	}
	
	public function signupcheck(){
		
		$passwd 	= $_GET['password'];
		$email 		= $_GET['email'];
		$username 		= $_GET['username'];
		
		$user = M("user");
		
		$getdata['email'] = $email;
		$result = $user->where($getdata)->find();
		if($result && isset($result['email'])){
			echo 1;
			return; 
		}
		unset($getdata);
		
		$getdata['user_name'] = $username;
		$result = $user->where($getdata)->find();
		if($result && isset($result['user_name'])){
			echo 2;
			return;
		}
		unset($getdata);
		
		$adddata['user_name'] = $username;
		$adddata['email'] = $email;
		$adddata['passwd'] = $passwd;
		$result = $user->add($adddata);
		if($result=== false){
			echo 4;
			return;
		}
		
		$this->setsession($username);
		echo 3;
		return;
	}
	
	
	public function checkusername(){
		$username 		= $_GET['username'];
		$user = M("user");
		$getdata['user_name'] = $username;
		$result = $user->where($getdata)->find();
		if($result && isset($result['user_name'])){
			return 1; 
		}
		return 0;
	}
	
	public function checkemail(){
		$email 		= $_GET['email'];
		$user = M("user");
		$getdata['email'] = $email;
		$result = $user->where($getdata)->find();
		if($result && isset($result['email'])){
			return 1; 
		}
		return 0;
	}
	
	public function shoucang(){
		$wb_id = $_GET['id'];
		
		if(!isset($_SESSION['username'])){
			echo 1;
			return;
		}
		$username = $_SESSION['username'];
		
		$shoucang = M('shoucang');
		$adddata['wb_id'] = $wb_id;
		$adddata['username'] = $username;
		
		$result = $shoucang->add($adddata);
		if($result === false){
			echo 1;
			return;
		}
		
		echo 0;
		return;
		
		
		
		
	}
	
	
	private function setsession($username){
		$_SESSION['username'] = $username;
	}
}