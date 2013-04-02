<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>数据录入</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Data Input">
    <meta name="author" content="Yuhao Zhang">
	
	<!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 10px;
      }
	  
	  .top-padder {
		padding-top: 50px;
        padding-bottom: 10px;
	  }
	  
	  .table th,
	  .table td {
	    padding: 8px;
	    line-height: 20px;
	    text-align: center;
	    vertical-align: middle;
	    border-top: 1px solid #dddddd;
	  }

    </style>
	

  </head>
  
  <body>
    
	<?php //Prepare for top-bar information display
	
	include_once './lib/common.php';
	include_once './lib/db.php';
	include_once './lib/user.php';
	include_once './lib/date.php';
	include_once './lib/salt.php';

	session_start();
	header('Cache-control:private');
	
	if( $_SESSION['access'] == false || empty($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if( !isset($_GET['stu_id']) || empty($_GET['stu_id']) ) {
		header('location:main.php');
	}
	
	$user = new user;
	$user = user::getbyid($_SESSION['id']);
	
	$date = new date;
	$date = date::getCurrentDate();
	$week = date::getCurrentWeek($user->school_name);
	
	?>
	
	<!-- Top Nav Bar -->
	<div class="navbar navbar-fixed-top">
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
	
	$student = new student;
	$student = student::getbyid($_GET['stu_id']);
	
	?>
	
	<!--Main Body -- Table -->
	<div class="container">
	<div class="top-padder">

		<fieldset>
		  <div id="legend" class="">
			<legend class="">学生编号：<?php echo $student->getWholeID(); ?> | 学生姓名：<?php echo $student->name; ?></legend>
		  </div>
		  
		<?php
		
			if (isset($_GET['m']) && $_GET['m'] == 'success') {
			
				echo '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>保存成功！</h4>
					您可以点击右上角关闭本消息，或者忽略消息继续操作。
				</div>
				';
			} else if(isset($_GET['m']) && $_GET['m'] == 'error') {
			
				echo '
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>保存错误！</h4>
					您保存的时候发生错误，请您尝试检查输入内容并重新保存。
				</div>
				';
			
			}
		?>
		  
		  
		  <form action="add_data.php?add" method="post">
		  
		  <table class="table table-hover table-bordered">
		  <thead>
			<tr class="info">
				<th>周次</th>
				<th>盐罐重量(g)</th>
				<th>本周加盐量(g)</th>
				<th>本周吃盐量(g)</th>
				<th>酱油称重(g)</th>
				<th>本周加酱油量(g)</th>
				<th>本周吃酱油量(g)</th>
			</tr>
		  </thead>
		  <tbody>
			<?php echo printDataTable($_GET['stu_id'], $week); ?>
		  </tbody>
		  </table>
		  
		  <div class="form-actions">
		  <div class="row-fluid">
			<div class="span2 offset4">
				<?php
				if(checkIfCurrentDataExist($_GET['stu_id'], $week)) {
					echo "
							<a class='btn btn-primary btn-block disabled' role='button'><i class='icon-folder-close icon-white'></i> 保存数据</a>
						</div>
						<div class='span2'>
							<a class='btn btn-success btn-block' href='print.php?stu_id={$_GET['stu_id']}' target='_blank'><i class='icon-print icon-white'></i> 打印反馈</a>
						</div>
					";
				} else {
					echo "
							<a class='btn btn-primary btn-block' href='#SaveModal' role='button' data-toggle='modal'><i class='icon-folder-close icon-white'></i> 保存数据</a>
						</div>
						<div class='span2'>
							<a class='btn btn-success btn-block disabled'><i class='icon-print icon-white'></i> 打印反馈</a>
						</div>
					";
				}
				?>
				
				
				
				
				
				<!-- 这里是确认保存的弹出窗口 -->
				<div id="SaveModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 id="myModalLabel">确认保存</h4>
				  </div>
				  <div class="modal-body">
					<p class="text-error"><strong>注意！</strong>请确认数据后保存，保存后将不能修改。</p>
				  </div>
				  <div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
					<button type="submit" class="btn btn-primary" data-loading-text="正在保存">确认保存</button>
				  </div>
				</div>
				<!--弹出窗口结束-->
			
		  </div>
		  </div>
		  
		  </form>

		</fieldset>

	</div>
	</div>
	
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jqBootstrapValidation.js"></script>
	<script>
		$(function () { $("input").not("[type=submit]").jqBootstrapValidation(); } );
	</script>
  
  </body>
 </html>