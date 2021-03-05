<?php require_once "../include/session.php"?>
<?php require_once "../include/function.php"; ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin" ?>
<?php include "../include/layouts/header.php"; ?>
<!-- main -->
    <div id="main">
        <div id="navigation">
            &nbsp;
        </div>
        <div id="page">
            <h2>Admin Menu</h2>
            <p>Welcome to the admin area, <?=htmlentities($_SESSION["username"])?></p>
            <ul>
                <li><a href="manage_content.php">Manage Website Content</a></li>
                <li><a href="manage_admins.php">Manage Admin User</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

<?php include "../include/layouts/footer.php";