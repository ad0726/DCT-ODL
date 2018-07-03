<?php
include('header.php');
echo "
        <section>";
$answer = $bdd->query('SELECT * FROM rebirth ORDER BY id DESC');
while ($line = $answer->fetch(PDO::FETCH_ASSOC)) {
    $comics[$line['name_period']][$line['id']] = array (
        "id" => $line['id'],
        "arc" => $line['arc'],
        "cover" => $line['cover'], 
        "contenu" => $line['contenu'], 
        "urban" => $line['urban'], 
        "dctrad" => $line['dctrad'], 
        "topic" => $line['topic']
    );
}
foreach ($comics as $period=>$ARlineID) {
    displayPeriod($period, $ARlineID);
}
echo "
        </section>\n";
include("footer.php");
?>