<?php require_once "../include/session.php"?>
<?php require_once "../include/function.php"; ?>
<?php
$_SESSION["admin_id"] = null;
$_SESSION["username"] = null;
redirect_to("login.php");
?>
<?php
//$_SESSION = [];
//if (isset($_COOKIE[session_name()])) {
//    setcookie(session_name(), '', time() - 4200, '/');
//}
//session_destroy();
//redirect_to("login.php");
//?>
