<?php
namespace Test\Controller;

use Think\Controller;
use Shop\Api\ProductApi;


/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/6
 * Time: 9:32
 */
class TestProductApiController extends Controller{

    /**
     * ²âÊÔ²éÑ¯
     */
    public function query(){
        $map=array(
          'name'=>array(
              'like',
              '%'.'%'
          ),
          'onshelf'=>1
        );
        $page=array('curpage'=>0,'size'=>5);
        $result=apiCall(ProductApi::QUERY,array($map,$page));
        dump($result);
    }


}