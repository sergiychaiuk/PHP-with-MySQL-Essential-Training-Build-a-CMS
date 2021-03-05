<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php confirm_logged_in(); ?>
<?php
if (isset($_POST["submit"])) {
    $required_fields = ["username", "password"];
    validate_presence($required_fields);

    $fields_with_max_lengths = ["username" => 30];
    validate_max_lengths($fields_with_max_lengths);

    if (empty($errors)) {
        $username = mysql_prep($_POST["username"]);
        $hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT, ['cost' => 10]);

        $query = "insert into admins (";
        $query .= " username, hashed_password";
        $query .= ") values (";
        $query .= " '{$username}', '{$hashed_password}'";
        $query .= ")";

        $result = mysqli_query($connection, $query);

        if ($result) {
            $_SESSION["message"] = "Admin created";
            redirect_to("manage_admins.php");
        } else {
            $_SESSION["message"] = "Admin creation failed";
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

            <h2>Create Admins</h2>

            <form action="new_admin.php" method="post">
                <p>Username:
                    <input type="text" name="username" value="">
                </p>
                <p>Password:
                    <input type="password" name="password" value="">
                </p>
                <input type="submit" name="submit" value="Create Admin">
            </form>
            <br>
            <a href="manage_admins.php">Cancel</a>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>