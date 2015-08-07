<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Shop\Controller;
use Admin\Api\AddonsApi;
use Shop\Api\AddressApi;
use Shop\Api\OrderCommentApi;
use Shop\Api\OrdersApi;
use Shop\Api\OrdersInfoViewApi;
use Shop\Api\OrdersItemApi;
use Shop\Api\OrderStatusApi;
use Shop\Api\OrderStatusHistoryApi;
use Shop\Api\ProductApi;
use Shop\Api\ProductSkuApi;
use Shop\Api\StoreApi;
use Shop\Model\OrdersModel;
use Shop\Model\OrderStatusHistoryModel;
use Think\Controller;
use Tool\Api\AreaApi;
use Tool\Api\CityApi;
use Tool\Api\ProvinceApi;

class OrdersController extends ShopController {

	protected function _initialize() {
		parent::_initialize();
	}
	
	/*
	 * 订单确认
	 * */
	public function index(){
		$this->display();
	}
	/*
	 * 支付方式
	 * */
	public function paytype(){
		$this->display();
	}

}
