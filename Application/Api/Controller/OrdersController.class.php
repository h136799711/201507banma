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
     * ����ҳ��ѯ
     */
    public function queryNoPaging(){

    }

    /**
     * ���涩��
     */
    public function save(){
        //�ջ���ַID
        $address_id = I('post.address_id', 0);
        $province_name = I('post.province_name', '');
        $city_name = I('post.city_name', '');
        $area_name = I('post.area_name', '');
        $notes = I('post.notes', array());
        if (IS_POST) {
            //�������Ʒ���б�
            $buyProductList = session("confirm_order_info");
            $map = array('id' => $address_id);
            $result = apiCall(AddressApi::GET_INFO, array($map));
            if (!$result['status']) {
                $this -> error($result['info']);
            }
            if (is_null($result['info'])) {
                LogRecord("�ջ���ַ��Ϣ��ȡʧ��", __FILE__ . __LINE__);
                //����ǿյ�
                $this -> error("�ջ���ַ��Ϣ��ȡʧ�ܣ�");
            }

            $address_info = $result['info'];
            //�ܼۣ������˷�
            $all_price = $buyProductList['all_price'];
            //�˷�
            $all_express = $buyProductList['all_express'];

            //��������
            $entity = array('wxaccountid' => $this -> wxaccount['id'], 'storeid' => 0, 'wxuser_id' => $userinfo['id'], 'price' => 0, 'mobile' => $address_info['mobile'], 'wxno' => $address_info['wxno'], 'contactname' => $address_info['contactname'], 'note' => '', 'country' => $address_info['country'], 'province' => $province_name, 'city' => $city_name, 'area' => $area_name, 'detailinfo' => $address_info['detailinfo'], 'orderid' => '', 'items' => '', );
            $i = 0;
            $ids = '';
            $orderid = $this -> getOrderID();
//			addWeixinLog($buyProductList['list'],'����');
            //�ֵ��̱��涩����ÿ������һ�Ŷ���
            foreach ($buyProductList['list'] as $key => $vo) {
                //����ID
                $entity['storeid'] = $key;
                $entity['orderid'] = $orderid . '_' . $key;
                $entity['items'] = $vo;
                //
                $price = 0.0;
                foreach ($vo['products'] as $item) {
//					addWeixinLog($item,'������test��');
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
                //				addWeixinLog($result,'����3333');
                if (!$result['status']) {
                    LogRecord($result['info'], __FILE__ . __LINE__);
                    $this -> error($result['info']);
                }
                $ids .= $result['info'] . '-';
            }
            $ids = trim($ids, "-");
            //TODO: �ӹ��ﳵ���Ƴ����Ӧ����Ʒ�����ݵ���ID����ƷID����ƷSKU
            //Ŀǰ ��ֱ��ɾ�����ﳵ
            session("shoppingcart", null);
            session("confirm_order_info", null);

            $this -> success("��������ɹ���ǰ��֧����", C('SITE_URL') . "/index.php/Shop/Pay/pay/id/$ids?showwxpaytitle=1");

        } else {
            LogRecord("��ֹ���ʣ�", __FILE__ . __LINE__);
            $this -> error("��ֹ���ʣ�");
        }
    }

    /**
     * ͳ������
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
