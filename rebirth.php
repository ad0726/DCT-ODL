<?php
include('header.php');
echo "
        <section>";
$answer = $bdd->query('SELECT * FROM odldc_rebirth ORDER BY id ASC');
while ($line = $answer->fetch(PDO::FETCH_ASSOC)) {
    $comics[$line['name_period']][$line['id']] = array (
        "id"         => $line['id'],
        "arc"        => $line['arc'],
        "cover"      => $line['cover'],
        "contenu"    => $line['contenu'],
        "urban"      => $line['urban'],
        "dctrad"     => $line['dctrad'],
        "link_urban" => $line['link_urban'],
        "topic"      => $line['topic']
    );
}
foreach ($comics as $period=>$ARlineID) {
    displayPeriod($period, $ARlineID);
}
displayBtnUp();
echo "
        </section>\n";
include("footer.php");
?>