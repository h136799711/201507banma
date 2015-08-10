<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;

use Common\Api\AccountApi;
use Uclient\Model\OAuth2TypeModel;
use Shop\Api\CategoryApi;
use Shop\Api\ProductApi;


class UserController extends ShopController{

	/**
	 * 个人信息
	 */	
	public function info(){
		$this->theme($this->themeType)->display();
	}
	
	/**
	 * 收货地址
	 */	
	public function address(){
		$this->theme($this->themeType)->display();
	}

	/**
	 * 个人中心
	 */
	public function index(){
		$id=I('id',0);
		$map=array('id'=>$id);
		if($id!=0){
			$result=apiCall(AccountApi::GET_INFO,array($id));
			$this->assign('user',$result['info']);
			$this->theme($this->themeType)->display();
		}else{
			$this->error('获取用户信息失败,请重新登录',U('Shop/Index/login'));
		}
		
	}

	/**
	 *	订单
	 */
	public function order(){
		$this->theme($this->themeType)->display();
	}
	/*
	 * 用户信息
	 * */
	public function infoview(){
		$this->theme($this->themeType)->display();
	}

}

