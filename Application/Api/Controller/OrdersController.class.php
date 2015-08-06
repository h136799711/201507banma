<?php
namespace Api\Controller;

use Shop\Api\OrdersApi;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/5
 * Time: 16:45
 */

class OrdersController extends ApiController{

    /**
     * 不分页查询
     */
    public function queryNoPaging(){

    }

    /**
     * 保存订单
     */
    public function save(){
        //收货地址ID
        $address_id = I('post.address_id', 0);
        $province_name = I('post.province_name', '');
        $city_name = I('post.city_name', '');
        $area_name = I('post.area_name', '');
        $notes = I('post.notes', array());
        if (IS_POST) {
            //购买的商品的列表
            $buyProductList = session("confirm_order_info");
            $map = array('id' => $address_id);
            $result = apiCall(AddressApi::GET_INFO, array($map));
            if (!$result['status']) {
                $this -> error($result['info']);
            }
            if (is_null($result['info'])) {
                LogRecord("收货地址信息获取失败", __FILE__ . __LINE__);
                //如果是空的
                $this -> error("收货地址信息获取失败！");
            }

            $address_info = $result['info'];
            //总价，不含运费
            $all_price = $buyProductList['all_price'];
            //运费
            $all_express = $buyProductList['all_express'];

            //订单对象
            $entity = array('wxaccountid' => $this -> wxaccount['id'], 'storeid' => 0, 'wxuser_id' => $userinfo['id'], 'price' => 0, 'mobile' => $address_info['mobile'], 'wxno' => $address_info['wxno'], 'contactname' => $address_info['contactname'], 'note' => '', 'country' => $address_info['country'], 'province' => $province_name, 'city' => $city_name, 'area' => $area_name, 'detailinfo' => $address_info['detailinfo'], 'orderid' => '', 'items' => '', );
            $i = 0;
            $ids = '';
            $orderid = $this -> getOrderID();
//			addWeixinLog($buyProductList['list'],'订单');
            //分店铺保存订单，每个店铺一张订单
            foreach ($buyProductList['list'] as $key => $vo) {
                //店铺ID
                $entity['storeid'] = $key;
                $entity['orderid'] = $orderid . '_' . $key;
                $entity['items'] = $vo;
                //
                $price = 0.0;
                foreach ($vo['products'] as $item) {
//					addWeixinLog($item,'订单［test］');
                    $price += ($item['price'] * intval($item['count']));
                }

                $entity['price'] = $price;
                if ($i < count($notes)) {
                    $entity['note'] = $notes[$i];

                }

                $i++;
                //				dump($entity['items']['products']);
                //				exit();

                //dump($entity);
                $result = apiCall(OrdersApi::ADD_ORDER, array($entity));
                //				addWeixinLog($result,'订单3333');
                if (!$result['status']) {
                    LogRecord($result['info'], __FILE__ . __LINE__);
                    $this -> error($result['info']);
                }
                $ids .= $result['info'] . '-';
            }
            $ids = trim($ids, "-");
            //TODO: 从购物车中移除相对应的商品，根据店铺ID，商品ID，商品SKU
            //目前 就直接删除购物车
            session("shoppingcart", null);
            session("confirm_order_info", null);

            $this -> success("订单保存成功，前往支付！", C('SITE_URL') . "/index.php/Shop/Pay/pay/id/$ids?showwxpaytitle=1");

        } else {
            LogRecord("禁止访问！", __FILE__ . __LINE__);
            $this -> error("禁止访问！");
        }
    }

    /**
     * 统计月销
     */
    public function monthlySales(){
        $p_ids=array();
        $p_ids=I('p_ids');
        $result=apiCall(OrdersApi::MONTHLY_SALES,array($p_ids));
        if($result['status']){
            $this->apiReturnSuc($result);
        }else{
            $this->apiReturnErr($result);
        }
    }

    function getSupportMethod(){

    }
}
