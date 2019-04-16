<?php
namespace xh\run\admin\controller;
use xh\library\session;
use xh\library\model;
use xh\library\url;
use xh\library\mysql;
use xh\library\view;
use xh\library\request;
use xh\library\functions;
use xh\unity\page;
use xh\unity\cog;
use xh\unity\callbacks;

class alipay{
    //构造一个mysql请求
    private $mysql;
    
    //权限验证
    protected function powerLogin($Mid){
        session::check();
        if (!(new model())->load('user', 'authority')->moduleValidate($Mid)){
            url::address(url::s('admin/index/home'),'您没有权限访问',3);
        }
        $this->mysql = new mysql();
    }
    
    //支付宝-全自动版
    //权限ID: 24
    public function automatic(){
        $this->powerLogin(24);
        $where = null;
        $userid = intval(request::filter('get.userid'));
        $id = intval(request::filter('get.id'));
        //检测是否查询会员id
        if (!empty($userid)) {
            $where = 'user_id = ' . $userid;
        }
        //检测是否查询支付宝id
        if (!empty($id)){
            $where = 'id=' . $id;
        }
        $result = page::conduct('client_alipay_automatic_account',request::filter('get.page'),10,$where,null,'id','asc');
        new view('alipay/index',[
            'mysql'=>$this->mysql,
            'result'=>$result
        ]);
    }
    
    //启动automatic轮训
    //权限ID: 24
    public function startAutomaticRb(){
        $this->powerLogin(24);
        $id = intval(request::filter('get.id'));
        //检查该支付宝
        $find_alipay = $this->mysql->query("client_alipay_automatic_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-3, '更改异常!');
        $training = 2;
        if ($find_alipay['training'] == 2) {
            //开启状态
            $training = 1;
            //检测账号是否异常
            if ($find_alipay['status'] != 4) functions::json(-3, '更改失败,当前支付宝没有在线!');
        }
        $update = $this->mysql->update("client_alipay_automatic_account", [
            'training'=>$training
        ],"id={$id}");
        if ($update > 0) functions::json(200, '更改轮训成功!');
        functions::json(-2, '更改失败!');
    }
    
    //启动网关
    //权限ID: 24
    public function startAutomaticGateway(){
        $this->powerLogin(24);
        $id = intval(request::filter('get.id'));
        //检查该支付宝
        $find_alipay = $this->mysql->query("client_alipay_automatic_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-3, '更改异常!');
        $receiving = 2;
        if ($find_alipay['receiving'] == 2) {
            //开启状态
            $receiving = 1;
            //检测账号是否异常
            if ($find_alipay['status'] != 4) functions::json(-3, '更改失败,当前支付宝没有在线!');
        }
        $update = $this->mysql->update("client_alipay_automatic_account", [
            'receiving'=>$receiving
        ],"id={$id}");
        if ($update > 0) functions::json(200, '更改网关成功!');
        functions::json(-2, '更改失败!');
    }
    
    //安全注销
    //权限ID: 24
    public function startAutomaticLogOut(){
        $this->powerLogin(24);
        $id = intval(request::filter('get.id'));
        //检查该支付宝
        $find_alipay = $this->mysql->query("client_alipay_automatic_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-3, '当前支付宝出现异常!');
        if ($find_alipay['status'] == 6 || $find_alipay['status'] == 1) functions::json(-3, '当前支付宝账号已经安全注销过了!');
        $update = $this->mysql->update("client_alipay_automatic_account", [
            'status'=>6
        ],"id={$id}");
        if ($update > 0) functions::json(200, '安全注销成功!');
        functions::json(-2, '注销失败!');
    }
    
    
    //删除支付宝
    //权限ID: 24
    public function automaticDelete(){
        $this->powerLogin(24);
        $id = intval(request::filter('get.id'));
        //检查该支付宝
        $find_alipay = $this->mysql->query("client_alipay_automatic_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-2, '删除该支付宝号时出现一个错误!');
        if ($find_alipay['status'] == 6) functions::json(-2, '当前支付宝正在进行安全注销,请耐心等待注销完成后再进行删除!');
        if ($find_alipay['status'] != 1) functions::json(-2, '请将支付宝安全下线后再进行删除!');
        $this->mysql->delete("client_alipay_automatic_account", "id={$id}");
        functions::json(200, '您成功的删除了该支付宝!');
    }
    
    
    //订单管理
    //权限ID：25
    public function automaticOrder(){
        $this->powerLogin(25);
        $sorting = request::filter('get.sorting','','htmlspecialchars');
        $code = request::filter('get.code','','htmlspecialchars');
        $where = null;
        
        //锁定用户查找
        if ($sorting == 'user'){
            if (!empty($code)){
                if ($_GET['locking'] == 'true'){
                    $_SESSION['ALIPAY']['WHERE'] = 'user_id=' . $code . ' ';
                }
            }
            if ($_GET['locking'] == 'false'){
                unset($_SESSION['ALIPAY']['WHERE']);
            }
        }
        
        //支付宝id
        if ($sorting == 'alipay'){
            if ($code != '') {
                $code = intval($code);
                $_SESSION['ALIPAY']['WHERE'] = "alipay_id={$code}";
            }else {
                unset($_SESSION['ALIPAY']['WHERE']);
            }
        }

        $where = $_SESSION['ALIPAY']['WHERE'];
        
        //排序
        if ($sorting == 'status'){
            if ($code < 1) $code = 0;
            if ($code <= 4) $where .= 'and status=' . $code;
            if ($code > 4) $code = 0;
        }
        //callback
        if ($sorting == 'callback'){
            if ($code < 0) $code = 0;
            if ($code <= 1) $where .= 'and callback_status=' . $code;
            if ($code > 1) $code = -1;
        }
        //订单号
        if ($sorting == 'trade_no'){
            if ($code != '') {
                $code = trim($code);
                $where = "trade_no like '%{$code}%' or out_trade_no like '%{$code}%'";
            }
        }
        

        $where = trim($where,'and');

        $result = page::conduct('client_alipay_automatic_orders',request::filter('get.page'),15,$where,null,'id','desc');
        
        new view('alipay/order',[
            'result'=>$result,
            'mysql'=>$this->mysql,
            'sorting'=>[
                'code'=>$code,
                'name'=>$sorting
            ],
            'where' => $where
        ]);
    }
    
    //手动回调管理员版
    //权限ID：25
    public function callback(){
        $this->powerLogin(25);
        $module_name = 'alipay_auto';
        $order_id = request::filter('get.id');
        if (empty($order_id)) functions::json(-1, '订单ID错误');
        $order = $this->mysql->query('client_alipay_automatic_orders', "id={$order_id}")[0];
        if (!is_array($order)) functions::json(-2, '当前订单不存在');
        //查询用户
        $user = $this->mysql->query("client_user","id={$order['user_id']}")[0];
        if (!is_array($user)) functions::json(-2, '该订单的主用户不存在');
        
        //检测订单是否为未支付
        if ($order['status'] != 4){
            $this->mysql->update("client_alipay_automatic_orders", [
                'pay_time'=>time(),
                'status' => 4
            ], "id={$order['id']}");
        }
                if ($order['pay_time'] == 0){
                    $pay_time = time();
                }else {
                    $pay_time = $order['pay_time'];
                }
                
                // 手续费扣除成功，开始回调
                $result = callbacks::curl($order['callback_url'], http_build_query([
                    'account_name' => $user['username'],
                    'pay_time' => $pay_time,
                    'status' => 'success',
                    'amount' => $order['amount'],
                    'out_trade_no' => $order['out_trade_no'],
                    'trade_no' => $order['trade_no'],
                    'fees' => 0.00,
                    'sign' => functions::sign($user['key_id'], [
                        'amount' => $order['amount'],
                        'out_trade_no' => $order['out_trade_no']
                    ]),
                    'callback_time' => $callback_time
                ]));
                
                $this->mysql->update("client_alipay_automatic_orders", [
                    'pay_time'=>$pay_time,
                    'callback_time' => $callback_time,
                    'callback_status' => 1,
                    'callback_content' => $result,
                    'fees' => 0.00
                ], "id={$order['id']}");
         
     
        functions::json(200, ' [' . date("Y/m/d H:i:s", time()) . ']: 订单号->' . $order['trade_no'] . ' 异步通知任务下发成功!');
        //-----------------------------
    }
    
    //删除订单ID,管理员版
    //权限ID：25
    public function automaticOrderDelete(){
        $this->powerLogin(25);
        $id = intval(request::filter('get.id'));
        $this->mysql->delete("client_alipay_automatic_orders", "id={$id}");
        functions::json(200, '您成功的删除了该订单!');
    }
    
    
    
    
}