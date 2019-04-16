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

class wechat{
    
    private $mysql;
    private $group;
    
    //初始化
    public function __construct(){
        (new model())->load('user', 'session')->check();
        $this->mysql = new mysql();
    }
    
    /*-----------------------------*/
    //全自动版
    public function automatic(){
        (new model())->load('user', 'group')->review('wechat_auto');
        $result = page::conduct('client_wechat_automatic_account',request::filter('get.page'),10,"user_id={$_SESSION['MEMBER']['uid']}",null,'id','asc');
        new view('automatic/index',[
            'result'=>$result,
            'mysql'=>$this->mysql
        ]);
    }
    
    //添加
    public function automaticAdd(){
       (new model())->load("automatic", "features")->add($this->mysql);
    }
    
    //启动automatic轮训
    public function startAutomaticRb(){
        (new model())->load("automatic", "features")->startRb($this->mysql);
    }
    
    //启动网关
    public function startAutomaticGateway(){
        (new model())->load("automatic", "features")->startGateway($this->mysql);
    }
    
    //安全注销
    public function startAutomaticLogOut(){
        (new model())->load("automatic", "features")->startLogOut($this->mysql);
    }
    
    //请求登录
    public function startAutomaticLogin(){
        (new model())->load("automatic", "features")->startLogin($this->mysql);
    }
    
    //获取微信状态
    public function getAutomaticStatus(){
        (new model())->load("automatic", "features")->getStatus($this->mysql);
    }

    //删除微信
    public function automaticDelete(){
        (new model())->load("automatic", "features")->delete($this->mysql);
    }
    
    //全部订单
    public function automaticOrder(){
        (new model())->load("automatic", "features")->order($this->mysql);
    }
    
    //手动补发
    public function automaticReissue(){
        (new model())->load("automatic", "features")->reissue($this->mysql);
    }
    
    //轮训通道测试
    public function robinTest(){
        new view('automatic/robinTest');
    }
    
    //单个微信测试
    public function gatewayTest(){
        new view('automatic/gatewayTest');
    }
    
    //微信配置
    public function automaticConfig(){
        new view('automatic/setting');
        
    }
    
    //微信配置result
    public function automaticConfigResult(){
        unset($_SESSION['automaticConfig']);
        $robin_arr = [1,2,3];
        $robin = intval(request::filter('get.robin'));
        if (!in_array($robin, $robin_arr)) functions::json(-1, '微信配置修改失败');
        userCog::update('automaticConfig', [
            'robin'=>$robin
        ], $_SESSION['MEMBER']['uid']);
        functions::json(200, '微信配置更新成功!');
    }
    
    /*-----------------------------*/
    
    
    
}
