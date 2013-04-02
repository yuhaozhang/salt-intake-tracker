<?php
//This php page is used to respond to data.php regarding the request of adding data.

include_once './lib/common.php';
include_once './lib/db.php';
include_once './lib/salt.php';

session_start();
header('Cache-control:private');

if ($_SESSION['access'] = true && checkIfAllPostSet()) {
	
	$stu_id = $_POST['student_id'];

	$salt = new salt;
	$salt->setData($_POST['student_id'], $_POST['week'], $_POST['add_salt_weight'], $_POST['salt_container_weight'], $_POST['add_soy_weight'], $_POST['soy_container_weight']);
	if($salt->save()) {
		header("location:data.php?stu_id=$stu_id&m=success");
	} else {
		header("location:data.php?stu_id=$stu_id&m=error");
	}

} else {
	header("location:main.php?m=error");
}

function checkIfAllPostSet() {
	$result = isset($_POST['student_id']) || isset($_POST['week']) || isset($_POST['add_salt_weight']) || isset($_POST['salt_container_weight'])
				|| isset($_POST['add_soy_weight']) || isset($_POST['soy_container_weight']);
	return $result;
}


?>