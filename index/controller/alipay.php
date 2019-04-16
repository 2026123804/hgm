<?php
namespace xh\run\index\controller;


use xh\library\model;
use xh\library\mysql;
use xh\library\view;
use xh\library\functions;
use xh\unity\page;
use xh\library\request;
use xh\unity\sms;
use xh\unity\userCog;

class alipay{
    
    private $mysql;
    
    //初始化
    public function __construct(){
        (new model())->load('user', 'session')->check();
        $this->mysql = new mysql();
    }
    
    
    //全自动版
    public function automatic(){
        (new model())->load('user', 'group')->review('alipay_auto');
        $result = page::conduct('client_alipay_automatic_account',request::filter('get.page'),10,"user_id={$_SESSION['MEMBER']['uid']}",null,'id','asc');
        new view('alipay/index',[
            'result'=>$result,
            'mysql'=>$this->mysql
        ]);
    }
    
    //添加-->OK
    public function automaticAdd(){
        (new model())->load("alipay", "features")->add($this->mysql);
    }
    
    //启动automatic轮训
    public function startAutomaticRb(){
        (new model())->load("alipay", "features")->startRb($this->mysql);
    }
    
    //启动网关
    public function startAutomaticGateway(){
        (new model())->load("alipay", "features")->startGateway($this->mysql);
    }
    
    //安全注销
    public function startAutomaticLogOut(){
        (new model())->load("alipay", "features")->startLogOut($this->mysql);
    }
    
    //请求登录
    public function startAutomaticLogin(){
        (new model())->load("alipay", "features")->startLogin($this->mysql);
    }
    
    //获取支付宝状态
    public function getAutomaticStatus(){
        (new model())->load("alipay", "features")->getStatus($this->mysql);
    }
    
    //删除支付宝
    public function automaticDelete(){
        (new model())->load("alipay", "features")->delete($this->mysql);
    }
    
    //全部订单
    public function automaticOrder(){
        (new model())->load("alipay", "features")->order($this->mysql);
    }
    
    //手动补发
    public function automaticReissue(){
        (new model())->load("alipay", "features")->reissue($this->mysql);
    }
    
    //轮训通道测试
    public function robinTest(){
        new view('alipay/robinTest');
    }
    
    //单个支付宝测试
    public function gatewayTest(){
        new view('alipay/gatewayTest');
    }
    
    //支付宝配置
    public function automaticConfig(){
        new view('alipay/setting');
        
    }
    
    //支付宝配置result
    public function automaticConfigResult(){
        unset($_SESSION['alipayConfig']);
        $robin_arr = [1,2,3];
        $robin = intval(request::filter('get.robin'));
        if (!in_array($robin, $robin_arr)) functions::json(-1, '支付宝配置修改失败');
        userCog::update('alipayConfig', [
            'robin'=>$robin
        ], $_SESSION['MEMBER']['uid']);
        functions::json(200, '支付宝配置更新成功!');
    }
    
    
}