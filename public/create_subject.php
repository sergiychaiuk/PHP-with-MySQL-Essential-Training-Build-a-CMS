<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php confirm_logged_in(); ?>
<?php
if (isset($_POST["submit"])) {
    $menu_name = mysql_prep($_POST["menu_name"]);
    $position = (int)$_POST["position"];
    $visible = (int)$_POST["visible"];

    $required_fields = ["menu_name", "position", "visible"];
    validate_presence($required_fields);

    $fields_with_max_lengths = ["menu_name" => 30];
    validate_max_lengths($fields_with_max_lengths);

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        redirect_to("new_subject.php");
    }

    $query = "insert into subjects (";
    $query .= " menu_name, position, visible";
    $query .= ") values (";
    $query .= " '{$menu_name}', {$position}, {$visible}";
    $query .= ")";

    $result = mysqli_query($connection, $query);

    if ($result) {
        $_SESSION["message"] = "Subject created.";
        redirect_to("manage_content.php");
    } else {
        $_SESSION["message"] = "Subject creation failed.";
        redirect_to("new_subject.php");
    }
} else {
    redirect_to("new_subject.php");
}
?>

<?php if (isset($connection)) { mysqli_close($connection); } ?>