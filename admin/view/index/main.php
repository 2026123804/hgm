<?php 
include_once (PATH_VIEW . 'common/header.php'); //头部
include_once (PATH_VIEW . 'common/nav.php'); //导航
?>

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- START CONTENT -->
<div class="content">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Dashboard</h1>
      <ol class="breadcrumb">
        <li class="active">This is a quick overview of some features</li>
    </ol>

    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="index.html" class="btn btn-light">Dashboard</a>
        <a href="#" class="btn btn-light"><i class="fa fa-refresh"></i></a>
        <a href="#" class="btn btn-light"><i class="fa fa-search"></i></a>
        <a href="#" class="btn btn-light" id="topstats"><i class="fa fa-line-chart"></i></a>
      </div>
    </div>
    <!-- End Page Header Right Div -->

  </div>
  <!-- End Page Header -->


 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- START CONTAINER -->
<div class="container-widget">

  <!-- Start Top Stats -->
  <div class="col-md-12">
  <ul class="topstats clearfix">
    <li class="arrow"></li>
    <li class="col-xs-6 col-lg-2">
      <span class="title"><i class="fa fa-dot-circle-o"></i> Today Profit</span>
      <h3>$36.45</h3>
      <span class="diff"><b class="color-down"><i class="fa fa-caret-down"></i> 26%</b> from yesterday</span>
    </li>
    <li class="col-xs-6 col-lg-2">
      <span class="title"><i class="fa fa-calendar-o"></i> This Week</span>
      <h3>$96.25</h3>
      <span class="diff"><b class="color-up"><i class="fa fa-caret-up"></i> 26%</b> from last week</span>
    </li>
    <li class="col-xs-6 col-lg-2">
      <span class="title"><i class="fa fa-shopping-cart"></i> Total Sales</span>
      <h3 class="color-up">696</h3>
      <span class="diff"><b class="color-up"><i class="fa fa-caret-up"></i> 26%</b> from last month</span>
    </li>
    <li class="col-xs-6 col-lg-2">
      <span class="title"><i class="fa fa-users"></i> Visitors</span>
      <h3>960</h3>
      <span class="diff"><b class="color-down"><i class="fa fa-caret-down"></i> 26%</b> from yesterday</span>
    </li>
    <li class="col-xs-6 col-lg-2">
      <span class="title"><i class="fa fa-eye"></i> Page View</span>
      <h3 class="color-up">46.230</h3>
      <span class="diff"><b class="color-down"><i class="fa fa-caret-down"></i> 26%</b> from yesterday</span>
    </li>
    <li class="col-xs-6 col-lg-2">
      <span class="title"><i class="fa fa-clock-o"></i> Avarage Time</span>
      <h3 class="color-down">2:10<small>min</small></h3>
      <span class="diff"><b class="color-up"><i class="fa fa-caret-up"></i> 26%</b> from last week</span>
    </li>
  </ul>
  </div>
  <!-- End Top Stats -->


  <!-- Start First Row -->
  <div class="row">

    <!-- Start Chart Daily -->
    <div class="col-md-12 col-lg-7">
      <div class=" panel-widget widget chart-with-stats clearfix" style="height:450px;">

        <div class="col-sm-12" style="height:450px;">
          <h4 class="title">TODAY SALES<small>Last update: 1 Hours ago</small></h4>
          <div class="top-label"><h2>11.291</h2><h4>Today Total</h4></div>
          <div class="bigchart" id="todaysales"></div>
        </div>
        <div class="right" style="height:450px;">
          <h4 class="title">PAGE VIEW</h4>
          <!-- start stats -->
          <ul class="widget-inline-list clearfix">
            <li class="col-12"><span>962</span>Themeforest<i class="chart sparkline-green"></i></li>
            <li class="col-12"><span>367</span>Codecanyon<i class="chart sparkline-blue"></i></li>
            <li class="col-12"><span>92</span>Photodune<i class="chart sparkline-red"></i></li>
          </ul>
          <!-- end stats -->
        </div>


      </div>
    </div>
    <!-- End Chart Daily -->


    <!-- Start Files -->
    <div class="col-md-12 col-lg-5">
      <div class="panel panel-widget" style="height:450px;">
        <div class="panel-title">
          My Files <span class="label label-danger">29</span>
          <ul class="panel-tools">
            <li><a class="icon"><i class="fa fa-refresh"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>
        <div class="panel-body table-responsive">

          <table class="table table-dic table-hover ">
            <tbody>
              <tr>
                <td><i class="fa fa-folder-o"></i>Projects</td>
                <td>Folder</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
              <tr>
                <td><i class="fa fa-file-archive-o"></i>Backup</td>
                <td>Zip</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
              <tr>
                <td><i class="fa fa-file-code-o"></i>Kode Theme</td>
                <td>Html</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
              <tr>
                <td><i class="fa fa-file-pdf-o"></i>Documents</td>
                <td>Pdf</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
              <tr>
                <td><i class="fa fa-folder-o"></i>Themes</td>
                <td>Folder</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
              <tr>
                <td><i class="fa fa-folder-o"></i>Uploaded Files</td>
                <td>Folder</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
              <tr>
                <td><i class="fa fa-folder-o"></i>Personal Files</td>
                <td>Folder</td>
                <td class="text-r">27/2/2015 12:34 AM</td>
              </tr>
            </tbody>
          </table>          

        </div>
      </div>
    </div>
    <!-- End Files -->

  </div>  
  <!-- End First Row -->


  <!-- Start Second Row -->
  <div class="row">



    <!-- Start Today Activity -->
    <div class="col-md-12 col-lg-3">
      <div class="panel panel-widget" style="height:380px;">
        <div class="panel-title">
          TODAY ACTIVITY <span class="label label-success">9</span>
          <ul class="panel-tools panel-tools-hover">
            <li><a class="icon"><i class="fa fa-refresh"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>
        <div class="panel-body">

          <ul class="widget-inline-list clearfix">
            <li class="col-4"><span>1:52:22</span>Active Time</li>
            <li class="col-4"><span>60%</span>Completed</li>
            <li class="col-4"><span>0:11:46</span>Break Time</li>
          </ul>

          <div id="todayactivity" class="chart-on-bottom"></div>

        </div>
      </div>
    </div>
    <!-- End Today Activity -->

    <!-- Start Server Status -->
    <div class="col-md-12 col-lg-6">
      <div class="panel panel-widget" style="height:380px;">
        <div class="panel-title">
          SERVER STATUS <span class="label label-default">196</span>
          <ul class="panel-tools panel-tools-hover">
            <li><a class="icon"><i class="fa fa-refresh"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>
        <div class="panel-body">

          <ul class="widget-inline-list clearfix">
            <li class="col-3 color10"><span>28.9GB</span>Total Usage</li>
            <li class="col-3"><span>92%</span>Space Left</li>
            <li class="col-3 color7"><span>22%</span>CPU</li>
            <li class="col-3"><span>512MB</span>Total RAM</li>
          </ul>

          <div id="realtime" class="flotchart-placeholder" style="height:190px;"></div>

        </div>
      </div>
    </div>
    <!-- End Server Status -->

    <!-- Start Profile Widget -->
    <div class="col-md-12 col-lg-3">
      <div class="widget profile-widget" style="height:380px;">
        <img src="<?php echo URL_VIEW;?>/static/console/img/profileimg.png" class="profile-image" alt="img">
        <h1>Jonathan Doe</h1>
        <p><i class="fa fa-map-marker"></i> London</p>
        <a href="#" class="btn btn-sm">Follow</a>
        <ul class="stats widget-inline-list clearfix">
          <li class="col-4"><span>2.109</span>Followers</li>
          <li class="col-4"><span>596</span>Photos</li>
          <li class="col-4"><span>902</span>Like</li>
        </ul>
      </div>
    </div>
    <!-- End Profile Widget -->


  </div>
  <!-- End Second Row -->


  <!-- Start Third Row -->
  <div class="row">


    <!-- Start General Stats -->
    <div class="col-md-12 col-lg-6">
      <div class="panel panel-widget" style="height:205px;">
        <div class="panel-title">
          General Stats
        </div>
        <div class="panel-body">

          <div class="easypie margin-b-50" data-percent="82"><span>82%</span>New Visit</div>
          <div class="easypie margin-b-50" data-percent="30"><span>30%</span>Order</div>
          <div class="easypie margin-b-50 margin-b-40" data-percent="62"><span>62%</span>Page View</div>
          <div class="easypie margin-b-50" data-percent="15"><span>15%</span>Client</div>
          <div class="easypie margin-b-50" data-percent="45"><span>45%</span>Storage</div>
          <div class="easypie margin-b-50" data-percent="75"><span>76%</span>Comments</div>

        </div>
      </div>
    </div>
    <!-- End General Stats -->

    <!-- Start TwitterBox -->
    <div class="col-md-6 col-lg-3">
      <div class="widget socialbox" style="background:#02A8F3; height:205px;">

        <p class="text">
          Never in all their history have men been able truly...
        </p>
        <p class="text-info">22 May, 2015 via mobile</p>

        <div class="logo"><i class="fa fa-twitter"></i></div>

        <ul class="info">
          <li><i class="fa fa-retweet"></i>694</li>
          <li><i class="fa fa-star-o"></i>2.192</li>
        </ul>

      </div>
    </div>
    <!-- End TwitterBox -->

    <!-- Start FacebookBox -->
    <div class="col-md-6 col-lg-3">
      <div class="widget socialbox" style="background:#47639E; height:205px;">

        <p class="text">
          Science has not yet mastered prophecy.
        </p>
        <p class="text-info">22 May, 2015 via mobile</p>

        <div class="logo"><i class="fa fa-facebook"></i></div>

        <ul class="info">
          <li><i class="fa fa-thumbs-up"></i>694</li>
          <li><i class="fa fa-comment"></i>2.192</li>
        </ul>

      </div>
    </div>
    <!-- End FacebookBox -->

  </div>
  <!-- End Third Row -->


  <!-- Start Fourth Row -->
  <div class="row">

    <!-- Start Browser Stats -->
    <div class="col-md-12 col-lg-3">
      <div class="panel panel-widget">
        <div class="panel-title">
          Browser Stats
          <ul class="panel-tools panel-tools-hover">
            <li><a class="icon"><i class="fa fa-refresh"></i></a></li>
            <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
            <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>
        <div class="panel-body">

          <ul class="basic-list">
            <li>Google Chrome <span class="right label label-default">42.8%</span></li>
            <li>Firefox <span class="right label label-danger">16.9%</span></li>
            <li>Safari <span class="right label label-success">15.5%</span></li>
            <li>Opera <span class="right label label-primary">11.8%</span></li>
            <li>Internet Explorer <span class="right label label-danger">3.2%</span></li>
            <li>Mobile <span class="right label label-warning">3%</span></li>
            <li>Others <span class="right label label-warning">0%</span></li>
          </ul>

        </div>
      </div>
    </div>
    <!-- End Browser Stats -->

    <!-- Start Orders -->
    <div class="col-md-12 col-lg-6">
      <div class="panel panel-widget">
        <div class="panel-title">
          LAST ORDERS <span class="label label-warning">196</span>
          <ul class="panel-tools">
            <li><a class="icon search-tool"><i class="fa fa-search"></i></a></li>
            <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
            <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>

        <div class="panel-search">
          <form>
            <input type="text" class="form-control" placeholder="Search...">
            <i class="fa fa-search icon"></i>
          </form>
        </div>


        <div class="panel-body table-responsive">

          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <td class="text-center"><i class="fa fa-trash"></i></td>
                <td>Order ID</td>
                <td>Product</td>
                <td>Buyer</td>
                <td>Date</td>
                <td>Payment</td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox1" type="checkbox"><label for="checkbox1"></label></div></td>
                <td># <b>9652</b></td>
                <td>Kode Gaming Laptop</td>
                <td>John Doe</td>
                <td>12/10/2015</td>
                <td>Credit Card</td>
              </tr>
              <tr>
                <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox2" type="checkbox"><label for="checkbox2"></label></div></td>
                <td># <b>1963</b></td>
                <td>New Season Jacket</td>
                <td>Jane Doe</td>
                <td>12/10/2015</td>
                <td>Paypal</td>
              </tr>
              <tr>
                <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox3" type="checkbox"><label for="checkbox3"></label></div></td>
                <td># <b>9652</b></td>
                <td>IO Mouse</td>
                <td>Jonathan Doe</td>
                <td>12/10/2015</td>
                <td>Credit Card</td>
              </tr>
              <tr>
                <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox4" type="checkbox"><label for="checkbox4"></label></div></td>
                <td># <b>9651</b></td>
                <td>Doe Bike</td>
                <td>Jonathan Doe</td>
                <td>12/10/2015</td>
                <td>Credit Card</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <!-- End Orders -->


    <!-- Start Inbox -->
    <div class="col-md-12 col-lg-3">
      <div class="panel panel-widget">
        <div class="panel-title">
          Inbox <span class="label label-danger">9</span>
          <ul class="panel-tools">
            <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
            <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>
        <div class="panel-body">

        <ul class="mailbox-inbox">

            <li>
              <a href="#" class="item clearfix">
                <img src="<?php echo URL_VIEW;?>/static/console/img/profileimg.png" alt="img" class="img">
                <span class="from">Jonathan Doe</span>
                Hello, m8 how is goin ?
                <span class="date">22 May</span>
              </a>
            </li>

            <li>
              <a href="#" class="item clearfix">
                <img src="<?php echo URL_VIEW;?>/static/console/img/profileimg2.png" alt="img" class="img">
                <span class="from">Egemem Ka</span>
                Problems look mighty small...
                <span class="date">22 May</span>
              </a>
            </li>

            <li>
              <a href="#" class="item clearfix">
                <img src="<?php echo URL_VIEW;?>/static/console/img/profileimg3.png" alt="img" class="img">
                <span class="from">James Throwing</span>
                New job offer ?
                <span class="date">22 May</span>
              </a>
            </li>

            <li>
              <a href="#" class="item clearfix">
                <img src="<?php echo URL_VIEW;?>/static/console/img/profileimg4.png" alt="img" class="img">
                <span class="from">Timmy Jefsin</span>
                Tonight Party
                <span class="date">22 May</span>
              </a>
            </li>


        </ul>

        </div>
      </div>
    </div>
    <!-- End Inbox -->

  </div>
  <!-- End Fourth Row -->


  <!-- Start Fifth Row -->
  <div class="row">


    <!-- Start Project Stats -->
    <div class="col-md-12 col-lg-6">
      <div class="panel panel-widget">
        <div class="panel-title">
          Projects Stats <span class="label label-info">62</span>
          <ul class="panel-tools">
            <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
            <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
            <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
          </ul>
        </div>

        <div class="panel-search">
          <form>
            <input type="text" class="form-control" placeholder="Search...">
            <i class="fa fa-search icon"></i>
          </form>
        </div>


        <div class="panel-body table-responsive">

          <table class="table table-hover">
            <thead>
              <tr>
                <td>ID</td>
                <td>Project</td>
                <td>Status</td>
                <td class="text-right">Progress</td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>965</td>
                <td>Kode Dashboard Template</td>
                <td><span class="label label-default">Developing</span></td>
                <td class="text-right"><span class="demo-project-stats"></span></td>
              </tr>
              <tr>
                <td>620</td>
                <td>EBI iOS Application</td>
                <td><span class="label label-warning">Design</span></td>
                <td class="text-right"><span class="demo-project-stats"></span></td>
              </tr>
              <tr>
                <td>621</td>
                <td>Kode Landing Page</td>
                <td><span class="label label-info">Testing</span></td>
                <td class="text-right"><span class="demo-project-stats"></span></td>
              </tr>
              <tr>
                <td>621</td>
                <td>John Coffe Shop Logo</td>
                <td><span class="label label-danger">Canceled</span></td>
                <td class="text-right"><span class="demo-project-stats"></span></td>
              </tr>
              <tr>
                <td>621</td>
                <td>BKM Website Design</td>
                <td><span class="label label-primary">Reply waiting</span></td>
                <td class="text-right"><span class="demo-project-stats"></span></td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <!-- Start Project Stats -->


    <!-- Start BlogPost -->
    <div class="col-md-12 col-lg-3">
      <div class="panel panel-widget blog-post">
        <div class="panel-body">

          <div class="image-div color10-bg">
            <img src="<?php echo URL_VIEW;?>/static/console/img/example1.jpg" class="image" alt="img">
            <h1 class="title"><a href="#">Across the sea of space, the stars are other suns.</a></h1>
          </div>
          <p class="text">There can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally...</p>
          <a href="#">Read More</a>
          <p class="author">
            <img src="<?php echo URL_VIEW;?>/static/console/img/profileimg.png" alt="img">
            <span>Jonathan Doe</span>
            Designer
          </p>


        </div>
      </div>
    </div>
    <!-- End BlogPost -->


    <!-- Start Teammates -->
    <div class="col-md-12 col-lg-3">
      <div class="panel panel-info panel-widget">
        <div class="panel-title">
          Teammates
        </div>
        <div class="panel-body">
          <ul class="basic-list image-list">
            <li><img src="<?php echo URL_VIEW;?>/static/console/img/profileimg.png" alt="img" class="img"><b>Jonathan Doe</b><span class="desc">Designer</span></li>
            <li><img src="<?php echo URL_VIEW;?>/static/console/img/profileimg2.png" alt="img" class="img"><b>Egemem Ka</b><span class="desc">Front-End Developer</span></li>
            <li><img src="<?php echo URL_VIEW;?>/static/console/img/profileimg3.png" alt="img" class="img"><b>Timmy Jefsin</b><span class="desc">Back-End Developer</span></li>
            <li><img src="<?php echo URL_VIEW;?>/static/console/img/profileimg4.png" alt="img" class="img"><b>James K. Throwing</b><span class="desc">Marketing</span></li>
            <li><img src="<?php echo URL_VIEW;?>/static/console/img/profileimg5.png" alt="img" class="img"><b>John Doe</b><span class="desc">iOS Developer</span></li>
          </ul>
        </div>
      </div>
    </div>
    <!-- End Teammates -->


  </div>
  <!-- End Fifth Row -->




</div>
<!-- END CONTAINER -->
 <!-- //////////////////////////////////////////////////////////////////////////// --> 

<?php include_once (PATH_VIEW . 'common/footer.php');?>


</div>
<!-- End Content -->
 <!-- //////////////////////////////////////////////////////////////////////////// --> 

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
Bootstrap Select
================================================ -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/bootstrap-select/bootstrap-select.js"></script>

<!-- ================================================
Bootstrap Toggle
================================================ -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<!-- ================================================
Flot Chart
================================================ -->
<!-- main file -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/flot-chart/flot-chart.js"></script>
<!-- time.js -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/flot-chart/flot-chart-time.js"></script>
<!-- stack.js -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/flot-chart/flot-chart-stack.js"></script>
<!-- pie.js -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/flot-chart/flot-chart-pie.js"></script>
<!-- demo codes -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/flot-chart/flot-chart-plugin.js"></script>


<!-- ================================================
Easy Pie Chart
================================================ -->
<!-- main file -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/easypiechart/easypiechart.js"></script>
<!-- demo codes -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/easypiechart/easypiechart-plugin.js"></script>

<!-- ================================================
Sparkline
================================================ -->
<!-- main file -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/sparkline/sparkline.js"></script>
<!-- demo codes -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/sparkline/sparkline-plugin.js"></script>

<!-- ================================================
Rickshaw
================================================ -->
<!-- d3 -->
<script src="<?php echo URL_VIEW;?>/static/console/js/rickshaw/d3.v3.js"></script>
<!-- main file -->
<script src="<?php echo URL_VIEW;?>/static/console/js/rickshaw/rickshaw.js"></script>
<!-- demo codes -->
<script src="<?php echo URL_VIEW;?>/static/console/js/rickshaw/rickshaw-plugin.js"></script>

<!-- ================================================
Moment.js
================================================ -->
<script type="text/javascript" src="<?php echo URL_VIEW;?>/static/console/js/moment/moment.min.js"></script>


<!-- ================================================
Below codes are only for index widgets
================================================ -->
<!-- Today Sales -->
<script>

// set up our data series with 50 random data points

var seriesData = [ [], [], [] ];
var random = new Rickshaw.Fixtures.RandomData(20);

for (var i = 0; i < 110; i++) {
  random.addData(seriesData);
}

// instantiate our graph!

var graph = new Rickshaw.Graph( {
  element: document.getElementById("todaysales"),
  renderer: 'bar',
  series: [
    {
      color: "#33577B",
      data: seriesData[0],
      name: 'Photodune'
    }, {
      color: "#77BBFF",
      data: seriesData[1],
      name: 'Themeforest'
    }, {
      color: "#C1E0FF",
      data: seriesData[2],
      name: 'Codecanyon'
    }
  ]
} );

graph.render();

var hoverDetail = new Rickshaw.Graph.HoverDetail( {
  graph: graph,
  formatter: function(series, x, y) {
    var date = '<span class="date">' + new Date(x * 1000).toUTCString() + '</span>';
    var swatch = '<span class="detail_swatch" style="background-color: ' + series.color + '"></span>';
    var content = swatch + series.name + ": " + parseInt(y) + '<br>' + date;
    return content;
  }
} );

</script>

<!-- Today Activity -->
<script>
// set up our data series with 50 random data points

var seriesData = [ [], [], [] ];
var random = new Rickshaw.Fixtures.RandomData(20);

for (var i = 0; i < 50; i++) {
  random.addData(seriesData);
}

// instantiate our graph!

var graph = new Rickshaw.Graph( {
  element: document.getElementById("todayactivity"),
  renderer: 'area',
  series: [
    {
      color: "#9A80B9",
      data: seriesData[0],
      name: 'London'
    }, {
      color: "#CDC0DC",
      data: seriesData[1],
      name: 'Tokyo'
    }
  ]
} );

graph.render();

var hoverDetail = new Rickshaw.Graph.HoverDetail( {
  graph: graph,
  formatter: function(series, x, y) {
    var date = '<span class="date">' + new Date(x * 1000).toUTCString() + '</span>';
    var swatch = '<span class="detail_swatch" style="background-color: ' + series.color + '"></span>';
    var content = swatch + series.name + ": " + parseInt(y) + '<br>' + date;
    return content;
  }
} );
</script>



</body>
</html>