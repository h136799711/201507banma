<?php
namespace Api\Controller;


use Shop\Api\AddressApi;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/11
 * Time: 11:14
 */
class AddressController extends ApiController{
    function getSupportMethod(){

    }

    /**
     * 添加收货地址
     */
    public function add(){
        $notes = "应用".$this->client_id."，调用Address添加接口";
        addLog("Address/add",$_GET,$_POST,$notes);

        $province=$this->_post('province','');
        $city=$this->_post('city','');
        $area=$this->_post('area','');
        $detailinfo=$this->_post('detailinfo','');
        $contactname=$this->_post('contactname','');
        $mobile=$this->_post('mobile','');

        if($province==""){
            $this->apiReturnErr("省份不能为空");
        }
        if($city==""){
            $this->apiReturnErr("城市不能为空");
        }
        if($area==""){
            $this->apiReturnErr("区不能为空");
        }
        if($detailinfo==""){
            $this->apiReturnErr("详细地址不能为空");
        }
        if($contactname==""){
            $this->apiReturnErr("联系人不能为空");
        }

        if($mobile==""){
            $this->apiReturnErr("联系电话不能为空");
        }

        $map=array(
            'uid'=>I("post.uid",0),
            'country'=>I("post.country",''),
            'city'=>I("",''),
            'province'=>I("post.province",''),
            'detailinfo'=>I("post.detailinfo",''),
            'area'=>I('post.area',''),
            'contactname'=>I('post.contactname',''),
            'mobile'=>I('post.mobile',''),
            'wxno'=>I('post.wxno','')
        );

        $result= apiCall(AddressApi::ADD,array($map));
        if($result['status']){
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr("添加失败");
        }
    }


    /**
     * 查看收货地址
     */
    public function queryNoPaging(){
        $notes = "应用".$this->client_id."，调用Address查询接口";
        addLog("Address/add",$_GET,$_POST,$notes);

        $map=array(
          'uid'=>I('uid'),
        );
        $result=apiCall(AddressApi::QUERY_NO_PAGING,array($map));
        if($result['status']){
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr("查询失败");
        }

    }

}