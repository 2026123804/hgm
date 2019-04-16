<?php include_once (PATH_VIEW . 'common/header.php'); $fix = DB_PREFIX;?>
            <!-- START CONTENT -->
            <section id="content">

                <!--start container-->
                <div class="container">

           
                    <!--chart dashboard end-->

                    <!-- //////////////////////////////////////////////////////////////////////////// -->

                    <!--card stats start-->
                    <div id="card-stats">
                        <div class="row">
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content  green white-text">
                                        <p class="card-stats-title"><i class="mdi-notification-adb"></i> 微信</p>
                                        <h4 class="card-stats-number">
                                        <?php
                                        $account = $mysql->select("select count(id) as count from {$fix}client_wechat_automatic_account where user_id={$_SESSION['MEMBER']['uid']}")[0];
                                        echo $account['count'];
                                        ?></h4>
                                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> <span class="green-text text-lighten-5">今日交易额：</span><?php 
                                        $nowTime = strtotime(date("Y-m-d",time()) . ' 00:00:00');
                                        $order = $mysql->select("select sum(amount) as money,count(id) as count from {$fix}client_wechat_automatic_orders where creation_time > {$nowTime} and status=4 and user_id={$_SESSION['MEMBER']['uid']}")[0];
                                        echo number_format($order['money'],3);
                                        ?> 
                                        </p>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content pink lighten-1 white-text">
                                        <p class="card-stats-title"><i class="mdi-maps-local-florist"></i> 支付宝</p>
                                        <h4 class="card-stats-number"> <?php
                                        $account = $mysql->select("select count(id) as count from {$fix}client_alipay_automatic_account where user_id={$_SESSION['MEMBER']['uid']}")[0];
                                        echo $account['count'];
                                        ?></h4>
                                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> <span class="deep-purple-text text-lighten-5">今日交易额：</span><?php 
                                        $nowTime = strtotime(date("Y-m-d",time()) . ' 00:00:00');
                                        $order = $mysql->select("select sum(amount) as money,count(id) as count from {$fix}client_alipay_automatic_orders where creation_time > {$nowTime} and status=4 and user_id={$_SESSION['MEMBER']['uid']}")[0];
                                        echo number_format($order['money'],3);
                                        ?>  
                                        </p>
                                    </div>
                                 
                                </div>
                            </div>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content blue-grey white-text">
                                        <p class="card-stats-title"><i class="mdi-action-trending-up"></i> 我的服务订单</p>
                                        <h4 class="card-stats-number"><?php 
                                        $nowTime = strtotime(date("Y-m-d",time()) . ' 00:00:00');
                                        $order = $mysql->select("select sum(amount) as money,count(id) as count from {$fix}service_order where creation_time > {$nowTime} and status=4 and user_id={$_SESSION['MEMBER']['uid']}")[0];
                                        echo $order['count'];
                                        ?></h4>
                                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> <span class="blue-grey-text text-lighten-5">今日盈利：</span><?php echo number_format($order['money'],3);?> 
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l3">
                                <div class="card">
                                    <div class="card-content purple white-text">
                                        <p class="card-stats-title"><i class="mdi-editor-attach-money"></i> 我的盈利</p>
                                        <h4 class="card-stats-number"><?php echo $_SESSION['MEMBER']['money'];?></h4>
                                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> <span class="purple-text text-lighten-5">总共提现金额：</span><?php 
                                        $order = $mysql->select("select sum(amount) as money from {$fix}client_withdraw where types=2 and user_id={$_SESSION['MEMBER']['uid']}")[0];
                                        echo $order['money'];
                                        ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--card stats end-->

                    <!-- //////////////////////////////////////////////////////////////////////////// -->

                    <!--card widgets start-->
                    <div id="card-widgets">
                        <div class="row">

                 

                           
                            <div class="col s12 m6 l4">
                                <div id="profile-card" class="card">
                                    <div class="card-image waves-effect waves-block waves-light">
                                        <img class="activator" src="<?php echo URL_VIEW;?>/static/images/user-bg.jpg">
                                    </div>
                                    <div class="card-content">
                                       
                                        <span class="card-title activator grey-text text-darken-4">联系客服</span>
                                        <p><i class="mdi-action-perm-identity cyan-text text-darken-2"></i> ＱＱ：<a href="http://wpa.qq.com/msgrd?v=3&uin=1300011208&site=qq&menu=yes" class="pypl-btn pypl-btn--white mpp-btn icon-panel__cta" target="_blank">1300011208</a>（小萝莉客服/请温柔对待）</p>
                                        <p><i class="mdi-action-perm-phone-msg cyan-text text-darken-2"></i> 电话：15270619680<span style="font-size:14px;">（紧急技术支持，只解决技术问题）</span></p>
                                        <p><i class="mdi-communication-email cyan-text text-darken-2"></i> 邮箱：1300011208@qq.com<span style="font-size:14px;">（问题反馈，如果有问题可提交至该邮箱）</span></p>

                                    </div>
                             
                                </div>
                            </div>
                            
                            <div class="col s12 m6 l4">
                                <div id="profile-card" class="card">
                                    <div class="card-image waves-effect waves-block waves-light" >
                                        <img class="activator" src="<?php echo URL_VIEW;?>/static/images/user-bg.jpg">
                                    </div>
                                    <div class="card-content">
                                       
                                        <span class="card-title activator grey-text text-darken-4">商户信息</span>
                                        <p style="color: green;"><i class="mdi-action-perm-identity cyan-text text-darken-2"></i> 商户ID：<?php echo $_SESSION['MEMBER']['uid'];?></p>
                                        <p><i class="mdi-image-remove-red-eye cyan-text text-darken-2"></i> S_KEY：<span id="skey" style="color: red;"><a href="#" onclick="skey();">已隐藏,点击查看</a></span></p>
                                        <script type="text/javascript">
										function skey(){
											$('#skey').text('<?php echo $_SESSION['MEMBER']['key_id'];?>');
											}
                                        </script>
                                        <p><i class="mdi-action-perm-phone-msg cyan-text text-darken-2"></i> 手机号：<?php echo $_SESSION['MEMBER']['phone'];?></p>

                                    </div>
                             
                                </div>
                            </div>
                            
                            
                            
                            
                            <div class="col s12 m6 l4">
                                <div id="profile-card" class="card">
                                    <div class="card-image waves-effect waves-block waves-light">
                                        <img class="activator" src="<?php echo URL_VIEW;?>/static/images/user-bg.jpg">
                                    </div>
                                    <div class="card-content">
                                       
                                        <span class="card-title activator grey-text text-darken-4">平台公告</span>
                                        <p><a href="" style="text-decoration:underline;">打造良好的互联网环境，请不要用于非法用途</a></p>
                                        <p><a href="" style="text-decoration:underline;">WHMCS6.0支付宝，微信免签约即时到账接口</a></p>
                                        <p><a href="" style="text-decoration:underline;">西部数码代理平台支付宝+微信免签约接口</a></p>

                                    </div>
                             
                                </div>
                            </div>
                            
                            

                        </div>

                    
                    <!--card widgets end-->

                    <!-- //////////////////////////////////////////////////////////////////////////// -->

                    <!--work collections start-->
                    <div id="work-collections">
                        <div class="row">
                            <div class="col s12 m12 l6">
                                <ul id="projects-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-device-now-widgets circle light-blue darken-2"></i>
                                        <span class="collection-header">我的服务订单</span>
                                        <p>My Service order</p>
                                
                                    </li>
                                    
                                    <?php foreach ($service_order as $order){?>
                                    
                                    <li class="collection-item">
                                        <div class="row">
                                            <div class="col s6">
                                                <p class="collections-title">交易金额：<span style="color:red;"><?php echo $order['amount'];?></span></p>
                                                <p class="collections-content">流水单号：<?php echo $order['trade_no'];?></p>
                                            </div>
                                            <div class="col s3">
                                            <?php 
                                            if ($order['status'] == 1) echo '<span class="task-cat cyan">未支付</span>';
                                            if ($order['status'] == 2) echo '<span class="task-cat cyan">未支付</span>';
                                            if ($order['status'] == 3) echo '<span class="task-cat grey darken-3">订单超时</span>';
                                            if ($order['status'] == 4) echo '<span class="task-cat green">已支付</span>';
                                            ?>  
                                            </div>
                                            <div class="col s3">
                                              创建时间：<?php echo date("Y/m/d H:i:s",$order['creation_time']);?><br>
                                              订单信息：<?php echo $order['out_trade_no'];?>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <?php }?>
                             
                                </ul>
                            </div>
                            <div class="col s12 m12 l6">
                                <ul id="issues-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-action-account-balance-wallet circle red darken-2"></i>
                                        <span class="collection-header">我的提现</span>
                                        <p>My withdrawal</p>
                                       
                                    </li>
                                    <?php foreach ($withdrawal as $with){?>
                                    <li class="collection-item">
                                        <div class="row">
                                            <div class="col s4">
                                                <p class="collections-title">提现金额：<span style="color:red;"><?php echo $with['amount'];?></span></p>
                                                <p class="collections-content">流水单号：<?php echo $with['flow_no'];?></p>
                                            </div>
                                            <div class="col s3">
                                              提现时间：<?php echo date("Y/m/d H:i:s",$with['apply_time']);?><br>
                                              订单信息：<?php echo $with['deal_time'] == 0 ? '银行处理中' : date("Y/m/d H:i:s",$with['deal_time']);?>
                                            </div>
                                            <div class="col s5" style="position:relative;right:-100px;">
                                                            
                                              提现状态：<?php 
                                              if ($with['types'] == 1) echo '<span style="color:#039be5;">银行正在处理</span>';
                                              if ($with['types'] == 2) echo '<span style="color:green;">已经到账</span>';
                                              if ($with['types'] == 3) echo '<span style="color:#bdbdbd;">银行驳回</span>';
                                              if ($with['types'] == 4) echo '<span style="color:red;">流水异常</span>';
                        ?><br>
                                              银行反馈：<?php echo $with['content'];?>                               
                                            </div>
                                        </div>
                                    </li>
                                    <?php }?>
                                  
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--work collections end-->

                </div>
                <!--end container-->
            </section>
            <!-- END CONTENT -->
	<?php include_once (PATH_VIEW . 'common/footer.php');?>    
    