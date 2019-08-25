<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

header('Content-Type: application/json');

if (($_REQUEST['formfilled'] == 42) && isset($_REQUEST['era'])) {
    $periods      = [];
    $periodsQuery = $bdd->query("SELECT name, id_period FROM odldc_period WHERE id_era = '{$_REQUEST['era']}' ORDER BY position ASC");
    while ($period = $periodsQuery->fetch(PDO::FETCH_ASSOC)) {
        $periods[] = $period;
    }

    if (!empty($periods)) {
        http_response_code(200);
        echo json_encode($periods);
    } else {
        http_response_code(404);
    }

} else if (($_REQUEST['formfilled'] == 42) && isset($_REQUEST['universe'])) {
    $eras      = [];
    $erasQuery = $bdd->query("SELECT name, id_era FROM odldc_era WHERE id_universe = '{$_REQUEST['universe']}' ORDER BY position ASC");

    while ($era = $erasQuery->fetch(PDO::FETCH_ASSOC)) {
        $eras[] = $era;
    }

    if (!empty($eras)) {
        http_response_code(200);
        echo json_encode($eras);
    } else {
        http_response_code(404);
    }
} else {
    http_response_code(400);
}