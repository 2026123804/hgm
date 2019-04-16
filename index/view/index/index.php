<?php
use xh\library\url;
?>
<!doctype html>
<html class="no-js" lang="zh_cn">
		<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">

		<!-- Page Title Here -->
		<title>新睿支付 - 3秒钟部署你的快捷支付通道!</title>
		<meta name="keywords" content="免签约支付,QQ支付,微信支付,支付宝支付,京东支付,免签约,支付宝免签约,微信免签约,QQ免签约,京东免签约">
		<meta name="description" content="新睿支付,立刻拥有第三方免签约支付接口!">
		<meta name="application-name" content="新睿支付">
		
		<!-- Disable screen scaling-->
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, user-scalable=0">
		<link rel="icon" href="<?php echo URL_ROOT;?>/favicon.ico" />
		<!-- Initializer -->
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>normalize.css">

		<!-- Web fonts and Web Icons -->
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>pageloader.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/fonts/'?>opensans/stylesheet.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/fonts/'?>asap/stylesheet.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>ionicons.min.css">

		<!-- Vendor CSS style -->
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>foundation.min.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/js/'?>vendor/jquery.fullPage.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/js/'?>vegas/vegas.min.css">

		<!-- Main CSS files -->
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>main.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>main_responsive.css">
		<link rel="stylesheet" href="<?php echo URL_VIEW . '/index/index/css/'?>style-color1.css">
		<script src="<?php echo URL_VIEW . '/index/index/js/'?>vendor/modernizr-2.7.1.min.js"></script>
		<style>
#jp_container_1 { position: fixed; top: 5%; left: 2% }
#jp_container_1 a { font-size: 26px; color: #ffffff }
#jp_container_1 a:hover { color: #f4645f }
</style>
		</head>
		<body id="menu" class="alt-bg">
<!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]--> 

<!-- Page Loader -->
<div class="page-loader" id="page-loader">
          <div><i class="ion ion-loading-d"></i>
    <p>新睿值得期待...</p>
  </div>
        </div>

<!-- BEGIN OF site cover -->
<div class="page-cover" id="s-cover"> 
          <!-- Cover Background -->
          <div class="cover-bg pos-abs full-size bg-img" data-image-src="<?php echo URL_VIEW . '/index/index/img/'?>bg-slide3.jpg"></div>
          
          <!-- BEGIN OF Slideshow Background -->
          <div class="cover-bg pos-abs full-size slide-show"> <i class='img' data-src='./<?php echo URL_VIEW . '/index/index/img/'?>bg-slide3.jpg'></i> <i class='img' data-src='./<?php echo URL_VIEW . '/index/index/img/'?>bg-slide2.jpg'></i> <i class='img' data-src='./<?php echo URL_VIEW . '/index/index/img/'?>bg-slide1.jpg'></i> </div>
          <!-- END OF Slideshow Background -->
          
          <div class="cover-bg-mask pos-abs full-size bg-color" data-bgcolor="rgba(0, 0, 0, 0.41)"></div>
        </div>
<!--END OF site Cover --> 

<!-- Begin of timer pane -->
<div class="pane-when " id="s-when">
          <div class="content"> 
    <!-- Clock -->
    <div class="clock clock-countdown">
              <div class="site-config"
						 data-date="1/15/2018 14:00:00" 
						 data-date-timezone="+8"
						 ></div>
              <div class="elem-center">
        <div class="digit">JIKE</div>
      </div>

            </div>

  </div>
        </div>
<!-- End of timer pane --> 

<!-- BEGIN OF site main content content here -->
<main class="page-main" id="mainpage"> 
          
          <!-- Begin of home page -->
          <div class="section page-home page page-cent" id="s-home"> 
    
    <!-- Content -->
    <section class="content">
              <header class="header">
        <div class="h-left">
                  <h2><strong>免签约</strong></h2>
                </div>
        <div class="h-right">
                  <h3>微信收款<br>
           支付宝收款</h3>
                  <h4 class="subhead"><a href="<?php echo url::s("index/user/register");?>">免费注册</a></h4>
                  <h4>QQ:10373458</h4>
                </div>
      </header>
            </section>
    
    <!-- Scroll down button -->
    <footer class="p-footer p-scrolldown"> <a href="<?php echo url::s("index/user/login");?>">
      <div class="arrow-d">
       <span style=" position: relative;top:12px;">登录</span>
      </div>
      </a> </footer>
  </div>
          <!-- End of home page --> 
          

          <!-- End of register page --> 
          

          <!-- End of about us page --> 
          

          <!-- End of Contact page  --> 
          
        </main>
<script src="<?php echo URL_STATIC . 'js/jquery.min.js'?>"></script>
<!-- All vendor scripts --> 
<script src="<?php echo URL_VIEW . '/index/index/js/'?>vendor/all.js"></script> 

<!-- Downcount JS --> 
<script src="<?php echo URL_VIEW . '/index/index/js/'?>jquery.downCount.js"></script> 

<!-- Form script --> 
<script src="<?php echo URL_VIEW . '/index/index/js/'?>form_script.js"></script> 

<!-- Javascript main files --> 
<script src="<?php echo URL_VIEW . '/index/index/js/'?>main.js"></script> 
<script type="text/javascript" src="<?php echo URL_VIEW . '/index/index/js/'?>/jquery.jplayer.min.js"></script> 
<script type="text/javascript">
 $(document).ready(function(){
  $("#jquery_jplayer_1").jPlayer({
   ready: function () {
    $(this).jPlayer("setMedia", {
     mp3: "https://download.ur.ci/download/jike.mp3",
    });
   },
   ended: function() { // The $.jPlayer.event.ended event
    $(this).jPlayer("play"); // Repeat the media
  },
   swfPath: "<?php echo URL_VIEW . '/index/index/js/'?>/js",
   supplied: "mp3"
  });
 });
</script>
<div id="jquery_jplayer_1"></div>
<div id="jp_container_1"> <a href="#" class="jp-play">
  <li class="ion-music-note" data-pack="default" data-tags="songs"></li>
  </a> <a href="#" class="jp-pause">
  <li class="ion-headphone" data-pack="default" data-tags="music, earbuds, beats"></li>
  </a> </div>
</body>
</html>
