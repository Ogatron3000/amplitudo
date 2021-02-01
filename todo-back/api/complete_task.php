<?php

// citanje
$todos = json_decode(file_get_contents('../todo.db'), true);

// validacija
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
} else {
    exit("Greska 1 - nepravilan id");
}
// if (isset($_POST['status'])) {
//     $status = $_POST['status'];
// } else {
//     exit("Greska 2 - nepravilan status");
// }

// if ($status == "true") $status = true;
// else $status = false;

// index zadatka za cekiranje
$toCheckId = array_search($id, array_column($todos, 'id'));

// cekiranje
$todos[$toCheckId]['zavrsen'] = !($todos[$toCheckId]['zavrsen']);

// cuvanje
if (file_put_contents('../todo.db', json_encode($todos))) {
    exit("OK");
} else {
    exit("Greska pri upisu...");
}
