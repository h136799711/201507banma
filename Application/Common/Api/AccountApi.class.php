<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 09:29
 */

namespace Common\Api;

use Admin\Api\MemberApi;
use Common\Model\WxuserGroupModel;
use Uclient\Api\UserApi;
use Weixin\Api\WxuserApi;

interface IAccount
{


    function login($username, $password,$type,$from);

    function register($entity);

    function getInfo($id);

    function update($uid,$password,$entity);

}

/**
 * 本系统账号相关操作统一接口
 * Class AccountApi
 * @package Common\Api
 */
class AccountApi implements IAccount
{
    /**
     * 登录
     */
    const LOGIN = "Common/Account/login";
    /**
     * 注册
     */
    const REGISTER = "Common/Account/register";
    /**
     * 获取用户信息
     */
    const GET_INFO = "Common/Account/getInfo";

    const UPDATE="Common/Account/update";



    /**
     * 用户更新
     */
    public function update($uid,$password,$data){
        return apiCall(UserApi::UPDATE,array($uid, $password, $data));
    }

    /**
     * 获得用户信息
     * @param $id
     * @return array
     */
    public function getInfo($id){

        $result = apiCall(UserApi::GET_INFO, array($id));
//        id,username,email,mobile,status
        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $user_info = $result['info'];

        $result = apiCall(MemberApi::GET_INFO, array(array('id'=>$id)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $member_info = $result['info'];

       // $result = apiCall(WxuserApi::GET_INFO, array(array('id'=>$id)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $wxuser_info = $result['info'];

        $info = array_merge($user_info,$member_info);
        return array('status'=>true,'info'=>$info);
    }


    public function login($username, $password,$type, $from)
    {
        // TODO: Implement login() method.
        $result=apiCall(UserApi::LOGIN,array($username, $password,$type, $from));
        return $result;
    }

    /**
     *
     * @param $entity | key＝》username,password ,from,     .realname,email,mobile非必须
     * @return array
     */
    public function register($entity)
    {


        if (!isset($entity['username']) || !isset($entity['password']) || !isset($entity['from'])) {
            return array('status' => false, 'info' => "账户信息缺失!");
        }

        $username = $entity['username'];
        $password = $entity['password'];
        $email = $entity['email'];
        $mobile = $entity['mobile'];
        $from = $entity['from'];
        $realname=$entity['realname'];
        $birthday=$entity['birthday'];

        $trans = M();
        $trans->startTrans();
        $error = "";
        $flag = false;
        $result = apiCall(UserApi::REGISTER, array($username, $password, $email, $mobile, $from));
        $uid = 0;
        if ($result['status']) {
            $uid = $result['info'];

            $member = array(
                'uid' => $uid,
                'realname' => $realname,
              //  'nickname' => $wxuser['nickname'],
                'idnumber' => '',
             //   'sex' =>  $wxuser['sex'],
                'birthday' => $birthday,
                'qq' => '',
                'score' => 0,
                'login' => 0,
            );

            $result = apiCall(MemberApi::ADD, array($member));
            if (!$result['status']) {
                $flag = true;
                $error = $result['info'];
            }
        } else {
            $flag = true;
            $error = $result['info'];
        }


        if ($flag) {
            apiCall(UserApi::DELETE_BY_ID, array($uid));
            $trans->rollback();
            return array('status' => false, 'info' => $error);
        } else {
            $trans->commit();
            return array('status' => true, 'info' => $uid);
        }

    }
}