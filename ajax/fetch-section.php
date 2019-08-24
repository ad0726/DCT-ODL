<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if (($_REQUEST['formfilled'] == 42) && isset($_REQUEST['era'])) {
    $periodsQuery = $bdd->query('SELECT * FROM odldc_period WHERE id_era = (SELECT id_era FROM odldc_era WHERE clean_name = "'.$_REQUEST['era'].'")');
    while ($period = $periodsQuery->fetch(PDO::FETCH_ASSOC)) {
        $periods[] = $period;
    }
    header('Content-Type: application/json');
    echo json_encode($periods);
} if (($_REQUEST['formfilled'] == 42) && isset($_REQUEST['universe'])) {
    $erasQuery = $bdd->query('SELECT * FROM odldc_era WHERE id_universe = (SELECT id_universe FROM odldc_universe WHERE clean_name = "'.$_REQUEST['universe'].'")');
    while ($era = $erasQuery->fetch(PDO::FETCH_ASSOC)) {
        $eras[] = $era;
    }
    header('Content-Type: application/json');
    echo json_encode($eras);
}