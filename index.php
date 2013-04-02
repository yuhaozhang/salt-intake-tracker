<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>用户登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="User Login">
    <meta name="author" content="Yuhao Zhang">
	
	<!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
	
	<style type="text/css">
	  body {
        padding-top: 40px;
      }
	  
	  #footer {
		text-align: center;
		padding: 30px 0;
		margin-top: 100px;
		border-top: 1px solid #e5e5e5;
		background-color: #f5f5f5;
	  }
	  

    </style>
	

  </head>
  
  <body>
    
	<?php //Check if should header to user main page or admin page.
	
	include_once './lib/common.php';
	include_once './lib/db.php';
	include_once './lib/user.php';

	session_start();
	header('Cache-control:private');
	
	/*
	if( isset($_SESSION['access']) && $_SESSION['access'] == true && !empty($_SESSION['id'])) {
		if ($_SESSION['is_admin']) header('location:admin.php');
            else header('location:main.php');
	}
	*/
	
	?>
	
	<div class="container">
	<div class="row-fluid">
	<div class="span8 offset2">
	  <form class="form-horizontal" action="login.php?login" method="POST">
		<fieldset>
		  <div id="legend" class="">
			<legend class="">用户登录</legend>
		  </div>
		  
		<?php
		
		if( isset($_SESSION['access']) && $_SESSION['access'] == true && isset($_SESSION['id'])) {
			
			$user = new user;
			$user = user::getbyid($_SESSION['id']);
			$user_name = $user->name;
			$admin = $user->is_admin;
			
			if( !$user->is_admin ) {
				echo "
				<div class='alert alert-error'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					您已用 $user_name 账号登录，<a href='main.php'>点此进入主页</a>。
				</div>
				";
			} else {
				echo "
				<div class='alert alert-error'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					您已用管理员账号 $user_name 登录，<a href='admin.php'>点此进入管理员页</a>。
				</div>
				";
			}
		}
		
		if (isset($_GET['error'])) {
		
			echo '
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				用户名或密码错误，请重新输入。
			</div>
			';
		} else if(isset($_GET['blank'])) {
		
			echo '
			<div class="alert alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				用户名或密码栏不能为空，请检查。
			</div>
			';
		}
		?>
		
		  
		<div class="control-group">

			  <!-- Text input-->
			  <label class="control-label" for="input01">用户名</label>
			  <div class="controls">
				<input type="text" name="username" placeholder="用户名" class="input-xlarge">
				<p class="help-block"></p>
			  </div>
			</div>

		<div class="control-group">

			  <!-- Text input-->
			  <label class="control-label" for="input01">密码</label>
			  <div class="controls">
				<input type="password" name="password" placeholder="密码" class="input-xlarge">
				<p class="help-block"></p>
			  </div>
			</div>

		<div class="control-group">
			  <label class="control-label"></label>

			  <!-- Button -->
			  <div class="controls">
				<button class="btn btn-primary">登录</button>
			  </div>
			</div>

		</fieldset>
	  </form>
	</div>
	</div>
	</div>

	
	<div id="footer" class="navbar navbar-fixed-bottom">
      <div class="container">
        <p class="muted credit">为正常使用所有功能，推荐使用<a href="https://www.google.com/intl/zh-CN/chrome/browser/">Chrome浏览器</a>，建议至少使用<a href="http://firefox.com.cn/download/">Firefox（火狐）</a>
		或<a href="http://windows.microsoft.com/zh-cn/internet-explorer/downloads/ie-9/worldwide-languages">Internet Explorer 8</a>以上浏览器登录。</p>
      </div>
    </div>
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  
  </body>
 </html>