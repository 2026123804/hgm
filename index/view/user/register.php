<?php
use xh\unity\cog;
use xh\library\url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="description" content="<?php echo cog::web()['description'];?>">
  <meta name="keywords" content="<?php echo cog::web()['keywords'];?>">
  <title>注册账号 | <?php echo cog::web()['name'];?></title>
  <!-- CORE CSS-->
  <link href="<?php echo URL_VIEW;?>/static/css/materialize.min.css" type="text/css" rel="stylesheet">
  <link href="<?php echo URL_VIEW;?>/static/css/style.min.css" type="text/css" rel="stylesheet">
    <!-- Custome CSS-->    
  <link href="<?php echo URL_VIEW;?>/static/css/custom/custom.min.css" type="text/css" rel="stylesheet">
  <link href="<?php echo URL_VIEW;?>/static/css/layouts/page-center.css" type="text/css" rel="stylesheet">
  <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
  <link href="<?php echo URL_VIEW;?>/static/js/plugins/prism/prism.css" type="text/css" rel="stylesheet">
  <link href="<?php echo URL_VIEW;?>/static/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet">
  
  <link href="<?php echo URL_VIEW;?>/static/js/plugins/sweetalert/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link rel="icon" href="<?php echo URL_ROOT;?>/favicon.ico" />
</head>

<body class="cyan">
  <!-- Start Page Loading -->
  <div id="loader-wrapper">
      <div id="loader"></div>        
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div>
  <!-- End Page Loading -->



  <div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel"  style="border-radius:8px;">
      <form class="login-form" id="from">
        <div class="row">
          <div class="input-field col s12 center">
            <h4>注册账号</h4>
            <p class="center" style="font-size: 8px;">已经注册过?<a href="<?php echo url::s("index/user/login");?>">立即登录</a></p>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person-outline prefix"></i>
            <input id="username" name="username" type="text">
            <label for="username" class="center-align">设置会员名</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password" name="pwd" type="password">
            <label for="password">设置你的登录密码</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock-outline prefix"></i>
            <input id="password-again" name="pwd_repeat" type="password">
            <label for="password-again">请再次输入你的登录密码</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-hardware-phone-android prefix"></i>
            <input id="text" type="text" name="phone" id="phone-code">
            <label for="text" class="center-align">请输入手机号码</label>
          </div>
          
          
        </div>
        <?php if (cog::read('registerCog')['scale_open'] == 1){?>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-thumb-up prefix"></i>
            <input id="text" type="text" name="recommend_username">
            <label for="text" class="center-align">推荐人会员名</label>
          </div>
        </div>
        <?php }?>
        <div class="row">
          <div class="input-field col s12">
            <a href="#" onclick="register_check()" class="btn waves-effect waves-light col s12" style="border-radius:5px;">同意条款并注册</a>
          </div>
          <div class="input-field col s12">
            <p class="margin center medium-small sign-up"> 
                      <input type="checkbox" name="provision" value="1" class="filled-in" id="filled-in-box" checked="checked" />
                      <label for="filled-in-box"></label>
                   	  <a href="">《新睿支付网站服务条款》</a>
           </p>
          </div>
        </div>
      </form>
    </div>
  </div>



  <!-- ================================================
    Scripts
    ================================================ -->
  <script>
 
  function register_check(){
	  $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo url::s('index/user/registerCheck');?>",
          data: $('#from').serialize(),
          success: function (data) {
              console.log(data);
              if(data.code == '200'){
            	  //swal("注册提示", data.msg, "success");
            	  location.href="<?php echo url::s('index/user/phoneCheck');?>";
              }else{
                  if(data.code == '-18'){
                	  play(['<?php echo FILE_CACHE . '/download/sound/会员名过短1.mp3';?>']);
                  }
                  if(data.code == '-19'){
                	  play(['<?php echo FILE_CACHE . '/download/sound/用户名重复2.mp3';?>','<?php echo FILE_CACHE . '/download/sound/用户名重复1.mp3';?>']);
                  }
                  if(data.code == '-23'){
                	  play(['<?php echo FILE_CACHE . '/download/sound/六位密码1.mp3';?>']);
                  }
                  if(data.code == '-20'){
                	  play(['<?php echo FILE_CACHE . '/download/sound/第二次密码错误1.mp3';?>']);
                  }
                  if(data.code == '-21'){
                    	  play(['<?php echo FILE_CACHE . '/download/sound/手机号错误1.mp3';?>']);
                  }
              	  if(data.code == '-22'){
              	  	  play(['<?php echo FILE_CACHE . '/download/sound/手机号已注册1.mp3';?>']);
              	  }
               
            	  swal("注册提示", data.msg, "error");
              }
          },
          error: function(data) {
              alert("error:"+data.responseText);
           }
  });
  }
  </script>

  <!-- jQuery Library -->
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/plugins/jquery-1.11.2.min.js"></script>
  <!--materialize js-->
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/materialize.min.js"></script>
  <!--prism-->
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/plugins/prism/prism.js"></script>
  <!--scrollbar-->
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <!--sweetalert -->
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/plugins/sweetalert/sweetalert.min.js"></script>   
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/plugins.min.js"></script>
  <!--custom-script.js - Add your own theme custom JS-->
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/custom-script.js"></script>
  <script type="text/javascript" src="<?php echo URL_VIEW;?>/static/js/plugins/formatter/jquery.formatter.min.js"></script>   
  <script type="text/javascript" src ="<?php echo URL_STATIC . '/js/jike.js'?>"></script>

</body>
</html>