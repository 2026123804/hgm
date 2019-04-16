<?php
use xh\library\url;
use xh\unity\cog;
include_once (PATH_VIEW . 'common/header.php'); //头部
include_once (PATH_VIEW . 'common/nav.php'); //导航
?>

<!-- START CONTENT -->
<div class="content">

  <!-- Start Page Header -->
  <div class="page-header">
   
      <ol class="breadcrumb">
        <li><a href="<?php echo url::s('admin/index/home');?>">控制台</a></li>
        <li class="active">通道开关</li>
      </ol>
      
  </div>
  <!-- End Page Header -->


 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- START CONTAINER -->
<div class="container-padding">
  
    <!-- Start Row -->
  <div class="row">

    <div class="col-md-12">
      <div class="panel panel-default">

        <div class="panel-title">
         通道开关
          <ul class="panel-tools">
            <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
          </ul>
        </div>

            <div class="panel-body">
              <form class="form-horizontal" id="from">

               <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">wechat（全自动版）</label>
                  <div class="col-sm-5">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="wechat_auto_open" name="wechat_auto_open" value="1" <?php if (cog::read('costCog')['wechat_auto']['open'] == 1) echo 'checked';?>>
                        <label for="wechat_auto_open"> 启动端口 （使用场景：支付体验，客户订单少，适用于中小型网站）</label>
                    </div>
                  </div>
                </div>
                
          
               
                
                <hr>
                
                <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">alipay（全自动版）</label>
                  <div class="col-sm-9">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="alipay_auto_open" name="alipay_auto_open" value="1" <?php if (cog::read('costCog')['alipay_auto']['open'] == 1) echo 'checked';?>>
                        <label for="alipay_auto_open"> 启动端口 （使用场景：支付体验，客户订单少，适用于中小型网站）</label>
                    </div>
                  </div>
                </div>
                
             
               
                
                 <hr>
                 
                  <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">tenpay（全自动版）</label>
                  <div class="col-sm-9">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="tenpay_auto_open" name="tenpay_auto_open" value="1" <?php if (cog::read('costCog')['tenpay_auto']['open'] == 1) echo 'checked';?>>
                        <label for="tenpay_auto_open"> 启动端口 （使用场景：支付体验，客户订单少，适用于中小型网站）</label>
                    </div>
                  </div>
                </div>

                <hr>
                 
                 <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">京东pay（全自动版）</label>
                  <div class="col-sm-9">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="jdpay_auto_open" name="jdpay_auto_open" value="1" <?php if (cog::read('costCog')['jdpay_auto']['open'] == 1) echo 'checked';?>>
                        <label for="jdpay_auto_open"> 启动端口 （使用场景：全自动模式，支持高并发，百万级订单）</label>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">服务版（微信/支付宝 v1.0）</label>
                  <div class="col-sm-9">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="service_auto_open" name="service_auto" value="1" <?php if (cog::read('costCog')['service_auto']['open'] == 1) echo 'checked';?>>
                        <label for="service_auto_open"> 启动端口 （使用场景：全自动模式，支持高并发，百万级订单）</label>
                    </div>
                  </div>
                </div>
                 <hr>
                 
                <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">用户提现（v1.0）</label>
                  <div class="col-sm-9">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="withdraw_open" name="withdraw" value="1" <?php if (cog::read('costCog')['withdraw']['open'] == 1) echo 'checked';?>>
                        <label for="withdraw_open"> 启动端口 （使用场景：一般用于用户盈利余额提现）</label>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">商城购物（v1.0）</label>
                  <div class="col-sm-9">
                    <div class="checkbox checkbox-success checkbox-inline">
                        <input type="checkbox" id="shop_open" name="shop" value="1" <?php if (cog::read('costCog')['shop']['open'] == 1) echo 'checked';?>>
                        <label for="shop_open"> 启动商城 （使用场景：一般用于商户购买商品）</label>
                    </div>
                  </div>
                </div>
                 
 				<hr>
                  <div class="form-group">
                  <label class="col-sm-2 control-label form-label"></label>
                  <div class="col-sm-10">
                   	<a href="#" onclick="edit()" class="btn btn-success"><i class="fa fa-refresh"></i>保存更新</a> &nbsp;&nbsp;
                   	<a href="<?php echo url::s('admin/index/home');?>" class="btn"><i class="fa fa-close"></i>取消</a>
                  </div>
                </div>

              </form> 

            </div>

      </div>
    </div>

  </div>
  <!-- End Row -->
  
    <script type="text/javascript">
			function edit(){
				$.ajax({
			          type: "POST",
			          dataType: "json",
			          url: "<?php echo url::s('admin/system/costCogResult');?>",
			          data: $('#from').serialize(),
			          success: function (data) {
			              if(data.code == '200'){
			            	  swal("操作提示", data.msg, "success");
			              }else{
			            	  swal("操作提示", data.msg, "error");
			              }
			          },
			          error: function(data) {
			              alert("error:"+data.responseText);
			           }
			  });
			}
   </script>
  
</div>
<!-- END CONTAINER -->
 <!-- //////////////////////////////////////////////////////////////////////////// --> 

<?php include_once (PATH_VIEW . 'common/footer.php');?>

</div>
<!-- End Content -->

<?php include_once (PATH_VIEW . 'common/chat.php');?>

<!-- ================================================
jQuery Library
================================================ -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/jquery.min.js"></script>

<!-- ================================================
Bootstrap Core JavaScript File
================================================ -->
<script src="<?php echo URL_VIEW;?>/static/console/js/bootstrap/bootstrap.min.js"></script>

<!-- ================================================
Plugin.js - Some Specific JS codes for Plugin Settings
================================================ -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/plugins.js"></script>

<!-- ================================================
Sweet Alert
================================================ -->
<script src="<?php echo URL_VIEW;?>/static/console/js/sweet-alert/sweet-alert.min.js"></script>

</body>
</html>