<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php confirm_logged_in(); ?>
<?php
$admin = find_admin_by_id($_GET["id"]);

if (!$admin) {
    redirect_to("manage_admins.php");
}
?>
<?php
if (isset($_POST["submit"])) {
    $required_fields = ["username", "password"];
    validate_presence($required_fields);

    $fields_with_max_lengths = ["username" => 30];
    validate_max_lengths($fields_with_max_lengths);

    if (empty($errors)) {
        $id = $admin["id"];
        $username = mysql_prep($_POST["username"]);
        $hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT, ['cost' => 10]);

        $query = "update admins set ";
        $query .= "username = '{$username}', ";
        $query .= "hashed_password = '{$hashed_password}' ";
        $query .= "where id = {$id} ";
        $query .= "limit 1";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION["message"] = "Admin updated";
            redirect_to("manage_admins.php");
        } else {
            $_SESSION["message"] = "Admin update failed";
        }
    }
}
?>
<?php $layout_context = "admin" ?>
<?php include "../include/layouts/header.php"; ?>

    <div id="main">
        <div id="navigation">

        </div>
        <div id="page">
            <?php
            echo message();
            echo from_errors($errors);
            ?>

            <h2>Edit Admins</h2>

            <form action="edit_admin.php?id=<?php echo urlencode($admin["id"]); ?>" method="post">
                <p>Username:
                    <input type="text" name="username" value="<?php echo htmlentities($admin["username"]) ?>">
                </p>
                <p>Password:
                    <input type="password" name="password" value="">
                </p>
                <input type="submit" name="submit" value="Edit Admin">
            </form>
            <br>
            <a href="manage_admins.php">Cancel</a>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>