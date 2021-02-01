<?php

if (isset($_POST['submit'])) {

    session_start();
    $_SESSION['errors'] = [];

    validate("first_name", true);
    validate('last_name', true);
    validate('city', false, true);
    validate('behaviour');
    validate('wish');

    if (empty($_SESSION['errors'])) {
        $wishData =  json_encode(array_slice($_SESSION, 1));
        $fileName = uniqid() . '.txt';
        if (!file_exists('zelje_db')) {
            mkdir('zelje_db');
        }
        $handle = fopen('zelje_db/' . $fileName, 'w');
        fwrite($handle, $wishData);
        fclose($handle);
        session_unset();
        header('Location: zelja_poslata.php');
    } else {
        header('Location: index.php');
    }
}

function validate($input, $alphaNum = false, $city = false)
{
    // persist data
    $_SESSION[$input] = $_POST[$input];

    if (empty($_POST[$input])) {
        $_SESSION['errors'][$input . '_err'] = "${input} is required";
    } elseif ($alphaNum) {
        $value = $_POST[$input];
        if (ctype_alpha(str_replace(' ', '', $value)) === false) {
            $_SESSION['errors'][$input . '_err'] = "${input} must be alphanumeric";
        }
    } elseif ($city) {
        $cities = ['London', 'Paris', 'Rome', 'Berlin', 'Madrid', 'Podgorica'];
        $value = $_POST[$input];
        if (!in_array($value, $cities)) {
            $_SESSION['errors'][$input . '_err'] = "$input is invalid, please choose one from the list";
        }
    }
}
