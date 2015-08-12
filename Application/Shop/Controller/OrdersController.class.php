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
	public function index() {
		$user=session('user');
		if($user==null){
			$this->error('请先登录',U('Shop/Index/login'));
		}else{
			$this->assign('user',$user);
			$id = I('id', 0);
			$user = session('user');
			$uid = array('uid' => $user['id']);
			$map = array('id' => $id);
			$pro = apiCall(ProvinceApi::QUERY_NO_PAGING, array());
			//			dump($pro);
			$this -> assign('pro', $pro['info']);
			$result = apiCall(AddressApi::QUERY, array($map));
			$this -> assign('add', $result['info']['list'][0]);
			$result = apiCall(AddressApi::QUERY, array($uid));
			//			dump($result);
			$this -> assign('address', $result['info']['list']);
			$this -> theme($this -> themeType) -> display();
		}
		
	}

	/*
	 * 支付方式
	 * */
	public function paytype() {
		$user=session('user');
		$this->assign('user',$user);
		$this -> theme($this -> themeType) -> display();
	}

}
