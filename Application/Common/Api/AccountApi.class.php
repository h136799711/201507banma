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


    function login($username, $password,$type);

    function register($entity);

    function getInfo($id);

    function update($uid,$entity);


    function updatePwd($uid,$oldPwd,$newPwd);

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
     * @param $uid 用户ID
     * @param $oldPwd 老密码
     * @param $newPwd 新密码
     */
    public function updatePwd($uid,$oldPwd,$newPwd){
        //根据用户ID查询用户

    }


    /**
     * 用户更新,不包括修改密码
     */
    public function update($uid,$data){
        $trans = M();
        $trans->startTrans();
        $map=array(
          'email'=>$data['email'],
          'mobile'=>$data['mobile'],
        );
        $result= apiCall(UserApi::SAVE_BY_ID,array($uid,$map));
        if($result['status']){
            $m=array(
              'uid'=>$uid
            );
            $map=array(
                'nickname'=>$data['nickname'],
                'realname'=>$data['realname'],
                'idnumber'=>$data['idnumber'],
                'birthday'=>$data['birthday'],
                'sex'=>$data['sex'],
                'qq'=> $data['qq'],
                'head'=>$data['head']
            );
            $result= apiCall(MemberApi::SAVE,array($m,$map));
            //$trans->commit();
            if($result['status']){
                $trans->commit();
                return array('status' => true, 'info' => $result['info']);
            }else{
                $trans->rollback();
                return array('status' => false, 'info' => $result['info']);
            }
        }else{
            $trans->rollback();
            return array('status' => false, 'info' => $result['info']);
        }
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


    public function login($username, $password,$type)
    {
        // TODO: Implement login() method.
        $result=apiCall(UserApi::LOGIN,array($username, $password,$type));
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
        //$head=$entity['head'];
        //$nickname=$entity['nickname'];
        $birthday=$entity['birthday'];
        //$sex=$entity['sex'];
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
                //'nickname' => $nickname,
                'idnumber' => '',
                //'sex' =>  $sex,
                'birthday' => $birthday,
                'qq' => '',
                //'head'=>$head,
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