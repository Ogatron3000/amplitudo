<?php

// citanje
$todos = json_decode(file_get_contents('../todo.db'), true);

// validacija
if (isset($_POST['tekst']) && $_POST['tekst'] != "") {
    $tekst = $_POST['tekst'];
} else {
    exit("Greska 1 - morate unijeti tekst...");
}
if (isset($_POST['opis']) && $_POST['opis'] != "") {
    $opis = $_POST['opis'];
} else {
    exit("Greska 2 - morate unijeti opis...");
}

// dodavanje
$todos[] = ['id' => generisiNoviID(), 'tekst' => $tekst, 'opis' => $opis, 'zavrsen' => false];

// cuvanje
if (file_put_contents('../todo.db', json_encode($todos))) {
    echo "OK";
} else {
    echo "Greska 3 - pogresan upis podataka...";
}

// helper
function generisiNoviID()
{
    global $todos;
    $max = 0;
    foreach ($todos as $todo) {
        if ($todo['id'] > $max) $max = $todo['id'];
    }
    return (int)$max + 1;
}
