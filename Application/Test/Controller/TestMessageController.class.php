<?php
namespace Test\Controller;

use Think\Controller;
/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/10
 * Time: 16:00
 */
class TestMessageController extends Controller{
    public function index(){

        $this->assign('num',rand(1000,9999));
        $this->display();
    }
}