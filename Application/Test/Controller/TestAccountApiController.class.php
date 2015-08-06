<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 09:45
 */

namespace Test\Controller;

use Common\Api\AccountApi;
use Think\Controller;
use Uclient\Model\OAuth2TypeModel;

/**
 *
 *
 * @Test AccountApi
 * Class TestAccountApiController
 * @package Test\Controller
 *
 */
class TestAccountApiController extends Controller {

    /**
     * ²âÊÔ×¢²á
     */
    public  function register(){
        import("Org.String");

        $username = \String::randString(9,0);

        //$password = \String::randString(6);

        $password="123456";

        $entity = [
            'username'=>$username,
            'password'=>$password,
            'from'=>OAuth2TypeModel::OTHER_APP,
            'realname'=>'lisi',
            'email'=>'',
            'phone'=>'',
        ];

        $result =  AccountApi::REGISTER($entity);
        
        $this->ajaxReturn($result,"xml");
    }

    /**
     * ²âÊÔµÇÂ¼
     */
    public function login(){
       // AccountApi::LOGIN($entity);
        $username="McpTodWRY";
        $password="123456";
        $type=1;
        $from="99";
        $result= apiCall(AccountApi::LOGIN,array($username, $password,$type, $from));
        $this->ajaxReturn($result,"xml");

    }


    /**
     * ¸ù¾Ýid»ñÈ¡
     */
    public function getInfo(){

        $result = apiCall(AccountApi::GET_INFO,array(1));

        $this->ajaxReturn($result,"xml");

    }
    
}