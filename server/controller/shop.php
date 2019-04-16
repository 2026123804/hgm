<?php
namespace xh\run\server\controller;
use xh\library\request;
use xh\library\mysql;
use xh\unity\cog;
use xh\library\functions;


//商品客户端接受回调
class shop{
    
    private $mysql;
    
    public function __construct(){
        $this->mysql = new mysql();
    }
    
    //验证服务端KEY
    protected function keyVerification(){
        $key = (request::filter('get.account_key') or request::filter('post.account_key'));
        //验证key是否正确
        if (cog::read("server")['key'] != $key) exit('通讯异常!');
    }
    
   
    //回调给网站用户
    function pay(){
        $this->keyVerification();
        //交易订单号
        $serial_no = request::filter('post.out_trade_no','','htmlspecialchars');
        //支付金额
        $amount = floatval(request::filter('post.amount','','htmlspecialchars'));
        //验证签名
        $sign = request::filter('post.sign','','htmlspecialchars');
        if ($sign != functions::sign(cog::read("server")['key'], ['amount'=>$amount,'out_trade_no'=>$serial_no])) exit('error!');
        //检测订单数据
        $findShopOrderData = $this->mysql->query("shop_order","serial_no={$serial_no}")[0];
        //检测数据
        if (!is_array($findShopOrderData) || $findShopOrderData['status'] != 0) exit('error!');
        //商品数据
        $shopInfo = $this->mysql->query("shop","id={$findShopOrderData['shop_id']}")[0];
        if (!is_array($shopInfo)) exit('error!');
        //计算单价
        $price = $findShopOrderData['amount'] / $findShopOrderData['quantity'];
        
        
        //用户组充值
        if ($shopInfo['category'] == 1){
            $rc = $this->mysql->update("client_user", ['group_id'=>$shopInfo['bind_special']],"id={$findShopOrderData['user_id']}");
            $order_rc = $this->mysql->update("shop_order", ['status'=>3,'pay_time'=>time(),'delivery_time'=>time()],"id={$findShopOrderData['id']}");
            if ($rc > 0 && $order_rc > 0) exit('用户组购买成功');
            exit('用户组购买失败');
        }
        
        //卡密购买
        if ($shopInfo['category'] == 2){
            //抽出库存
            $card_Find = $this->mysql->query("shop_card","shop_id={$shopInfo['id']} and status=0");
            for ($i=0;$i<$findShopOrderData['quantity'];$i++){
                if (is_array($card_Find[$i])){
                    //取出卡密
                    $cardInfo[] = [
                        'card'=>$card_Find[$i]['card_no']
                    ];
                }else {
                    //卡号不足
                    $cardInfo[] = [
                        'card'=>'发货库存不足,已退款,金额：'.$price,
                        'pwd'=>'发货库存不足,已退款,金额：'.$price
                    ];
                    //退款操作
                    //实时查询user信息
                    $userInfo = $this->mysql->query("client_user","id={$findShopOrderData['user_id']}")[0];
                    $this->mysql->update("client_user", ['money'=>$userInfo['money']+$price],"id={$userInfo['id']}");
                }
                $this->mysql->update("shop_card", ['status'=>1,'sell_time'=>time(),'user_id'=>$findShopOrderData['id']],"id={$card_Find[$i]['id']}");
            }
            $cardInfo = json_encode($cardInfo);
            //更新訂單信息
            $rc = $this->mysql->update("shop_order", ['status'=>2,'ship'=>$cardInfo,'pay_time'=>time(),'delivery_time'=>time()],"id={$findShopOrderData['id']}");
            if ($rc > 0) exit('卡密购买成功');
            exit('卡密购买失败');
        }
        
        
        //商品购买
        if ($shopInfo['category'] == 3){
            $ship = json_encode([
                ['time'=>time(),'info'=>'订单等待支付'],
                ['time'=>time(),'info'=>'已支付成功,等待平台发货']
            ]);
            //更新訂單信息
            $rc = $this->mysql->update("shop_order", ['status'=>1,'ship'=>$ship,'pay_time'=>time()],"id={$findShopOrderData['id']}");
            $this->mysql->update("shop", ['warehouse'=>($shopInfo['warehouse']-$findShopOrderData['quantity'])],"id={$shopInfo['id']}");
            if ($rc > 0) exit('商品购买成功');
            exit('商品购买失败');
        }
       
            
    }

    
}