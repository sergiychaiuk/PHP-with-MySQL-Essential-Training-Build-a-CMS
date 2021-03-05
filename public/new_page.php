<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin" ?>
<?php include "../include/layouts/header.php"; ?>
<?php find_selected_page(); ?>

    <div id="main">
        <div id="navigation">
            <?php
            /** @var $current_subject */
            /** @var $current_page */
            $option = ["public" => false, "link" => "manage_content.php"];
            echo navigation($current_subject, $current_page, $option); ?>
        </div>
        <div id="page">
            <?php echo message(); ?>
            <?php $errors = errors(); ?>
            <?php echo from_errors($errors); ?>
            <h2>Create Page</h2>

            <form action="create_page.php?subject=<?=urlencode($_GET["subject"])?>" method="post">
                <p>Menu name:
                    <input type="text" name="menu_name" value="">
                </p>
                <p>Position:
                    <select name="position">
                        <?php
                        $page_set = find_pages_for_subject($_GET["subject"], false);
                        $page_count = mysqli_num_rows($page_set);
                        for ($count = 1; $count <= ($page_count + 1); $count++) {
                            echo "<option value=\"{$count}\">{$count}</option>";
                        }
                        ?>
                    </select>
                </p>
                <p>Visible:
                    <input type="radio" name="visible" value="0"> No
                    &nbsp;
                    <input type="radio" name="visible" value="1"> Yes
                </p>
                <p>Content:
                    <br>
                    <textarea name="content" cols="30" rows="10"></textarea>
                </p>
                <input type="submit" name="submit" value="Create Subject">
            </form>
            <br>
            <a href="manage_content.php">Cancel</a>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>