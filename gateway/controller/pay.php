<?php
namespace xh\run\gateway\controller;
use xh\library\mysql;
use xh\library\request;
use xh\library\functions;
use xh\library\view;
use xh\library\ip;

class pay{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }

    //全自动版微信 v1.0
    public function automaticWechat(){
        $type = request::filter('get.content_type','','htmlspecialchars');
        $id = intval(request::filter('get.id'));
        $order = $this->mysql->query('client_wechat_automatic_orders',"id={$id}")[0];
        if (!is_array($order)) functions::json(-1, '当前交易号不存在');
        if ($order['status'] == 3) functions::json(-1, '当前订单已经过期,请重新发起支付');
        if ($order['status'] == 4) functions::json(200, '当前订单已经支付成功!');
        //查询微信信息
        $order['wechat_name'] = $this->mysql->query("client_wechat_automatic_account","id={$order['wechat_id']}")[0]['name'];
        //检测是否手机访问
        if (ip::isMobile()){
            $path = 'automatic/wechatMobile';
        }else{
            $path = 'automatic/wechat';
        }
        $pay_data = [
            'id' => $order['id'], 
            'wechat_name' => $order['wechat_name'],
            'creation_time' => $order['creation_time'],
            'status' => $order['status'],
            'amount' => $order['amount'],
            'success_url' => $order['success_url'],
            'error_url' => $order['error_url'],
            'out_trade_no' => $order['out_trade_no'],
            'trade_no' => $order['trade_no'],
            'qrcode' => $order['qrcode']
            
        ];
        //检测网页类型是否为json
        if ($type == 'json'){
            functions::json(200, 'success', $pay_data);
        }else{
            new view($path,$pay_data);
        }
    }
    
    //订单查询
    public function automaticWechatQuery(){
        $id = intval(request::filter('get.id'));
        $order = $this->mysql->query('client_wechat_automatic_orders',"id={$id}")[0];
        if (!is_array($order)) functions::json(-1, '当前交易号不存在');
        if ($order['status'] == 1) functions::json(1, '正在与网关连接中..');
        if ($order['status'] == 2) functions::json(100, '请扫码支付',['qrcode'=>$order['qrcode']]);
        if ($order['status'] == 3) functions::json(-2, '当前订单已经过期,请重新发起支付');
        if ($order['status'] == 4) functions::json(200, '当前订单已经支付成功!');
    }
    
    
    //全自动版支付宝 v1.0
    public function automaticAlipay(){
        $type = request::filter('get.content_type','','htmlspecialchars');
        $id = intval(request::filter('get.id'));
        $order = $this->mysql->query('client_alipay_automatic_orders',"id={$id}")[0];
        if (!is_array($order)) functions::json(-1, '当前交易号不存在');
        if ($order['status'] == 3) functions::json(-1, '当前订单已经过期,请重新发起支付');
        if ($order['status'] == 4) functions::json(200, '当前订单已经支付成功!');
        //查询微信信息
        $order['alipay_name'] = $this->mysql->query("client_alipay_automatic_account","id={$order['alipay_id']}")[0]['name'];
        //检测是否手机访问
        if (ip::isMobile()){
            $path = 'alipay/alipayMobile';
        }else{
            $path = 'alipay/alipay';
        }
        $pay_data = [
            'id' => $order['id'],
            'alipay_name' => $order['alipay_name'],
            'creation_time' => $order['creation_time'],
            'status' => $order['status'],
            'amount' => $order['amount'],
            'success_url' => $order['success_url'],
            'error_url' => $order['error_url'],
            'out_trade_no' => $order['out_trade_no'],
            'trade_no' => $order['trade_no'],
            'qrcode' => $order['qrcode']
            
        ];
        //检测网页类型是否为json
        if ($type == 'json'){
            functions::json(200, 'success', $pay_data);
        }else{
            new view($path,$pay_data);
        }
    }
    
    //订单查询
    public function automaticAlipayQuery(){
        $id = intval(request::filter('get.id'));
        $order = $this->mysql->query('client_alipay_automatic_orders',"id={$id}")[0];
        if (!is_array($order)) functions::json(-1, '当前交易号不存在');
        if ($order['status'] == 1) functions::json(1, '正在与网关连接中..');
        if ($order['status'] == 2) functions::json(100, '请扫码支付',['qrcode'=>$order['qrcode']]);
        if ($order['status'] == 3) functions::json(-2, '当前订单已经过期,请重新发起支付');
        if ($order['status'] == 4) functions::json(200, '当前订单已经支付成功!');
    }

    //服务版
    public function service(){
        $type = request::filter('get.content_type','','htmlspecialchars');
        $id = intval(request::filter('get.id'));
        $order = $this->mysql->query('service_order',"id={$id}")[0];
        if (!is_array($order)) functions::json(-1, '当前交易号不存在');
        if ($order['status'] == 3) functions::json(-1, '当前订单已经过期,请重新发起支付');
        if ($order['status'] == 4) functions::json(200, '当前订单已经支付成功!');
        //查询服务信息
        $service = $this->mysql->query("service_account","id={$order['service_id']}")[0];
        //检测是否手机访问
        if (ip::isMobile()){
            if ($service['types'] == 1) $path = 'service/wechatMobile';
            if ($service['types'] == 2) $path = 'service/alipayMobile';
        }else{
            if ($service['types'] == 1) $path = 'service/wechat';
            if ($service['types'] == 2) $path = 'service/alipay';
        }
        $pay_data = [
            'id' => $order['id'],
            'service_name' => $service['alipay_name'],
            'creation_time' => $order['creation_time'],
            'status' => $order['status'],
            'amount' => $order['amount'],
            'success_url' => $order['success_url'],
            'error_url' => $order['error_url'],
            'out_trade_no' => $order['out_trade_no'],
            'trade_no' => $order['trade_no'],
            'qrcode' => $order['qrcode']
            
        ];
        //检测网页类型是否为json
        if ($type == 'json'){
            functions::json(200, 'success', $pay_data);
        }else{
            new view($path,$pay_data);
        }
        
    }
    
    //订单查询
    public function serviceQuery(){
        $id = intval(request::filter('get.id'));
        $order = $this->mysql->query('service_order',"id={$id}")[0];
        if (!is_array($order)) functions::json(-1, '当前交易号不存在');
        if ($order['status'] == 1) functions::json(1, '正在与网关连接中..');
        if ($order['status'] == 2) functions::json(100, '请扫码支付',['qrcode'=>$order['qrcode']]);
        if ($order['status'] == 3) functions::json(-2, '当前订单已经过期,请重新发起支付');
        if ($order['status'] == 4) functions::json(200, '当前订单已经支付成功!');
    }
    
    

    
}