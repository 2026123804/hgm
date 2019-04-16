<?php
namespace xh\run\index\controller;


use xh\library\model;
use xh\library\mysql;
use xh\library\view;
use xh\unity\page;
use xh\library\request;


class service{
    
    private $mysql;
    
    //初始化
    public function __construct(){
        (new model())->load('user', 'session')->check();
        (new model())->load('user', 'group')->review('service_auto');
        $this->mysql = new mysql();
    }
    
    
    
    //订单首页
    public function order(){
        $where = "user_id={$_SESSION['MEMBER']['uid']} and ";
        $sorting = request::filter('get.sorting','','htmlspecialchars');
        $code = request::filter('get.code','','htmlspecialchars');
        
        //serviceID
        if ($sorting == 'service'){
            if ($code != '' && $_SESSION['SERVICE']['ORDER']['WHERE'] == ''){
                $code_arr = explode(",", $code);
                if (is_array($code_arr)){
                    $wecaht_where = '';
                    for ($i=0;$i<count($code_arr);$i++){
                        $wecaht_where .= ' or service_id=' . $code_arr[$i];
                    }
                    
                    $_SESSION['SERVICE']['ORDER']['WHERE'] .= '(' . trim(trim($wecaht_where),'or') . ')';
                }
            }
            
            if ($_GET['code'] == 'closed'){
                unset($_SESSION['SERVICE']['ORDER']['WHERE']);
            }
        }
        
        
        //wechat
        if ($sorting == 'gateway'){
            if ($code == 'alipay'){
                $_SESSION['SERVICE']['ORDER']['WHERE'] =  "types=2";
          
            }
            
            if ($code == 'wechat'){
                $_SESSION['SERVICE']['ORDER']['WHERE'] =  "types=1";
                
            }
            
            if ($code == 'all'){
                unset($_SESSION['SERVICE']['ORDER']['WHERE']);
            }
        }

        $where = $where . $_SESSION['SERVICE']['ORDER']['WHERE'];
        $where = trim(trim($where),'and');
        
        //排序
        if ($sorting == 'status'){
            if ($code < 1) $code = 0;
            if ($code <= 4) $where .= ' and status=' . $code;
            if ($code > 4) $code = 0;
        }
        
        //callback
        if ($sorting == 'callback'){
            if ($code < 0) $code = 0;
            if ($code <= 1) $where .= ' and callback_status=' . $code;
            if ($code > 1) $code = -1;
        }
        //订单号
        if ($sorting == 'trade_no'){
            if ($code != '') {
                $code = trim($code);
                $where .= " and (trade_no like '%{$code}%' or out_trade_no like '%{$code}%')";
            }
        }
        
        $result = page::conduct('service_order',request::filter('get.page'),15,$where,null,'id','desc');
        
        new view('service/order',[
            'result'=>$result,
            'mysql'=>$this->mysql,
            'sorting'=>[
                'code'=>$code,
                'name'=>$sorting
            ],
            'where' => $where
        ]);
    }
    
    //轮训通道测试
    public function robinTest(){
        new view('service/robinTest');
    }
    
    
}