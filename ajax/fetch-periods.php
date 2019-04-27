<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if (($_REQUEST['formfilled'] == 42) && isset($_REQUEST['era'])) {
    $idEra = $bdd->query('SELECT id_era FROM odldc_era WHERE clean_name = "'.$_REQUEST['era'].'"')->fetch(PDO::FETCH_ASSOC);
    $periodsQuery = $bdd->query('SELECT * FROM odldc_period WHERE id_era = "'.$idEra['id_era'].'"');
    while ($period = $periodsQuery->fetch(PDO::FETCH_ASSOC)) {
        $periods[] = $period;
    }
    header('Content-Type: application/json');
    echo json_encode($periods);
}