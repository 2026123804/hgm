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

class service{
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
    

    
    //服务版
    //权限ID：27
    public function index(){
        $this->powerLogin(27);
        $sorting = request::filter('get.sorting','','htmlspecialchars');
        $code = request::filter('get.code','','htmlspecialchars');
        
        //只看微信
        if ($sorting == 'type'){
            $list = [1,2];
            if (in_array($code, $list)){
                $_SESSION['SERVICE_ACCOUNT']['WHERE'] = 'types=' . $code . ' ';
            }else{
                unset($_SESSION['SERVICE_ACCOUNT']['WHERE']);
            }
        }
        
        if ($sorting == 'status'){
            $list = [1,2];
            if (in_array($code, $list)){
                if ($code == 1){
                    $_SESSION['SERVICE_ACCOUNT']['WHERE'] = 'status=4 ';
                }else{
                    $_SESSION['SERVICE_ACCOUNT']['WHERE'] = 'status!=4 ';
                }
            }else{
                unset($_SESSION['SERVICE_ACCOUNT']['WHERE']);
            }
        }
        
        $where = $_SESSION['SERVICE_ACCOUNT']['WHERE'];
        
        //服务id
        if ($sorting == 'service'){
            if ($code != '') {
                $code = intval($code);
                $where = "id={$code}";
            }
        }
        $result = page::conduct('service_account',request::filter('get.page'),10,$where,null,'id','asc');
        new view('service/index',[
            'mysql'=>$this->mysql,
            'result'=>$result
        ]);
    }
    
    //添加微信通道
    //权限ID：27
    public function addWecaht(){
        $this->powerLogin(27);
        //开始添加通道
        $key_id = strtoupper(substr(md5(mt_rand((mt_rand(1000,9999)+mt_rand(1000,9999)),mt_rand(1000000,99999999))), 0, 18));
        $in = $this->mysql->insert("service_account", [
            'name'=>0,
            'status'=>1,
            'login_time'=>0,
            'heartbeats'=>0,
            'active_time'=>0,
            'key_id'=>$key_id,
            'training'=>2,
            'receiving'=>2,
            'android_heartbeat'=>0,
            'types'=>1
        ]);
        
        if ($in>0) functions::json(200, '恭喜您新增了一条微信服务通道!');
        functions::json(-3, '添加失败,请联系管理员!');
    }
    
    //添加支付宝通道
    //权限ID：27
    public function addAlipay(){
        $this->powerLogin(27);
        //开始添加通道
        $key_id = strtoupper(substr(md5(mt_rand((mt_rand(1000,9999)+mt_rand(1000,9999)),mt_rand(1000000,99999999))), 0, 18));
        $in = $this->mysql->insert("service_account", [
            'name'=>0,
            'status'=>1,
            'login_time'=>0,
            'heartbeats'=>0,
            'active_time'=>0,
            'key_id'=>$key_id,
            'training'=>2,
            'receiving'=>2,
            'android_heartbeat'=>0,
            'types'=>2
        ]);
        
        if ($in>0) functions::json(200, '恭喜您新增了一条支付宝服务通道!');
        functions::json(-3, '添加失败,请联系管理员!');
    }
    
    
    //登录账号
    //权限ID：27
    public function login(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该账号
        $find = $this->mysql->query("service_account","id={$id}")[0];
        if (!is_array($find)) functions::json(-3, '当前账号出现异常,请联系客服!');
        if ($find['status'] == 4 || $find['status'] == 6) functions::json(-3, '当前账号状态无法进行登录,请稍后重试!');
        $update = $this->mysql->update("service_account", [
            'status'=>2,
            'login_time'=>time()
        ],"id={$id}");
        functions::json(200, '正在获取登录信息..');
    }
    //获取登录装填
    //权限ID：27
    public function loginStatus(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该账号
        $find = $this->mysql->query("service_account","id={$id}")[0];
        //判断账号
        if ($find['login_time'] + 120 < time() || $find['status'] == 6 || $find['status'] == 5 || $find['status'] == 1) {
            $this->mysql->update("service_account", ['status'=>1],"id={$id}");
            functions::json(-2, '登录服务超时!');
        }
        if ($find['status'] == 2) functions::json(2, '正在获取登录信息..');
        if ($find['status'] == 3) functions::json(3, '登录信息获取成功,准备登录..');
        if ($find['status'] == 7) functions::json(7, '请扫码登录',['img'=>$find['login_img']]);
        if ($find['status'] == 4) functions::json(4, '登录成功');
    }
    
    //启动轮训
    //权限ID: 27
    public function startRobin(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该服务
        $find = $this->mysql->query("service_account","id={$id}")[0];
        if (!is_array($find)) functions::json(-3, '更改异常!');
        $training = 2;
        if ($find['training'] == 2) {
            //开启状态
            $training = 1;
            //检测账号是否异常
            if ($find['status'] != 4) functions::json(-3, '更改失败,当前服务没有在线!');
        }
        $update = $this->mysql->update("service_account", [
            'training'=>$training
        ],"id={$id}");
        if ($update > 0) functions::json(200, '更改轮训成功!');
        functions::json(-2, '更改失败!');
    }
    
    //启动网关
    //权限ID: 27
    public function startGateway(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该服务
        $find_alipay = $this->mysql->query("service_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-3, '更改异常!');
        $receiving = 2;
        if ($find_alipay['receiving'] == 2) {
            //开启状态
            $receiving = 1;
            //检测账号是否异常
            if ($find_alipay['status'] != 4) functions::json(-3, '更改失败,当前服务没有在线!');
        }
        $update = $this->mysql->update("service_account", [
            'receiving'=>$receiving
        ],"id={$id}");
        if ($update > 0) functions::json(200, '更改网关成功!');
        functions::json(-2, '更改失败!');
    }
    
    //设置为主要系统收款账号
    public function setLord(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该服务
        $find_alipay = $this->mysql->query("service_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-3, '更改异常!');
        $lord = 1;
        if ($find_alipay['lord'] == 1) {
            //开启状态
            $lord = 0;
        }
        $update = $this->mysql->update("service_account", [
            'lord'=>$lord
        ],"id={$id}");
        if ($update > 0) functions::json(200, '设置成功!');
        functions::json(-2, '更改失败!');
    }
    
    //安全注销
    //权限ID: 27
    public function startLogOut(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该服务
        $find_alipay = $this->mysql->query("service_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-3, '当前支付宝出现异常!');
        if ($find_alipay['status'] == 6 || $find_alipay['status'] == 1) functions::json(-3, '当前服务已经安全注销过了!');
        $update = $this->mysql->update("service_account", [
            'status'=>6
        ],"id={$id}");
        if ($update > 0) functions::json(200, '安全注销成功!');
        functions::json(-2, '注销失败!');
    }
    
    //删除服务
    //权限ID: 27
    public function delete(){
        $this->powerLogin(27);
        $id = intval(request::filter('get.id'));
        //检查该服务
        $find_alipay = $this->mysql->query("service_account","id={$id}")[0];
        if (!is_array($find_alipay)) functions::json(-2, '删除该服务时出现一个错误!');
        if ($find_alipay['status'] == 6) functions::json(-2, '当前服务正在进行安全注销,请耐心等待注销完成后再进行删除!');
        if ($find_alipay['status'] != 1) functions::json(-2, '请将服务安全下线后再进行删除!');
        $this->mysql->delete("service_account", "id={$id}");
        functions::json(200, '您成功的删除了该服务!');
    }
    
    //订单管理
    //权限ID：26
    public function order(){
        $this->powerLogin(26);
        $sorting = request::filter('get.sorting','','htmlspecialchars');
        $code = request::filter('get.code','','htmlspecialchars');
        
        //只看微信
        if ($sorting == 'type'){
            $list = [1,2];
            if (in_array($code, $list)){
                $_SESSION['SERVICE']['WHERE'] = 'types=' . $code . ' ';
            }else{
                unset($_SESSION['SERVICE']['WHERE']);
            }
        }
        
        //锁定用户查找
        if ($sorting == 'user'){
            if (!empty($code)){
                 $_SESSION['SERVICE']['WHERE'] = 'user_id=' . $code . ' ';
            }else{
                unset($_SESSION['SERVICE']['WHERE']);
            }
        }
        
        //account
        if ($sorting == 'account'){
            $list = [1,2];
            if (in_array($code, $list)){
                if ($code == 1){
                    $_SESSION['SERVICE']['WHERE'] = 'user_id=0';
                }else{
                    $_SESSION['SERVICE']['WHERE'] = 'user_id != 0';
                }
                
            }else{
                unset($_SESSION['SERVICE']['WHERE']);
            }
        }

        $where = $_SESSION['SERVICE']['WHERE'];
        
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
        
        //服务id
        if ($sorting == 'service'){
            if ($code != '') {
                $code = intval($code);
                $where = "service_id={$code}";
            }
        }
        


        $where = trim($where,'and');
 
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
    
    //手动回调管理员版
    //权限ID：26
    public function callback(){
        $this->powerLogin(26);
        $module_name = 'service_auto';
        $order_id = request::filter('get.id');
        if (empty($order_id)) functions::json(-1, '订单ID错误');
        $order = $this->mysql->query('service_order', "id={$order_id}")[0];
        if (!is_array($order)) functions::json(-2, '当前订单不存在');
        if ($order['user_id'] != 0){
            //查询用户
            $user = $this->mysql->query("client_user","id={$order['user_id']}")[0];
            if (!is_array($user)) functions::json(-2, '该订单的主用户不存在');
        }else{
            $user['username'] = "SYSTEM_CALLBACK";
            $user['key_id'] = cog::read('server')['key'];
        }
        
        
        //检测订单是否为未支付
        if ($order['status'] != 4){
            $this->mysql->update("service_order", [
                'pay_time'=>time(),
                'status' => 4
            ], "id={$order['id']}");
        }
        if ($order['pay_time'] == 0){
            $pay_time = time();
        }else {
            $pay_time = $order['pay_time'];
        }
        
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
            'callback_time' => $callback_time,
            'type'=>$order['types'],
            'account_key' => $user['key_id']
            
        ]));
        
        $this->mysql->update("service_order", [
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
    //权限ID：26
    public function orderDelete(){
        $this->powerLogin(26);
        $id = intval(request::filter('get.id'));
        $this->mysql->delete("service_order", "id={$id}");
        functions::json(200, '您成功的删除了该订单!');
    }
    
    //通道测试
    //权限ID: 27
    public function robinTest(){
        $this->powerLogin(27);
        new view('service/robinTest');
    }
    
    //单通道测试
    public function gatewayTest(){
        $this->powerLogin(27);
        new view('service/gatewayTest');
    }
    
}
