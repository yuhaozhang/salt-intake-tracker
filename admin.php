<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>���û�ע��</title>
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
				  <li><a href="login.php?logoff"><i class="icon-chevron-left"></i> �˳�</a></li>
				</ul>
			</div>
		</div>
		</div>
		
	  </div>
	  
	</div>
	
	
	<div class="container">
		<div class= "top-padder">
		
		
			<ul class="nav nav-tabs">
			  <li class="active"><a href="#register" data-toggle="tab">������û�</a></li>
			  <li><a href="#settings" data-toggle="tab">ϵͳ����</a></li>
			</ul>
		
		<?php
			if(isset($_GET['m'])) {
			
				if ($_GET['m'] == 'error') {
				
					echo '
					<div class="alert alert-error">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>�������󣬲���δ�ɹ���</h4>
					</div>
					';
				} else if($_GET['m'] == 'name_exist') {
				
					echo '
					<div class="alert alert-block">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>��������û����Ѵ��ڣ�������û������ԡ�</h4>
					</div>
					';
				} else if($_GET['m'] == 'reg_success') {
				
					echo '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>���û�ע��ɹ���</h4>
						<a href="login.php?logoff">����˳������ص�½ҳ</a>
					</div>
					';
				} else if($_GET['m'] == 'set_success') {
				
					echo '
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>���óɹ���</h4>
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
					  <label class="control-label" for="username">��¼�˺�</label>
					  <div class="controls">
						<input type="text" placeholder="��¼�˺�" maxlength="20" minlength="3" data-validation-minlength-message="�˺�ӦΪ��λ�����ַ�"
						 pattern="\w*" data-validation-pattern-message="ֻ��Ϊ��Сд��ĸ�����ֻ��»���" required data-validation-required-message="������" name="username" class="input-xlarge">
						<p class="help-block"></p>
					  </div>
					</div>

				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="password">��¼����</label>
					  <div class="controls">
						<input type="password" placeholder="��¼����" maxlength="30" minlength="6" data-validation-minlength-message="����ӦΪ6λ�����ַ�"
						required data-validation-required-message="������" name="password" class="input-xlarge">
					  </div>
					</div>

				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="confirm_password">ȷ������</label>
					  <div class="controls">
						<input type="password" placeholder="ȷ������" maxlength="30" data-validation-match-match="password" data-validation-match-message="������������벻һ��"
						required data-validation-required-message="������" name="confirm_password" class="input-xlarge">
						<p class="help-block">��ȷ����������</p>
					  </div>
					</div>

				<div class="control-group">

					  <!-- Text input-->
					  <label class="control-label" for="real_name">��ʦ����</label>
					  <div class="controls">
						<input type="text" placeholder="��ʦ����" maxlength="10" required data-validation-required-message="������" name="real_name" class="input-xlarge">
						<p class="help-block"></p>
					  </div>
					</div>

				

				<div class="control-group">

					  <!-- Select Basic -->
					  <label class="control-label" for="school_name">����ѧУ</label>
					  <div class="controls">
						<select class="input-xlarge" name="school_name">
				  <option value="01">01</option>
				  <option value="02">02</option></select>
					  </div>

					</div>

				<div class="control-group" for="algorithm">

					  <!-- Select Basic -->
					  <label class="control-label">�����㷨</label>
					  <div class="controls">
						<select class="input-xlarge" name="algorithm">
				  <option value="1">�𽥼��������㷨</option>
				  <option value="2">Ѹ�ټ��������㷨</option></select>
					  </div>

					</div>
				
				<div class="control-group warning">
				  <label class="control-label">����Ա�˻�</label>
				  <div class="controls">
					  <!-- Inline Radios -->
					  <label class="radio inline">
						<input type="radio" value="1" id="is_admin" name="is_admin">
						��
					  </label>
					  <label class="radio inline">
						<input type="radio" value="0" id="is_admin" name="is_admin" checked="checked">
						��
					  </label>
					  <p class="help-block">����Ա�˻���ӵ������û���Ȩ�ޣ���ӵ��ѧ����Ϣ�����ܡ�</p>
				  </div>
				</div>

				<div class="control-group">
					  <label class="control-label"></label>

					  <!-- Button -->
					  <div class="controls">
						<input type="hidden" name="register_submitted" value="1"/>
						<button type="submit" class="btn btn-primary">����û�</button>
					  </div>
					</div>
					
					</form>
		  
		  </div>
		  
		  <!-- ��ʼ�����趨 -->
		  <div class="tab-pane" name="settings" id="settings" >
			
			<div class="alert alert-warning">
				<h4>��ע��</h4>
				<p>�������ڴ�ҳ������Ŀ����ʼ���ڡ�������Ŀ����ʼ�����������ڣ���Ŀ�е���������������һ����Ϊ��ʼ����㡣��ˣ��������ȷ�� <strong>�趨����ʼ���ڵ�Ϊ������һ��</strong> ����ȷ����ȷ�����ڼ��㡣</p>
			</div>
			
			<div class="alert alert-info">
				<h4>��ǰ�����趨</h4>
				<table class="table table-hover">
				  <thead>
					<tr>
						<th>���</th>
						<th>ѧУ����</th>
						<th>��ʼ�����趨</th>
						<th>��Ŀǰ����</th>
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
					  <label class="control-label">ѡ������ѧУ</label>
					  <div class="controls">
						<select class="input-xlarge" name="school">
							<option value="01">ѧУ01</option>
							<option value="02">ѧУ02</option>
						</select>
						<p class="help-block">���������ô˴�ѡ���ѧУ����ʼ����</p>
					  </div>

					</div>
				
				<div class="control-group">

					  <!-- Select Basic -->
					  <label class="control-label">�µ���Ŀ��ʼ����</label>
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
						<button class="btn btn-success">�ύ����</button>
					  </div>
					</div>
			  </form>
		  </div>
		</div>
			
	  <!-- ��������������Ľ��� -->
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