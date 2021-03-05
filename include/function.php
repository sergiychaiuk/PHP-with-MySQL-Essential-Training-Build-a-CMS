<?php

function redirect_to ($new_location) {
    header("Location: ".$new_location);
    exit();
}

function mysql_prep ($string) {
    global $connection;

    return mysqli_real_escape_string($connection, $string);
}

function confirm_query ($result_set) {
    if (!$result_set) {
        die("Database query failed.");
    }
}

function from_errors ($errors = []) {
    $output = "";

    if (!empty($errors)) {
        $output .= "<div class=\"error\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";

        foreach ($errors as $key => $error) {
            $output .= "<li>".htmlentities($error)."</li>";
        }

        $output .= "</ul>";
        $output .= "</div>";
    }

    return $output;
}

function find_all_subject ($public = true) {
    global $connection;

    $query = "select * ";
    $query .= "from subjects ";
    if ($public) {
        $query .= "where visible = 1 ";
    }
    $query .= "order by position asc";

    $subject_set = mysqli_query($connection, $query);

    confirm_query($subject_set);

    return $subject_set;
}

function find_pages_for_subject ($subject_id, $public = true) {
    global $connection;

    $safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

    $query = "select * ";
    $query .= "from pages ";
    $query .= "where subject_id = {$safe_subject_id} ";
    if ($public) {
        $query .= "and visible = 1 ";
    }
    $query .= "order by position asc";

    $page_set = mysqli_query($connection, $query);

    confirm_query($page_set);

    return $page_set;
}

function find_subject_by_id ($subject_id, $public = true) {
    global $connection;

    $safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

    $query = "select * ";
    $query .= "from subjects ";
    $query .= "where id = {$safe_subject_id} ";
    if ($public) {
        $query .= "and visible = 1 ";
    }
    $query .= "limit 1";

    $subject_set = mysqli_query($connection, $query);

    confirm_query($subject_set);

    if ($subject = mysqli_fetch_assoc($subject_set)) {
        return  $subject;
    } else {
        return null;
    }
}

function find_page_by_id ($page_id, $public = true) {
    global $connection;

    $safe_page_id = mysqli_real_escape_string($connection, $page_id);

    $query = "select * ";
    $query .= "from pages ";
    $query .= "where id = {$safe_page_id} ";
    if ($public) {
        $query .= "and visible = 1 ";
    }
    $query .= "limit 1";

    $page_set = mysqli_query($connection, $query);

    confirm_query($page_set);

    if ($page = mysqli_fetch_assoc($page_set)) {
        return  $page;
    } else {
        return null;
    }
}

function find_default_page_for_subject ($subject_id) {
    $page_set = find_pages_for_subject($subject_id);
    if ($first_page = mysqli_fetch_assoc($page_set)) {
        return $first_page;
    } else {
        return null;
    }
}

function find_selected_page ($public = false) {
    global $current_subject;
    global $current_page;

    if (isset($_GET["subject"])) {
        $current_subject =  find_subject_by_id($_GET["subject"], $public);
        if ($public && $current_subject) {
            $current_page = find_default_page_for_subject($current_subject["id"]);
        } else {
            $current_page = null;
        }
    } elseif (isset($_GET["page"])) {
        $current_page =  find_page_by_id($_GET["page"], $public);
        if ($public && $current_page) {
            $current_subject =  find_subject_by_id($current_page["subject_id"], $public);
        } else {
            $current_subject = null;
        }
    } else {
        $current_subject = null;
        $current_page = null;
    }
}

//function navigation ($subject_array, $page_array) {
//    $output = "<br><a href=\"admin.php\">&laquo; Main menu</a><ul class=\"subjects\">";
//
//    $subject_set = find_all_subject(false);
//
//    while ($subject = mysqli_fetch_assoc($subject_set)) {
//        $output .= "<li";
//        if ($subject_array && $subject["id"] == $subject_array["id"]) {
//            $output .= " class=\"selected\"";
//        }
//        $output .= ">";
//
//        $output .= "<a href=\"manage_content.php?subject=";
//        $output .= urlencode($subject["id"]);
//        $output .= "\">";
//        $output .= htmlentities($subject["menu_name"]);
//        $output .= "</a>";
//
//        $page_set = find_pages_for_subject($subject["id"], false);
//        $output .= "<ul class=\"pages\">";
//
//        while ($page = mysqli_fetch_assoc($page_set)) {
//            $output .= "<li";
//            if ($page_array && $page["id"] == $page_array["id"]) {
//                $output .= " class=\"selected\"";
//            }
//            $output .= ">";
//
//            $output .= "<a href=\"manage_content.php?page=";
//            $output .= urlencode($page["id"]);
//            $output .= "\">";
//            $output .= htmlentities($page["menu_name"]);
//            $output .= "</a>";
//
//            $output .= "</li>";
//        }
//
//        mysqli_free_result($page_set);
//
//        $output .= "</ul>";
//        $output .= "</li>";
//    }
//
//    mysqli_free_result($subject_set);
//
//    $output .= "</ul>";
//
//    return $output;
//}

function navigation ($subject_array, $page_array, $option = []) {
    $output = "";

    if (!$option["public"]) {
        $output = "<br><a href=\"../public/admin.php\">&laquo; Main menu</a>";
    }
    $output .= "<ul class=\"subjects\">";

    $subject_set = find_all_subject();

    while ($subject = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li";
        if ($subject_array && $subject["id"] == $subject_array["id"]) {
            $output .= " class=\"selected\"";
        }
        $output .= ">";

        $output .= "<a href=\"".$option["link"]."?subject=";
        $output .= urlencode($subject["id"]);
        $output .= "\">";
        $output .= htmlentities($subject["menu_name"]);
        $output .= "</a>";

        if ($option["public"]) {
            if ($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject["id"]) {
                $page_set = find_pages_for_subject($subject["id"], $option["public"]);
                $output .= "<ul class=\"pages\">";

                while ($page = mysqli_fetch_assoc($page_set)) {
                    $output .= "<li";
                    if ($page_array && $page["id"] == $page_array["id"]) {
                        $output .= " class=\"selected\"";
                    }
                    $output .= ">";

                    $output .= "<a href=\"".$option["link"]."?page=";
                    $output .= urlencode($page["id"]);
                    $output .= "\">";
                    $output .= htmlentities($page["menu_name"]);
                    $output .= "</a>";

                    $output .= "</li>";
                }

                $output .= "</ul>";
                mysqli_free_result($page_set);
            }
        } else {
            $page_set = find_pages_for_subject($subject["id"], $option["public"]);
            $output .= "<ul class=\"pages\">";

            while ($page = mysqli_fetch_assoc($page_set)) {
                $output .= "<li";
                if ($page_array && $page["id"] == $page_array["id"]) {
                    $output .= " class=\"selected\"";
                }
                $output .= ">";

                $output .= "<a href=\"".$option["link"]."?page=";
                $output .= urlencode($page["id"]);
                $output .= "\">";
                $output .= htmlentities($page["menu_name"]);
                $output .= "</a>";

                $output .= "</li>";
            }

            $output .= "</ul>";
            mysqli_free_result($page_set);
        }
        $output .= "</li>";
    }

    mysqli_free_result($subject_set);

    $output .= "</ul>";

    return $output;
}

function find_all_admins () {
    global $connection;

    $query = "select *";
    $query .= "from admins ";
    $query .= "order by username ASC";

    $admin_set = mysqli_query($connection, $query);

    confirm_query($admin_set);

    return $admin_set;
}

function find_admin_by_id ($admin_id) {
    global $connection;

    $query = "select * ";
    $query .= "from admins ";
    $query .= "where id = {$admin_id} ";
    $query .= "limit 1";

    $admin_set = mysqli_query($connection, $query);

    confirm_query($admin_set);

    if ($admin = mysqli_fetch_assoc($admin_set)) {
        return $admin;
    } else {
        return null;
    }
}

function find_admin_by_username ($username) {
    global $connection;

    $safe_username = mysql_prep($username);

    $query = "select * ";
    $query .= "from admins ";
    $query .= "where username = '{$safe_username}' ";
    $query .= "limit 1";

    $admin_set = mysqli_query($connection, $query);

    confirm_query($admin_set);

    if ($admin = mysqli_fetch_assoc($admin_set)) {
        return $admin;
    } else {
        return null;
    }
}

function attempt_login ($username, $password) {
    $admin = find_admin_by_username($username);
    if ($admin) {
        if (password_verify($password, $admin["hashed_password"])) {
            return $admin;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function logged_in () {
    return isset($_SESSION["admin_id"]);
}

function confirm_logged_in () {
    if (!logged_in()) {
        redirect_to("login.php");
    }
}

//function password_encrypt ($password) {
//    $hash_format = "$2y$10$";
//    $salt_length = 22;
//    $salt = generate_salt($salt_length);
//    $format_and_salt = $hash_format.$salt;
//
//    return crypt($password, $format_and_salt);
//}
//
//function generate_salt ($length) {
//    $unique_random_string = md5(uniqid(mt_rand(), true));
//    $base64_string = base64_encode($unique_random_string);
//    $modified_base64_string = str_replace('+', '.', $base64_string);
//
//    return substr($modified_base64_string, 0, $length);
//}
//
//function password_check ($password, $existing_hash) {
//    $hash = crypt($password, $existing_hash);
//
//    if ($hash === $existing_hash) {
//        return true;
//    } else {
//        return false;
//    }
//
////    if (hash_equals($existing_hash, crypt($password, $existing_hash))) {
////        return true;
////    } else {
////        return false;
////    }
//}