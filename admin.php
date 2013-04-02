<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>新用户注册</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="New User Registration">
    <meta name="author" content="Yuhao Zhang">
	
	<!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 20px;
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
	
	ob_start();
	
	if( $_SESSION['access'] == false || empty($_SESSION['id'])) {
		header('location:index.php');
	}
	
	$user = new user;
	$user = user::getbyid($_SESSION['id']);
	
	$date = new date;
	$initial_date = new date;
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
				  <li><a href="login.php?logoff"><i class="icon-chevron-left"></i> 退出</a></li>
				</ul>
			</div>
		</div>
		</div>
		
	  </div>
	  
	</div>
	
	
	<div class="container">
		<div class= "top-padder">
		
		
			<ul class="nav nav-tabs">
			  <li class="active"><a href="#register" data-toggle="tab">添加新用户</a></li>
			  <li><a href="#settings" data-toggle="tab">系统设置</a></li>
			</ul>
		
		<?php
			if(isset($_GET['m'])) {
			
				if ($_GET['m'] == 'error') {
				
					echo '
					<div class="alert alert-error">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>发生错误，操作未成功。</h4>
					</div>
					';
				} else if($_GET['m'] == 'name_exist') {
				
					echo '
					<div class="alert alert-block">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>您输入的用户名已存在，请更换用户名后尝试。</h4>
					</div>
					';
				} else if($_GET['m'] == 'reg_success') {
				
					echo '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>新用户注册成功！</h4>
						<a href="login.php?logoff">点此退出并返回登陆页</a>
					</div>
					';
				} else if($_GET['m'] == 'set_success') {
				
					echo '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>设置成功！</h4>
					</div>
					';
				}
			}
			
		?>
		
		<div class="tab-content">
		  <div class="tab-pane active" name="register" id="register" >
			<form id="register" class="form-horizontal" method="post">
		
				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="username">登录账号</label>
					  <div class="controls">
						<input type="text" placeholder="登录账号" maxlength="20" minlength="3" data-validation-minlength-message="账号应为三位以上字符"
						 pattern="\w*" data-validation-pattern-message="只能为大小写字母、数字或下划线" required data-validation-required-message="必填项" name="username" class="input-xlarge">
						<p class="help-block"></p>
					  </div>
					</div>

				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="password">登录密码</label>
					  <div class="controls">
						<input type="password" placeholder="登录密码" maxlength="30" minlength="6" data-validation-minlength-message="密码应为6位以上字符"
						required data-validation-required-message="必填项" name="password" class="input-xlarge">
					  </div>
					</div>

				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="confirm_password">确认密码</label>
					  <div class="controls">
						<input type="password" placeholder="确认密码" maxlength="30" data-validation-match-match="password" data-validation-match-message="两次输入的密码不一致"
						required data-validation-required-message="必填项" name="confirm_password" class="input-xlarge">
						<p class="help-block">请确认您的密码</p>
					  </div>
					</div>

				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="real_name">教师姓名</label>
					  <div class="controls">
						<input type="text" placeholder="教师姓名" maxlength="10" required data-validation-required-message="必填项" name="real_name" class="input-xlarge">
						<p class="help-block"></p>
					  </div>
					</div>

				

				<div class="control-group">

					  <!-- Select Basic -->
					  <label class="control-label" for="school_name">所在学校</label>
					  <div class="controls">
						<select class="input-xlarge" name="school_name">
				  <option value="01">01</option>
				  <option value="02">02</option></select>
					  </div>

					</div>

				<div class="control-group" for="algorithm">

					  <!-- Select Basic -->
					  <label class="control-label">采用算法</label>
					  <div class="controls">
						<select class="input-xlarge" name="algorithm">
				  <option value="1">逐渐减少盐量算法</option>
				  <option value="2">迅速减少盐量算法</option></select>
					  </div>

					</div>
				
				<div class="control-group warning">
				  <label class="control-label">管理员账户</label>
				  <div class="controls">
					  <!-- Inline Radios -->
					  <label class="radio inline">
						<input type="radio" value="1" id="is_admin" name="is_admin">
						是
					  </label>
					  <label class="radio inline">
						<input type="radio" value="0" id="is_admin" name="is_admin" checked="checked">
						否
					  </label>
					  <p class="help-block">管理员账户将拥有添加用户等权限，不拥有学生信息管理功能。</p>
				  </div>
				</div>

				<div class="control-group">
					  <label class="control-label"></label>

					  <!-- Button -->
					  <div class="controls">
						<input type="hidden" name="register_submitted" value="1"/>
						<button type="submit" class="btn btn-primary">添加用户</button>
					  </div>
					</div>
					
					</form>
		  
		  </div>
		  
		  <!-- 起始日期设定 -->
		  <div class="tab-pane" name="settings" id="settings" >
			
			<div class="alert alert-warning">
				<h4>请注意</h4>
				<p>您可以在此页设置项目的起始日期。设置项目的起始日期意义在于，项目中的周期数都将以这一天作为起始点计算。因此，请您务必确保 <strong>设定的起始日期点为“星期一”</strong> ，以确保正确的日期计算。</p>
			</div>
			
			<div class="alert alert-info">
				<h4>当前日期设定</h4>
				<table class="table table-hover">
				  <thead>
					<tr>
						<th>编号</th>
						<th>学校名称</th>
						<th>起始日期设定</th>
						<th>至目前周数</th>
					</tr>
				  </thead>
				  <tbody>
					<?php echo printInitialDateTable(); ?>
				  </tbody>
			  </table>
			</div>
			
			
			<form class="form-horizontal" method="POST">
				<div class="control-group">

					  <!-- Select Basic -->
					  <label class="control-label">选择设置学校</label>
					  <div class="controls">
						<select class="input-xlarge" name="school">
							<option value="01">学校01</option>
							<option value="02">学校02</option>
						</select>
						<p class="help-block">您将会设置此处选择的学校的起始日期</p>
					  </div>

					</div>
				
				<div class="control-group">

					  <!-- Select Basic -->
					  <label class="control-label">新的项目起始日期</label>
					  <div class="controls">
						<select class="input-small" name="year" onchange="YYYYDD(this.value)">
						</select>
						<select class="input-small" name="month" onchange="MMDD(this.value)">
						</select>
						<select class="input-small" name="day" onchange="DD(this.value)">
						</select>
					  </div>

					</div>

				<div class="control-group">
					  <label class="control-label"></label>
					  <!-- Button -->
					  <div class="controls">
						<input type="hidden" name="settings_submitted" value="1"/>
						<button class="btn btn-success">提交设置</button>
					  </div>
					</div>
			  </form>
		  </div>
		</div>
			
	  <!-- 下面是与服务器的交流 -->
				<?php 
				
					if (isset($_POST['register_submitted'])) {
						$user = user::getbyname($_POST["username"]);
						if($user->id)
						{
							header('location:admin.php?m=name_exist');
						}
						else{
							$user = new user;
							$user->name = $_POST['username'];
							$user->password = md5($_POST['password']);
							$user->real_name = $_POST['real_name'];
							$user->school_name = $_POST['school_name'];
							$user->algorithm = $_POST['algorithm'];
							$user->is_admin = $_POST['is_admin'];
							if($user->save()) header('location:admin.php?m=reg_success');
							else header('location:admin.php?m=error');
						}
					} else if (isset($_POST['settings_submitted'])) {  
						if(date::saveInitialDate($_POST['year'],$_POST['month'],$_POST['day'], $_POST['school'])) {
							header('location:admin.php?m=set_success');
						} else {
							header('location:admin.php?m=error');
						}
					}
				?>
	
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
		$(function () { $("input,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	</script>
  
  </body>
 </html>