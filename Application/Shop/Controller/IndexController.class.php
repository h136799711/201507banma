<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;

use Admin\Api\DatatreeApi;
use Shop\Api\CategoryApi;
use Shop\Api\BannersApi;
use Shop\Api\ProductApi;
use Shop\Model\ProductModel;
use Weixin\Api\WxuserApi;
use Ucenter\Api\MemberApi;
use Ucenter\Api\UcenterMemberApi;
use Distributor\Api\DistributorInfoApi;


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
        $this->display();
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
		$this->display();
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
			$this->display();
		}
	}
	/*
	 * 登录
	 * TODO：第三方登录
	 * */
	public function login(){
		if(IS_GET){
			$this->display();
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

