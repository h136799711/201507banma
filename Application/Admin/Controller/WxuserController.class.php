<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Api\WxuserGroupApi;
use Common\Api\WeixinApi;
use Weixin\Api\WxaccountApi;
use Weixin\Api\WxuserApi;

class WxuserController extends AdminController {
	private $wxapi;
	protected function _initialize() {
		parent::_initialize();
	}

    /**
     * 分销商管理
     */
    public  function  distribution(){

        $nickname = I('post.nickname','');


        $where = array('groupid'=>array('gt',1));

//        $where['subscribe_time'] = array( array('EGT', $startdatetime), array('elt', $enddatetime), 'and');
        if(!empty($nickname)){
            $where['nickname'] = array('like','%'.$nickname.'%');
        }
        $params = array();
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $order = "  create_time desc ";
        $fields = false;

        $result = apiCall(WxuserApi::QUERY,array($where,$page,$order,$fields,$params));

        $group = apiCall(WxuserGroupApi::QUERY_NO_PAGING);

        $this->exitIfError($group);

        if(!$result['status']){
            $this->error($result['info']);
        }

        $list = $result['info']['list'];
        //2次循环处理 ， 用户组名称
        foreach($list as &$vo){
            foreach($group['info'] as $gp){
                if($gp['id'] == $vo['groupid']){
                    $vo['_group_name'] = $gp['name'];
                }
            }
        }

//        dump($list);


        $this -> assign('startdatetime', $startdatetime);
        $this -> assign('enddatetime', $enddatetime);
        $this->assign("nickname",$nickname);
        $this->assign("list",$list);
        $this->assign("show",$result['info']['show']);

        $this->display();
    }
	
	/**
	 * 查看
	 */
	public function view(){
		$id = I('get.id',0);
		$result = apiCall(WxuserApi::GET_INFO, array(array('id'=>$id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->assign("vo",$result['info']);
		$this->display();
	}

	public function index() {
		//get.startdatetime
		$nickname = I('nickname','');
		
		
		$startdatetime = I('startdatetime', date('Y-m-d', time() - 24 * 3600), 'urldecode');
		$enddatetime = I('enddatetime', date('Y-m-d', time()), 'urldecode');

		//分页时带参数get参数
		$params = array('startdatetime' => $startdatetime, 'enddatetime' => $enddatetime);

		$startdatetime = strtotime($startdatetime);
		$enddatetime = strtotime($enddatetime);

		if ($startdatetime === FALSE || $enddatetime === FALSE) {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error(L('ERR_DATE_INVALID'));
		}

		$map = array();

		$map['subscribe_time'] = array( array('EGT', $startdatetime), array('elt', $enddatetime), 'and');

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " subscribe_time desc ";
		if(!empty($nickname)){
			$map['nickname'] = array('like','%'.$nickname.'%');
			$params['nickname'] = $nickname;
		}
		
		//
		$result = apiCall(WxuserApi::QUERY, array($map, $page, $order, $params));

		//
		if ($result['status']) {
			$this -> assign('nickname', $nickname);
			$this -> assign('startdatetime', $startdatetime);
			$this -> assign('enddatetime', $enddatetime);
			$this -> assign('show', $result['info']['show']);
			$this -> assign('list', $result['info']['list']);
			$this -> display();
		} else {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error($result['info']);
		}
	}

	
	/**
	 *
	 */
	public function select() {

		$map['nickname'] = array('like', "%" . I('q', '', 'trim') . "%");
		$map['id'] = I('q', -1);
		$map['_logic'] = 'OR';
		$page = array('curpage' => 0, 'size' => 20);
		$order = " subscribe_time desc ";

		$result = apiCall(WxuserApi::QUERY, array($map, $page, $order, false, 'id,nickname,avatar,openid'));

		if ($result['status']) {
			$list = $result['info']['list'];
			$this -> success($list);
		} else {
			LogRecord($result['info'], __LINE__);
		}

	}

	/**
	 * 将用户添加到会员组
	 * TODO: 后期考虑会员组-会员之间的关系做一张表，建立多对多关系	 *
	 */
	public function addToGroup() {
		if (IS_POST) {
			$groupid = I('post.groupid', 0);
			$id = I('post.uid', 0);
			$result = apiCall(WxuserApi::SAVE_BY_ID, array($id, array('groupid' => $groupid)));

			if ($result['status']) {
				$this -> success(L('RESULT_SUCCESS'), U('Admin/WxuserGroup/subMember', array('groupid' => $groupid)));
			} else {
				LogRecord($result['info'], __LINE__);
			}
		}
	}

	/**
	 * 将用户添加到会员组
	 * TODO: 后期考虑会员组-会员之间的关系做一张表，建立多对多关系
	 */
	public function delFromGroup() {
		if (IS_GET) {
			$groupid = I('get.groupid', 0);
			$id = I('get.uid', 0);

			$result = apiCall(WxuserApi::SAVE_BY_ID, array($id, array('groupid' => 1)));

			if ($result['status']) {
				$this -> success(L('RESULT_SUCCESS'), U('Admin/WxuserGroup/subMember', array('groupid' => $groupid)));
			} else {
				LogRecord($result['info'], __LINE__);
			}
		}
	}

	public function viewFamily() {

        $level = I('post.level',1);

		//get.startdatetime
		$startdatetime = I('startdatetime', date('Y/m/d', time() - 24 * 3600), 'urldecode');
		$enddatetime = I('enddatetime', date('Y/m/d', time()), 'urldecode');

		//分页时带参数get参数
		$params = array('startdatetime' => $startdatetime, 'enddatetime' => $enddatetime);

		$startdatetime = strtotime($startdatetime);
		$enddatetime = strtotime($enddatetime);

		if ($startdatetime === FALSE || $enddatetime === FALSE) {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error(L('ERR_DATE_INVALID'));
		}

		$map = array();

		$map['subscribe_time'] = array( array('EGT', $startdatetime), array('elt', $enddatetime), 'and');

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " subscribe_time desc ";
		
		$wxuserid = I('get.id',0);
        

		//
		$result = apiCall(WxuserApi::QUERY_SUB_MEMBER, array($wxuserid,$level, $page, $params));
		
		//
		if ($result['status']) {
            $this -> assign("level",$level);
            $this -> assign('startdatetime', $startdatetime);
			$this -> assign('enddatetime', $enddatetime);
			$this -> assign('show', $result['info']['show']);
			$this -> assign('list', $result['info']['list']);
			$this -> display();
		} else {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error($result['info']);
		}
	}
	
	/**
	 * 同步
	 */
//	public function syncUser(){
//
//		set_time_limit(0);
//		$uid = session("uid");
//
//		$wxaccount = getWxAccountID();// "eotprkjn1426473619";
//		$result = apiCall(WxaccountApi::GET_INFO, array( array('id' => $wxaccount)));
//
//		if($result['status'] === false){
//			$this->error($result['info']);
//		}
//		if(is_array($result['info'])){
//			$appid = $result['info']['appid'];
//			$appsecret = $result['info']['appsecret'];
//			$this->wxapi = new WeixinApi($appid,$appsecret);
//			$nextOpenID = I('get.next_openid','');
//			$userlist = $this->wxapi->getUserList($nextOpenID);
////			dump($users);
//			$count = $userlist['count'];
//			$openids = $userlist['data']['openid'];
//			$nextOpenID = $userlist['next_openid'];
//
//			$wxaccountid = getWxAccountID();
//			$success = 0;
//			for($i = 0 ; $i < $count;$i++){
////				dump($openids[$i]);
////				$result = $this->wxapi->getBaseUserInfo($openids[$i]);
////				if($result['status']){
//				$result = $this->addOrUpdateWxuser($openids[$i] , $wxaccountid);
//				if($result){
//					$success++;
//				}
////				}
//			}
//			if (strlen($nextOpenID)){
//				$this->success('本次更新'.$success.'条,正在获取下一批粉丝数据',U('Admin/Wxuser/syncUser',array('token'=>$token,'next_openid'=>$nextOpenID)));
//
//			}else {
//				$this->success('更新完成,现在获取粉丝详细信息',U('Admin/Wxuser/index',array('token'=>$this->token)));
//
//			}
////			dump($count - $success);
////			dump($result);
//		}else{
//			$this->error("当前无公众号！");
//		}
//	}


	
	public function sendText(){
		if(IS_GET){

			$id = I('get.id',0);
            $result = apiCall(WxuserApi::GET_INFO,array(array('id'=>$id)));
            $this->exitIfError($result);

            $this->assign("vo",$result['info']);
			$this->display();

		}elseif(IS_POST){

			$text = I('post.text','');
			$wxuserid = I('post.uid',0);

			$this->sendTextTo($wxuserid,$text);
			$this->success("发送成功！");

		}
	}
	
	
	private function sendTextTo($wxuserid,$text){
		//
		$wxaccountid = getWxAccountID();
		$result = apiCall(WxuserApi::GET_INFO,array(array("id"=>$wxuserid)));
		$wxaccount = apiCall(WxaccountApi::GET_INFO, array(array("id"=>$wxaccountid)));
		$openid = "";

		if($result['status'] && is_array($result['info'])){
			$openid = $result['info']['openid'];
		}else{
            $this->error("用户信息获取失败!");
        }

		if($wxaccount['status'] && is_array($wxaccount['info'])){
            $appid =  $wxaccount['info']['appid'];
            $appsecret =  $wxaccount['info']['appsecret'];

            $param = array(
                'appid'=>$appid,
                'appsecret'=>$appsecret,
                'openid'=>$openid,
                'text'=>$text,
            );

            tag("send_msg_to_user",$param);
//            dump($param);
//			$wxapi = new WeixinApi($appid,$appsecret);
//			$wxapi->sendTextToFans($openid, $text);
//			$wxapi->sendTextToFans($openid, $text);//发2次
		}
	}


}
