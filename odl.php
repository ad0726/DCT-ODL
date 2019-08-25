<?php
$ROOT = './';
include($ROOT.'partial/header.php');

if (isset($_REQUEST['universe']) && isset($_REQUEST['era'])) {
    $universe_id = $_REQUEST['universe'];  // todo: sanitize
    $era_id      = $_REQUEST['era'];       // todo: sanitize
    $periods     = [];
    $arcs        = [];

    $universe      = $bdd->query("SELECT * FROM odldc_universe WHERE id_universe = '$universe_id'")->fetch(PDO::FETCH_ASSOC);
    $universe['clean_name'] = "rebirth"; // for debug
    $era           = $bdd->query("SELECT * FROM odldc_era WHERE id_universe = '$universe_id'")->fetch(PDO::FETCH_ASSOC);
    $periods_query = $bdd->query("SELECT name, id_period FROM odldc_period WHERE id_universe = '$universe_id' AND id_era = '$era_id'");
    $arcs_query    = $bdd->query("SELECT * FROM odldc_{$universe['clean_name']} WHERE id_era = '$era_id' ORDER BY id ASC");              // todo: replace id to position

    while ($row = $periods_query->fetch(PDO::FETCH_ASSOC)) {
        $periods[$row['id_period']] = $row['name'];
    }

    while ($line = $arcs_query->fetch(PDO::FETCH_ASSOC)) {
        $arcs[$periods[$line['id_period']]][$line['id']] = array (
            "id"         => $line['id'],
            "arc"        => $line['arc'],
            "cover"      => $line['cover'],
            "contenu"    => $line['contenu'],
            "urban"      => $line['urban'],
            "dctrad"     => $line['dctrad'],
            "isEvent"    => $line['isEvent']
        );
    }

    if (!empty($arcs)) {
        echo "<section class='odl' id='{$era['clean_name']}_page'>";

        foreach ($arcs as $period=>$ARlineID) {
            displayPeriod($period, $ARlineID);
        }

        displayBtnUp();

        echo "</section>\n";
    } else {
        echo "Aucun arc dans cet ordre de lecture.";
    }
} else {
    echo "Page not found";
}

include($ROOT."partial/footer.php");
