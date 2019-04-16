<?php
namespace xh\run\server\controller;


use xh\unity\encrypt;
use xh\library\request;
use xh\library\functions;
use xh\library\mysql;
use xh\unity\cog;
use xh\unity\callbacks;

class pc{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }
    
    
    //PC版登录
    public function login(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data','','htmlspecialchars'), PC_KEY),true);
        $username = $data['member_id'];
        $pwd = $data['pwd'];
        $key = $data['key'];
        $find = $this->mysql->query("client_user","username='{$username}'")[0];
        
        //key识别
        $find_alipay_key = $this->mysql->query("client_alipay_automatic_account","user_id={$find['id']} and key_id='{$key}'")[0];
        $find_wechat_key = $this->mysql->query("client_wechat_automatic_account","user_id={$find['id']} and key_id='{$key}'")[0];
        
        //支付宝
        if (is_array($find_alipay_key)){
            $keyInfo = [
              'type'=>'alipay',
              'id'=>$find_alipay_key['id']
            ];
            
            //检测当前支付宝是否在线
            if ($find_alipay_key['status'] == 4) functions::json_encode_pc(-4, '当前支付宝已再云端或其他设备上登录,无需重复登录');
        }
        
        //微信
        if (is_array($find_wechat_key)){
            $keyInfo = [
                'type'=>'wechat',
                'id'=>$find_wechat_key['id']
            ];
            if ($find_wechat_key['status'] == 4) functions::json_encode_pc(-4, '当前微信已再云端或其他设备上登录,无需重复登录');
        }
        
        if (!is_array($keyInfo)) functions::json_encode_pc(-4, 'DEVICE Key识别失败!');
        
        if (is_array($find)){
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode_pc(-2, '密码错误');
            functions::json_encode_pc(200, '登录成功',$keyInfo);
        }
        
        //如果没找到用户名，检测是否为手机号
        if (!functions::isMobile($username)) functions::json_encode_pc('-3', '会员名输入有误');
        
        //检测手机号码
        $find = $this->mysql->query("client_user","phone={$username}")[0];
        
        if (is_array($find)) {
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode_pc(-2, '密码错误');
            functions::json_encode_pc(200, '登录成功',$keyInfo);
        }
        
        functions::json_encode_pc(-3, '手机号码输入有误');
    }
    
    
    //自动验证PC登录
    private function loginCheck(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data','','htmlspecialchars'), PC_KEY),true);
        $username = $data['member_id'];
        $pwd = $data['pwd'];
        $type = $data['type'];
        $id = $data['id'];
        $find_user = $this->mysql->query("client_user","username='{$username}'")[0];
      
        if (is_array($find_user)) {
            //开始验证密码
            if (functions::pwd($pwd, $find_user['token']) != $find_user['pwd']) functions::json_encode_pc(-2, '密码错误');
            $data['user'] = $find_user;
        }else{
            //检测手机号码
            $find_phone = $this->mysql->query("client_user","phone={$username}")[0];
            if (is_array($find_phone)) {
                //开始验证密码
                if (functions::pwd($pwd, $find_phone['token']) != $find_phone['pwd']) functions::json_encode_pc(-2, '密码错误');
                $data['user'] = $find_phone;
            }
        }

        if (!is_array($data['user'])) functions::json_encode_pc(-2, '账号或会员名有误');
 
        if ($type == 'alipay'){
            $data['acc'] = $this->mysql->query("client_alipay_automatic_account","id={$id} and user_id={$find_user['id']}")[0];
            $data['acc_table_name'] = 'client_alipay_automatic_account';
            $data['order_table_name'] = 'client_alipay_automatic_orders';
            $data['module'] = 'alipay_auto';
            $data['acc_where_ec'] = "alipay_id";
        }
        
        if ($type == 'wechat'){
            $data['acc'] = $this->mysql->query("client_wechat_automatic_account","id={$id} and user_id={$find_user['id']}")[0];
            $data['acc_table_name'] = 'client_wechat_automatic_account';
            $data['order_table_name'] = 'client_wechat_automatic_orders';
            $data['module'] = 'wechat_auto';
            $data['acc_where_ec'] = "wechat_id";
        }
        
        if (!is_array($data['acc'])) functions::json_encode_pc(-4, '设备匹配不成功');
        
        return $data;
    }
    
    
    //上载登录成功
    public function uploadLoginData(){
        $data = $this->loginCheck();
        $this->mysql->update($data['acc_table_name'], [
            'name' => $data['name'],
            'status'=>4,
            'login_time'=>time(),
            'active_time'=>time()
        ],"id={$data['acc']['id']}");

        functions::json_encode_pc(200, '登录成功');
    }
    
    
    //上载异常通知
    public function uploadLoginError(){
        $data = $this->loginCheck();
        $this->mysql->update($data['acc_table_name'], [
            'status'=>5,
            'training'=>2,
            'receiving'=>2
        ],"id={$data['acc']['id']}");
        functions::json_encode_pc(200, '异常通知成功');
    }
    
    //上载订单通知
    public function uploadOrder(){
        $data = $this->loginCheck();
        $id = intval($data['id']);
        $money = floatval($data['money']);
        $order = trim($data['order']);
        $today_money = floatval($data['today_money']);
        $today_pens = intval($data['today_pens']);
        $find_order = $this->mysql->query($data['order_table_name'],"status=2 and amount={$money} and trade_no={$order}")[0];
        if (is_array($find_order)) {
            $this->mysql->update($data['order_table_name'], [
                'status'=>4,
                'pay_time'=>time()
            ],"id={$find_order['id']}");
            $remark = ' - 订单信息：'.$order;
            $average = 1;
        }else{
            $remark = ' - 该订单不是第三方交易订单';
            $average = 0;
        }
        //查询用户信息
        $find_uid = $this->mysql->query($data['acc_table_name'],"id={$id}")[0]['user_id'];
        //写到交易记录
        $this->mysql->insert("client_pay_record", [
            'pay_time'=>time(),
            'amount'=>$money,
            'user_id'=>$find_uid,
            'pay_note'=>'[公开版]支付宝ID：'.$id . $remark,
            'types'=>2,
            'version_code'=>'alipay_auto',
            'average'=>$average
        ]);
        //更新当前账号的实时统计
        $this->mysql->update($data['acc_table_name'], ['today_money'=>$today_money,'today_pens'=>$today_pens],"id={$id}");
        functions::json_encode_pc(200, '订单提交成功');
    }
    
    
    //回调-该方法具有强大的逻辑性
    public function callback(){
        $data = $this->loginCheck();
        //模块名称
        $module_name = $data['module'];
        //收款账户ID
        $acc_id = $data['id'];
        if (empty($acc_id)) functions::json_encode_pc(-1, '设备有误');
        //通过id得到用户信息
        $acc = $this->mysql->query($data['acc_table_name'],"id={$acc_id}")[0];
        if (!is_array($acc)) functions::json_encode_pc(-1, '设备错误');
        //得到用户信息
        $user = $this->mysql->query("client_user","id={$acc['user_id']}")[0];
        if (!is_array($user)) functions::json_encode_pc(-1, '商户错误');
        //得到用户组
        $group = $this->mysql->query('client_group',"id={$user['group_id']}")[0];
        //解析数据
        $authority = json_decode($group['authority'],true)[$module_name];
        if (!is_array($group) || $group['authority'] == -1 || $authority['open'] != 1) functions::json_encode_pc(-1, '用户组错误');
        // -------------------------
        // 获取需要回调的列表
        $order = $this->mysql->query($data['order_table_name'], "{$data['acc_where_ec']}={$acc_id} and status=4 and callback_status=0");
     
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
                    $this->mysql->update($data['order_table_name'], [
                        'callback_time' => $callback_time,
                        'callback_status' => 1,
                        'callback_content' => $result,
                        'fees' => $fees
                    ], "id={$obj['id']}");
                }
            }
        }
        $this->mysql->update($data['acc_table_name'], ['active_time'=>time()],"id={$acc_id}");
        functions::json_encode_pc(200, '异步通知成功');
    }
    

}