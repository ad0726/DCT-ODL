<?php
include('header.php');
echo "<section class='odl' id='rebirth_page'>";

$answer   = $bdd->query('SELECT * FROM odldc_rebirth ORDER BY id ASC');
$periodID = "";
while ($line = $answer->fetch(PDO::FETCH_ASSOC)) {
    if ($line['id_period'] != $periodID) {
        $query    = $bdd->query("SELECT name FROM odldc_period WHERE id_period = \"".$line['id_period']."\"")->fetch(PDO::FETCH_ASSOC);
        $period   = $query['name'];
        $periodID = $line['id_period'];
    }

    $comics[$period][$line['id']] = array (
        "id"         => $line['id'],
        "arc"        => $line['arc'],
        "cover"      => $line['cover'],
        "contenu"    => $line['contenu'],
        "urban"      => $line['urban'],
        "dctrad"     => $line['dctrad'],
        "isEvent"    => $line['isEvent']
    );
}

foreach ($comics as $period=>$ARlineID) {
    displayPeriod($period, $ARlineID);
}

displayBtnUp();

echo "</section>\n";

include("footer.php");
?>