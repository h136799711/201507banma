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
use Shop\Api\AddressApi;
use Tool\Api\ProvinceApi;
use Tool\Api\CityApi;
use Tool\Api\AreaApi;


class UserController extends ShopController{

	/**
	 * 个人信息
	 */	
	public function info(){
		$user=session('user');
		$this->assign('user',$user);
		$this->assign('phone',substr_replace($user['mobile'],'****', 3, 4));
		$this->theme($this->themeType)->display();
	}
	/*
	 * 获取市
	 * */
	public function getcity(){
		$sno=I('sno',0);
		if($sno!=0){
			$map=array('father'=>$sno);
			$result=apiCall(CityApi::QUERY_NO_PAGING,array($map));
			$this->ajaxReturn($result['info'],'json');
		}
		
	}	
	/*
	 * 获取区
	 * */
	public function getarea(){
		$cno=I('cno',0);
		
			$map=array('father'=>$cno);
			$result1=apiCall(AreaApi::QUERY_NO_PAGING,array($map));
			$this->ajaxReturn($result1['info'],'json');
		
		
	}	
	/**
	 * 收货地址
	 */	
	public function address(){
		$user=session('user');
		$this->assign('user',$user);
		if(IS_GET){
			$id=I('id',0);
			$user=session('user');
			$uid=array('uid'=>$user['id']);
			$map=array('id'=>$id);
			$pro=apiCall(ProvinceApi::QUERY_NO_PAGING,array());
//			dump($pro);
			$this->assign('pro',$pro['info']);
			if($id!=0){
				$result=apiCall(AddressApi::QUERY,array($map));
				$this->assign('add',$result['info']['list'][0]);
				$result=apiCall(AddressApi::QUERY,array($uid));
	//			dump($result);
				$this->assign('address',$result['info']['list']);
				
				$this->theme($this->themeType)->display();
			}else{
				$result=apiCall(AddressApi::QUERY_WITH_CITY_WITH_AREA,array($uid));
	//			dump($result);
				$this->assign('address',$result['info']);
				$this->theme($this->themeType)->display();
			}
		}else{
			$user=session('user');
			$entity=array(
				'uid'=>$user['id'],
				'country'=>'中国',
				'province'=>I('sheng',''),
				'city'=>I('shi',''),
				'area'=>I('qu',''),
				'detailinfo'=>I('address',''),
				'contactname'=>I('uname',''),
				'mobile'=>I('phone',''),
				'wxno'=>'',
			);
			$result=apiCall(AddressApi::ADD,array($entity));
			if($result['status']){
				$this->assign("add",null);
				$this->success('添加地址成功!');
				
			}else{
				$this->error('添加失败');
			}
		}
	}
	/*
	 * 修改地址
	 * */
	public function editadd(){
		$id=I('id',0);
		if($id!=0){
			$entity=array(
				'country'=>'中国',
				'city'=>I('sheng',''),
				'province'=>I('shi',''),
				'area'=>I('qu',''),
				'detailinfo'=>I('address',''),
				'contactname'=>I('uname',''),
				'mobile'=>I('phone',''),
				'wxno'=>'',
			);
			$result=apiCall(AddressApi::SAVE_BY_ID,array($id,$entity));
			if($result['status']){
				$this->assign("add",null);
				$this->success('修改地址成功!',U('Shop/User/address'));
			}else{
				$this->error('修改失败');
			}
		}
		
	}
	/*
	 * 删除地址
	 * */
	 public function deladdress(){
	 	$id=I('id',0);
		if($id!=0){
//			dump($id);
			$map=array('id'=>$id);
			$result=apiCall(AddressApi::DELETE,array($map));
			$this->success('删除地址成功!',U('Shop/User/address'));
		}else{
			$this->error('删除失败');
		}
	 }
	/**
	 * 个人中心
	 */
	public function index(){
		$user=session('user');
		$this->assign('user',$user);
		$id=$user['id'];
		$map=array('id'=>$id);
		if($id!=0){
			$result=apiCall(AccountApi::GET_INFO,array($id));
			$this->assign('user',$result['info']);
			$this->assign('phone',substr_replace($result['info']['mobile'],'****', 3, 4));
			$this->theme($this->themeType)->display();
		}else{
			$this->error('获取用户信息失败,请重新登录',U('Shop/Index/login'));
		}
		
	}

	/**
	 *	订单
	 */
	public function order(){
		$user=session('user');
		$this->assign('user',$user);
		$this->theme($this->themeType)->display();
	}
	/*
	 * 用户信息
	 * */
	public function infoview(){
		$user=session('user');
		$this->assign('user',$user);
		$this->assign('phone',substr_replace($user['mobile'],'****', 3, 4));
		$this->theme($this->themeType)->display();
	}

}

