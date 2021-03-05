<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php
/** @var $current_subject */
if (!$current_subject) {
    redirect_to("manage_content.php");
}
?>
<?php
if (isset($_POST["submit"])) {
    $required_fields = ["menu_name", "position", "visible"];
    validate_presence($required_fields);

    $fields_with_max_lengths = ["menu_name" => 30];
    validate_max_lengths($fields_with_max_lengths);

    if (empty($errors)) {
        $id = $current_subject["id"];
        $menu_name = mysql_prep($_POST["menu_name"]);
        $position = (int)$_POST["position"];
        $visible = (int)$_POST["visible"];

        $query = "update subjects set ";
        $query .= "menu_name = '{$menu_name}', ";
        $query .= "position = '{$position}', ";
        $query .= "visible = '{$visible}' ";
        $query .= "where id = {$id} ";
        $query .= "limit 1";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION["message"] = "Subject updated.";
            redirect_to("manage_content.php");
        } else {
            $message = "Subject update failed.";
        }
    }
}
?>
<?php $layout_context = "admin" ?>
<?php include "../include/layouts/header.php"; ?>

    <div id="main">
        <div id="navigation">
            <?php
            /** @var $current_subject */
            /** @var $current_page */
            $option = ["public" => false, "link" => "manage_content.php"];
            echo navigation($current_subject, $current_page, $option); ?>
        </div>
        <div id="page">
            <?php
                if (!empty($message)) {
                    echo "<div class=\"message\">".htmlentities($message)."</div>";
                }
            ?>
            <?php echo from_errors($errors); ?>
            <h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>

            <form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
                <p>Menu name:
                    <input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>">
                </p>
                <p>Position:
                    <select name="position">
                        <?php
                        $subject_set = find_all_subject(false);
                        $subject_count = mysqli_num_rows($subject_set);
                        for ($count = 1; $count <= $subject_count; $count++) {
                            echo "<option value=\"{$count}\"";
                            if ($current_subject["position"] == $count) {
                                echo " selected";
                            }
                            echo ">{$count}</option>";
                        }
                        ?>
                    </select>
                </p>
                <p>Visible:
                    <input type="radio" name="visible" value="0" <?php if ($current_subject["visible"] == 0) { echo "checked"; } ?>> No
                    &nbsp;
                    <input type="radio" name="visible" value="1" <?php if ($current_subject["visible"] == 1) { echo "checked"; } ?>> Yes
                </p>
                <input type="submit" name="submit" value="Edit Subject">
            </form>
            <br>
            <a href="manage_content.php">Cancel</a>
            &nbsp;
            &nbsp;
            <a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete subject</a>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>