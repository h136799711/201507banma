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
     * ����ҳ��ѯ
     */
    public function queryNoPaging(){
        $notes = "Ӧ��".$this->client_id."��������Ʒ��ѯ�ӿ�";

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
     * ��ҳ��ѯ
     * pname:���ƣ�ģ����ѯ��
     * curpage:��ǰҳ
     * size:��ʾ����
     */
    public function query(){
        $notes = "Ӧ��".$this->client_id."��������Ʒ��ҳ��ѯ�ӿ�";
        addLog("Product/queryNoPaging",$_GET,$_POST,$notes);
        $map=array(
            'name'=>array('like','%'.I('pname','').'%'),   //ģ����ѯ
            'onshelf'=>1   //���ϼܵĲ�Ʒ
        );
        $page = array('curpage'=>I('curpage',0),'size'=>I('size',10)); //��ҳ��Ʒ

        $result=apiCall(ProductApi::QUERY,array($map,$page));
        if($result['status']){
            $this->apiReturnSuc($result['info']['list']);
        }else{
            $this->apiReturnErr("������Ʒ");
        }
    }


    function getSupportMethod()
    {
        return array(
            'item'=>array(
                'param'=>'access_token|username|password',
                'return'=>'array(\"status\"=>����״̬,\"info\"=>\"��Ϣ\")',
                'author'=>'hebidu [hebiduhebi@163.com]',
                'version'=>'1.0.0',
                'description'=>'�û���¼��֤',
                'demo_url'=>'http://manual.itboye.com#',
            ),
        );

    }
}