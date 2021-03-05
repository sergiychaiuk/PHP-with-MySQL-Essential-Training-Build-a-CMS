<?php require_once "../include/session.php"; ?>
<?php require_once "../include/db_connection.php"; ?>
<?php require_once "../include/function.php"; ?>
<?php $layout_context = "admin" ?>
<?php confirm_logged_in(); ?>
<?php include "../include/layouts/header.php"; ?>

<?php find_selected_page(); ?>

    <div id="main">
        <div id="navigation">
            <?php
            /** @var $current_subject */
            /** @var $current_page */
            $option = ["public" => false, "link" => "manage_content.php"];
            echo navigation($current_subject, $current_page, $option); ?>
            <br>
            <a href="new_subject.php">+ Add a subject</a>
        </div>
        <div id="page">
            <?php echo message(); ?>
            <?php
                if ($current_subject) {
                    echo "<h2>Manage Content</h2>";
                    echo "Menu name: ".htmlentities($current_subject["menu_name"])."<br>";
                    echo "Position: ".$current_subject["position"]."<br>";
                    echo "Visible: ".($current_subject["visible"] == 1 ? 'yes' : 'no')."<br><br>";
                    echo "<a href=\"edit_subject.php?subject=".urlencode($current_subject["id"])."\">Edit Subject</a>";
                    ?>
                    <div style="margin-top: 2em; border-top: 1px solid #000000">
                        <h3>Pages in this subject</h3>
                        <ul>
                            <?php
                            $subject_pages = find_pages_for_subject($current_subject["id"], false);
                            while ($page = mysqli_fetch_assoc($subject_pages)) {
                                echo "<li>";
                                $safe_page_id = urlencode($page["id"]);
                                echo "<a href=\"manage_content.php?page={$safe_page_id}\">";
                                echo htmlentities($page["menu_name"]);
                                echo "</a>";
                                echo "</li>";
                            }

                            mysqli_free_result($subject_pages);
                            ?>
                        </ul>
                    </div>
                    <?php
                    echo "<a href=\"new_page.php?subject=".urlencode($current_subject["id"])."\">+ Add a new page to this subject</a>";
                } elseif ($current_page) {
                    echo "<h2>Manage Page</h2>";
                    echo "Menu name: ".htmlentities($current_page["menu_name"])."<br>";
                    echo "Position: ".$current_page["position"]."<br>";
                    echo "Visible: ".($current_page["visible"] == 1 ? 'yes' : 'no')."<br>";
                    echo "Content:<br><div class=\"view-content\">".htmlentities($current_page["content"])."</div><br>";
                    echo "<a href=\"edit_page.php?page=".urlencode($current_page["id"])."\">Edit Subject</a>";
                } else {
                    echo "Please select a subject or a page.";
                }
            ?>
        </div>
    </div>

<?php include "../include/layouts/footer.php"; ?>