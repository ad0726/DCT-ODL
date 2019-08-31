<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if (!empty($_REQUEST['id'])) {
    $line = $bdd->query('SELECT * FROM arc WHERE id_arc = '.$_REQUEST['id'])->fetch(PDO::FETCH_ASSOC);
    $line['period'] = $bdd->query('SELECT name FROM period WHERE id_period = "'.$line['id_period'].'"')->fetch(PDO::FETCH_COLUMN);

    header('Content-Type: application/json');
    echo json_encode($line);
}