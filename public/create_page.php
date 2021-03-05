<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php confirm_logged_in(); ?>
<?php
$id_subject = (int)$_GET["subject"];
if (isset($_POST["submit"])) {
    $menu_name = mysql_prep($_POST["menu_name"]);
    $position = (int)$_POST["position"];
    $visible = (int)$_POST["visible"];
    $content = mysql_prep($_POST["content"]);

    $required_fields = ["menu_name", "position", "visible", "content"];
    validate_presence($required_fields);

    $fields_with_max_lengths = ["menu_name" => 30, "content" => 100];
    validate_max_lengths($fields_with_max_lengths);

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        redirect_to("new_page.php?subject=".urlencode($id_subject));
    }

    $query = "insert into pages (";
    $query .= " subject_id, menu_name, position, visible, content";
    $query .= ") values (";
    $query .= " {$id_subject}, '{$menu_name}', {$position}, {$visible}, '{$content}'";
    $query .= ")";

    $result = mysqli_query($connection, $query);

    if ($result) {
        $_SESSION["message"] = "Page created.";
        redirect_to("manage_content.php");
    } else {
        $_SESSION["message"] = "Page creation failed.";
        redirect_to("new_page.php?subject=".urlencode($id_subject));
    }
} else {
    redirect_to("new_page.php?subject=".urlencode($id_subject));
}
?>

<?php if (isset($connection)) { mysqli_close($connection); } ?>
