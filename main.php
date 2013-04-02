<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>��ʦ��ҳ��</title>
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
				  <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> ����
					  <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
					  <li><!-- Button to trigger modal -->
					  <a href="#myModal" role="button" data-toggle="modal"><i class="icon-plus"></i> ���ѧ��</a>
					  </li>
					  <li><a href="login.php?logoff"><i class="icon-home"></i> �˳�</a></li>
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
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
		<h3 id="myModalLabel">���ѧ��</h3>
	  </div>
	  <div class="modal-body">
		
		<form class="form-horizontal" action="add_student.php?add" method="post">
			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01">ѧУ/��ͥ/��ϵ���</label>
				  <div class="controls">
					<input type="text" name="school_id" placeholder="ѧУ���" class="input-small" maxlength="5" required>
					<input type="text" name="family_id" placeholder="��ͥ���" class="input-small" maxlength="5" required>
					<input type="hidden" name="relation_id" value="1"><!--������ʹ�������ر����Զ�����relation_id-->
					<span class="input-mini uneditable-input">1</span>
					<p class="help-block">��ϵ���Ĭ��Ϊ1�����ø���</p>
				  </div>
				</div>
			
			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01">����</label>
				  <div class="controls">
					<input type="text" name="name" placeholder="����" class="input-xlarge" required maxlength="10">
					<p class="help-block"></p>
				  </div>
				</div>

			<div class="control-group">
				  <label class="control-label">�Ա�</label>
				  <div class="controls">
			  <!-- Inline Radios -->
			  <label class="radio inline">
				<input type="radio" value=0 name="gender" checked="checked">
				��
			  </label>
			  <label class="radio inline">
				<input type="radio" value=1 name="gender">
				Ů
			  </label>
		  </div>
				</div>

			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01">����</label>
				  <div class="controls">
					<select class="input-small" name="year" onchange="YYYYDD(this.value)">
					</select>
					<select class="input-small" name="month" onchange="MMDD(this.value)">
					</select>
					<select class="input-small" name="day" onchange="DD(this.value)">
					</select>
					<p class="help-block">�������б�ѡ������</p>
				  </div>
				</div>

			<div class="control-group">
				  <label class="control-label"></label>

				  <!-- Button -->
				  <div class="controls">
					<button type="submit" class="btn btn-success">ȷ�����</button>
				  </div>
				</div>
		 </form>
		 

	  </div>
	  <div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">�رմ���</button>
	  </div>
	</div>
	
	<!--Main Body -- Table -->
	<div class="container">
	<div class="top-padder">

		<fieldset>
		  <div id="legend" class="">
			<legend class="">ѧ���б�</legend>
		  </div>
		  
		  <?php
		
			if (isset($_GET['m']) && $_GET['m'] == 'success') {
			
				echo '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>�����ɹ���</h4>
					�����Ե�����Ͻǹرձ���Ϣ�����ߺ�����Ϣ����������
				</div>
				';
			} else if(isset($_GET['m']) && $_GET['m'] == 'error') {
			
				echo '
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>������������</h4>
					������һ�β��������з������������Գ��� 1��������ݲ��������룻 2���鿴�Ƿ��¼��ʱ��δ��¼������������ִ�������ϵ����Ա��
				</div>
				';
			
			}
		?>
		  
		  <table class="table table-hover">
		  
			<tr>
				<th>#</th>
				<th>ѧ�����</th>
				<th>����</th>
				<th>�Ա�</th>
				<th>����</th>
				<th>����������</th>
				<th></th>
			</tr>
			
			<?php 
			
				echo printStuTable($user->id, $week);
			
			?>
			
		  
		  </table>
		  
		  <!-- ������ȷ��ɾ���ĵ������� -->
			<div id="DeleteModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
				<h4 id="myModalLabel">ȷ��ɾ��</h4>
			  </div>
			  <div class="modal-body">
				<p class="text-error"><strong>ע�⣡</strong>��ȷ�Ϻ�ɾ����ɾ�������ݽ����ָܻ���</p>
			  </div>
			  <div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">ȡ��</button>
				<button onclick="javascript:window.location.href='add_student.php?delete=' + GetDeleteNum();" class="btn btn-primary">ȷ��ɾ��</button>
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
			<!--�������ڽ���-->

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