<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php $layout_context = "public" ?>
<?php include "../include/layouts/header.php"; ?>

<?php find_selected_page(true); ?>

    <div id="main">
        <div id="navigation">
            <?php
            /** @var $current_subject */
            /** @var $current_page */
            $option = ["public" => true, "link" => "index.php"];
            echo navigation($current_subject, $current_page, $option); ?>
        </div>
        <div id="page">
            <?php
            if ($current_page) {
                ?>
                <h2><?php echo htmlentities($current_page["menu_name"]) ?></h2>
                <?
                echo nl2br(htmlentities($current_page["content"]));
            } else {
                ?>
                <p>Welcome!</p>
                <?
            }
            ?>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>