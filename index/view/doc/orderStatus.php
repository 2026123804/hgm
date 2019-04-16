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
                    <li class="active">查询订单状态</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
          <div class="section">
          
 <p class="caption">查询订单状态API文档</p>
 
        <!--Striped Table-->
            <div class="divider"></div>
            <p><b style="font-size:14px;">微信状态查询接口URL（公开版v1.0）：</b><span style="color:green;">https://payme.cn.com/gateway/pay/automaticWechatQuery.do</span> 或节点接口：<span style="color:green;">https://pay.ht/gateway/pay/automaticWechatQuery.do</span></p>
 <div class="divider"></div>
 <p><b style="font-size:14px;">支付宝状态查询接口URL（公开版v1.0）：</b><span style="color:green;">https://payme.cn.com/gateway/pay/automaticAlipayQuery.do</span> 或节点接口：<span style="color:green;">https://pay.ht/gateway/pay/automaticAlipayQuery.do</span></p>
 <div class="divider"></div>
 <p><b style="font-size:14px;">服务版状态查询接口URL（服务版v1.0）：</b><span style="color:green;">https://payme.cn.com/gateway/pay/serviceQuery.do</span> 或节点接口：<span style="color:green;">https://pay.ht/gateway/pay/serviceQuery.do</span></p>
            <div id="striped-table">
              
              <div class="row">
             
                <div class="col s12 m12 l12">
                  <table class="striped">
                    <thead>
                      <tr>
                        <th>参数(GET)</th>
                        <th>说明</th>
                        <th>示例</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>id</td>
                        <td>订单ID、在创建订单时会返回订单ID号、注意，只有json网页类型下才会有返回值</td>
                        <td>10000</td>
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
              <h4 class="header">在线接口调试</h4>
              <div class="row">
            
                <div class="col s12 m12 l12">
                  <div class="row">
                    <form class="col s12" id="ls_url" action="https://payme.cn.com/gateway/pay/automaticWechatQuery.do" method="get" target="_blank">
                      <div class="row">
        
        		<div  id="input-select">
                  <div class="input-field col s12">
                    <label>接口类型</label>
                    <select onchange="ls(this);">
                      <option value="" disabled>请选择接口</option>
                      <option value="https://payme.cn.com/gateway/pay/automaticWechatQuery.do" selected>微信公开版：https://payme.cn.com/gateway/pay/automaticWechatQuery.do</option>
                      <option value="https://payme.cn.com/gateway/pay/automaticAlipayQuery.do">支付宝公开版：https://payme.cn.com/gateway/pay/automaticAlipayQuery.do</option>
                      <option value="https://payme.cn.com/gateway/pay/serviceQuery.do">服务版（v1.0）：https://payme.cn.com/gateway/pay/serviceQuery.do</option>
                    </select>
                  </div>
                  </div>
                  
                   <div class="input-field col s12">
                          <input type="text" class="validate" name="id" value="" placeholder="订单ID">
                          <label>id</label>
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



      function ls(obj){
          $('#ls_url').attr('action',$(obj).val());
          }


     
	  </script>
      <?php include_once (PATH_VIEW . 'common/footer.php');?>    
   