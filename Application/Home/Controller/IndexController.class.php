<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	/*
	 * 首页
	 * */
    public function index(){
    	
        $this->display();
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
    public function qrcode(){

        vendor("phpqrcode.phpqrcode");
        $data = I('get.text','http://www.gooraye.net','urldecode') ;

        // 纠错级别：L、M、Q、H
        $level = 'L';
        // 点的大小：1到10,用于手机端4就可以了
        $size = 4;
        // 生成的文件名
        $fileName = RUNTIME_PATH.'phpqrcode/'.time().'.png';
        \QRcode::png($data,$fileName,$level,$size,true);

    }
}