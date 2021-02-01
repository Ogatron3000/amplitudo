<?php
$todos = json_decode(file_get_contents('../todo.db'), true);

if (!isset($_POST['pretraga'])) {
    echo json_encode($todos);
} else {
    if (isset($_POST['tekst']) && !empty($_POST['tekst'])) {
        $todos = array_filter($todos, function ($todo) {
            return stripos($todo['tekst'], $_POST['tekst']) !== false;
        });
    }
    if (isset($_POST['opis']) && !empty($_POST['opis'])) {
        $todos = array_filter($todos, function ($todo) {
            return stripos($todo['opis'], $_POST['opis']) !== false;
        });
    }
    if (isset($_POST['zavrsen']) && $_POST['zavrsen'] !== '') {
        $todos = array_filter($todos, function ($todo) {
            if ($_POST['zavrsen'] == 1) {
                return $todo['zavrsen'] === true;
            } else if ($_POST['zavrsen'] == 0) {
                return $todo['zavrsen'] === false;
            }
        });
    }
    echo json_encode(array_values($todos));
}
