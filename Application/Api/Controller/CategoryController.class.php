<?php
namespace Api\Controller;
use Shop\Api\CategoryApi;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/8
 * Time: 14:55
 */
class CategoryController extends ApiController{

    function getSupportMethod(){

    }

  /**
   * 不分页查询,通用类目查询
   * parentId 父项ID
   */
    public function queryNoPaging(){
      $parent=I('parentId',0);
      $map=array(
        'parent'=>$parent
      );
      $result= apiCall(CategoryApi::QUERY_NO_PAGING,array($map));
      if($result['status']){
        $this->apiReturnSuc($result['info']);
      }else{
        $this->apiReturnErr("暂无信息");
      }

    }

}