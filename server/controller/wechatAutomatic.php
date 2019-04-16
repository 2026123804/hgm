<?php
namespace xh\run\server\controller;
use xh\library\request;
use xh\library\mysql;
use xh\unity\cog;
use xh\library\functions;
use xh\unity\sms;
use xh\unity\encrypt;

//微信-全自动版-服务端
class wechatAutomatic{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }
    
    //验证服务端KEY
    protected function keyVerification(){
        $key = (request::filter('get.key') or request::filter('post.key'));
        //验证key是否正确
        if (cog::read("server")['key'] != $key) functions::json(-1, '通讯失败');
    }
    
    //安卓验证账号密码
    protected function loginAndroid(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data'), cog::read("server")['key']),true);
        $username = $data['member_id'];
        $pwd = $data['pwd'];
        $DEVICE_Key = $data['device_Key'];
        $find = $this->mysql->query("client_user","username='{$username}'")[0];
        if (is_array($find)){
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode(-2, '密码错误!');
            $find['device'] = $this->mysql->query("client_wechat_automatic_account","user_id={$find['id']} and key_id='{$DEVICE_Key}'")[0];
            if (!is_array($find['device'])) functions::json_encode(-4, 'DEVICE Key识别失败!');
            $find['data'] = $data;
            return $find;
        }
        //如果没找到用户名，检测是否为手机号
        if (!functions::isMobile($username)) functions::json_encode('-6', request::filter('post.data','','htmlspecialchars'));
        //检测手机号码
        $find = $this->mysql->query("client_user","phone={$username}")[0];
        if (is_array($find)) {
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode(-2, '密码错误!');
            $find['device'] = $this->mysql->query("client_wechat_automatic_account","user_id={$find['id']} and key_id='{$DEVICE_Key}'")[0];
            if (!is_array($find['device'])) functions::json_encode(-3, 'DEVICE Key识别失败!');
            $find['data'] = $data;
            return $find;
        }
        functions::json_encode(-3, '手机号码输入有误');
    }
    
    
    //安卓获取二维码生成任务 -> 废除
    public function orderGet(){
        $user = $this->loginAndroid();
        //查询准备生成二维码的订单
        $order = $this->mysql->query('client_wechat_automatic_orders',"wechat_id={$user['device']['id']} and status=1 and user_id={$user['id']}","id,amount,trade_no");
        //更新心跳
        $this->mysql->update("client_wechat_automatic_account", ['android_heartbeat'=>time()],"id={$user['device']['id']}");
        functions::json_encode(200, 'success',$order[0]);
    }
    
    
    //安卓上载二维码到服务器
    public function uploadCode(){
        $user = $this->loginAndroid();
        //上载二维码
        $order_id = $user['data']['order_id'];//订单ID
        $qrcode = $user['data']['qrcode'];//支付二维码
        $order_eck = $this->mysql->query("client_wechat_automatic_orders","status=4 and trade_no={$order_id}")[0];
        if (!is_array($order_eck)){
            $this->mysql->update("client_wechat_automatic_orders", [
                'status'=>2,
                'qrcode'=>$qrcode
            ],"wechat_id={$user['device']['id']} and trade_no={$order_id}");
        }
        functions::json_encode(200, 'success');
    }
    

    //获取所有需要登录的微信账户，并处理一些事物
    public function loginGet(){
        $this->keyVerification();
        $NowTime = time() - 120;
        $find = $this->mysql->query("client_wechat_automatic_account","status=2 and login_time>{$NowTime}","id,user_id,key_id");
        $this->mysql->update("client_wechat_automatic_account", ['status'=>3],"status=2");
        //判定微信掉线
        $droppedResult = $this->mysql->query('client_wechat_automatic_account',"status!=4 and status!=1 and login_time<{$NowTime} or status=6");
        //判断异常的微信账户，并发送短信
        $errorResult = $this->mysql->query("client_wechat_automatic_account","status=5");
        //更改状态
        $this->mysql->update("client_wechat_automatic_account", ['status'=>1,'training'=>2,'receiving'=>2],"status!=4 and status!=1 and login_time<{$NowTime} or status=6");
        if (count($errorResult) > 0){
            foreach ($errorResult as $error){
                $find_user = $this->mysql->query("client_user","id={$error['user_id']}")[0];
                if (is_array($find_user)){
                    //发送短信
                    (new sms())->sendError($find_user['phone'], $error['name']);
                    $this->mysql->update("client_wechat_automatic_account", ['status'=>1],"id={$error['id']}");
                }
            }
        }
        
        $errorWechat = array_merge($droppedResult,$errorResult);
       
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: wechatAutomatic Success',[
            'login'=>['list'=>$find,'num'=>count($find)],
            'dropped'=>['list'=>$errorWechat,'num'=>count($errorWechat)]
        ]);
    }
    
    //上载微信二维码
    public function uploadLoginImg(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $login_img = request::filter('post.img');
        if (empty($login_img)) functions::json(-2, ' ['.date("Y/m/d H:i:s",time()).']: 微信ID->' . $id . ' 没有截取到登录二维码');
        $this->mysql->update("client_wechat_automatic_account", [
            'status'=>7,
            'login_img'=>str_replace("@", "+", $login_img)
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 微信ID->' . $id . ' 登录二维码上载完毕');
    }
    
    //上载登录成功，以及更新微信信息
    public function uploadLoginData(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $name = request::filter('post.name');
        $this->mysql->update("client_wechat_automatic_account", [
            'name' => $name,
            'status'=>4,
            'login_time'=>time(),
            'active_time'=>time()
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 微信ID->' . $id . ' 登录成功');
    }
    
    //上载异常通知
    public function uploadLoginError(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $this->mysql->update("client_wechat_automatic_account", [
            'status'=>5,
            'training'=>2,
            'receiving'=>2
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 微信ID->' . $id . ' 异常通知成功');
    }
    
    
    //上载订单通知
    public function uploadOrder(){
        $this->keyVerification();
        $id = intval(request::filter('post.id'));
        $money = floatval(request::filter('post.money'));
        $order = trim(request::filter('post.order'));
        $today_money = floatval(request::filter('post.today_money'));
        $today_pens = intval(request::filter('post.today_pens'));
        $find_order = $this->mysql->query('client_wechat_automatic_orders',"wechat_id={$id} and status=2 and amount={$money} and trade_no={$order}")[0];
        if (is_array($find_order)) {
            $this->mysql->update("client_wechat_automatic_orders", [
                'status'=>4,
                'pay_time'=>time()
            ],"id={$find_order['id']}");
            $remark = ' - 订单信息：'.$order;
            $average = 1;
        }else {
            $remark = ' - 该订单不是第三方交易订单';
            $average = 0;
        }
        //查询用户信息
        $find_uid = $this->mysql->query("client_wechat_automatic_account","id={$id}")[0]['user_id'];
        //写到交易记录
        $this->mysql->insert("client_pay_record", [
            'pay_time'=>time(),
            'amount'=>$money,
            'user_id'=>$find_uid,
            'pay_note'=>'[公开版]微信ID：'.$id . $remark,
            'types'=>1,
            'version_code'=>'wechat_auto',
            'average'=>$average
        ]);
        //更新当前微信账号的实时统计
        $this->mysql->update("client_wechat_automatic_account", ['today_money'=>$today_money,'today_pens'=>$today_pens],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 微信ID->' . $id . ' 订单处理完成');
    }
    
    //程序自杀通知
    public function cillself(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $this->mysql->update("client_wechat_automatic_account", [
            'status'=>1,
            'training'=>2,
            'receiving'=>2
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 微信ID->' . $id . ' 自杀成功');
    }


}
