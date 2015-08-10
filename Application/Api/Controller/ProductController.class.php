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

    protected  $allowType = array("json","rss","html");

    /**
     * 不分页查询
     * pname 商品名称(选填)
     * cate_id商品分类ID(选填)
     */
    public function queryNoPaging(){
        $notes = "应用".$this->client_id."，调用商品查询接口";
        addLog("Product/queryNoPaging",$_GET,$_POST,$notes);

        $map=array(
            'name'=>array('like','%'.I('pname','').'%'),
            'onshelf'=>1,   //已上架的产
        );
        $cate_id=I("cate_id",0);
        if($cate_id!=0){
            $map['cate_id']=$cate_id;
        }
        $result=apiCall(ProductApi::QUERY_NO_PAGING,array($map));
        if($result['status']){
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr($result['info']);
        }
    }

    /**
     * 分页查询
     * pname:名称（模糊查询）(选填)
     * pageNo:当前页
     * pageSize:显示个数
     * cate_id商品分类ID(选填)
     */
    public function query(){
        $notes = "应用".$this->client_id."，调用商品分页查询接口";
        addLog("Product/queryNoPaging",$_GET,$_POST,$notes);
        $map=array(
            'name'=>array('like','%'.I('pname','').'%'),   //模糊查询
            'onshelf'=>1   //已上架的产品
        );
        $cate_id=I("cate_id",0);
        if($cate_id!=0){
            $map['cate_id']=$cate_id;
        }
        $page = array('curpage'=>I('pageNo',0),'size'=>I('pageSize',10)); //分页产品

        $result=apiCall(ProductApi::QUERY,array($map,$page));
        if($result['status']){
            $this->apiReturnSuc($result['info']['list']);
        }else{
            $this->apiReturnErr("暂无商品");
        }
    }


    /**
     * 商品详情展示
     * pid 商品ID
     */
    public function detail(){
        $notes = "应用".$this->client_id."，调用商品详情接口";
        addLog("Product/detail",$_GET,$_POST,$notes);
        $pid=I('pid',0);
        if($pid==0){
            $this->apiReturnErr("请通过正常途径访问商品详情！");
        }
        $map=array(
            'id'=>$pid,
        );
        $result=apiCall(ProductApi::QUERY_NO_PAGING,array($map));
        if($result['status']){
            $this->apiReturnSuc($result['info'][0]);
        }else{
            $this->apiReturnErr("不存在的商品ID");
        }

    }


    function getSupportMethod()
    {
     /*   return array(
            'item'=>array(
                'param'=>'access_token|username|password',
                'return'=>'array(\"status\"=>返回状态,\"info\"=>\"信息\")',
                'author'=>'hebidu [hebiduhebi@163.com]',
                'version'=>'1.0.0',
                'description'=>'用户登录验证',
                'demo_url'=>'http://manual.itboye.com#',
            ),
        );*/

    }
}