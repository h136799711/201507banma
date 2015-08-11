<?php
namespace Test\Controller;

use Shop\Api\AddressApi;
use Think\Controller;


/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/11
 * Time: 17:44
 */
class TestController extends  Controller{
    public function testAddress(){
        $map=array(
            'uid'=>13
        );

        dump(apiCall(AddressApi::QUERY_WITH_CITY_WITH_AREA,array($map)));
    }
}