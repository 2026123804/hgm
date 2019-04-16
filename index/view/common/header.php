<?php
use xh\library\url;
use xh\unity\cog;
use xh\library\model;
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
    <title><?php echo cog::web()['name'];?></title>
    <!-- CORE CSS-->    
    <link rel="icon" href="<?php echo URL_ROOT;?>/favicon.ico" />
    <link href="<?php echo URL_VIEW;?>/static/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo URL_VIEW;?>/static/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- Custome CSS-->    
    <link href="<?php echo URL_VIEW;?>/static/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="<?php echo URL_VIEW;?>/static/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo URL_VIEW;?>/static/js/plugins/jvectormap/jquery-jvectormap.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo URL_VIEW;?>/static/js/plugins/chartist-js/chartist.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="<?php echo URL_VIEW;?>/static/js/plugins/sweetalert/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
</head>
<body style="font-family:微软雅黑;">

    <div id="main">
        <!-- START WRAPPER -->
        <div class="wrapper">

            <!-- START LEFT SIDEBAR NAV-->
            <aside id="left-sidebar-nav">
                <ul id="slide-out" class="side-nav fixed leftside-navigation">
                <li class="user-details cyan darken-2">
                <div class="row">
                     <div class="col col s3 m3 l3">
                        <img style="height:50px;width:50px;margin-top:10px;" id="img_avatar" src="<?php echo strlen($_SESSION['MEMBER']['avatar']) < 2 ? URL_VIEW . '/static/images/avatar.png' : URL_VIEW . 'upload/avatar/' . $_SESSION['MEMBER']['uid'] . '/' . $_SESSION['MEMBER']['avatar'];?>" class="circle">
                    </div>
                    <div class="col col s9 m9 l9">
                        <ul id="profile-dropdown" class="dropdown-content">
                            <li><a href="#" onclick="edit();"><i class="mdi-action-settings"></i> 修改资料</a></li>
                            <li><a href="#" onclick="imgSelect();"><i class="mdi-action-perm-media"></i> 更换头像</a><input type="file" name="avatar" id="uploadavatar"  style="display:none;" onchange="uploadPic();"></li>
                            <li class="divider"></li>
                            <li><a href="#" onclick="pay();"><i class="mdi-action-payment"></i> 余额充值</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo url::s("index/member/logout");?>"><i class="mdi-hardware-keyboard-tab"></i> 安全注销</a>
                            </li>
                        </ul>
                        <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown"><?php echo $_SESSION['MEMBER']['username'];?> ( <span class="user-roal" style="color: #FFD700;"><b><?php echo $_SESSION['MEMBER']['group']['name'];?></b></span> )<i class="mdi-navigation-arrow-drop-down right"></i></a>
                        <p class="user-roal">余额：<b style="color: #b2ff59;"><?php echo $_SESSION['MEMBER']['balance'];?></b></p>
                    </div>
                </div>
                </li>
                <li class="bold active"><a href="<?php echo url::s('index/panel/home');?>" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> 仪表盘</a>
                </li>
               <?php if ((new model())->load("user", "group")->check('wechat_auto')){?>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-cyan active"><i class="mdi-action-view-carousel"></i> 微信</a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="<?php echo url::s('index/wechat/automatic');?>">公开版 v1.0</a></li>
                                    <li><a href="<?php echo url::s('index/wechat/automaticOrder');?>">支付订单</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>
        		<?php }?>
                <?php if ((new model())->load("user", "group")->check('alipay_auto')){?>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-cyan active"><i class="mdi-action-account-balance-wallet"></i> 支付宝</a>
                            <div class="collapsible-body">
                                <ul>
                                   
                                   <li><a href="<?php echo url::s('index/alipay/automatic');?>">公开版 v1.0</a></li>
                                   
                                   
                                    <li><a href=<?php echo url::s('index/alipay/automaticOrder');?>>支付订单</a>
                                    </li>
                                   
                                </ul>
                            </div>
                 		 </li>
                 	</ul>
                </li>
                <?php }?>
                
                <?php if ((new model())->load("user", "group")->check('service_auto')){?>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-cyan active"><i class="mdi-device-now-widgets"></i> 服务版</a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="<?php echo url::s('index/service/order');?>">我的订单</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php }?>
                
                <?php if ((new model())->load("user", "group")->check('shop')){?>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-cyan active"><i class="mdi-action-add-shopping-cart"></i> 商城</a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="<?php echo url::s('index/shop/index');?>">商品列表</a></li>
                                    <li><a href="<?php echo url::s('index/shop/order');?>">订单记录</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php }?>
                
               <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-cyan active"><i class="mdi-action-account-balance-wallet"></i> 个人中心</a>
                            <div class="collapsible-body">
                                <ul>
                                   
                                   <li><a href="<?php echo url::s('index/member/withdraw');?>">我的提现</a></li>
                                   <li><a href="<?php echo url::s('index/member/record');?>">收款记录</a></li>
                                </ul>
                            </div>
                 		 </li>
                 	</ul>
                </li>
                
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-cyan active"><i class="mdi-av-my-library-books"></i> 接口文档</a>
                            <div class="collapsible-body">
                                <ul>
                                   <li><a href="<?php echo url::s('index/doc/video');?>">视频教程</a></li>
                                   <li><a href="<?php echo url::s('index/doc/getQrcode');?>">扫码支付</a></li>
                                   <li><a href="<?php echo url::s('index/doc/sign');?>">签名算法</a></li>
                                   <li><a href="<?php echo url::s('index/doc/getOrder');?>">订单信息</a></li>
                                   <li><a href="<?php echo url::s('index/doc/orderStatus');?>">订单状态</a></li>
                                   <li><a href="<?php echo url::s('index/doc/callback');?>">异步通知</a></li>
                                </ul>
                            </div>
                 		 </li>
                 	</ul>
                </li>
     </ul>

                <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
            </aside>
            <!-- END LEFT SIDEBAR NAV-->

            <!-- //////////////////////////////////////////////////////////////////////////// -->
            
            
            <script>

            //选择头像
			function imgSelect(){
		        document.getElementById('uploadavatar').click(); 
				}

			//上传头像
			function uploadPic(){
			    var pic = $("#uploadavatar")[0].files[0];
			    var fd = new FormData();
			    fd.append('avatar', pic);
			    $.ajax({
			        url:"<?php echo url::s('index/member/avatarUpload');?>",
			        type:"post",
			        // Form数据
			        data: fd,
			        cache: false,
			        contentType: false,
			        processData: false,
			        success:function(data){
			            if(data.code == '200'){
			            	play(['<?php echo FILE_CACHE . "/download/sound/头像更改成功1.mp3";?>']);
			            	swal("操作提示", data.msg, "success");
			            	$('#img_avatar').attr('src','<?php echo URL_VIEW . '/upload/avatar/' . $_SESSION['MEMBER']['uid'] . '/';?>' + data.data.img);
			            }else{
			            	swal("操作提示", data.msg, "error");
			            }
			        }
			    });
			                    
			}
                        	  function edit(){
                        		  layer.open({
                        			  type: 2,
                        			  title: '修改资料',
                        			  shadeClose: true,
                        			  shade: 0.8,
                        			  area: ['500px', '550px'],
                        			  content: '<?php echo url::s('index/member/edit');?>' //iframe的url
                        			}); 
                        	  }

                        	  function pay(){
                        		  layer.open({
                        			  type: 2,
                        			  title: '充值',
                        			  shadeClose: true,
                        			  shade: 0.8,
                        			  area: ['500px', '340px'],
                        			  content: '<?php echo url::s('index/member/pay');?>' //iframe的url
                        			});
                            	  }
                            </script>