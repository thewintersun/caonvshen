<?php

require_once 'Home/Common/DDlog.class.php';

// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        echo "aaaaa";
    	$this->display("Index:index");
    	
	}
    public function default1(){
        $this->display();
    }
	public function productInfo(){
		$this->display("Index:productInfo");
	}
	public function taocang(){
		$this->display("Index:taocang");
	}
	public function download(){
		$this->display("Index:download");
	}
	public function userCase(){
		$this->display("Index:userCase");
	}
	public function payStep_1(){
	    $user_meal=C('USER_MEAL');
        $this->assign("user_meal",$user_meal);
	    $this->assign("pi",5);
        
		$this->display("Index:payStep_1");
	}
	public function payStep_2(){
	    $user_meal=C('USER_MEAL');
       
	    $this->assign("pi",5);
		$tType=$_GET["tType"];
        $single_price=$user_meal[$tType]["price"];
		$_SESSION["payment"]["tType"]=$tType;
        $_SESSION["payment"]["tValue"]=$single_price;
		$this->display("Index:payStep_2");
	}
	public function payStep_3(){
	    $this->assign("pi",5);
		$tCount=$_GET["tCount"];
		$_SESSION["payment"]["tCount"]=$tCount;
        //dump($_SESSION);
		$this->display("Index:payStep_3");
	}
    public function payStep_4(){
        $this->assign("pi",5);
        $msgText=$_POST["msgText"];
        $_SESSION["payment"]["msgText"]=$msgText;
        $this->display("Index:payStep_4");
    }
    public function login(){
    	$this->autologin_check();
        $this->display("User:login");
    }
	
    public function pclogin(){
        $this->display("User:pclogin");
    }
    
	
    //用户注册省份信息
    public function register() {
    	$this->autologin_check();
		
        import ( "@.ORG.Address" );
        $address = new Address ();
        $province = $address->provinceArray;
        $this->assign ( "province", $province );
        $this->display("User:register");
    }
	
	
    //#注册# 根据省份 ajax载入城市
    public function cityLocation() {
        import ( "@.ORG.Address" );
        $address = new Address ();
        $province = "p" . $_GET ["province"];
        if (isset ( $province )) {
            $this->ajaxReturn ( $address->$province, "操作成功", 1 );
        } else {
        	ddlog::warn("get city location error. province is ".$province);
            $this->ajaxReturn ( "0", "操作失败", 0 );
        }
    }
    public function aboutUs(){
    	$this->autologin_check();
		
        $this->display("Index:aboutUs");
    }
	public function indexBar(){
		$this->autologin_check();
		
		$pi=$_GET["pageIndex"];
		if(!$pi){
			$pi=1;
		}
		$this->assign("pi",$pi);
		switch ($pi) {
			case 1:
				$this->index();
				break;
			case 2:
				$this->productInfo();
				break;
			case 3:
				$this->taocang();
				break;
			case 4:
				$this->userCase();
				break;
			case 5:
				$this-> choose_software();
				break;
			case 6:
                $this->download();
                break;
            case 7:
                $this->register();
                break;
            case 8:
                $this->aboutUs();
                break;
			default:
				
				break;
		}
	}
	public function showPage(){
		$this->ajaxReturn($_GET["pageId"],"处理成功",1);
		
	}
    
    //保存购买的支付金额到session
    public function savePaySum(){
        $_SESSION["payment"]["paySum"]=$_GET["paySum"];
        $this -> ajaxReturn("操作成功","操作成功", 1);
    }
	
	
    //购买套餐后修改数据库account表中的信息
    public function changeAccountInfo(){
        $account=M("Account");
        $data["account_id"] = $_SESSION["account_id"];
        $saveData["rank"]=$_SESSION["payment"]["tType"];
        $_SESSION["rank"]=$_SESSION["payment"]["tType"];
        $countYear=$_SESSION["payment"]["tCount"];
        $saveData["deadline"]=strtotime("+$countYear year");
        $result=$account->where($data)->save($saveData);
        
        /*
         * ！！！！！！支付账户、支付平台需获取支付接口后添加！！！！！！
         */
        $pay=M("Pay");
        $payData["account_id"]=$_SESSION["account_id"];
        $payData["pay_time"]=time();
        $payData["pay_sum"]=$_SESSION["payment"]["paySum"];
        $payData["pay_leave_message"]=$_SESSION["payment"]["msgText"];
        $payResult=$pay->add($payData);
        if($result){
            if($payResult){
                $this -> ajaxReturn("交易成功","操作成功", 1);
            }else{
            	ddlog::warn("exchange fail. accountid is ".$payData["account_id"]. " paysum is ".$payData["pay_sum"]);
                $this -> ajaxReturn("交易失败","操作失败", 0);
            }            
        }else{
        	ddlog::warn("save account rankinfo fail. accountid is ".$data["account_id"]. " rank is ".$_SESSION["rank"]);
            $this -> ajaxReturn("交易失败","操作失败", 0);
        } 
    }


	// 用户自己选择时长的购买套餐的方式
	public function choose_software(){
		$this->autologin_check();
		
		$user_meal = C('USER_MEAL');
		
		$account = M("account");
		$condition_data["account_id"] = $_SESSION["account_id"];
		$accList = $account -> where($condition_data) -> field("deadline,rank")->find();
		
		$deadline_date 	= date('Y年m月d日',$accList["deadline"]);
		$deadline_y		= ((int)substr($deadline_date,0,4));//取得年份
		$deadline_m		= ((int)substr($deadline_date,5,2));//取得月份
		$deadline_d		= ((int)substr($deadline_date,8,2));//取得几号
		
		$today_date		= date('Y年m月d日',time()+28800);
		$rank_str		=  "免费套餐";
		switch ($accList['rank']) {
			case '1':
				$rank_str		=  "基本套餐";
				break;
			case '2':
				$rank_str		=  "标准套餐";
				break;
			case '3':
				$rank_str		=  "高级套餐";
				break;
			default:
				break;
		}
		
		$this->assign("pi",5);
		
		$this->assign("user_meal",$user_meal);
		$this->assign("deadline_digit",$accList["deadline"]);
		$this->assign("deadline",$deadline_date);
		$this->assign("deadline_y",$deadline_y);
		$this->assign("deadline_m",$deadline_m);
		$this->assign("deadline_d",$deadline_d);
		$this->assign("rank",$accList['rank']);
		$this->assign("rank_str",$rank_str);
		$this->assign("today_date", $today_date);
		
		$this->display("Index:choose_software");
	}


	// 用户自己选择时长的购买套餐的方式
	public function choose_software_confirm(){
		$this->autologin_check();
		
		$user_meal = C('USER_MEAL');
		
		$price 		= $_GET["price"];
		$buy_month 	= $_GET["buy_month"];
		$rank		= $_GET["rank"];
		
		$rank_str		=  "免费套餐";
		switch ($rank) {
			case '1':
				$rank_str		=  "基本套餐";
				break;
			case '2':
				$rank_str		=  "标准套餐";
				break;
			case '3':
				$rank_str		=  "高级套餐";
				break;
			default:
				break;
		}
		
		$this->assign("pi",5);
		
		$_SESSION["payment"]["rank"] 	= $rank;
		$_SESSION["payment"]["tType"] 	= $rank_str;
		$_SESSION["payment"]["tValue"] 	= $user_meal[$rank]['price'];
		$_SESSION["payment"]["buy_month"]	= $buy_month;
		
		// 总计
		$_SESSION["payment"]["tCount"]	=  $price;
		
		$_SESSION["payment"]["upgrade_price"] = $_GET["upgrade_price"];
		
		$this -> display();
	}



    public function choose_success(){
        $this->assign("pi",5);
        $msgText=$_POST["msgText"];
		
		// 后台check订单
		$check_result = $this->check_order();
		
		if($check_result == false){
			ddlog::warn("check order fail. order info not match. accountid is ".$_SESSION["account_id"]);
			
			$this->assign("order_status",1);
			$this->assign("order_info","订单验证错误");
			$this->display();
			return;
		}
		
		
		// 把订单写入数据库
		$all_price = $_SESSION["payment"]["tCount"];
		$buy_month = $_SESSION["payment"]["buy_month"];
		$upgrade_rank = $_SESSION["payment"]["rank"];
		$account_id	 =  $_SESSION["account_id"];
		
		
		$payModel	= M("pay");
		$rand_int = rand(0, 10);
		$order_id = date('YmdHis',time()).$account_id.$rand_int; // 前面是月份后面是account_id
		
		$savedata['order_id'] 		= $order_id;
		$savedata['account_id'] 	= $account_id;
		$savedata['create_time'] 	= time();
		$savedata['pay_sum'] 		= $all_price;
		$savedata['upgrade_rank'] 	= $upgrade_rank;
		$savedata['buy_month'] 		= $buy_month;
		$savedata['pay_leave_message'] 	= $msgText;
		
		$savedata['status'] 		= 0;
		
		$result = $payModel->add($savedata);
		if($result==FALSE){
			
			ddlog::warn("add order data error. orderid is ".$order_id." accountid is ".$account_id. " paysum is ".$all_price);
			
			$this->assign("order_status",2);
			$this->assign("order_info","order save error");
			$this->display();
			return;
		}
		
		// 返回订单信息
		$this->assign("order_status",0);
		$this->assign("order_info","success");
		$this->assign("order_id",$order_id);
		$this->assign("order_price",$all_price);
		
        $this->display();
        
    }


	// 用来显示目前有多少个注册用户了
    public function getRegistersNum(){
        $account=M("Account");
        $accountNum=$account->count(); 
        if($accountNum){
            $this->ajaxReturn($accountNum,"操作成功",1);
        }else if($accountNum===0){
            $this->ajaxReturn($accountNum,"操作成功",1);
        }else{
        	
			ddlog::warn("get accountnum fail");
			
            $this->ajaxReturn("getRegistersNum：失败","操作成功",1);
        }   
    }
	
	// 验证订单的合法性。
	private function check_order(){
		$user_meal = C('USER_MEAL');
		
		$all_price = $_SESSION["payment"]["tCount"];
		$buy_month = $_SESSION["payment"]["buy_month"];
		$upgrade_rank = $_SESSION["payment"]["rank"];
		$account_id	 =  $_SESSION["account_id"];
		
		$account = M('account');
		$condition_data["account_id"] = $_SESSION["account_id"];
		
		$accList = $account -> where($condition_data) -> field("deadline,rank")->find();
		if($accList == FALSE){
			ddlog::warn("find deadline, rank fail. accountid is ".$condition_data["account_id"]);
			return false;
		}
		
		$deadline 	= $accList['deadline'];
		$oldrank	= $accList['rank'];
		
		
		// intval函数可能存在越界的情况
		$oldrank_price 		= intval($user_meal[$oldrank]['price'],10);
		$upgrade_rank_price = intval($user_meal[$upgrade_rank]['price'],10);
		$rankprice_diff		= $upgrade_rank_price - $oldrank_price;
		
		$remain_days = ceil(($deadline - time()) / (3600*24));
		if($remain_days < 0){
			$remain_days = 0;
		}
		
		$diff_money = floor( ($remain_days / 30) * $rankprice_diff);
		
		// 计算优惠后的month的时间
		$buy_month = $this->get_favor_time($buy_month);
		$add_money	= intval($buy_month,10) * $upgrade_rank_price;
		
		
		// check 钱是否计算正确，防止网页端参数被人改了
		if(($diff_money+ $add_money) == $all_price){
			return true;
		}
		echo $upgrade_rank_price.":".$diff_money.":".$add_money.":".$all_price;
		return false;
	}

	// 计算优惠之后的month的月数
	private function get_favor_time($month_num){
  		if($month_num<=10){
  			return $month_num;
  		}
  		if($month_num <=12){
  			return 10;
  		}
  		
  		if($month_num <=22){
  			return $month_num - 2;
  		}
  		
  		if($month_num <=24){
  			return 20;
  		}
  		
  		if($month_num <=34){
  			return $month_num - 4;
  		}
  		if($month_num <=36){
  			return 30;
  		}
  	}


	// 支付成功后的处理
	private function payed_success($order_id){
		
		$user_meal=C('USER_MEAL');
		
		// 从orderid中找出原来的订单的细节
		$data['order_id'] = $order_id;
		$order = M('pay');
		$order_result = $order->where($condition_data)->field("account_id, pay_sum, upgrade_rank, buy_month, status")->find();
		if($order_result== FALSE){
			ddlog::warn("find order error. orderid is ".$order_id);
			return;
		}
		
		$account_id = $order_result["account_id"];
		$rank		= $order_result["upgrade_rank"];
		$buy_month	= $order_result["buy_month"];
		$status		= $order_result["status"];
		
		if($status == 1){
			ddlog::warn("already payed it. accountid is ".$account_id. " orderis is ".$order_id);
			return;
		}
		
		
		$account = M('account');
		$condition_data["account_id"] = $account_id;
		
		
		$accList = $account -> where($condition_data) -> field("deadline")->find();
		if($accList == FALSE){
			ddlog::warn("select deadline error. accountid is ".$account_id);
			return;
		}
		
		$deadline 	= $accList['deadline'];
		$now		= time();
		$deadline_start = $deadline;
		
		// 判断下deadline和现在的时间， 可能用户下了订单过了长时间才支付的情况， 现在的时间已经超过deadline的时间。
		if($now > $deadline){
			$deadline_start = $now;
		}
		
		$new_deadline = strtotime(date('Y-m-d,h:i:s',$deadline_start). " ".$buy_month." month");
		
		$saveaccount_data["account_id"] = $account_id;
		$saveaccount_data["deadline"] 	= $new_deadline;
		$saveaccount_data["rank"] 		= $rank;
		$saveaccount_data["max_user_num"] 	= $user_meal[$rank]["user_num"];
		$saveaccount_data["max_answer_num"] = $user_meal[$rank]["max_listen"];
		
		$result = $account->save($saveaccount_data);
		if($result == FALSE){
			ddlog::warn("save order to account error. accountid is ".$account_id);
			return;
		}
		
		$savepay_data["pay_account"] 			= 0;
		$savepay_data["receive_ban_account"] 	= 0;
		$savepay_data["pay_time"] 				= time();
		$savepay_data["pay_platform"] 			= 0;
		$savepay_data["status"] 				= 1;
		$savepay_data["pay_account"] 			= 0;
		
		$result = $order->where("order_id=".$order_id)->save($savepay_data);
		if($result == FALSE){
			ddlog::warn("change order table error. orderid is ".$order_id);
			return;
		}
	}


	// 检查是否设置自动登陆， 如果设置了测设置相关的参数
	private function autologin_check(){
		
		if (isset ( $_COOKIE ["autoLogin"] ) &&  $_COOKIE ["autoLogin"] =="on" ){
				
			if( isset ( $_COOKIE ["accountId"] ) && isset ( $_COOKIE ["userId"] ) && isset($_COOKIE["codePWD"])  ) {
				
				// 先验证密码
				$user 		= M ( "user" );
				$account 	= M ( "account" );
				$acdata["account_name"] = $_COOKIE ["accountId"];
				$list = $account->where ( $acdata)->field ( "account_id,server,rank" )->find ();
				
				if ($list  === NULL) {
					return 1;
				} else if ($list === FALSE) {
					return 2;
				} else {
					$account_server=$list["server"];
					$udata ["account_id"] = $list ["account_id"];
					$udata ["user_name"] = $_COOKIE ["userId"];
					
					$list_user = $user->where ( $udata )->field ( "user_id,user_name,password,role" )->find ();
					if ($list_user === NULL) {
						return 3;
					} else if ($list_user === FALSE) {
						return 4;
					} else {
						if ($list_user ["password"] != $_COOKIE["codePWD"]) {
							return 5;
						} else {
							
							//验证密码成功
							$_SESSION["account_id"]=$udata ["account_id"];
							$_SESSION["server"]=$account_server;
							$_SESSION["user_id"]=$list_user["user_id"];
							$_SESSION["role"]=$list_user["role"];
		                    $_SESSION["rank"]=$list_user["rank"];
		                    
							$_SESSION ["account_name"] 	= $_COOKIE ["accountId"];
							$_SESSION ["user_name"] 	= $_COOKIE ["userId"];
						}
					}
				}
				return 0;
			}
		}
		// 没有自动登陆则返回false
		return 6;
	}
}