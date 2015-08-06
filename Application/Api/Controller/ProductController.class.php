<?php
namespace Api\Controller;
use Shop\Api\ProductApi;
/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/5
 * Time: 12:04
 */
class ProductController extends ApiController{

    /**
     * 不分页查询
     */
    public function queryNoPaging(){
        $notes = "应用".$this->client_id."，调用商品查询接口";

        addLog("Product/queryNoPaging",$_GET,$_POST,$notes);
        $map=array(
            'name'=>array('like',I('pname','')),
        );
        $result=apiCall(ProductApi::QUERY_NO_PAGING,array($map));
        if($result['status']){
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr($result['info']);
        }

    }

    /**
     * 分页查询
     * pname:名称（模糊查询）
     * curpage:当前页
     * size:显示个数
     */
    public function query(){
        $notes = "应用".$this->client_id."，调用商品分页查询接口";
        addLog("Product/queryNoPaging",$_GET,$_POST,$notes);
        $map=array(
            'name'=>array('like','%'.I('pname','').'%'),   //模糊查询
            'onshelf'=>1   //已上架的产品
        );
        $page = array('curpage'=>I('curpage',0),'size'=>I('size',10)); //分页产品

        $result=apiCall(ProductApi::QUERY,array($map,$page));
        if($result['status']){
            $this->apiReturnSuc($result['info']['list']);
        }else{
            $this->apiReturnErr("暂无商品");
        }
    }


    function getSupportMethod()
    {
        return array(
            'item'=>array(
                'param'=>'access_token|username|password',
                'return'=>'array(\"status\"=>返回状态,\"info\"=>\"信息\")',
                'author'=>'hebidu [hebiduhebi@163.com]',
                'version'=>'1.0.0',
                'description'=>'用户登录验证',
                'demo_url'=>'http://manual.itboye.com#',
            ),
        );

    }
}