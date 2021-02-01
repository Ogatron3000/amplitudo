<?php

// citanje
$todos = json_decode(file_get_contents('../todo.db'), true);

// validacija
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
} else {
    exit("Greska 1 - nepravilan index");
}
if (isset($_POST['tekst']) && $_POST['tekst'] != "") {
    $tekst = $_POST['tekst'];
} else {
    exit("Greska 2 - morate unijeti tekst...");
}
if (isset($_POST['opis']) && $_POST['opis'] != "") {
    $opis = $_POST['opis'];
} else {
    exit("Greska 3 - morate unijeti opis...");
}

// index zadatka za izmjenu
$toEditId = array_search($id, array_column($todos, 'id'));

// izmjena
$todos[$toEditId] = ['id' => $id, 'tekst' => $tekst, 'opis' => $opis, 'zavrsen' => $todos[$toEditId]['zavrsen']];

// cuvanje
if (file_put_contents('../todo.db', json_encode($todos))) {
    echo "OK";
} else {
    echo "Greska 4 - pogresan upis podataka...";
}
