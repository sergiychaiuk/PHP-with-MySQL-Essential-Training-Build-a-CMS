<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php
$username = "";
if (isset($_POST["submit"])) {
    $required_fields = ["username", "password"];
    validate_presence($required_fields);

    if (empty($errors)) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $found_admin = attempt_login($username, $password);

        if ($found_admin) {
            $_SESSION["admin_id"] = $found_admin["id"];
            $_SESSION["username"] = $found_admin["username"];
            redirect_to("admin.php");
        } else {
            $_SESSION["message"] = "Username/password not found";
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

            <h2>Login</h2>

            <form action="login.php" method="post">
                <p>Username:
                    <input type="text" name="username" value="<?=htmlentities($username)?>">
                </p>
                <p>Password:
                    <input type="password" name="password" value="">
                </p>
                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>