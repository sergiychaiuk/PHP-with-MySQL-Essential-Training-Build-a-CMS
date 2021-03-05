<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php confirm_logged_in(); ?>
<?php
$admin = find_admin_by_id($_GET["id"]);

if (!$admin) {
    redirect_to("manage_admin.php");
}

$id = $admin["id"];
$query = "delete from admins where id = {$id} limit 1";
$result = mysqli_query($connection, $query);

if ($result && mysqli_affected_rows($connection) == 1) {
    $_SESSION["message"] = "Admin deleted";
    redirect_to("manage_admins.php");
} else {
    $_SESSION["message"] = "Admin deletion failed";
    redirect_to("manage_admins.php");
}