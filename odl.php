<?php
$ROOT = './';
include($ROOT.'partial/header.php');

if (isset($_REQUEST['era'])) {
    $era_id            = $_REQUEST['era'];       // todo: sanitize
    $periods           = [];
    $arcs              = [];
    $where_clause_arcs = "";

    $era           = $bdd->query("SELECT clean_name FROM era WHERE id_era = '$era_id'")->fetch(PDO::FETCH_ASSOC);
    $periods_query = $bdd->query("SELECT name, id_period FROM period WHERE id_era = '$era_id'");

    while ($row = $periods_query->fetch(PDO::FETCH_ASSOC)) {
        $periods[$row['id_period']] = $row['name'];
    }

    $n = count($periods);
    $i = 0;
    foreach ($periods as $id=>$name) {
        ++$i;
        $where_clause_arcs .= "id_period = '$id'";
        if ($i < $n) {
            $where_clause_arcs .= " OR ";
        }
    }

    $sql = "SELECT *
            FROM arc
            WHERE $where_clause_arcs ORDER BY position ASC";

    $arcs_query    = $bdd->query($sql);              // todo: replace id to position


    while ($line = $arcs_query->fetch(PDO::FETCH_ASSOC)) {
        $arcs[$periods[$line['id_period']]][$line['position']] = [
            "id"         => $line['position'],
            "arc"        => $line['title'],
            "cover"      => $line['cover'],
            "contenu"    => $line['content'],
            "urban"      => $line['link_a'],
            "dctrad"     => $line['link_b'],
            "isEvent"    => $line['is_event']
        ];
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
