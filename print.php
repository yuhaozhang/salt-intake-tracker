<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>ѧ��������ӡ</title>
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
	
	<!-- �����ڴ�ӡʱ����top nav bar -->
	<style media=print> 
		.Noprint{display:none;}<!--�ñ���ʽ�ڴ�ӡʱ���طǴ�ӡ��Ŀ--> 
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
				<a class="brand">��ӭ��<?php echo $user->name; ?>��</a>
			</div>
			<div class="span9">
				<ul class="nav pull-right">
				  <li><h4><?php echo $date->year; ?>��<?php echo $date->month; ?>��<?php echo $date->day; ?>�գ���<strong><?php echo $week; ?></strong>��</h4></li>
				  <li class="divider-vertical"></li>
				  <li><h4>ѧУ���ƣ�</h4></li>
				  <li><h4 class="text-info"><?php echo $user->school_name; ?></h4></li>
				  <li class="divider-vertical"></li>
				  <li><h4>��ʦ������</h4></li>
				  <li><h4 class="text-info"><?php echo $user->real_name; ?></h4></li>
				  <li class="divider-vertical"></li>
				  <li><a href="main.php"><i class="icon-chevron-left"></i> ����</a></li>
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
			<legend class="">ѧ����ţ�<?php echo $student->getWholeID(); ?> | ѧ��������<?php echo $student->name; ?></legend>
		  </div>
		  
		  <table class="table table-condensed">
		  <thead>
			<tr>
				<th>�ܴ�</th>
				<th>�ι޳���(g)</th>
				<th>���ܼ�����(g)</th>
				<th>���ܳ�����(g)</th>
				<th>���ͳ���(ml)</th>
				<th>���ܼӽ�����(ml)</th>
				<th>���ܳԽ�����(ml)</th>
			</tr>
		  </thead>
		  <tbody>
			<!--------------------������ݱ��-------------------->
			<?php echo printDataTableInPrint($_GET['stu_id'], $week); ?>
		  </tbody>
		  </table>
		  
		  <div id="legend">
			<legend class="">����ͼ</legend>
		  </div>
		  
		  <!---------------�������ͼ---------------->
		  <?php echo printDiagram($_GET['stu_id'], $week, $target_data); ?>
		  
		  <div id="legend">
			<legend class="">�÷�</legend>
		  </div>
		  
		  <div class="alert">
			<strong>˵����</strong>�����ﵽ�˽���ʳ��ʳ�Σ����ͣ�50%��Ŀ�꣬���õ�4��С�컨��С�ƻ����������Ŭ���˵��ǻ�Ҫ�ٽ����������õ�2��С�컨��С�ƻ�����
			�����Ŀ�껹�о��룬�õ�1��С�컨��С�ƻ���������Ҫ�����ˣ�
		  </div>
		  
		  <!---------------���С�컨���---------------->
		  <?php
			echo printFlowerTable($_GET['stu_id'], $week, $target_data);
		  ?>
		  
		  <div id="legend">
			<legend class="">�ҳ�����</legend>
		  </div>
		  
		  <!---------------����ҳ�����---------------->
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