<?php
namespace xh\run\gateway\controller;


use xh\library\request;
use xh\library\mysql;
use xh\library\functions;
use xh\unity\cog;
use xh\library\ip;
use xh\library\url;
use xh\unity\encrypt;
use xh\unity\userCog;

class index{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }
    
    //端口：automatic
    //网关关卡
    //通讯端口：80
    public function checkpoint(){
        $data = [];
        //网页类型
        $type = request::filter('post.content_type','','htmlspecialchars');
        //商户ID
        $acc_id = intval(request::filter('post.account_id'));
        //通道
        $thoroughfare = request::filter('post.thoroughfare','','htmlspecialchars');
        //检测是否轮训
        $data['robin'] = intval(request::filter('post.robin'));
        //callback_url
        $data['callback_url'] = request::filter('post.callback_url','','htmlspecialchars');
        //success_url
        $data['success_url'] = request::filter('post.success_url','','htmlspecialchars');
        //error_url
        $data['error_url'] = request::filter('post.error_url','','htmlspecialchars');
        //out_trade_no
        $data['out_trade_no'] = request::filter('post.out_trade_no','','htmlspecialchars');
        //trade_no -> 自动生成
        $data['trade_no'] = date("YmdHis") . mt_rand(10000,99999);
        //amount
        $data['amount'] = floatval(request::filter('post.amount'));
        //type -> service_auto -> 专用类型
        $data['type'] = intval(request::filter('post.type'));
        //sign
        $sign = request::filter('post.sign','','htmlspecialchars');
        if ($data['amount'] <= 0) functions::str_json($type, -1, '支付金额不正确');
        if (empty($data['callback_url']) || empty($data['success_url']) || empty($data['error_url'])) functions::str_json($type, -1, 'callback_url(异步通知)、success_url(成功跳转)、error_url(失败跳转), 等地址不能空参数');
        if (empty($data['out_trade_no'])) functions::str_json($type, -1, '没有交易信息,请检查参数是否正确');
        if (!is_array(cog::read('costCog')[$thoroughfare])) functions::str_json($type, -1, '当前通道不存在');
        $find_user = $this->mysql->query("client_user","id={$acc_id}")[0];
        if (!is_array($find_user) && !is_array($_SESSION['SYSTEM_PAY_ID'])) functions::str_json($type, -1, '该商户不存在');
        //检测是否系统订单
        if (is_array($_SESSION['SYSTEM_PAY_ID'])){
            $find_user['key_id'] = cog::read('server')['key'];
        }
        //验证签名
        if ($sign != functions::sign($find_user['key_id'], ['amount'=>$data['amount'],'out_trade_no'=>$data['out_trade_no']])) functions::str_json($type, -3, '签名失败');
        //automatic 微信全自动版
        if ($thoroughfare == 'wechat_auto'){
            $this->automatic($find_user,$type,$data);
        }
        //支付宝版本
        if ($thoroughfare == 'alipay_auto'){
            $this->alipay($find_user,$type,$data);
        }
        //服务版本
        if ($thoroughfare == 'service_auto'){
            $this->service($find_user, $type, $data);
        }
    }
    
    //全自动版,微信
    private function automatic($user,$type_content,$data){
        if ($data['amount'] > 15000) functions::str_json($type_content, -1, '支付金额不能大于15000元');
        //查询用户组
        $group = json_decode($this->mysql->query('client_group',"id={$user['group_id']}")[0]['authority'],true)['wechat_auto'];
        //判断用户组是否存在
        if ($group['open'] != 1) functions::str_json($type_content, -1, 'service->您没有权限使用该通道!');
        //现在的时间
        $now_time = time()-20;
        //轮训
        if ($data['robin'] == 2) {
            //读取轮训算法ID
            $robin = userCog::read('automaticConfig', $user['id'])['robin'];
            //随机算法 v1.2
            if ($robin == 1){
                //随机算法
                $find_wechat = $this->mysql->query("client_wechat_automatic_account","status=4 and user_id={$user['id']} and training=1 and receiving=1 and android_heartbeat>{$now_time}");
                $count_wechat = count($find_wechat);
                if ($count_wechat == 0) functions::str_json($type_content, -1, 'automatic->初始化失败,没有可用的通道');
                $find_wechat = $find_wechat[mt_rand(0,$count_wechat-1)];
            }
            
            //实时收款算法，按少到多排序 v1.0
            if ($robin == 2){
                $find_wechat = $this->mysql->query("client_wechat_automatic_account","status=4 and user_id={$user['id']} and training=1 and receiving=1 and android_heartbeat>{$now_time}",null,"today_money","asc");
                $find_wechat = $find_wechat[0];
            }
            
            //顺序模式算法 v1.0 
            if ($robin == 3){
                $find_wechat = $this->mysql->query("client_wechat_automatic_account","status=4 and user_id={$user['id']} and training=1 and receiving=1 and android_heartbeat>{$now_time}",null,"id","asc");
                $find_wechat = $find_wechat[0];
            }
        }else{
            //指定微信号 单条进入
            $key_id = request::filter('post.keyId','','htmlspecialchars');
            $find_wechat = $this->mysql->query("client_wechat_automatic_account","key_id='{$key_id}'")[0];
            if (!is_array($find_wechat)) functions::str_json($type_content, -1, 'automatic->初始化失败,当前支付通道异常');
            if ($find_wechat['status'] != 4 || $user['id'] != $find_wechat['user_id'] || $find_wechat['receiving'] != 1 || $find_wechat['android_heartbeat'] < $now_time) functions::str_json($type_content, -1, 'automatic->初始化失败,当前支付通道不可使用');
        }
        //已经得到wechat参数
        //生成订单
        $create_order  = $this->mysql->insert('client_wechat_automatic_orders', [
            'wechat_id'=>$find_wechat['id'],
            'creation_time'=>time(),
            'pay_time'=>0,
            'status'=>1,
            'amount'=>$data['amount'],
            'callback_url'=>$data['callback_url'],
            'success_url'=>$data['success_url'],
            'error_url'=>$data['error_url'],
            'user_id'=>$user['id'],
            'callback_time'=>0,
            'out_trade_no'=>$data['out_trade_no'],
            'ip'=>ip::get(),
            'trade_no'=>$data['trade_no']
        ]);
        if ($create_order > 0){
            if ($type_content == 'json'){
                functions::str_json($type_content, 200, 'success',["order_id"=>$create_order]);
            }
            url::address(url::s("gateway/pay/automaticWechat","id={$create_order}"));
        }
        url::address(url::s("gateway/pay/automaticWechat","id={$create_order}"));
    }
    
    //全自动版支付宝
    private function alipay($user,$type_content,$data){
        if ($data['amount'] > 50000) functions::str_json($type_content, -1, '支付金额不能大于50000元');
        //查询用户组
        $group = json_decode($this->mysql->query('client_group',"id={$user['group_id']}")[0]['authority'],true)['alipay_auto'];
        //判断用户组是否存在
        if ($group['open'] != 1) functions::str_json($type_content, -1, 'service->您没有权限使用该通道!');
        //现在的时间
        $now_time = time()-20;
        //轮训
        if ($data['robin'] == 2) {
            //读取轮训算法ID
            $robin = userCog::read('alipayConfig', $user['id'])['robin'];
            //随机算法 v1.2
            if ($robin == 1){
                //随机算法
                $find_alipay = $this->mysql->query("client_alipay_automatic_account","status=4 and user_id={$user['id']} and training=1 and receiving=1 and android_heartbeat>{$now_time}");
                $count_alipay = count($find_alipay);
                if ($count_alipay == 0) functions::str_json($type_content, -1, 'automatic->初始化失败,没有可用的通道');
                $find_alipay = $find_alipay[mt_rand(0,$count_alipay-1)];
            }
            
            //实时收款算法，按少到多排序 v1.0
            if ($robin == 2){
                $find_alipay = $this->mysql->query("client_alipay_automatic_account","status=4 and user_id={$user['id']} and training=1 and receiving=1 and android_heartbeat>{$now_time}",null,"today_money","asc");
                $find_alipay = $find_alipay[0];
            }
            
            //顺序模式算法 v1.0
            if ($robin == 3){
                $find_alipay = $this->mysql->query("client_alipay_automatic_account","status=4 and user_id={$user['id']} and training=1 and receiving=1 and android_heartbeat>{$now_time}",null,"id","asc");
                $find_alipay = $find_alipay[0];
            }
            
        }else{
            //指定微信号 单条进入
            $key_id = request::filter('post.keyId','','htmlspecialchars');
            $find_alipay = $this->mysql->query("client_alipay_automatic_account","key_id='{$key_id}'")[0];
            if (!is_array($find_alipay)) functions::str_json($type_content, -1, 'automatic->初始化失败,当前支付通道异常');
            if ($find_alipay['status'] != 4 || $user['id'] != $find_alipay['user_id'] || $find_alipay['receiving'] != 1 || $find_alipay['android_heartbeat'] < $now_time) functions::str_json($type_content, -1, 'automatic->初始化失败,当前支付通道不可使用');
        }
        //已经得到alipay参数
        //生成订单
        $create_order  = $this->mysql->insert('client_alipay_automatic_orders', [
            'alipay_id'=>$find_alipay['id'],
            'creation_time'=>time(),
            'pay_time'=>0,
            'status'=>1,
            'amount'=>$data['amount'],
            'callback_url'=>$data['callback_url'],
            'success_url'=>$data['success_url'],
            'error_url'=>$data['error_url'],
            'user_id'=>$user['id'],
            'callback_time'=>0,
            'out_trade_no'=>$data['out_trade_no'],
            'ip'=>ip::get(),
            'trade_no'=>$data['trade_no']
        ]);
        if ($create_order > 0){
            if ($type_content == 'json'){
                functions::str_json($type_content, 200, 'success',["order_id"=>$create_order]);
            }
            url::address(url::s("gateway/pay/automaticAlipay","id={$create_order}"));
        }
        functions::str_json($type_content, -1, 'automatic->订单创建失败,请联系客服');
    }
    
    
    //服务版
    private function service($user,$type_content,$data){
        //检测类型是否正确
        $pay_type = [1,2];
        if (!in_array($data['type'], $pay_type)) functions::str_json($type_content, -1, 'service->类型初始化失败!');
        if ($data['type'] == 1){
            if ($data['amount'] > 15000) functions::str_json($type_content, -1, '支付金额不能大于15000元');
        }
        if ($data['type'] == 2){
            if ($data['amount'] > 50000) functions::str_json($type_content, -1, '支付金额不能大于50000元');
        }
        //系统where
        $where_system = '';
        //检测是否系统订单
        if (is_array($_SESSION['SYSTEM_PAY_ID'])){
            $user['id'] = 0;
            $where_gateway = '';
            $where_system = 'and lord=1';
        }else{
            //不是系统订单，user信息生效
            //查询用户组
            $group = json_decode($this->mysql->query('client_group',"id={$user['group_id']}")[0]['authority'],true)['service_auto'];
            //判断用户组是否存在
            if ($group['open'] != 1) functions::str_json($type_content, -1, 'service->您没有权限使用该通道!');
            //分割 or gateway
            $gateway_count = count($group['gateway']);
            for ($i=0;$i<$gateway_count;$i++){
                $where_gateway .=  'id=' . $group['gateway'][$i] . ' or ';
            }
            $where_gateway =  '('. trim(trim(trim($where_gateway),'or')) . ') and ';
        }
        //现在的时间
        $now_time = time()-20;
        //轮训
        if ($data['robin'] == 2) {
            //读取轮训算法ID
            $robin = userCog::read('serviceConfig', 0)['robin'];
            //随机算法 v1.2
            if ($robin == 1){
                //随机算法
                $find_service = $this->mysql->query("service_account","{$where_gateway}status=4 and training=1 and receiving=1 and android_heartbeat>{$now_time} and types={$data['type']} {$where_system}");
                $count_alipay = count($find_service);
                if ($count_alipay == 0) functions::str_json($type_content, -1, 'service->请稍后,支付通道抢修中..');
                $find_service = $find_service[mt_rand(0,$count_alipay-1)];
            }
            //实时收款算法，按少到多排序 v1.0
            if ($robin == 2){
                $find_service = $this->mysql->query("service_account","{$where_gateway}status=4 and training=1 and receiving=1 and android_heartbeat>{$now_time} and types={$data['type']} {$where_system}",null,"today_money","asc");
                $find_service = $find_service[0];
            }
            //顺序模式算法 v1.0
            if ($robin == 3){
                $find_service = $this->mysql->query("service_account","{$where_gateway}status=4 and training=1 and receiving=1 and android_heartbeat>{$now_time} and types={$data['type']} {$where_system}",null,"id","asc");
                $find_service = $find_service[0];
            }
        }else{
            //指定微信号 单条进入
            $key_id = request::filter('post.keyId','','htmlspecialchars');
            $find_service = $this->mysql->query("service_account","key_id='{$key_id}'")[0];
            if (!is_array($find_service)) functions::str_json($type_content, -1, 'service->请稍后,支付通道抢修中..');
            if ($find_service['status'] != 4 || $find_service['receiving'] != 1 || $find_service['android_heartbeat'] < $now_time) functions::str_json($type_content, -1, 'service->请稍后,支付通道抢修中..');
        }
        unset($_SESSION['SYSTEM_PAY_ID']);
        //已经得到参数
        //生成订单
        $create_order  = $this->mysql->insert('service_order', [
            'service_id'=>$find_service['id'],
            'creation_time'=>time(),
            'pay_time'=>0,
            'status'=>1,
            'amount'=>$data['amount'],
            'callback_url'=>$data['callback_url'],
            'success_url'=>$data['success_url'],
            'error_url'=>$data['error_url'],
            'user_id'=>$user['id'],
            'callback_time'=>0,
            'out_trade_no'=>$data['out_trade_no'],
            'ip'=>ip::get(),
            'trade_no'=>$data['trade_no'],
            'types'=>$find_service['types']
        ]);
        if ($create_order > 0){
            if ($type_content == 'json'){
                functions::str_json($type_content, 200, 'success',["order_id"=>$create_order]);
            }
            url::address(url::s("gateway/pay/service","id={$create_order}"));
        }
        functions::str_json($type_content, -1, 'service->订单创建失败,请联系客服');
    }

}
