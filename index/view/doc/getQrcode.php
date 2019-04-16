<?php
use xh\library\url;
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
                <h5 class="breadcrumbs-title">接口文档</h5>
                <ol class="breadcrumbs">
                    <li><a href="<?php echo url::s('index/panel/home');?>">仪表盘</a></li>
                    <li><a href="#">文档</a></li>
                    <li class="active">发起支付请求</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
          <div class="section">
          
 <p class="caption">扫码支付API文档</p>
 
        <!--Striped Table-->
            <div class="divider"></div>
            <p><b style="font-size:16px;">接口URL：</b><span style="color:green;">https://payme.cn.com</span> 或节点接口：<span style="color:green;">https://pay.ht</span></p>

            <div id="striped-table">
              
              <div class="row">
             
                <div class="col s12 m12 l12">
                  <table class="striped">
                    <thead>
                      <tr>
                        <th>参数(POST)</th>
                        <th>说明</th>
                        <th>示例</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>account_id</td>
                        <td>商户ID、在平台首页右边获取商户ID</td>
                        <td>10000</td>
                      </tr>
                      <tr>
                        <td>content_type</td>
                        <td>请求过程中返回的网页类型，text或json</td>
                        <td>json</td>
                      </tr>
                      <tr>
                        <td>thoroughfare</td>
                        <td>初始化支付通道，目前通道：wechat_auto（公开版微信）、alipay_auto（公开版支付宝）、service_auto（服务版微信/支付宝）</td>
                        <td>wechat_auto</td>
                      </tr>
                      <tr>
                        <td>type</td>
                        <td>支付类型，该参数在服务版下有效（service_auto），其他可为空参数，微信：1，支付宝：2</td>
                        <td>1</td>
                      </tr>
                      <tr>
                        <td>out_trade_no</td>
                        <td>订单信息，在发起订单时附加的信息，如用户名，充值订单号等字段参数</td>
                        <td>2018062668945</td>
                      </tr>
                      
                      <tr>
                        <td>robin</td>
                        <td>轮训，2：开启轮训，1：进入单通道模式</td>
                        <td>2</td>
                      </tr>
                      
                      <tr>
                        <td>keyId</td>
                        <td>设备KEY，在公开版列表里面Important参数下的DEVICE Key一项，如果该请求为轮训模式，则本参数无效，本参数为单通道模式</td>
                        <td>785D239777C4DE7739</td>
                      </tr>
                      
                      <tr>
                        <td>amount</td>
                        <td>支付金额，在发起时用户填写的支付金额</td>
                        <td>1.00</td>
                      </tr>
                      
                      
                      <tr>
                        <td>callback_url</td>
                        <td>异步通知地址，在支付完成时，本平台服务器系统会自动向该地址发起一条支付成功的回调请求</td>
                        <td>https://www.codesceo.com/callback_url/pay.do</td>
                      </tr>
                      
                      
                      <tr>
                        <td>success_url</td>
                        <td>支付成功后网页自动跳转地址，仅在网页类型为text下有效，json会将该参数返回</td>
                        <td>https://www.codesceo.com/index/doc/getQrcode.do</td>
                      </tr>
                      
                      
                      <tr>
                        <td>error_url</td>
                        <td>支付失败时，或支付超时后网页自动跳转地址，仅在网页类型为text下有效，json会将该参数返回</td>
                        <td>https://www.codesceo.com/index/doc/getQrcode.do</td>
                      </tr>
                      
                      <tr>
                        <td>sign</td>
                        <td>签名算法，在支付时进行签名算法，详见<a href="<?php echo url::s("index/doc/sign");?>">《新睿支付签名算法》</a></td>
                        <td>d92eff67b3be05f5e61502e96278d01b</td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!--Hoverable Table-->

 

          </div>


        </div>
        <!--end container-->
        
      </section>
      <!-- END CONTENT -->
      
      
       <!--start container-->
        <div class="container">
          <div class="section">

         

            <div class="divider"></div>

            <!--Input fields-->
            <div id="input-fields">
              <h4 class="header">在线接口调试（<a href="<?php echo URL_ROOT . '/download/demo.zip';?>" target="_blank">DEMO下载</a>）</h4>
              <div class="row">
            
                <div class="col s12 m12 l12">
                  <div class="row">
                    <form class="col s12" action="https://payme.cn.com" method="post" target="_blank">
                      <div class="row">
                        <div class="input-field col s12">
                          <input type="text" class="validate" name="account_id" value="<?php echo $_SESSION['MEMBER']['uid'];?>">
                          <label>account_id</label>
                        </div>
        		
        		<div  id="input-select">
                  <div class="input-field col s12">
                    <label>content_type</label>
                    <select name="content_type">
                      <option value="" disabled selected>请选择网页类型</option>
                      <option value="text">text</option>
                      <option value="json">json</option>
                    </select>
                  </div>
                  
                
                  <div class="input-field col s12">
                    <label>thoroughfare</label>
                    <select name="thoroughfare">
                      <option value="" disabled selected>请选择支付通道</option>
                      <option value="service_auto">service_auto</option>
                      <option value="wechat_auto">wechat_auto</option>
                      <option value="alipay_auto">alipay_auto</option>
                    </select>
                  </div>
                  
                  <div class="input-field col s12">
                    <label>type</label>
                    <select name="type">
                      <option value="" disabled selected>请选择支付类型</option>
                      <option value="">不填写</option>
                      <option value="1">微信</option>
                      <option value="2">支付宝</option>
                    </select>
                  </div>
                  
                  <div class="input-field col s12">
                    <label>robin</label>
                    <select name="robin">
                      <option value="" disabled selected>请选择通道技术</option>
                      <option value="2">轮训</option>
                      <option value="1">单通道</option>
                    </select>
                  </div>
                  
                  
                  </div>
                  
                  <div class="input-field col s12">
                          <input type="text" class="validate" name="out_trade_no" value="test<?php echo mt_rand(10000,99999);?>">
                          <label>out_trade_no</label>
                        </div>
                        
                        
                         <div class="input-field col s12">
                          <input type="text" class="validate" name="keyId" value="" placeholder="如果为轮训,这里为空,该参数是单通道使用">
                          <label>keyId</label>
                        </div>
                        
                        
                         <div class="input-field col s12">
                          <input type="text" class="validate" name="amount" value="1.00">
                          <label>amount</label>
                        </div>
                        
                         <div class="input-field col s12">
                          <input type="text" class="validate" name="callback_url" value="https://www.codesceo.com/callback_url/pay.do">
                          <label>callback_url</label>
                        </div>
                        
                        <div class="input-field col s12">
                          <input type="text" class="validate" name="success_url" value="https://www.codesceo.com/index/doc/getQrcode.do">
                          <label>success_url</label>
                        </div>
                        
                        <div class="input-field col s12">
                          <input type="text" class="validate" name="error_url" value="https://www.codesceo.com/index/doc/getQrcode.do">
                          <label>error_url</label>
                        </div>
                        
                        <div class="input-field col s12">
                          <input type="text" class="validate" name="sign" value="" placeholder="签名算法,请自行通过签名算法加密后得出结果填写至此处..">
                          <label>sign</label>
                        </div>
                        
             
                      </div>
                     
                      <div class="row">
                        <div class="input-field col s12">
                          <input type="submit" class="btn waves-effect waves-light teal" value="开始测试">
                        </div>
                      </div>
                    
                    </form>
                  </div>
                </div>
              </div>
            </div>
</div></div>
            <div class="divider"></div>

            <!--Prefilling Text Inputs-->
      <script type="text/javascript">

      function reissue(id){
    	  swal({   title: "订单通知",   
              text: "手动补发也是需要扣除手续费,您是否要继续?",   
              type: "info",   showCancelButton: true,   
              closeOnConfirm: false,   
              showLoaderOnConfirm: true,
              confirmButtonText: "是的,我愿意承担手续费!"
               }, 
              function(){
              //开始请求微信登录
            	   $.get("<?php echo url::s('index/wechat/automaticReissue',"id=");?>" + id, function(result){
                  	 if(result.code == '200'){
                   			swal("微信提示", result.msg, "success");
    	              		setTimeout(function(){location.href = '';},1000);
        	              }else{
        	            	swal("订单通知", result.msg, "error");
        	             }
              		});
                  
         });
      }

      function trade_no(obj){
          location.href = "<?php echo url::s('index/wechat/automaticOrder',"sorting=trade_no&code=");?>" + $(obj).val();
          }

      function wechat(){
          var wechat = $('#wechat').val();
          console.log(wechat);
          location.href = "<?php echo url::s('index/wechat/automaticOrder',"sorting=wechat&code=");?>" + wechat;
          
          }

     
	  </script>
      <?php include_once (PATH_VIEW . 'common/footer.php');?>    
   