<?php 
use xh\library\url;
use xh\unity\cog;
use xh\unity\userCog;
include_once (PATH_VIEW . 'common/header.php'); //头部
include_once (PATH_VIEW . 'common/nav.php'); //导航
?>

<!-- START CONTENT -->
<div class="content">

  <!-- Start Page Header -->
  <div class="page-header">
   
      <ol class="breadcrumb">
        <li><a href="<?php echo url::s('admin/index/home');?>">控制台</a></li>
        <li class="active">服务端</li>
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
          服务端
          <ul class="panel-tools">
            <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
          </ul>
        </div>

            <div class="panel-body">
              <form class="form-horizontal" id="from">
              
               <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">服务端通讯密钥</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control form-control-line" name="key" placeholder="服务端连接WEB通讯密钥" value="<?php echo cog::read('server')['key'];?>">
                  </div>
                </div>
                
                <div class="form-group has-success">
                  <label class="col-sm-2 control-label form-label">接受异常手机</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control form-control-line" name="service_phone" placeholder="接受服务账号异常手机号" value="<?php echo cog::read('server')['service_phone'];?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label form-label">服务通道轮训规则</label>
                  <div class="col-sm-10">
                   <select class="selectpicker" name="serviceConfig">
                      <optgroup label="请选择轮训规则">
                        <option value="1" <?php if (userCog::read('serviceConfig', 0)['robin'] == 1) echo 'selected';?>>随机通道 [ v1.0 ] - 推荐</option>
                        <option value="2" <?php if (userCog::read('serviceConfig', 0)['robin'] == 2) echo 'selected';?>>实时收款 [ v1.0 ] - 按照少到多排序</option>
                        <option value="3" <?php if (userCog::read('serviceConfig', 0)['robin'] == 3) echo 'selected';?>>顺序模式 [ v1.0 ] - 自动顺序选择 - 不推荐</option>
                      </optgroup>
                 
                    </select>                             
                  </div>
                </div>

      
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
			          url: "<?php echo url::s('admin/server/result');?>",
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