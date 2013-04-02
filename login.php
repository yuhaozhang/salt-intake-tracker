<?php

include_once './lib/common.php';
include_once './lib/db.php';
include_once './lib/user.php';

session_start();
header('Cache-control:private');

if (isset($_GET['login'])) {
    if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $user = user::getbyname($_POST['username']);
        
        if ($user->id && ( $user->password == md5($_POST['password']) ) ) {
            $_SESSION['access'] = true;
            $_SESSION['id'] = $user->id;
            $_SESSION['username'] = $user->name;
			$_SESSION['is_admin'] = $user->is_admin;
			
            if ($user->is_admin) header('location:admin.php');
            else header('location:main.php');
        }
        else {
            $_SESSION['access'] = false;
            $_SESSION['username'] = null;
            header('location:index.php?error');
        }
    }
    else{
    $_SESSION['access'] = false;
    $_SESSION['username'] = null;
    header('location:index.php?blank');
    }
    exit();
}
else if (isset($_GET['logoff'])) {
    if (isset($_COOKIE[session_name()])) setcookie((session_name()), '', time() - 42000, '/');
    $_SESSION = array();
	$_SESSION['access'] = false;
	session_unset();
    session_destroy();
	header('location:index.php');

}


?>

