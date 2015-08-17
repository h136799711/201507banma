<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Shop\Controller;
use Think\Controller;
use Shop\Api\ProductApi;
class ShopCartController extends ShopController {
	/*
	 * 购物车
	 * */
    public function shopcart(){
    	$pros=cookie('shopcats');
		for($i=0;$i<count($pros);$i++){
			$map=array('id'=>$pros[$i]);
			$results[]=apiCall(ProductApi::QUERY_NO_PAGING,array($map));
		}
		$this->assign('products',$results);
    	$user=session('user');
		$this->assign('user',$user);
       $this->theme($this->themeType)->display();
    }
	
	
}