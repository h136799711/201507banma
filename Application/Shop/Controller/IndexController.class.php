<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;

use Common\Api\AccountApi;
use Uclient\Model\OAuth2TypeModel;
use Shop\Api\CategoryApi;
use Shop\Api\ProductApi;

class IndexController extends ShopController{
	/*
	 * 首页
	 * */
    public function index(){
    	$map=array('parent'=>0);
    	$result=apiCall(CategoryApi::QUERY_NO_PAGING,array($map));
		$result1=apiCall(CategoryApi::QUERY_NO_PAGING,array($map1));
		$orders = " createtime desc ";
		$page = array('curpage'=>I('post.p',0),'size'=>4);
		$result_new=apiCall(ProductApi::QUERY,array($ss,$page,$orders));
		$this->assign('biggroup',$result['info']);
		$this->assign('group',$result1['info']);
		$this->assign('new',$result_new['info']['list']);
//		dump($result_new);
		$user=session('user');
		$this->assign('user',$user);
//		dump($user);
		$this->theme($this->themeType)->display();
    }
	/*
	 * 商品详情
	 * */
	 public function spxq(){
	 	$map=array('id'=>I('id',''));
	 	$this->theme($this->themeType)->display();
	 }
	/*
	 * 商品分类
	 * */
	public function lists(){
		$id=array('cate_id'=>I('id',''));
		$page = array('curpage'=>I('post.p',0),'size'=>8);
		$result=apiCall(ProductApi::QUERY,array($id,$page));
//		dump($result);
		$this->assign('lists',$result['info']['list']);
		$this->theme($this->themeType)->display();
	}
	/*
	 * 搜索
	 * */
	public function sousuo(){
		$name=I('name','');
		$map['name'] = array('like','%'. $name . '%');
		$page = array('curpage'=>I('post.p',0),'size'=>8);
		$result=apiCall(ProductApi::QUERY,array($map,$page));
//		dump($result);
		$this->assign('lists',$result['info']['list']);
		$this->display('lists');
	}
	
	/*
	 * 注册
	 * TODO:短信注册
	 * */
	public function register(){
		if(IS_GET){
			$this->theme($this->themeType)->display();
		}else{
			$entity=array(
				'username'=>I('uname',''),
				'password'=>I('pwd',''),
				'mobile'=>I('phone',''),
				'email'=>I('email',''),
				'reg_time'=>time(),
				'from'=>OAuth2TypeModel::SELF,
				'realname'=>'',
				'birthday'=>'',
			);
			$result=apiCall(AccountApi::REGISTER,array($entity));
			if($result['status']){
				$this->success('注册成功!正在跳转登录页面',U('Shop/Index/login'));
			}
//			dump($result);
		}
	}
	/*
	 * 登录
	 * TODO：第三方登录
	 * */
	public function login(){
		if(IS_GET){
//			dump("dsafasdfasd");
			$this->theme($this->themeType)->display();
		}else{
			$username=I('uname','');
			$password=I('pwd','');
			$type=1;
			$from=OAuth2TypeModel::SELF;
//			dump("jinlaidasfasdf");
			$result=apiCall(AccountApi::LOGIN,array($username,$password,$type,$from));
			if($result['status']){
				$id=$result['info'];
				$user=apiCall(AccountApi::GET_INFO,array($id));
				session('user',$user['info']);
				$this->success('登陆成功!正在跳转登录页面',U('Shop/Index/index'));
			}else{
				$this->error($result['info']);
			}
		}
	}
	
	public function distributor(){
		
    	if(IS_GET){
    		//phpinfo();
    		$referrer=I('referrer');
			$map=array(
				'id'=>$referrer
			);
			$result=apiCall(WxuserApi::QUERY_NO_PAGING ,array($map));
			$this->assign('referrer',$result[info][0]);
       	 	$this->theme($this->themeType)->display();
		}else if(IS_AJAX){
			
			$map=array(
				'id'=>$this->userinfo['id'],
			);
			$userInfo= apiCall(WxuserApi::QUERY_NO_PAGING,array($map));
			$userInfo['info']['groupid']=13;
			//dump($userInfo['info']);
			$result=apiCall(WxuserApi::SAVE_BY_ID,array($this->userinfo['id'],$userInfo['info']));
			
			$entity=array(
				'name'=>I("name"),
				'phone'=>I("phone"),
				'address'=>I("address"),
				'create_time'=>time(),
				'uid'=>$userInfo['info'][0]['uid'],
				'wxaccount_id'=>1
			);
			$result=apiCall(DistributorInfoApi::ADD,array($entity));
			session('[destroy]'); // 删除session
			$this->success('操作成功',U('Shop/Index/index'));
		}
    }


	protected function _initialize(){
		parent::_initialize();
		$showStartPage = true;
		$last_entry_time = cookie("last_entry_time");
		if(empty($last_entry_time)){
			//一小时过期
			cookie("last_entry_time",time(),3600);
			$last_entry_time = time();			
		}elseif(time() - $last_entry_time < 20*60){
			$showStartPage = false;
		}else{
			//一小时过期
			cookie("last_entry_time",time(),3600);
		}
		
		$this->assign("showstartpage",$showStartPage);
		
	}
	
}

