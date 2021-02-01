<?php

// citanje
$todos = json_decode(file_get_contents('../todo.db'), true);

// validacija
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
} else {
    exit("Greska 1 - nepravilan index");
}

// index zadatka za brisanje
$toDeleteId = array_search($id, array_column($todos, 'id'));

// validacija++
if ($toDeleteId === false) {
    exit("Ne postoji zadatak sa predatim ID-jem...");
}

// brisanje
unset($todos[$toDeleteId]);

// cuvanje
if (file_put_contents('../todo.db', json_encode(array_values($todos)))) {
    exit('OK');
} else {
    exit("Greska pri brisanju...");
}
