<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php confirm_logged_in(); ?>
<?php
$current_page = find_page_by_id($_GET["page"], false);

$id = $current_page["id"];
$query = "delete from pages where id = {$id} limit 1";
$result = mysqli_query($connection, $query);

if ($result && mysqli_affected_rows($connection) == 1) {
    $_SESSION["message"] = "Page deleted";
    redirect_to("manage_content.php");
} else {
    $_SESSION["message"] = "Page deletion failed";
    redirect_to("manage_content.php?page={$id}");
}