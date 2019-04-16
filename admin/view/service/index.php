<?php 
use xh\library\url;
use xh\library\model;
use xh\library\ip;
include_once (PATH_VIEW . 'common/header.php'); //头部
include_once (PATH_VIEW . 'common/nav.php'); //导航
$fix = DB_PREFIX;
?>
<link href="<?php echo str_replace("admin", 'index', URL_VIEW);?>/static/js/plugins/sweetalert/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
<!-- START CONTENT -->
<div class="content">

  <!-- Start Page Header -->
  <div class="page-header">
   
      <ol class="breadcrumb">
        <li><a href="<?php echo url::s('admin/index/home');?>">控制台</a></li>
        <li class="active">服务账号管理</li>
      </ol>
  </div>
  <!-- End Page Header -->
 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- START CONTAINER -->
<div class="container-padding">


  <!-- Start Row -->
  <div class="row">
    <!-- Start Panel -->
    <div class="col-md-12">
      <div class="panel panel-default">
     	 <div class="panel-title" >
         <button type="button" onclick="addWechat();" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> 新增微信</button>
		 <button type="button" onclick="addAlipay();" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> 新增支付宝</button>
		 
		 <button type="button" onclick="robinTest(1);" class="btn btn-info btn-xs"><i class="fa fa-xing-square"></i> 通道测试(微信)</button>
		 <button type="button" onclick="robinTest(2);" class="btn btn-info btn-xs"><i class="fa fa-xing-square"></i> 通道测试(支付宝)</button>
        </div>
        <div class="panel-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <td>设备信息  [ <a href="<?php echo url::s('admin/service/index','sorting=type&code=go');?>">全部</a> / <a href="<?php echo url::s('admin/service/index','sorting=type&code=1');?>">微信</a> / <a href="<?php echo url::s('admin/service/index','sorting=type&code=2');?>">支付宝</a> ]</td>
                <td>状态 [ <a href="<?php echo url::s('admin/service/index','sorting=status&code=go');?>">全部</a> / <a style="color: green;" href="<?php echo url::s('admin/service/index','sorting=status&code=1');?>">在线</a> / <a style="color: red;" href="<?php echo url::s('admin/service/index','sorting=status&code=2');?>">离线</a> ]</td>
                <td>活跃</td>
                <th>网关</th>
                <td>收款</td>
                <td>类型</td>
                <td>操作  <div class="checkbox checkbox-warning" style="display:inline-block;margin:0 0 0 25px;padding:0;position:relative;top:6px;">
                        <input id="checkboxAll" type="checkbox">
                        <label for="checkboxAll">
                        </label>
                        
                        <button type="button" id="deletes" onclick="deletes();" class="btn btn-option1 btn-xs" style="display:none;position:relative;top:-8px;"><i class="fa fa-trash-o"></i>删除</button>
                        
                    </div></td>
              </tr>
            </thead>
            <tbody>
            <?php  foreach ($result['result'] as $ru){  ?>
              <tr>
                <td>
                    <p><b><?php echo (new model())->load('service', 'types')->get($ru['types']);?>名称</b>: <?php echo $ru['name'] == '0' ? '<span style="color:red">Unused</span>' : '<span style="color:red">'.$ru['name'].'</span>';?>  [ 今日总额: <span style="color: green;"><?php echo $ru['today_money'];?></span> / 笔数: <span style="color: red;"><?php echo $ru['today_pens'];?></span> ] ( <a href="<?php echo url::s('admin/service/order',"sorting=service&code={$ru['id']}");?>">交易订单</a> )</p>
                    <p><b>设备KEY: </b> <?php echo $ru['key_id'];?> <?php if ($ru['status']!=6 && $ru['status']!=4){?> [ <a href="#" onclick="login('<?php echo $ru['id'];?>');">立即登录</a> ]<?php }?> </p>
                    
                    
                    
                </td>
                
                
                <td>
                   <p>状态: <?php echo (new model())->load('wechat', 'distinguish')->status($ru['status']); if ($ru['status']!=6 && $ru['status']!=1) echo ' ( <a href="#" style="color:red;" onclick="startAutomaticLogOut('.$ru['id'].');">安全下线</a> )';?></p>
                    <p>最后登录: <?php echo $ru['login_time'] == 0 ? '从未登录' : date("Y/m/d H:i:s",$ru['login_time']);?></p>
                </td>
                
                <td>
                    <p><b>最近活跃时间:</b> <?php echo $ru['active_time'] == 0 ? '从未登录' : date("Y/m/d H:i:s",$ru['active_time']);?></p>
                    <p><b>安卓活跃时间:</b> <?php echo $ru['android_heartbeat'] == 0 ? '无信息' : date("Y/m/d H:i:s",$ru['android_heartbeat']);?></p>
                </td>
                
                 <td>
                    <p><b>轮训开关: </b><?php echo $ru['training'] == 1 ? '<span style="color:#4caf50;">open ( <a href="#" style="color:#006064;" onclick="startAutomaticRb('.$ru['id'].');">关闭轮训 </a> )</span>' : '<span style="color:red;">closed ( <a href="#" style="color:#e57373;" onclick="startAutomaticRb('.$ru['id'].');">启动轮训 </a>)</span>';?></p>
                    <p><b>网关开关: </b><?php echo $ru['receiving'] == 1 ? '<span style="color:#4caf50;">open ( <a href="#" style="color:#006064;" onclick="startAutomaticGateway('.$ru['id'].');">停止网关 </a> )</span>' : '<span style="color:red;">closed ( <a href="#" style="color:#e57373;" onclick="startAutomaticGateway('.$ru['id'].');">启动网关 </a>)</span>';?></p>
                </td>
                
                <td>
                        <p><b>今日收入:</b> <?php //查询今日收入
                        $nowTime = strtotime(date("Y-m-d",time()) . ' 00:00:00');
                        $order = $mysql->select("select sum(amount) as money,count(id) as count,sum(fees) as fees from {$fix}service_order where service_id={$ru['id']} and creation_time > {$nowTime} and status=4");
                        echo '<span style="color:red;font-weight:bold;"> '.floatval($order[0]['money']) .' </span> / 手续费: <span style="color:blue;">'. number_format($order[0]['fees'],3) .'</span>  ( 订单数量: <span style="color:green;font-weight:bold;">'.$order[0]['count'].'</span> )';
                        ?></p>
                        <p><b>昨日收入:</b> <?php 
                        $zrTime = strtotime(date("Y-m-d",$nowTime-86400) . ' 00:00:00'); //昨日的时间
  
                        $order = $mysql->select("select sum(amount) as money,count(id) as count,sum(fees) as fees from {$fix}service_order where service_id={$ru['id']} and creation_time > {$zrTime} and creation_time<{$nowTime} and status=4");
                        echo '<span style="color:red;font-weight:bold;"> '.floatval($order[0]['money']) .' </span> / 手续费: <span style="color:blue;">'. number_format($order[0]['fees'],3) .'</span>  ( 订单数量: <span style="color:green;font-weight:bold;">'.$order[0]['count'].'</span> )';
                        ?></p>
                        </td>
                        
                         <td>
                        <p><b>账号类型:</b> <?php if ($ru['types'] == 1) echo '<span style="color:green;">微信</span>'; if ($ru['types'] == 2) echo '<span style="color:red;">支付宝</span>'; ?> [ <?php if ($ru['lord'] == 0){?><a href="#" onclick="setLord('<?php echo $ru['id'];?>');" style="color: green;font-size:8px;">设置为系统主用</a><?php }else {?><a href="#" onclick="stopLord('<?php echo $ru['id'];?>');" style="color: red;font-size:8px;">取消系统主用</a><?php }?> ] [ <a href="#" onclick="gatewayTest('<?php echo $ru['key_id'];?>','<?php echo $ru['types'];?>')">单通道测试</a> ]</p>
                        <p><b>全部收入:</b> <?php 
                        $order = $mysql->select("select sum(amount) as money,count(id) as count,sum(fees) as fees from {$fix}service_order where service_id={$ru['id']} and status=4");
                        echo '<span style="color:red;font-weight:bold;"> '.floatval($order[0]['money']) .' </span> / 手续费: <span style="color:blue;">'. number_format($order[0]['fees'],3) .'</span>  ( 订单数量: <span style="color:green;font-weight:bold;">'.$order[0]['count'].'</span> )';
                        ?></p>
                        </td>
               
                <td>
                <p style="margin-top: -15px;"><div class="checkbox checkbox-danger checkbox-circle">
                        <input onclick="showBtn()" name="items" value="<?php echo $ru['id'];?>" id="checkbox<?php echo $ru['id'];?>" type="checkbox">
                        <label for="checkbox<?php echo $ru['id'];?>">
                            勾选,准备操作!
                        </label>
                    </div></p>
                <p><a href="#" onclick="del('<?php echo $ru['id'];?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i>移除<?php echo (new model())->load('service', 'types')->get($ru['types']);?></a></p>
                </td>
              </tr>
            <?php }?>
            </tbody>
          </table>
          
          <div style="float:right;">
          <?php (new model())->load('page', 'turn')->auto($result['info']['pageAll'], $result['info']['page'], 10); ?>
          </div>
          <div style="clear: both"></div>
          
        </div>

      </div>
    </div>
    <!-- End Panel -->  
            <script type="text/javascript">

            function login(id){
            	  swal({   title: "服务登录",   
                      text: "您即将开始登录该服务账号,是否继续?",   
                      type: "info",   showCancelButton: true,   
                      closeOnConfirm: false,   
                      showLoaderOnConfirm: true,
                      confirmButtonText: "立即登录"
                       }, 
                      function(){
                      //开始请求服务登录
                    	   $.get("<?php echo url::s('admin/service/login',"id=");?>" + id, function(result){
                          	 if(result.code == '200'){
                           		    $('.showSweetAlert p').html(result.msg);
                             		login_listen(id);
                	              }else{
                	            	swal("服务登录", result.msg, "error");
                	             }
                      		});
                          
                 });
              }
              var listen_login = 0;
              //伪造线程
              function login_listen(id){
              	listen_login = setInterval(function(){ $.get("<?php echo url::s('admin/service/loginStatus',"id=");?>" + id, function(result){
              		if(result.code > 0){	
              			if(result.code == '2' || result.code == '3' ){ $('.showSweetAlert p').html(result.msg); }
              			if(result.code == '7'){
        				//将二维码展现出来扫码
              				$('.showSweetAlert h2').html('请使用扫一扫');
              				$('.showSweetAlert p').html("<img style='width:200px;height:200px;' src='data:image/png;base64," + result.data.img + "'/>");
                      	}
              			if(result.code == '4'){
        	            	swal("服务登录", result.msg, "success");
        	              	setTimeout(function(){location.href = '';},1000);
                        }
                     }else{
                    	 swal("服务登录", result.msg, "error");
                    	 setTimeout(function(){location.href = '';},1000);
                     }
              	  });  },1000);
              }
            

            function addWechat(){
            	  swal({
                      title: "微信提醒", 
                      text: "您确定要新增加一个微信服务通道吗?", 
                      type: "warning", 
                      showCancelButton: true, 
                      confirmButtonColor: "#DD6B55", 
                      confirmButtonText: "是的,我要新增!", 
                      closeOnConfirm: false 
                    },
                    function(){
                       $.get("<?php echo url::s('admin/service/addWecaht');?>", function(result){
                      	 if(result.code == '200'){
            	            	swal("微信提示", result.msg, "success");
            	              	setTimeout(function(){location.href = '';},1000);
            	              }else{
            	            	swal("微信提示", result.msg, "error");
            	              }
                      	  });
                    });	
              }

            function addAlipay(){
            	  swal({
                      title: "服务提醒", 
                      text: "您确定要新增加一个服务服务通道吗?", 
                      type: "warning", 
                      showCancelButton: true, 
                      confirmButtonColor: "#DD6B55", 
                      confirmButtonText: "是的,我要新增!", 
                      closeOnConfirm: false 
                    },
                    function(){
                       $.get("<?php echo url::s('admin/service/addAlipay');?>", function(result){
                      	 if(result.code == '200'){
            	            	swal("服务提示", result.msg, "success");
            	              	setTimeout(function(){location.href = '';},1000);
            	              }else{
            	            	swal("服务提示", result.msg, "error");
            	              }
                      	  });
                    });	
              }
            
            
            function startAutomaticRb(id){
            	  swal({
                      title: "服务提醒", 
                      text: "当前操作是更改服务轮训状态,您是否继续?", 
                      type: "warning", 
                      showCancelButton: true, 
                      confirmButtonColor: "#DD6B55", 
                      confirmButtonText: "确认", 
                      closeOnConfirm: false 
                    },
                    function(){
                       $.get("<?php echo url::s('admin/service/startRobin',"id=");?>" + id, function(result){
                      	 if(result.code == '200'){
            	            	swal("服务提示", result.msg, "success");
            	              	setTimeout(function(){location.href = '';},1000);
            	              }else{
            	            	swal("服务提示", result.msg, "error");
            	              }
                      	  });
                    });	
              }

            function startAutomaticGateway(id){
            	  swal({
                      title: "服务提醒", 
                      text: "当前操作是更改网关状态,您是否继续?", 
                      type: "warning", 
                      showCancelButton: true, 
                      confirmButtonColor: "#DD6B55", 
                      confirmButtonText: "是的,继续!", 
                      closeOnConfirm: false 
                    },
                    function(){
                       $.get("<?php echo url::s('admin/service/startGateway',"id=");?>" + id, function(result){
                      	 if(result.code == '200'){
            	            	swal("服务提示", result.msg, "success");
            	              	setTimeout(function(){location.href = '';},1000);
            	              }else{
            	            	swal("服务提示", result.msg, "error");
            	              }
                      	  });
                    });	
              }

            function startAutomaticLogOut(id){
            	  swal({
                      title: "服务提醒", 
                      text: "您是否要退出当前服务?", 
                      type: "warning", 
                      showCancelButton: true, 
                      confirmButtonColor: "#DD6B55", 
                      confirmButtonText: "是的,我要退出!", 
                      closeOnConfirm: false 
                    },
                    function(){
                       $.get("<?php echo url::s('admin/service/startLogOut',"id=");?>" + id, function(result){
                      	 if(result.code == '200'){
            	            	swal("服务提示", result.msg, "success");
            	              	setTimeout(function(){location.href = '';},1000);
            	              }else{
            	            	swal("服务提示", result.msg, "error");
            	              }
                      	  });
                    });	
              }

			function del(id){
		              swal({
		                title: "服务提醒", 
		                text: "你确定要删除该服务吗？", 
		                type: "warning", 
		                showCancelButton: true, 
		                confirmButtonColor: "#DD6B55", 
		                confirmButtonText: "是的,我要删除该服务!", 
		                closeOnConfirm: false 
		              },
		              function(){
		                 $.get("<?php echo url::s('admin/service/delete','id=');?>" + id, function(result){

		                	 if(result.code == '200'){
				            	swal("操作提示", result.msg, "success");
				              	setTimeout(function(){location.href = '';},1500);
				              }else{
				            	  swal("操作提示", result.msg, "error");
				              }
		                	  });

						  
		              });		
			}


			function deletes(){ 
		           swal({
		                title: "非常危险", 
		                text: "你确定要批量删除已选中的服务吗？", 
		                type: "warning", 
		                showCancelButton: true, 
		                confirmButtonColor: "#DD6B55", 
		                confirmButtonText: "是的,我要删除这些服务!", 
		                closeOnConfirm: false 
		              },
		              function(){
				           $("input[name='items']:checked").each(function(){
				        	 $.get("<?php echo url::s('admin/service/delete','id=');?>" + $(this).val(), function(result){
						            	swal("操作提示", '当前操作已经执行完毕!', "success");
						              	setTimeout(function(){location.href = '';},1500);
				                	  });
				           });  
						  
		              });
		           
				}


			//轮训测试
			  function robinTest(type){
				  swal({
				      title: "轮训通道测试",
				      text: "请输入要测试支付的金额<input type='text' id='amount' value='1.00'>"
				      +"请输入要接收异步通知的回调url<input type='text' id='callback_url'>",showCancelButton: true,   
				      html: true,
		              confirmButtonText: "确认测试" , 
				      type: "prompt",
				  }, function(){
				       window.open('<?php echo url::s("admin/service/robinTest");?>' + '?type='+type+'&amount=' + $('#amount').val() + '&callback_url=' + $('#callback_url').val());
					   location.href='';
				      })
				 $('.showSweetAlert fieldset input').attr('type','hidden');
				 $('#amount').val('1.00');
				 $('#callback_url').val('https://www.baidu.com');
			  }


			function showBtn(){
				var Inc = 0;
				$("input[name='items']:checkbox").each(function(){
                    if(this.checked){
                    	$('#deletes').show();
                    	return true;
                    }
                    Inc++;
              });
	              if($("input[name='items']:checkbox").length == Inc){
	            	  $('#deletes').hide();
		          }
			}


			


			function setLord(id){
	          	  swal({
	                    title: "服务提醒", 
	                    text: "您确定要将该服务号设置为系统主要使用收款账号吗?", 
	                    type: "warning", 
	                    showCancelButton: true, 
	                    confirmButtonColor: "#DD6B55", 
	                    confirmButtonText: "确认", 
	                    closeOnConfirm: false 
	                  },
	                  function(){
	                     $.get("<?php echo url::s('admin/service/setLord',"id=");?>" + id, function(result){
	                    	 if(result.code == '200'){
	          	            	swal("服务提示", result.msg, "success");
	          	              	setTimeout(function(){location.href = '';},1000);
	          	              }else{
	          	            	swal("服务提示", result.msg, "error");
	          	              }
	                    	  });
	                  });	
	            }


			function gatewayTest(id,types){
				  swal({
				      title: "单通道测试",
				      text: "请输入要测试支付的金额<input type='text' id='amount' value='1.00'>"
				      +"请输入要接收异步通知的回调url<input type='text' id='callback_url'>",showCancelButton: true,   
				      html: true,
		              confirmButtonText: "确认测试" , 
				      type: "prompt",
				  }, function(){
				       window.open('<?php echo url::s("admin/service/gatewayTest");?>' + '?amount=' + $('#amount').val()  + "&type="+types+"&keyId=" + id + '&callback_url=' + $('#callback_url').val());
					   location.href='';
				      })
				 
				 $('.showSweetAlert fieldset input').attr('type','hidden');
				 $('#amount').val('1.00');
				 $('#callback_url').val('https://www.baidu.com');
			  }


			function stopLord(id){
	          	  swal({
	                    title: "服务提醒", 
	                    text: "你确定要取消该服务号对系统的服务吗？", 
	                    type: "warning", 
	                    showCancelButton: true, 
	                    confirmButtonColor: "#DD6B55", 
	                    confirmButtonText: "确认", 
	                    closeOnConfirm: false 
	                  },
	                  function(){
	                     $.get("<?php echo url::s('admin/service/setLord',"id=");?>" + id, function(result){
	                    	 if(result.code == '200'){
	          	            	swal("服务提示", '取消成功', "success");
	          	              	setTimeout(function(){location.href = '';},1000);
	          	              }else{
	          	            	swal("服务提示", result.msg, "error");
	          	              }
	                    	  });
	                  });	
	            }

            </script>
            

<!-- End Moda Code -->


 
  </div>
  <!-- End Row -->
  
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
<!--<script src="<?php echo URL_VIEW;?>/static/console/js/sweet-alert/sweet-alert.min.js"></script>-->
<script type="text/javascript" src="<?php echo str_replace('admin', 'index', URL_VIEW);?>/static/js/plugins/sweetalert/sweetalert.min.js"></script>  
<!-- ================================================
Bootstrap Select
================================================ -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/bootstrap-select/bootstrap-select.js"></script>

<script>


$(function(){
       //实现全选与反选  
       $("#checkboxAll").click(function() {
           if (this.checked){
               $("input[name='items']:checkbox").each(function(){   
                     $(this).prop("checked", true);
               });
               showBtn();
           } else {     
               $("input[name='items']:checkbox").each(function() {     
                     $(this).prop("checked", false);    
               });
               showBtn();
           }   
       });  
   });  
</script>

</body>
</html>