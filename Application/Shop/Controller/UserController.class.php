<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;

use Shop\Api\OrdersApi;
use Shop\Model\OrdersModel;
use Admin\Api\DatatreeApi;
use Shop\Api\WalletApi;
use Shop\Api\WalletHisApi;
use Addons\WeixinPromotion\WeixinPromotionAddon;
use Weixin\Api\WxuserApi;


class UserController extends ShopController{

	/**
	 * 个人信息
	 */	
	public function info(){
		$this->theme($this->themeType)->display();
	}

	/**
	 * 个人中心
	 */
	public function index(){
		$this->theme($this->themeType)->display();
	}

	/**
	 *	订单
	 */
	public function order(){
		$this->theme($this->themeType)->display();
	}
	

}

