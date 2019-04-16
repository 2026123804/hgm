<?php
namespace xh\run\server\controller;


use xh\unity\encrypt;
use xh\library\request;
use xh\library\functions;
use xh\library\mysql;
use xh\unity\cog;

class index{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }
    
	//检测服务端掉线
    public function checkOnline(){
        $time = time()-180;
        //支付宝公开版
        $this->mysql->update("client_wechat_automatic_account", ['status'=>5,'receiving'=>2],"status=4 and active_time<{$time}");
        $time = time()-180;
        //微信公开版
        $this->mysql->update("client_alipay_automatic_account", ['status'=>5,'receiving'=>2],"status=4 and active_time<{$time}");
        $time = time()-180;
        //服务版
        $this->mysql->update("service_account", ['status'=>5,'receiving'=>2],"status=4 and active_time<{$time}");
        functions::json(200, '异常处理完毕');
    }
    
    
    //安卓登录
    public function login(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data','','htmlspecialchars'), cog::read("server")['key']),true);
        $username = $data['member_id'];
        $pwd = $data['pwd'];
        $find = $this->mysql->query("client_user","username='{$username}'")[0];
        if (is_array($find)){
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode(-2, '密码错误!');
            functions::json_encode(200, '登录成功');
        }
        //如果没找到用户名，检测是否为手机号
        if (!functions::isMobile($username)) functions::json_encode('-3', '会员名输入有误');
        //检测手机号码
        $find = $this->mysql->query("client_user","phone={$username}")[0];
        if (is_array($find)) {
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode(-2, '密码错误!');
            functions::json_encode(200, '登录成功');
        }
        functions::json_encode(-3, '手机号码输入有误');
    }
    
    //获取二维码生成任务
    public function taskGet(){
        //调用登录
        $user = $this->loginAndroid();
        //任务队列
        $Task = [];
        //检测微信是否有值
        if (is_array($user['wechat'])){
            //查询准备生成二维码的订单
            $wechat_order = $this->mysql->query('client_wechat_automatic_orders',"wechat_id={$user['wechat']['id']} and status=1 and user_id={$user['id']}","amount,trade_no")[0];
            //更新心跳
            //$this->mysql->update("client_wechat_automatic_account", ['android_heartbeat'=>time()],"id={$user['wechat']['id']}");
            if (is_array($wechat_order)){
                //将该任务添加到队列
                $Task[] = array_merge($wechat_order,['type'=>'wechat']);
            }
            //更新心跳
            $this->mysql->update("client_wechat_automatic_account", ['android_heartbeat'=>time()],"id={$user['wechat']['id']}");
        }
        //检测支付宝是否有值
        if (is_array($user['alipay'])){
            //查询准备生成二维码的订单
            $alipay_order = $this->mysql->query('client_alipay_automatic_orders',"alipay_id={$user['alipay']['id']} and status=1 and user_id={$user['id']}","amount,trade_no")[0];
            //更新心跳
            //$this->mysql->update("client_alipay_automatic_account", ['android_heartbeat'=>time()],"id={$user['alipay']['id']}");
            if (is_array($alipay_order)){
                //将该任务添加到队列
                $Task[] = array_merge($alipay_order,['type'=>'alipay']);
            }
            //更新心跳
            $this->mysql->update("client_alipay_automatic_account", ['android_heartbeat'=>time()],"id={$user['alipay']['id']}");
        }
        //下发任务
        functions::json_encode(200, 'success',$Task);
    }
    
    //安卓验证账号密码
    protected function loginAndroid(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data'), cog::read("server")['key']),true);
        //会员名/手机号
        $username = $data['member_id'];
        //密码
        $pwd = $data['pwd'];
        //微信_key
        $WECHAT_Key = $data['wechat_key'];
        //支付宝_key
        $ALIPAY_Key = $data['alipay_key'];
        //QQ_key -> 未开发
        $QQ_key = $data['tenpay_key'];
        
        $find = $this->mysql->query("client_user","username='{$username}'")[0];
        
        //验证用户名模式
        if (is_array($find)){
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode(-2, '密码错误!');
            //验证微信key
            $find['wechat'] = $this->mysql->query("client_wechat_automatic_account","user_id={$find['id']} and key_id='{$WECHAT_Key}'")[0];
            //验证支付宝key
            $find['alipay'] = $this->mysql->query("client_alipay_automatic_account","user_id={$find['id']} and key_id='{$ALIPAY_Key}'")[0];
            //附加post参数
            $find['data'] = $data;
            //返回
            return $data;
        }
        
        //如果没找到用户名，检测是否为手机号
        if (!functions::isMobile($username)) functions::json_encode('-6', request::filter('post.data','','htmlspecialchars'));
        //检测手机号码
        $find = $this->mysql->query("client_user","phone={$username}")[0];
        if (is_array($find)) {
            //开始验证密码
            if (functions::pwd($pwd, $find['token']) != $find['pwd']) functions::json_encode(-2, '密码错误!');
            //验证微信key
            $find['wechat'] = $this->mysql->query("client_wechat_automatic_account","user_id={$find['id']} and key_id='{$WECHAT_Key}'")[0];
            //验证支付宝key
            $find['alipay'] = $this->mysql->query("client_alipay_automatic_account","user_id={$find['id']} and key_id='{$ALIPAY_Key}'")[0];
            //附加post参数
            $find['data'] = $data;
            //返回
            return $find;
        }
        functions::json_encode(-3, '手机号码输入有误');
    }

    
}
