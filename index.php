<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>�û���¼</title>
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
			<legend class="">�û���¼</legend>
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
					������ $user_name �˺ŵ�¼��<a href='main.php'>��˽�����ҳ</a>��
				</div>
				";
			} else {
				echo "
				<div class='alert alert-error'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					�����ù���Ա�˺� $user_name ��¼��<a href='admin.php'>��˽������Աҳ</a>��
				</div>
				";
			}
		}
		
		if (isset($_GET['error'])) {
		
			echo '
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				�û���������������������롣
			</div>
			';
		} else if(isset($_GET['blank'])) {
		
			echo '
			<div class="alert alert-block">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				�û���������������Ϊ�գ����顣
			</div>
			';
		}
		?>
		
		  
		<div class="control-group">

			  <!-- Text input-->
			  <label class="control-label" for="input01">�û���</label>
			  <div class="controls">
				<input type="text" name="username" placeholder="�û���" class="input-xlarge">
				<p class="help-block"></p>
			  </div>
			</div>

		<div class="control-group">

			  <!-- Text input-->
			  <label class="control-label" for="input01">����</label>
			  <div class="controls">
				<input type="password" name="password" placeholder="����" class="input-xlarge">
				<p class="help-block"></p>
			  </div>
			</div>

		<div class="control-group">
			  <label class="control-label"></label>

			  <!-- Button -->
			  <div class="controls">
				<button class="btn btn-primary">��¼</button>
			  </div>
			</div>

		</fieldset>
	  </form>
	</div>
	</div>
	</div>

	
	<div id="footer" class="navbar navbar-fixed-bottom">
      <div class="container">
        <p class="muted credit">Ϊ����ʹ�����й��ܣ��Ƽ�ʹ��<a href="https://www.google.com/intl/zh-CN/chrome/browser/">Chrome�����</a>����������ʹ��<a href="http://firefox.com.cn/download/">Firefox�������</a>
		��<a href="http://windows.microsoft.com/zh-cn/internet-explorer/downloads/ie-9/worldwide-languages">Internet Explorer 8</a>�����������¼��</p>
      </div>
    </div>
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  
  </body>
 </html>