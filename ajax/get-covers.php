<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if (isset($_REQUEST['ids']) && isset($_REQUEST['era'])) {
    $era     = $_REQUEST['era'];
    $firstId = $_REQUEST['ids'][0];
    $lastId  = end($_REQUEST['ids']);
    $query   = $bdd->query("SELECT cover FROM arc WHERE position BETWEEN $firstId AND $lastId ORDER BY position ASC");

    while ($cover = $query->fetch(PDO::FETCH_ASSOC)) {
        $covers[] = "assets/img/covers/".$cover['cover'];
    }

    header('Content-Type: application/json');
    echo json_encode($covers);
}