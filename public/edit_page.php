<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php require_once "../include/validation_function.php"; ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php
/** @var $current_page */
if (!$current_page) {
    redirect_to("manage_content.php");
}
?>
<?php
if (isset($_POST["submit"])) {
    $required_fields = ["menu_name", "position", "visible", "content"];
    validate_presence($required_fields);

    $fields_with_max_lengths = ["menu_name" => 30, "content" => 100];
    validate_max_lengths($fields_with_max_lengths);

    if (empty($errors)) {
        $id = $current_page["id"];
        $menu_name = mysql_prep($_POST["menu_name"]);
        $position = (int)$_POST["position"];
        $visible = (int)$_POST["visible"];
        $content = mysql_prep($_POST["content"]);

        $query = "update pages set ";
        $query .= "menu_name = '{$menu_name}', ";
        $query .= "position = '{$position}', ";
        $query .= "visible = '{$visible}', ";
        $query .= "content = '{$content}' ";
        $query .= "where id = {$id} ";
        $query .= "limit 1";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION["message"] = "Page updated.";
            redirect_to("manage_content.php");
        } else {
            $message = "Page update failed.";
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
        <h2>Edit Page: <?php echo htmlentities($current_page["menu_name"]); ?></h2>

        <form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
            <p>Menu name:
                <input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>">
            </p>
            <p>Position:
                <select name="position">
                    <?php
                    $page_set = find_pages_for_subject($current_page["subject_id"], false);
                    $page_count = mysqli_num_rows($page_set);
                    for ($count = 1; $count <= $page_count; $count++) {
                        echo "<option value=\"{$count}\"";
                        if ($current_page["position"] == $count) {
                            echo " selected";
                        }
                        echo ">{$count}</option>";
                    }
                    ?>
                </select>
            </p>
            <p>Visible:
                <input type="radio" name="visible" value="0" <?php if ($current_page["visible"] == 0) { echo "checked"; } ?>> No
                &nbsp;
                <input type="radio" name="visible" value="1" <?php if ($current_page["visible"] == 1) { echo "checked"; } ?>> Yes
            </p>
            <p>Content:
                <br>
                <textarea name="content" cols="30" rows="10"><?php echo htmlentities($current_page["content"]); ?></textarea>
            </p>
            <input type="submit" name="submit" value="Edit Subject">
        </form>
        <br>
        <a href="manage_content.php">Cancel</a>
        &nbsp;
        &nbsp;
        <a href="delete_page.php?page=<?php echo urlencode($current_page["id"]); ?>" onclick="return confirm('Are you sure?');">Delete page</a>
    </div>
</div>

<?php include "../include/layouts/footer.php"; ?>
