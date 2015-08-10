<?php
namespace Api\Controller;

use Shop\Api\BannersApi;
/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/8
 * Time: 13:42
 */
class BannersController extends ApiController{

    function getSupportMethod(){

    }

    /**
     * 不分页查询
     */
    public function queryNoPaging(){
        $notes = "应用".$this->client_id."，调用Banners不分页查询接口";
        addLog("Banners/queryNoPaging",$_GET,$_POST,$notes);
       // dump(getDatatree('SHOP_INDEX_BANNERS'));

        $map=array(
            'position'=>18,
        );
        $result=apiCall(BannersApi::QUERY_NO_PAING,array($map));
        if($result['status']){
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr("暂无信息");
        }
    }



}