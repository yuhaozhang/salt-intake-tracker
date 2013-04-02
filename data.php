<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="gb2312">
    <title>����¼��</title>
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
	
	$student = new student;
	$student = student::getbyid($_GET['stu_id']);
	
	?>
	
	<!--Main Body -- Table -->
	<div class="container">
	<div class="top-padder">

		<fieldset>
		  <div id="legend" class="">
			<legend class="">ѧ����ţ�<?php echo $student->getWholeID(); ?> | ѧ��������<?php echo $student->name; ?></legend>
		  </div>
		  
		<?php
		
			if (isset($_GET['m']) && $_GET['m'] == 'success') {
			
				echo '
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>����ɹ���</h4>
					�����Ե�����Ͻǹرձ���Ϣ�����ߺ�����Ϣ����������
				</div>
				';
			} else if(isset($_GET['m']) && $_GET['m'] == 'error') {
			
				echo '
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>�������</h4>
					�������ʱ���������������Լ���������ݲ����±��档
				</div>
				';
			
			}
		?>
		  
		  
		  <form action="add_data.php?add" method="post">
		  
		  <table class="table table-hover table-bordered">
		  <thead>
			<tr class="info">
				<th>�ܴ�</th>
				<th>�ι�����(g)</th>
				<th>���ܼ�����(g)</th>
				<th>���ܳ�����(g)</th>
				<th>���ͳ���(g)</th>
				<th>���ܼӽ�����(g)</th>
				<th>���ܳԽ�����(g)</th>
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
							<a class='btn btn-primary btn-block disabled' role='button'><i class='icon-folder-close icon-white'></i> ��������</a>
						</div>
						<div class='span2'>
							<a class='btn btn-success btn-block' href='print.php?stu_id={$_GET['stu_id']}' target='_blank'><i class='icon-print icon-white'></i> ��ӡ����</a>
						</div>
					";
				} else {
					echo "
							<a class='btn btn-primary btn-block' href='#SaveModal' role='button' data-toggle='modal'><i class='icon-folder-close icon-white'></i> ��������</a>
						</div>
						<div class='span2'>
							<a class='btn btn-success btn-block disabled'><i class='icon-print icon-white'></i> ��ӡ����</a>
						</div>
					";
				}
				?>
				
				
				
				
				
				<!-- ������ȷ�ϱ���ĵ������� -->
				<div id="SaveModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
					<h4 id="myModalLabel">ȷ�ϱ���</h4>
				  </div>
				  <div class="modal-body">
					<p class="text-error"><strong>ע�⣡</strong>��ȷ�����ݺ󱣴棬����󽫲����޸ġ�</p>
				  </div>
				  <div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">ȡ��</button>
					<button type="submit" class="btn btn-primary" data-loading-text="���ڱ���">ȷ�ϱ���</button>
				  </div>
				</div>
				<!--�������ڽ���-->
			
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