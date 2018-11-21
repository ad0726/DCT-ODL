<?php
include('header.php');
if (isset($_REQUEST['search']) && !empty($_REQUEST['search']) && ($_REQUEST['period'] === "rebirth")) {
    $period = $_REQUEST['period'];
    $search = "%".$_REQUEST['search']."%";
    $arc     = $bdd->query("SELECT * FROM odldc_$period WHERE arc LIKE '$search'");
    $content = $bdd->query("SELECT * FROM odldc_$period WHERE contenu LIKE '$search'");

    while ($result = $arc->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $result;
    }
    while ($result = $content->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $result;
    }

    array_multisort($results, SORT_ASC);
    echo "<section>";
    echo "<p class='text-result'>Voici les résultats pour :<br />".$_REQUEST['search']."</p>";
    foreach ($results as $k=>$line) {
        displayLine($line);
    }
    echo "</section>";
} elseif (!isset($_REQUEST['period']) || !isset($_REQUEST['search']) || ($_REQUEST['search'] !== "rebirth")) {
    echo "<section>";
    echo "<p class='text-result'>Aucun résultat trouvé.</p>";
    echo "</section>";
}
include('footer.php');