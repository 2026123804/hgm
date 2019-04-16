<?php
use xh\library\url;
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        <title>在线支付 - 微信安全支付</title>
        <link rel="stylesheet" type="text/css" href="<?php echo URL_VIEW;?>static/css/mobile/QRCode.css">
        <script type="text/javascript" src="<?php echo URL_VIEW;?>static/js/jquery.min.js"></script>
 		<script type="text/javascript" src="<?php echo URL_VIEW;?>static/js/qrcode.js"></script>
 		<script type="text/javascript" src="<?php echo URL_VIEW;?>static/js/layer/layer.js"></script>
 		<link href="<?php echo URL_VIEW;?>static/css/wechat/wechat_pay.css" rel="stylesheet" media="screen">
    </head>
    <body>
    <div style="width: 100%; text-align: center;font-family:微软雅黑;">
        <div id="panelWrap" class="panel-wrap">
            <!-- CUSTOM LOGO -->
            <div class="panel-heading">
                <div class="row">
             	<div class="col-md-12 text-center">
                  <h1 class="mod-title">
<span class="ico-wechat"></span><span class="text">微信支付</span>
</h1>
             </div>
              
                </div>
            </div>
            <!-- PANEL TlogoEMPLATE START -->
            <div class="panel panel-easypay">
                <!-- PANEL HEADER -->
                <div class="panel-heading">
                    <h3>
                        <small>订单号：<?php echo $trade_no;?></small>
 
                    </h3>
                    <div class="money">
                        <span class="price"><?php echo $amount;?></span>
                        <span class="currency">元</span>
                    </div>
                </div>
                <div class="qrcode-warp">
                    <div id="qrcode">
                    <img id="qrcode_load" src="<?php echo URL_VIEW . '/static/loading.gif';?>" style="display: block;"></div></div>
                <div class="panel-footer">
                    <!-- SYSTEM MESSAGE -->
                    <span id="Span1" class="warning" style="color:red;"><small>1.手机截屏 <br> 2.打开微信扫一扫，右上角从相册选择图片(查看) <br> 3.完成支付</small></span>
                </div>
                <div class="panel-footer">
                    <input type="button" id="btnDL" onclick="alert('请使用手机截屏功能!');" value="立即支付" class="btn  btn-primary btn-lg btn-block">
                </div>
                            </div>
        </div>
    </div>
     <script type="text/javascript">
var intDiff = parseInt('<?php echo ($creation_time + 86400) - time();?>');//倒计时总秒数量
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
	//if (minute == 00 && second == 00) document.getElementById('qrcode').innerHTML='<br/><br/><br/><br/><br/><br/><br/><h2>二维码超时 请重新发起交易</h2><br/>';
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    //$('#day_show').html(day+"天");
    //$('#hour_show').html('<s id="h"></s>'+hour+'时');
    //$('#minute_show').html('<s></s>'+minute+'分');
    //$('#btnDL').valu('<s></s>'+second+'秒');
    $('#btnDL').val("立即支付(" + hour + '时' + minute + '分' + second + '秒'+')');
    intDiff--;
    }, 1000);
} 
$(function(){
    timer(intDiff);
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
				var qrcode = new QRCode('qrcode', {
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