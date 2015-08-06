<?php
namespace Test\Controller;

use Think\Controller;
use Shop\Api\CategoryPropApi;
/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/6
 * Time: 10:38
 */
class TestCategoryPropApiController extends Controller{

    /**
     *
     */
    public function queryPropTable(){

        $map=array(
            'cate_id'=>2,
        );
        dump(apiCall(CategoryPropApi::QUERY_PROP_TABLE,$map));
    }
}