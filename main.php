<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>教师主页面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Teacher Main Screen">
    <meta name="author" content="Yuhao Zhang">
	
	<!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }
	  
	  .top-padder {
		padding-top: 50px;
        padding-bottom: 10px;
	  }
	  

    </style>
	

  </head>
  
  <body>
    
	<?php //Prepare for top-bar information display
	
	include_once './lib/common.php';
	include_once './lib/db.php';
	include_once './lib/user.php';
	include_once './lib/date.php';
	include_once './lib/display.php';

	session_start();
	header('Cache-control:private');
	
	if( $_SESSION['access'] == false || !isset($_SESSION['id'])) {
		header('location:index.php');
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
				  <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> 操作
					  <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
					  <li><!-- Button to trigger modal -->
					  <a href="#myModal" role="button" data-toggle="modal"><i class="icon-plus"></i> 添加学生</a>
					  </li>
					  <li><a href="login.php?logoff"><i class="icon-home"></i> 退出</a></li>
					</ul>
				  </li>
				</ul>
			</div>
		</div>
		</div>
		
	  </div>
	  
	</div>
	
	
 
	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">添加学生</h3>
	  </div>
	  <div class="modal-body">
		
		<form class="form-horizontal" action="add_student.php?add" method="post">
			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01">学校/家庭/关系编号</label>
				  <div class="controls">
					<input type="text" name="school_id" placeholder="学校编号" class="input-small" maxlength="5" required>
					<input type="text" name="family_id" placeholder="家庭编号" class="input-small" maxlength="5" required>
					<input type="hidden" name="relation_id" value="1"><!--在这里使用了隐藏表单，自动发送relation_id-->
					<span class="input-mini uneditable-input">1</span>
					<p class="help-block">关系编号默认为1，不用更改</p>
				  </div>
				</div>
			
			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01">姓名</label>
				  <div class="controls">
					<input type="text" name="name" placeholder="姓名" class="input-xlarge" required maxlength="10">
					<p class="help-block"></p>
				  </div>
				</div>

			<div class="control-group">
				  <label class="control-label">性别</label>
				  <div class="controls">
			  <!-- Inline Radios -->
			  <label class="radio inline">
				<input type="radio" value=0 name="gender" checked="checked">
				男
			  </label>
			  <label class="radio inline">
				<input type="radio" value=1 name="gender">
				女
			  </label>
		  </div>
				</div>

			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01">生日</label>
				  <div class="controls">
					<select class="input-small" name="year" onchange="YYYYDD(this.value)">
					</select>
					<select class="input-small" name="month" onchange="MMDD(this.value)">
					</select>
					<select class="input-small" name="day" onchange="DD(this.value)">
					</select>
					<p class="help-block">从下拉列表选择日期</p>
				  </div>
				</div>

			<div class="control-group">
				  <label class="control-label"></label>

				  <!-- Button -->
				  <div class="controls">
					<button type="submit" class="btn btn-success">确认添加</button>
				  </div>
				</div>
		 </form>
		 

	  </div>
	  <div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭窗口</button>
	  </div>
	</div>
	
	<!--Main Body -- Table -->
	<div class="container">
	<div class="top-padder">

		<fieldset>
		  <div id="legend" class="">
			<legend class="">学生列表</legend>
		  </div>
		  
		  <?php
		
			if (isset($_GET['m']) && $_GET['m'] == 'success') {
			
				echo '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>操作成功！</h4>
					您可以点击右上角关闭本消息，或者忽略消息继续操作。
				</div>
				';
			} else if(isset($_GET['m']) && $_GET['m'] == 'error') {
			
				echo '
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>操作发生错误！</h4>
					在您上一次操作过程中发生错误，您可以尝试 1）检查内容并重新输入； 2）查看是否登录超时或未登录；如果持续出现错误，请联系管理员。
				</div>
				';
			
			}
		?>
		  
		  <table class="table table-hover">
		  
			<tr>
				<th>#</th>
				<th>学生编号</th>
				<th>姓名</th>
				<th>性别</th>
				<th>生日</th>
				<th>本周已输入</th>
				<th></th>
			</tr>
			
			<?php 
			
				echo printStuTable($user->id, $week);
			
			?>
			
		  
		  </table>
		  
		  <!-- 这里是确认删除的弹出窗口 -->
			<div id="DeleteModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 id="myModalLabel">确认删除</h4>
			  </div>
			  <div class="modal-body">
				<p class="text-error"><strong>注意！</strong>请确认后删除，删除后数据将不能恢复。</p>
			  </div>
			  <div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
				<button onclick="javascript:window.location.href='add_student.php?delete=' + GetDeleteNum();" class="btn btn-primary">确认删除</button>
			  </div>
			</div>
			
			<script>  
				function SaveDeleteNum(num)  {  
					delete_num = num;
				} 
				
				function GetDeleteNum()  {  
					return delete_num;
				}
			</script>  
			<!--弹出窗口结束-->

		</fieldset>

	</div>
	</div>
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/date_select.js"></script>
	<script src="js/jqBootstrapValidation.js"></script>
	<script>
		$(function () { $("input").not("[type=submit]").jqBootstrapValidation(); } );
	</script>
  
  </body>
 </html>