<?php
use xh\library\functions;
use xh\library\url;
use xh\unity\cog;
//初始化系统ID
$_SESSION['SYSTEM_PAY_ID'] = [
    'key' => substr(md5(mt_rand(1000000,99999999)), 0,10),
    'id'=>0
];
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>请稍后..</title>
</head>
<body>
<form action="<?php echo url::s('gateway/index/checkpoint');?>" method="post" id="frmSubmit">
    <input type="hidden" name="account_id" value="<?php echo $_SESSION['SYSTEM_PAY_ID']['key'];?>" />
	<input type="hidden" name="content_type" value="text"/>
	<input type="hidden" name="thoroughfare" value="service_auto"/>
	<input type="hidden" name="out_trade_no" value="<?php echo $serial_no;?>"/>
	<input type="hidden" name="sign" value="<?php echo functions::sign(cog::read('server')['key'], ['amount'=>floatval($amount),'out_trade_no'=>$serial_no]);?>"/>
	<input type="hidden" name="robin" value="2" />
	<input type="hidden" name="callback_url" value="<?php echo url::s("server/shop/pay");?>" />
	<input type="hidden" name="success_url" value="<?php echo url::s('index/shop/order');?>" />
	<input type="hidden" name="error_url" value="<?php echo url::s('index/shop/index');?>" />
	<input type="hidden" name="amount" value="<?php echo floatval($amount);?>" />
	<input type="hidden" name="type" value="<?php echo intval($pay_type);?>" />
</form>
<script type="text/javascript">
document.getElementById("frmSubmit").submit();
</script>
</body>
</html>