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
                <h5 class="breadcrumbs-title">使用教程</h5>
                <ol class="breadcrumbs">
                    <li><a href="<?php echo url::s('index/panel/home');?>">仪表盘</a></li>
                    <li><a href="#">文档</a></li>
                    <li class="active">安装APK</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
          <div class="section">
          
 <p class="caption">APK在线视频安装教学</p>
 
        <!--Striped Table-->
            <div class="divider"></div>
            

            <div id="striped-table">
              
              <div class="row">
             
                <div class="col s12 m12 l12">
                  <video width="100%" height="960" controls="controls">
  <source src="https://gss3.baidu.com/6LZ0ej3k1Qd3ote6lo7D0j9wehsv/tieba-smallvideo-transcode/244425142_3aa8d0d53da319941c0af8c877439095_616be3b7448f_1.mp4" type="video/mp4" />
  <object data="https://gss3.baidu.com/6LZ0ej3k1Qd3ote6lo7D0j9wehsv/tieba-smallvideo-transcode/244425142_3aa8d0d53da319941c0af8c877439095_616be3b7448f_1.mp4" width="100%" height="960">
  </object>
</video>
                </div>
              </div>
            </div>

            <!--Hoverable Table-->

 

          </div>


        </div>
        <!--end container-->
        
      </section>


      <?php include_once (PATH_VIEW . 'common/footer.php');?>    
   