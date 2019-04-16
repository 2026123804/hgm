<?php
namespace xh\run\server\controller;

use xh\library\mysql;
use xh\unity\callbacks;
use xh\library\functions;
use xh\library\request;

class callback{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }
    
    //Automatic v1.0 接口
    public function automatic(){
        $module_name = 'wechat_auto';
        $wechat_id = request::filter('post.id');
        if (empty($wechat_id)) functions::json(-1, '微信ID错误');
        //通过微信id得到用户信息
        $wechat = $this->mysql->query('client_wechat_automatic_account',"id={$wechat_id}")[0];
        if (!is_array($wechat)) functions::json(-1, '微信错误');
        //得到用户信息
        $user = $this->mysql->query("client_user","id={$wechat['user_id']}")[0];
        if (!is_array($user)) functions::json(-1, '商户错误');
        //得到用户组
        $group = $this->mysql->query('client_group',"id={$user['group_id']}")[0];
        //解析数据
        $authority = json_decode($group['authority'],true)[$module_name];
        if (!is_array($group) || $group['authority'] == -1 || $authority['open'] != 1) functions::json(-1, '用户组错误');
        // -------------------------
        // 获取需要回调的列表
        $order = $this->mysql->query('client_wechat_automatic_orders', "wechat_id={$wechat_id} and status=4 and callback_status=0");
        foreach ($order as $obj) {
            // 开始扣手续费
            $fees = $obj['amount'] * $authority['cost'];
            $user_balance = $user['balance'] - $fees; // 用户最终余额
            if ($user_balance >= 0) {
                // 扣除费用
                $deductionStatus = $this->mysql->update("client_user", [
                    'balance' => $user_balance
                ], "id={$user['id']}");
                if ($deductionStatus > 0 || $obj['amount'] < 1) {
                    $user['balance'] = $user_balance;
                    $callback_time = time();
                    // 手续费扣除成功，开始回调
                    $result = callbacks::curl($obj['callback_url'], http_build_query([
                        'account_name' => $user['username'],
                        'pay_time' => $obj['pay_time'],
                        'status' => 'success',
                        'amount' => $obj['amount'],
                        'out_trade_no' => $obj['out_trade_no'],
                        'trade_no' => $obj['trade_no'],
                        'fees' => $fees,
                        'sign' => functions::sign($user['key_id'], [
                            'amount' => $obj['amount'],
                            'out_trade_no' => $obj['out_trade_no']
                        ]),
                        'callback_time' => $callback_time,
                        'type'=>1,
                        'account_key'=>$user['key_id']
                    ]));
                    $this->mysql->update("client_wechat_automatic_orders", [
                        'callback_time' => $callback_time,
                        'callback_status' => 1,
                        'callback_content' => $result,
                        'fees' => $fees
                    ], "id={$obj['id']}");
                }
            }
        }
        $this->mysql->update("client_wechat_automatic_account", ['active_time'=>time()],"id={$wechat_id}");
        functions::json(200, ' [' . date("Y/m/d H:i:s", time()) . ']: 微信ID->' . $wechat_id . ' 异步通知成功');
       //-----------------------------
    }
    
    
    //alipay v1.0 接口
    public function alipay(){
        $module_name = 'alipay_auto';
        $alipay_id = request::filter('post.id');
        if (empty($alipay_id)) functions::json(-1, '支付宝ID错误');
        //通过微信id得到用户信息
        $wechat = $this->mysql->query('client_alipay_automatic_account',"id={$alipay_id}")[0];
        if (!is_array($wechat)) functions::json(-1, '支付宝错误');
        //得到用户信息
        $user = $this->mysql->query("client_user","id={$wechat['user_id']}")[0];
        if (!is_array($user)) functions::json(-1, '商户错误');
        //得到用户组
        $group = $this->mysql->query('client_group',"id={$user['group_id']}")[0];
        //解析数据
        $authority = json_decode($group['authority'],true)[$module_name];
        if (!is_array($group) || $group['authority'] == -1 || $authority['open'] != 1) functions::json(-1, '用户组错误');
        // -------------------------
        // 获取需要回调的列表
        $order = $this->mysql->query('client_alipay_automatic_orders', "alipay_id={$alipay_id} and status=4 and callback_status=3");
        foreach ($order as $obj) {
            // 开始扣手续费
            $fees = $obj['amount'] * $authority['cost'];
            $user_balance = $user['balance'] - $fees; // 用户最终余额
            if ($user_balance >= 0) {
                // 扣除费用
                $deductionStatus = $this->mysql->update("client_user", [
                    'balance' => $user_balance
                ], "id={$user['id']}");
                if ($deductionStatus > 0 || $obj['amount'] < 1) {
                    $user['balance'] = $user_balance;
                    $callback_time = time();
                    // 手续费扣除成功，开始回调
                    $result = callbacks::curl($obj['callback_url'], http_build_query([
                        'account_name' => $user['username'],
                        'pay_time' => $obj['pay_time'],
                        'status' => 'success',
                        'amount' => $obj['amount'],
                        'out_trade_no' => $obj['out_trade_no'],
                        'trade_no' => $obj['trade_no'],
                        'fees' => $fees,
                        'sign' => functions::sign($user['key_id'], [
                            'amount' => $obj['amount'],
                            'out_trade_no' => $obj['out_trade_no']
                        ]),
                        'callback_time' => $callback_time,
                        'type'=>2,
                        'account_key'=>$user['key_id']
                    ]));
                    $this->mysql->update("client_alipay_automatic_orders", [
                        'callback_time' => $callback_time,
                        'callback_status' => 1,
                        'callback_content' => $result,
                        'fees' => $fees
                    ], "id={$obj['id']}");
                }
            }
        }
        $this->mysql->update("client_alipay_automatic_account", ['active_time'=>time()],"id={$alipay_id}");
        functions::json(200, ' [' . date("Y/m/d H:i:s", time()) . ']: 微信ID->' . $alipay_id . ' 异步通知成功');
        //-----------------------------
    }
    
}
