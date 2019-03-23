<?php
session_start();
include('function.php');

if (($_REQUEST['formfilled'] == 42) && (!empty($_REQUEST['id'])) && (!empty($_REQUEST['name_era'])) && (!empty($_REQUEST['page']))) {
    $line = $bdd->query('SELECT * FROM odldc_'.$_REQUEST['name_era'].' WHERE id = '.$_REQUEST['id'])->fetch(PDO::FETCH_ASSOC);
    $line['period'] = ($bdd->query('SELECT name FROM odldc_period WHERE id_period = "'.$line['id_period'].'"')->fetch(PDO::FETCH_ASSOC))['name'];

    header('Content-Type: application/json');
    echo json_encode($line);
}