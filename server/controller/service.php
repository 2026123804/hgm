<?php
namespace xh\run\server\controller;
use xh\library\request;
use xh\library\mysql;
use xh\unity\cog;
use xh\library\functions;
use xh\unity\sms;
use xh\unity\encrypt;
use xh\unity\callbacks;

//服务版
class service{
    
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
    
    //----------------------------------
    //安卓验证区域
    public function login(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data'), cog::read("server")['key']),true);
        //验证key是否正确
        if (cog::read("server")['key'] != $data['key']) functions::json_encode(-1, '通讯失败,KEY验证失败');
        //微信_key
        $WECHAT_Key = $data['wechat_key'];
        //支付宝_key
        $ALIPAY_Key = $data['alipay_key'];
        //QQ_key -> 未开发
        $QQ_key = $data['tenpay_key'];
        //验证微信key
        $find['wechat'] = $this->mysql->query("service_account","key_id='{$WECHAT_Key}'")[0];
        //验证支付宝key
        $find['alipay'] = $this->mysql->query("service_account","key_id='{$ALIPAY_Key}'")[0];
        //附加post参数
        $find['data'] = $data;
        //返回
        return $find;
    }
    
    //获取二维码生成任务
    public function taskGet(){
        //调用登录
        $service = $this->login();
        //任务队列
        $Task = [];
        //检测微信是否有值
        if (is_array($service['wechat'])){
            //查询准备生成二维码的订单
            $wechat_order = $this->mysql->query('service_order',"service_id={$service['wechat']['id']} and status=1","amount,trade_no")[0];
            //更新心跳
            //$this->mysql->update("service_account", ['android_heartbeat'=>time()],"id={$service['wechat']['id']}");
            if (is_array($wechat_order)){
                //将该任务添加到队列
                $Task[] = array_merge($wechat_order,['type'=>'wechat']);
            }
            //更新心跳
            $this->mysql->update("service_account", ['android_heartbeat'=>time()],"id={$service['wechat']['id']}");
        }
        //检测支付宝是否有值
        if (is_array($service['alipay'])){
            //查询准备生成二维码的订单
            $alipay_order = $this->mysql->query('service_order',"service_id={$service['alipay']['id']} and status=1","amount,trade_no")[0];
            //更新心跳
            //$this->mysql->update("service_account", ['android_heartbeat'=>time()],"id={$service['alipay']['id']}");
            if (is_array($alipay_order)){
                //将该任务添加到队列
                $Task[] = array_merge($alipay_order,['type'=>'alipay']);
            }
            //更新心跳
            $this->mysql->update("service_account", ['android_heartbeat'=>time()],"id={$service['alipay']['id']}");
        }
        //下发任务
        functions::json_encode(200, 'success',$Task[0]);
    }
    
    
    //安卓上载二维码到服务器
    public function uploadCode(){
        $encrpty = new encrypt();
        $data = json_decode($encrpty->Decode(request::filter('post.data'), cog::read("server")['key']),true);
        file_put_contents(ROOT_PATH . '/code.txt', json_encode($data));
        //验证key是否正确
        if (cog::read("server")['key'] != $data['key']) functions::json_encode(-1, '通讯失败,KEY验证失败');
        //上载二维码
        $order_id = $data['order_id'];//订单ID
        $qrcode = $data['qrcode'];//支付二维码
        
        $order_eck = $this->mysql->query("service_order","status=4 and trade_no={$order_id}")[0];
        
        if (!is_array($order_eck)){
            $this->mysql->update("service_order", [
                'status'=>2,
                'qrcode'=>$qrcode
            ],"trade_no={$order_id}");
        }

        functions::json_encode(200, 'success');
    }
    //----------------------------------
    
    
    //获取登录线程
    public function getLogin(){
        $this->keyVerification();
        $NowTime = time() - 120;
        $find = $this->mysql->query("service_account","status=2 and login_time>{$NowTime}","id,key_id,types");
        $this->mysql->update("service_account", ['status'=>3],"status=2");
        //判定服务掉线
        $droppedResult = $this->mysql->query('service_account',"status!=4 and status!=1 and login_time<{$NowTime} or status=6");
        //判断异常的服务账户
        $errorResult = $this->mysql->query("service_account","status=5");
        //更改状态
        $this->mysql->update("service_account", ['status'=>1,'training'=>2,'receiving'=>2],"status!=4 and status!=1 and login_time<{$NowTime} or status=6");
        if (count($errorResult) > 0){
            foreach ($errorResult as $error){
                    (new sms())->sendError(cog::read('server')['service_phone'], $error['name']);
                    $this->mysql->update("service_account", ['status'=>1],"id={$error['id']}");
            }
        }
        $errorWechat = array_merge($droppedResult,$errorResult);
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: wechatAutomatic Success',[
            'login'=>['list'=>$find,'num'=>count($find)],
            'dropped'=>['list'=>$errorWechat,'num'=>count($errorWechat)]
        ]);
    }
    
    //上载服务二维码
    public function uploadLoginImg(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $login_img = request::filter('post.img');
        if (empty($login_img)) functions::json(-2, ' ['.date("Y/m/d H:i:s",time()).']: 服务ID->' . $id . ' 没有截取到登录二维码');
        $this->mysql->update("service_account", [
            'status'=>7,
            'login_img'=>str_replace("@", "+", $login_img)
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 服务ID->' . $id . ' 登录二维码上载完毕');
    }
    
    //上载登录成功，以及更新服务信息
    public function uploadLoginData(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $name = request::filter('post.name');
        if (trim($name) == "") $name = '商户' . $id;
        $this->mysql->update("service_account", [
            'name' => $name,
            'status'=>4,
            'login_time'=>time(),
            'active_time'=>time()
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 服务ID->' . $id . ' 登录成功');
    }
    
    //上载异常通知
    public function uploadLoginError(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $this->mysql->update("service_account", [
            'status'=>5,
            'training'=>2,
            'receiving'=>2
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 服务ID->' . $id . ' 异常通知成功');
    }
    
    //上载订单通知
    public function uploadOrder(){
        $this->keyVerification();
        $id = intval(request::filter('post.id'));
        $money = floatval(request::filter('post.money'));
        $order = trim(request::filter('post.order'));
        $today_money = floatval(request::filter('post.today_money'));
        $today_pens = intval(request::filter('post.today_pens'));
        $find_order = $this->mysql->query('service_order',"service_id={$id} and status=2 and amount={$money} and trade_no={$order}")[0];
        if (is_array($find_order)) {
            $this->mysql->update("service_order", [
                'status'=>4,
                'pay_time'=>time()
            ],"id={$find_order['id']}");
        }
        //写到交易记录
        $this->mysql->insert("client_pay_record", [
            'pay_time'=>time(),
            'amount'=>$money,
            'user_id'=>$find_order['user_id'],
            'pay_note'=>'服务版'. ' - 订单信息：'.$order,
            'types'=>$find_order['types'],
            'version_code'=>'service_auto',
            'average'=>1
        ]);
        //更新当前服务账号的实时统计
        $this->mysql->update("service_account", ['today_money'=>$today_money,'today_pens'=>$today_pens],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 服务ID->' . $id . ' 订单处理完成');
    }
    
    
    //异步通知
    public function callback(){
        $module_name = 'service_auto';
        $service_id = request::filter('post.id');
        if (empty($service_id)) functions::json(-1, '服务ID错误');
        //服务信息
        $service = $this->mysql->query('service_account',"id={$service_id}")[0];
        if (!is_array($service)) functions::json(-1, '服务错误');
        // -------------------------
        // 获取需要回调的列表
        $order = $this->mysql->query('service_order', "service_id={$service_id} and status=4 and callback_status=0");

        foreach ($order as $obj) {
            //检测是否为用户订单
            if ($obj['user_id'] != 0){
                //是用户
                $user = $this->mysql->query("client_user","id={$obj['user_id']}")[0];
                //得到该用户组
                $group = $this->mysql->query('client_group',"id={$user['group_id']}")[0];
                //解析数据
                $authority = json_decode($group['authority'],true)[$module_name];
                //判断用户组是否存在
                if (is_array($group) || $group['authority'] != -1 || $authority['open'] == 1) {
                    //手续费扣掉后的金额
                    $fees = $obj['amount'] * $authority['cost'];
                    $user_money =  $obj['amount'] - $fees;
                        
                        if (intval($obj['reached']) === 0){
                            //给用户加钱
                            $deductionStatus = $this->mysql->update("client_user", [
                                'money' => $user['money']+$user_money
                            ], "id={$user['id']}");
                            //直接强制修改reached
                            $this->mysql->update("service_order", ['reached'=>1],"id={$obj['id']}");
                        }
                        
                        $user['money'] = $user['money']+$user_money;
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
                                'type'=>$obj['types'],
                                'account_key'=>$user['key_id']
                            ]));
                            //更新订单
                            $this->mysql->update("service_order", [
                                'callback_time' => $callback_time,
                                'callback_status' => 1,
                                'callback_content' => $result,
                                'fees' => $fees,
                                'reached'=>1
                            ], "id={$obj['id']}");

                }
            }else{
                    //进行系统回调
                    $callback_time = time();
                    // 手续费扣除成功，开始回调
                    $result = callbacks::curl($obj['callback_url'], http_build_query([
                        'account_name' => 'SYSTEM_CALLBACK',
                        'pay_time' => $obj['pay_time'],
                        'status' => 'success',
                        'amount' => $obj['amount'],
                        'out_trade_no' => $obj['out_trade_no'],
                        'trade_no' => $obj['trade_no'],
                        'fees' => 0,
                        'sign' => functions::sign(cog::read('server')['key'], [
                            'amount' => $obj['amount'],
                            'out_trade_no' => $obj['out_trade_no']
                        ]),
                        'callback_time' => $callback_time,
                        'type'=>$obj['types'],
                        'account_key'=>cog::read('server')['key']
                    ]));
                    //更新订单
                    $this->mysql->update("service_order", [
                        'callback_time' => $callback_time,
                        'callback_status' => 1,
                        'callback_content' => $result,
                        'fees' => $fees,
                        'reached' => 1
                    ], "id={$obj['id']}");
            }  
        }
        
        $this->mysql->update("service_account", ['active_time'=>time()],"id={$service_id}");
        functions::json(200, ' [' . date("Y/m/d H:i:s", time()) . ']: 服务ID->' . $service_id . ' 异步通知成功');
        //-----------------------------
    }
    
    
    //程序自杀通知
    public function cillself(){
        $this->keyVerification();
        $id = request::filter('post.id');
        $this->mysql->update("service_account", [
            'status'=>1,
            'training'=>2,
            'receiving'=>2
        ],"id={$id}");
        functions::json(200, ' ['.date("Y/m/d H:i:s",time()).']: 服务ID->' . $id . ' 自杀成功');
    }
    
}