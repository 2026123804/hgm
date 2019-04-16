<?php
use xh\library\url;
use xh\library\model;
$fix = DB_PREFIX;
?>
	<?php include_once (PATH_VIEW . 'common/header.php');?>
    <!-- START CONTENT -->
      <section id="content">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper">
            <!-- Search for small screen -->
            <div class="header-search-wrapper grey hide-on-large-only">
                <i class="mdi-action-search active"></i>
                <input type="text" name="Search" class="header-search-input z-depth-2" placeholder="Explore Materialize">
            </div>
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Automatic v1.0</h5>
                <ol class="breadcrumbs">
                    <li><a href="<?php echo url::s('index/panel/home');?>">仪表盘</a></li>
                    <li><a href="#">微信</a></li>
                    <li class="active">Automatic</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
          <div class="section">

            <p class="caption">
            <a href="<?php echo url::s("index/wechat/automaticOrder");?>" style="font-size: 14px;" class="btn waves-effect waves-light  cyan darken-2"><i class="mdi-editor-border-all left" style="width: 10px;"></i>全部订单</a>
            <a onclick="add();" style="font-size: 14px;" class="btn waves-effect waves-light  cyan darken-2"><i class="mdi-content-add left" style="width: 10px;"></i>添加微信</a>
            <a onclick="setting();" style="font-size: 14px;" class="btn waves-effect waves-light  cyan darken-2"><i class="mdi-action-settings left" style="width: 10px;"></i>微信配置</a>
            <a onclick="robinTest();" style="font-size: 14px;" class="btn waves-effect waves-light  cyan darken-2"><i class="mdi-hardware-gamepad left" style="width: 10px;"></i>通道测试（轮训）</a>
            <a onclick="apk();" style="font-size: 14px;" class="btn waves-effect waves-light  cyan darken-2"><i class="mdi-file-cloud-download left" style="width: 10px;"></i>安卓APP下载</a>
            <!--  <a onclick="pc();" style="font-size: 14px;" class="btn waves-effect waves-light  cyan darken-2"><i class="mdi-hardware-desktop-windows left" style="width: 10px;"></i>电脑软件下载</a> -->
            <a href="<?php echo URL_ROOT . '/download/Android.zip'?>" style="font-size: 14px;" class="btn waves-effect waves-light cyan darken-2"><i class="mdi-hardware-memory left" style="width: 10px;"></i>下载安卓环境</a>
            </p>
        

            <!--Striped Table-->
            <div id="striped-table">

              <div class="row">
   
                <div class="col s12 m12 l12">
                  <table class="striped"  style="font-size: 14px;">
                    <thead>
                      <tr>
                        <th>标识ID</th>
                        <th>名称/状态</th>
                        <th>收款信息</th>
                        <th>活跃</th>
                        <th>Important</th>
                        <th>Operating</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result['result'] as $ru){?>
                      <tr>
                        <td><?php echo $ru['id'];?></td>
                        <td>
                        Name: <?php echo $ru['name'] == '0' ? '<span style="color:red;">Unused</span>' : '<span style="color:green;">'.$ru['name'].'</span>';?> [ 今日总额: <span style="color: green;"><?php echo $ru['today_money'];?></span> / 笔数: <span style="color: red;"><?php echo $ru['today_pens'];?></span> ] ( <a href="#" onclick="del('<?php echo $ru['id'];?>');" style="color:#757575;">删除微信</a> )
                        <br>Status: <?php echo (new model())->load('wechat', 'distinguish')->status($ru['status']); if ($ru['status']!=6 && $ru['status']!=1) echo ' ( <a href="#" style="color:red;" onclick="startAutomaticLogOut('.$ru['id'].');">安全下线</a> )';?>
                        <br>Last login: <?php echo $ru['login_time'] == 0 ? '从未登录' : date("Y/m/d H:i:s",$ru['login_time']);?>
                        </td>
                        <td>
                        <b>今日收入:</b> <?php //查询今日收入
                        $nowTime = strtotime(date("Y-m-d",time()) . ' 00:00:00');
                        $order = $mysql->select("select sum(amount) as money,count(id) as count,sum(fees) as fees from {$fix}client_wechat_automatic_orders where wechat_id={$ru['id']} and creation_time > {$nowTime} and status=4 and user_id={$_SESSION['MEMBER']['uid']}");
                        echo '<span style="color:red;font-weight:bold;"> '.floatval($order[0]['money']) .' </span> / 手续费: <span style="color:blue;">'. number_format($order[0]['fees'],3) .'</span> ( 订单数量: <span style="color:green;font-weight:bold;">'.$order[0]['count'].'</span> )';
                        ?><br>
                        <b>昨日收入:</b> <?php 
                        $zrTime = strtotime(date("Y-m-d",$nowTime-86400) . ' 00:00:00'); //昨日的时间
  
                        $order = $mysql->select("select sum(amount) as money,count(id) as count,sum(fees) as fees from {$fix}client_wechat_automatic_orders where wechat_id={$ru['id']} and creation_time > {$zrTime} and creation_time<{$nowTime} and status=4 and user_id={$_SESSION['MEMBER']['uid']}");
                        echo '<span style="color:red;font-weight:bold;"> '.floatval($order[0]['money']) .' </span> / 手续费: <span style="color:blue;">'. number_format($order[0]['fees'],3) .'</span>  ( 订单数量: <span style="color:green;font-weight:bold;">'.$order[0]['count'].'</span> )';
                        ?><br>
                        <b>全部收入:</b> <?php 
                        $order = $mysql->select("select sum(amount) as money,count(id) as count,sum(fees) as fees from {$fix}client_wechat_automatic_orders where wechat_id={$ru['id']} and status=4 and user_id={$_SESSION['MEMBER']['uid']}");
                        echo '<span style="color:red;font-weight:bold;"> '.floatval($order[0]['money']) .' </span> / 手续费: <span style="color:blue;">'. number_format($order[0]['fees'],3) .'</span>  ( 订单数量: <span style="color:green;font-weight:bold;">'.$order[0]['count'].'</span> )';
                        ?>
                        </td>
                        <td>
                        <b>ACTIVE Time:</b> <?php echo $ru['active_time'] == 0 ? '从未登录' : date("Y/m/d H:i:s",$ru['active_time']);?><br>
                        <b>ACTIVE Number:</b> <?php echo $ru['heartbeats'];?><br>
                        <b>Android Time:</b> <?php echo $ru['android_heartbeat'] == 0 ? '无信息' : date("Y/m/d H:i:s",$ru['android_heartbeat']);?>
                        </td>
                        
                        <td><b>DEVICE Key: </b> <?php echo $ru['key_id'];?><br>
                        <b>ROUND Robin: </b><?php echo $ru['training'] == 1 ? '<span style="color:#4caf50;">open ( <a href="#" style="color:#006064;" onclick="startAutomaticRb('.$ru['id'].');">关闭轮训 </a> )</span>' : '<span style="color:red;">closed ( <a href="#" style="color:#e57373;" onclick="startAutomaticRb('.$ru['id'].');">启动轮训 </a>)</span>';?><br>
                        <b>Gateway: </b><?php echo $ru['receiving'] == 1 ? '<span style="color:#4caf50;">open ( <a href="#" style="color:#006064;" onclick="startAutomaticGateway('.$ru['id'].');">停止网关 </a> )</span>' : '<span style="color:red;">closed ( <a href="#" style="color:#e57373;" onclick="startAutomaticGateway('.$ru['id'].');">启动网关 </a>)</span>';?>  [ <a href="#" onclick="gatewayTest('<?php echo $ru['key_id'];?>')">单通道测试</a> ]
                        </td>
                        <td><?php if ($ru['status']!=6 && $ru['status']!=4){?><a onclick="login('<?php echo $ru['id'];?>');" style="font-size: 14px;" class="btn waves-effect waves-light indigo"><i class="mdi-action-lock-open left" style="width: 10px;"></i>云端登录</a><?php }else {echo '<span style="color:#4caf50;;">wechat online</span>';}?></td>
                      </tr>
                    <?php }?>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="row"><ul class="pagination"><?php (new model())->load('page', 'turn')->auto($result['info']['pageAll'], $result['info']['page'], 10); ?></ul></div>
  
            </div>
            
            

          </div>


        </div>
        <!--end container-->

      </section>
      <!-- END CONTENT -->
      <script type="text/javascript">
      function add(){
    	  swal({
              title: "微信提醒", 
              text: "您确定要新增加一个微信通道吗?", 
              type: "warning", 
              showCancelButton: true, 
              confirmButtonColor: "#DD6B55", 
              confirmButtonText: "是的,我要新增!", 
              closeOnConfirm: false 
            },
            function(){
               $.get("<?php echo url::s('index/wechat/automaticAdd');?>", function(result){
              	 if(result.code == '200'){
    	            	swal("微信提示", result.msg, "success");
    	              	setTimeout(function(){location.href = '';},1000);
    	              }else{
    	            	swal("微信提示", result.msg, "error");
    	              }
              	  });
            });	
      }
      function startAutomaticRb(id){
    	  swal({
              title: "微信提醒", 
              text: "当前操作是更改微信轮训状态,您是否继续?", 
              type: "warning", 
              showCancelButton: true, 
              confirmButtonColor: "#DD6B55", 
              confirmButtonText: "是的,我要更改!", 
              closeOnConfirm: false 
            },
            function(){
               $.get("<?php echo url::s('index/wechat/startAutomaticRb',"id=");?>" + id, function(result){
              	 if(result.code == '200'){
    	            	swal("微信提示", result.msg, "success");
    	              	setTimeout(function(){location.href = '';},1000);
    	              }else{
    	            	swal("微信提示", result.msg, "error");
    	              }
              	  });
            });	
      }
      
      function startAutomaticGateway(id){
    	  swal({
              title: "微信提醒", 
              text: "当前操作是更改网关状态,您是否继续?", 
              type: "warning", 
              showCancelButton: true, 
              confirmButtonColor: "#DD6B55", 
              confirmButtonText: "是的,继续!", 
              closeOnConfirm: false 
            },
            function(){
               $.get("<?php echo url::s('index/wechat/startAutomaticGateway',"id=");?>" + id, function(result){
              	 if(result.code == '200'){
    	            	swal("微信提示", result.msg, "success");
    	              	setTimeout(function(){location.href = '';},1000);
    	              }else{
    	            	swal("微信提示", result.msg, "error");
    	              }
              	  });
            });	
      }

      function startAutomaticLogOut(id){
    	  swal({
              title: "微信提醒", 
              text: "您是否要退出当前微信?", 
              type: "warning", 
              showCancelButton: true, 
              confirmButtonColor: "#DD6B55", 
              confirmButtonText: "是的,我要退出!", 
              closeOnConfirm: false 
            },
            function(){
               $.get("<?php echo url::s('index/wechat/startAutomaticLogOut',"id=");?>" + id, function(result){
              	 if(result.code == '200'){
    	            	swal("微信提示", result.msg, "success");
    	              	setTimeout(function(){location.href = '';},1000);
    	              }else{
    	            	swal("微信提示", result.msg, "error");
    	              }
              	  });
            });	
      }

      function login(id){
    	  swal({   title: "微信登录",   
              text: "您即将开始登录微信,是否继续?",   
              type: "info",   showCancelButton: true,   
              closeOnConfirm: false,   
              showLoaderOnConfirm: true,
              confirmButtonText: "立即登录"
               }, 
              function(){
              //开始请求微信登录
            	   $.get("<?php echo url::s('index/wechat/startAutomaticLogin',"id=");?>" + id, function(result){
                  	 if(result.code == '200'){
                   		    $('.showSweetAlert p').html(result.msg);
                     		login_listen(id);
        	              }else{
        	            	swal("微信登录", result.msg, "error");
        	             }
              		});
                  
         });
      }
      var listen_login = 0;
      var music = 0;
      //伪造线程
      function login_listen(id){
      	listen_login = setInterval(function(){ $.get("<?php echo url::s('index/wechat/getAutomaticStatus',"id=");?>" + id, function(result){
      		if(result.code > 0){	
      			if(result.code == '2' || result.code == '3' ){ $('.showSweetAlert p').html(result.msg); }
      			if(result.code == '7'){
				//将二维码展现出来扫码
      				$('.showSweetAlert h2').html('请使用微信扫一扫');
      				$('.showSweetAlert p').html("<img style='width:200px;height:200px;' src='data:image/png;base64," + result.data.img + "'/>");
      				if(music == 0){
      					play(['<?php echo FILE_CACHE . "/download/sound/微信扫一扫1.mp3";?>']);
      					music = 1;
          			}
              	}
      			if(result.code == '4'){
	            	swal("微信登录", result.msg, "success");
	              	setTimeout(function(){location.href = '';},1000);
                }
             }else{
            	 swal("微信登录", result.msg, "error");
            	 setTimeout(function(){location.href = '';},1000);
             }
      	  });  },1000);
      }

	  function del(id){
		  swal({   title: "微信提醒",   
              text: "请验证您的登录密码:",   
              type: "input",   showCancelButton: true,   
              closeOnConfirm: false,   
              animation: "slide-from-top",   
              inputPlaceholder: "会员登录密码",
              confirmButtonText: "确认删除" }, 
              function(inputValue){   
                  if (inputValue === false) return false;      
                  if (inputValue === "") {     
                  swal.showInputError("请输入您的登录密码!");     
                  return false   
                  }
             $.get("<?php echo url::s('index/wechat/automaticDelete',"id=");?>" + id + "&pwd=" + inputValue, function(result){
              	 if(result.code == '200'){
               		    swal("微信提醒", result.msg, "success");
    	              	setTimeout(function(){location.href = '';},1000);
    	              }else{
    	            	swal.showInputError(result.msg);     
    	             }
          		});
         });
		  $('.showSweetAlert input').attr('type','password');
	  }

	 //轮训测试
	  function robinTest(id){
		  swal({
		      title: "轮训通道测试",
		      text: "请输入要测试支付的金额<input type='text' id='amount' value='1.00'>"
		      +"请输入要接收异步通知的回调url<input type='text' id='callback_url'>",showCancelButton: true,   
		      html: true,
              confirmButtonText: "确认测试" , 
		      type: "prompt",
		  }, function(){

		       window.open('<?php echo url::s("index/wechat/robinTest");?>' + '?amount=' + $('#amount').val() + '&callback_url=' + $('#callback_url').val());
			   location.href='';
		      })
		 
		 $('.showSweetAlert fieldset input').attr('type','hidden');
		 $('#amount').val('1.00');
		 $('#callback_url').val('https://www.baidu.com');
	  }

	  function gatewayTest(id){
		  swal({
		      title: "单通道测试",
		      text: "请输入要测试支付的金额<input type='text' id='amount' value='1.00'>"
		      +"请输入要接收异步通知的回调url<input type='text' id='callback_url'>",showCancelButton: true,   
		      html: true,
              confirmButtonText: "确认测试" , 
		      type: "prompt",
		  }, function(){

		       window.open('<?php echo url::s("index/wechat/gatewayTest");?>' + '?amount=' + $('#amount').val()  + "&keyId=" + id + '&callback_url=' + $('#callback_url').val());
			   location.href='';
		      })
		 
		 $('.showSweetAlert fieldset input').attr('type','hidden');
		 $('#amount').val('1.00');
		 $('#callback_url').val('https://www.baidu.com');
	  }

		//微信设置
	  function setting(){ 
		  layer.open({
			  type: 2,
			  title: '微信配置',
			  shadeClose: true,
			  shade: 0.8,
			  area: ['600px', '400px'],
			  content: '<?php echo url::s('index/wechat/automaticConfig');?>' //iframe的url
			}); 
	  }

		//下载apk
	  function apk(){
		  swal({   title: "APK下载提醒",   
              text: "当前下载安卓软件环境包，包含XP框架（免root版），XP框架（root版），新睿支付v1.1（自动生成二维码必启动）",   
              type: "input",   showCancelButton: true,   
              closeOnConfirm: false,   
              animation: "slide-from-top",   
              inputPlaceholder: "请输入您的会员登录密码",
              confirmButtonText: "立即下载" }, 
              function(inputValue){   
                  if (inputValue === false) return false;      
                  if (inputValue === "") {     
                  swal.showInputError("请输入您的登录密码!");     
                  return false   
                  }
             $.get("<?php echo url::s('index/apk/verification',"pwd=");?>" + inputValue, function(result){
              	 if(result.code == '200'){
               		    swal("下载提醒", result.msg, "success");
               		    var url = "<?php echo url::s('index/apk/download',"pwd=");?>" + inputValue;
    	              	setTimeout(function(){location.href=url},1000);
    	              }else{
    	            	swal.showInputError(result.msg);     
    	             }
          		});
         });
		  $('.showSweetAlert input').attr('type','password');
	  }

		//下载pc
	  function pc(){
		  swal({   title: "软件下载提醒",   
              text: "由于云端数据处理庞大，以及服务器消耗都是巨大的，所以我们提供给客户自行挂机的辅助软件，当然，挂机版手续费相对云端版的手续费，会降低很多。",   
              type: "input",   showCancelButton: true,   
              closeOnConfirm: false,   
              animation: "slide-from-top",   
              inputPlaceholder: "请输入您的会员登录密码",
              confirmButtonText: "立即下载" }, 
              function(inputValue){   
                  if (inputValue === false) return false;      
                  if (inputValue === "") {     
                  swal.showInputError("请输入您的登录密码!");     
                  return false   
                  }
             $.get("<?php echo url::s('index/pc/verification',"pwd=");?>" + inputValue, function(result){
              	 if(result.code == '200'){
               		    swal("下载提醒", result.msg, "success");
               		    var url = "<?php echo url::s('index/pc/download',"pwd=");?>" + inputValue;
    	              	setTimeout(function(){window.open(url)},1000);
    	              }else{
    	            	swal.showInputError(result.msg);     
    	             }
          		});
         });
		  $('.showSweetAlert input').attr('type','password');
	  }
	  </script>
      <?php include_once (PATH_VIEW . 'common/footer.php');?>    
   