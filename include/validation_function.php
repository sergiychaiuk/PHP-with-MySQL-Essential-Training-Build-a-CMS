<?php

$errors = [];

function field_name_as_text ($field_name) {
    $field_name = str_replace("_", " ", $field_name);
    $field_name = ucfirst($field_name);
    return $field_name;
}

function has_presence ($value) {
    return isset($value) && $value !== "";
}

function validate_presence ($required_fields) {
    global $errors;

    foreach ($required_fields as $field) {
        $value = trim($_POST[$field]);
        if (!has_presence($value)) {
            $errors[$field] = field_name_as_text($field)." can`t be blank";
        }
    }
}

function has_max_length ($value, $max) {
    return strlen($value) <= $max;
}

function validate_max_lengths ($fields_with_max_lengths) {
    global $errors;

    foreach ($fields_with_max_lengths as $field => $max) {
        $value = trim($_POST[$field]);
        if (!has_max_length($value, $max)) {
            $errors[$field] = field_name_as_text($field)." is too long";
        }
    }
}