<?php
namespace Test\Controller;
use Shop\Api\OrdersApi;
use Think\Controller;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/6
 * Time: 9:51
 */
class TestOrdersApiController extends Controller{
    /**
     *
     */
    public function query(){
        $map=array(
            'wxuser_id'=>1
        );
        $page=array('curpage'=>0,'size'=>10);
        $order="";
        $result=apiCall(OrdersApi::QUERY,array($map,$page));
        dump($result);
    }

}