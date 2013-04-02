<?php
//This php page is used to respond to main.php regarding the request of adding student.

include_once './lib/common.php';
include_once './lib/db.php';
include_once './lib/student.php';

session_start();
header('Cache-control:private');

if (isset($_GET['add'])) {
	
	if($_SESSION['access'] = true && !empty($_SESSION['id'])) {
	
		$student = new student;
		//From POST
		$student->name = $_POST['name'];
		$student->school_id = $_POST['school_id'];
		$student->family_id = $_POST['family_id'];
		$student->relation_id = $_POST['relation_id'];
		$student->gender = $_POST['gender'];
		$student->birthday = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
		$student->latest_data_week = -1;
		
		//From session
		$student->teacher_id = $_SESSION['id'];
		
		if($student->save()) {
			header("location:main.php?m=success");
		} else {
			header("location:main.php?m=error");
		}
		
	
	} else {
		header('location:main.php?m=error');
	}
} elseif (isset($_GET['delete'])) {
	
	if(student::delete($_GET['delete'])) {
		header("location:main.php?m=success");
	} else {
		header("location:main.php?m=error");
	}

} else {
	header('location:main.php?m=error');
}



?>