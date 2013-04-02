<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>学生反馈打印</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Feedback Print Page">
    <meta name="author" content="Yuhao Zhang">
	
	<!--[if lt IE 9]><script src="js/excanvas.js"></script><![endif]-->
	<script src="js/jquery-1.4.4.min.js"></script>
	<script src="js/jquery.jqplot.min.js"></script>
	<script src="js/jqplot.pointLabels.min.js"></script>
	
	<!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- For Plot -->
	<link href="css/jquery.jqplot.min.css" rel="stylesheet"/>
	
	<!-- 用于在打印时隐藏top nav bar -->
	<style media=print> 
		.Noprint{display:none;}<!--用本样式在打印时隐藏非打印项目--> 
	</style>
	
	<style type="text/css">
      body {
        padding-top: 0px;
        padding-bottom: 0px;
      }
	  
	  .top-padder {
		padding-top: 50px;
        padding-bottom: 10px;
	  }
	  
	  .container-img {
		padding-bottom: 20px;
	  }
	  
	  .table th,
	  .table td {
	    padding: 6px;
	    line-height: 20px;
	    text-align: center;
	    vertical-align: middle;
	    border-top: 1px solid #dddddd;
	  }
	  
	  .jpchart {
		padding-bottom: 20px;
	  }
	  
	  legend {
		font-size: 18px;
	  }
	  
    </style>
	

  </head>
  
  <body>
    
	<?php //Prepare for top-bar information display
	
	include_once './lib/common.php';
	include_once './lib/db.php';
	include_once './lib/user.php';
	include_once './lib/date.php';

	session_start();
	header('Cache-control:private');
	
	if( $_SESSION['access'] == false || empty($_SESSION['id'])) {
		header('location:index.php');
	}
	
	$user = new user;
	$user = user::getbyid($_SESSION['id']);
	
	$date = new date;
	$date = date::getCurrentDate();
	$week = date::getCurrentWeek($user->school_name);
	
	?>
	
	<!-- Top Nav Bar -->
	<div class="navbar navbar-fixed-top Noprint">
	  <div class="navbar-inner">
		<div class="container">
		<div class="row-fluid">
			<div class="span3">
				<a class="brand">欢迎，<?php echo $user->name; ?>！</a>
			</div>
			<div class="span9">
				<ul class="nav pull-right">
				  <li><h4><?php echo $date->year; ?>年<?php echo $date->month; ?>月<?php echo $date->day; ?>日：第<strong><?php echo $week; ?></strong>周</h4></li>
				  <li class="divider-vertical"></li>
				  <li><h4>学校名称：</h4></li>
				  <li><h4 class="text-info"><?php echo $user->school_name; ?></h4></li>
				  <li class="divider-vertical"></li>
				  <li><h4>教师姓名：</h4></li>
				  <li><h4 class="text-info"><?php echo $user->real_name; ?></h4></li>
				  <li class="divider-vertical"></li>
				  <li><a href="main.php"><i class="icon-chevron-left"></i> 返回</a></li>
				</ul>
			</div>
		</div>
		</div>
		
	  </div>
	  
	</div>
	
	<?php //Prepare for the data
	
	include_once './lib/student.php';
	include_once './lib/display.php';
	include_once './lib/salt.php';
	include_once './lib/target.php';
	
	$student = new student;
	$student = student::getbyid($_GET['stu_id']);
	
	$target = new target;
	
	//echo"<br /><br /><br />";
	$target->setTarget($_GET['stu_id']);
	$target->updateTarget();
	$target_data = $target->getTarget();
	
	?>
	
	<!--Main Body -- Table -->
	<div class="container">
	<div class="top-padder">

		<fieldset>
		  <div id="legend" class="">
			<legend class="">学生编号：<?php echo $student->getWholeID(); ?> | 学生姓名：<?php echo $student->name; ?></legend>
		  </div>
		  
		  <table class="table table-condensed">
		  <thead>
			<tr>
				<th>周次</th>
				<th>盐罐称重(g)</th>
				<th>本周加盐量(g)</th>
				<th>本周吃盐量(g)</th>
				<th>酱油称重(ml)</th>
				<th>本周加酱油量(ml)</th>
				<th>本周吃酱油量(ml)</th>
			</tr>
		  </thead>
		  <tbody>
			<!--------------------输出数据表格-------------------->
			<?php echo printDataTableInPrint($_GET['stu_id'], $week); ?>
		  </tbody>
		  </table>
		  
		  <div id="legend">
			<legend class="">折线图</legend>
		  </div>
		  
		  <!---------------输出折线图---------------->
		  <?php echo printDiagram($_GET['stu_id'], $week, $target_data); ?>
		  
		  <div id="legend">
			<legend class="">得分</legend>
		  </div>
		  
		  <div class="alert">
			<strong>说明：</strong>如果你达到了降低食用食盐（酱油）50%的目标，将得到4朵小红花（小黄花）；如果你努力了但是还要再接再励，将得到2朵小红花（小黄花）；
			如果离目标还有距离，得到1朵小红花（小黄花），下周要加油了！
		  </div>
		  
		  <!---------------输出小红花表格---------------->
		  <?php
			echo printFlowerTable($_GET['stu_id'], $week, $target_data);
		  ?>
		  
		  <div id="legend">
			<legend class="">家长反馈</legend>
		  </div>
		  
		  <!---------------输出家长反馈---------------->
		  <?php
			echo printFeedback($_GET['stu_id'], $week, $target_data);
		  ?>

		</fieldset>

	</div>
	</div>
	
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
	
  
  </body>
 </html>