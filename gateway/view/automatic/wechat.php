<?php
use xh\library\url;
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="Content-Language" content="zh-cn">
<meta name="renderer" content="webkit">
<title>在线支付 - 微信安全支付</title>
<script type="text/javascript" src="<?php echo URL_VIEW;?>static/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_VIEW;?>static/js/qrcode.js"></script>
<script type="text/javascript" src="<?php echo URL_VIEW;?>static/js/layer/layer.js"></script>
<link href="<?php echo URL_VIEW;?>static/css/wechat/wechat_pay.css" rel="stylesheet" media="screen">
<style>
.switch-tip-icon-img {
    position: absolute;
    left: 70px;
    top: 70px;
    z-index: 11;

}
.shadow{  
   -webkit-box-shadow: #666 0px 0px 10px;  
   -moz-box-shadow: #666 0px 0px 10px;  
   box-shadow: #666 0px 0px 10px;  
    padding-top: 15px;
    padding-right: 5px;
    padding-bottom: 1px;
    padding-left: 5px;
   background: #FFFFFF; 
   width:240px;
  height:240px;
} 
.time-item strong {
    background:#13A500;
    color:#fff;
    line-height:30px;
    font-size:20px;
    font-family:Arial;
    padding:0 10px;
    margin-right:10px;
    border-radius:5px;
    box-shadow:1px 1px 3px rgba(0,0,0,0.2);
}
h2 {
	line-height:50px;
    font-family:"微软雅黑";
    font-size:16px;
    letter-spacing:2px;
}
</style>
</head>
<body>
<div class="body">
<h1 class="mod-title">
<span class="ico-wechat"></span><span class="text">微信支付</span>
</h1>
<div class="mod-ct">
<div class="order">
</div>
<div class="amount" style="color: red;">￥<b><?php echo $amount;?></b></div>
<br>
<div align="center">
<div class="shadow"><div align="center">
<font class="qr-image" id="qrcode">
<div id="qrcode_img"><img id="qrcode_load" style="width: 50%;height:50%;margin-top:52px;" alt="Scan me!" style="display: block;" src="<?php echo URL_VIEW . '/static/loading.gif';?>"></div>
</font>
</div></div>
<h2>距离该订单过期还有</h2>
<div class="time-item">
<strong id="hour_show"><s id="h"></s>0时</strong>
<strong id="minute_show"><s></s>00分</strong>
    <strong id="second_show"><s></s>00秒</strong>
</div>

</div>

<div class="detail" id="orderDetail">
<dl class="detail-ct" style="display: none;">
<dt>商家</dt>
<dd id="storeName"><?php echo $wechat_name;?></dd>
<dt>商品类型</dt>
<dd id="productName">自动充值</dd>
<dt>商户订单号</dt>
<dd id="billId"><?php echo $trade_no;?> </dd>
<dt>创建时间</dt>
<dd id="createTime"><?php echo date("Y-m-d H:i:s",$creation_time);?></dd>
</dl>
<a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
</div>
<div class="tip">
<span class="dec dec-left"></span>
<span class="dec dec-right"></span>
<div class="ico-scan"></div>
<div class="tip-text">
<p>请使用微信扫一扫</p>
<p>扫描二维码完成支付</p>
</div>
</div>
<div class="tip-text">
</div>
</div>
<div class="foot">
<div class="inner">

<p>本站为第三方辅助软件服务商，与QQ财付通和腾讯网无任何关系</p>
<p>在付款前请确认收款人账户信息，转账后将立即到达对方账户</p>

</div>
</div>
</div>
  <script type="text/javascript">
var intDiff = parseInt('<?php echo ($creation_time+86400) - time();?>');//倒计时总秒数量

function timer(intDiff){
    window.setInterval(function(){
    var day=0,
        hour=0,
        minute=0,
        second=0;//时间默认值       
    if(intDiff > 0){
        day = Math.floor(intDiff / (60 * 60 * 24));
        hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
        minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
        second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
    }
	//if (minute == 00 && second == 00) document.getElementById('qrcode').innerHTML='<br/><br/><br/><br/><br/><h2>二维码超时 请重新发起交易</h2><br/><br/><br/>';
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    $('#day_show').html(day+"天");
    $('#hour_show').html('<s id="h"></s>'+hour+'时');
    $('#minute_show').html('<s></s>'+minute+'分');
    $('#second_show').html('<s></s>'+second+'秒');
    intDiff--;
    }, 1000);
} 
$(function(){
    timer(intDiff);
});
 

    // 订单详情
    $('#orderDetail .arrow').click(function (event) {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function () {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function () {
                $('#orderDetail').addClass('detail-open');
            });
        }
    });

    var updateQrImg = 0;

    //订单监控  {订单监控}
    function order(){
    	$.get("<?php echo url::s('gateway/pay/automaticWechatQuery',"id={$id}");?>", function(result){
        	
    		//成功
    		if(result.code == '200'){
    			play(['<?php echo FILE_CACHE . "/download/sound/当前订单支付成功1.mp3";?>']);
				//回调页面
        		window.clearInterval(orderlst);
    			layer.confirm(result.msg, {
    			  icon: 1,
    			  title: '支付成功',
  				  btn: ['我知道了'] //按钮
  				}, function(){
  					location.href="<?php echo $success_url;?>";
  				});
    			setTimeout(function(){location.href="<?php echo $success_url;?>";},5000);
    		}

    		//支付二维码
    		if(result.code == '100' && updateQrImg == 0){
    			play(['<?php echo FILE_CACHE . "/download/sound/处理完成打开微信1.mp3";?>']);
        		$('#qrcode_load').remove();
				//设置参数方式 
				var qrcode = new QRCode('qrcode_img', { 
				  text: result.data.qrcode, 
				  width: 256, 
				  height: 256, 
				  colorDark : '#000000', 
				  colorLight : '#ffffff', 
				  correctLevel : QRCode.CorrectLevel.H 
				});
				updateQrImg = 1;
    		}
    		
        	//订单已经超时
    		if(result.code == '-1' || result.code == '-2'){
    			play(['<?php echo FILE_CACHE . "/download/sound/订单超时1.mp3";?>']);
    			window.clearInterval(orderlst);
    			layer.confirm(result.msg, {
    			  icon: 2,
    			  title: '支付失败',
  				  btn: ['确认'] //按钮
  				}, function(){
  					location.href="<?php echo $error_url;?>";
  				});
        	}
    	  });
     }
    //周期监听
    var orderlst = setInterval("order()",1000);
 
</script>
<script type="text/javascript" src ="<?php echo URL_STATIC . '/js/jike.js'?>"></script>
<script type="text/javascript">play(['<?php echo FILE_CACHE . "/download/sound/请稍等1.mp3";?>']);</script>

</body></html>