<?php
$ROOT = "../";
include($ROOT.'partial/header.php');

echo "<section class='changelog'>";

if (isset($_SESSION['pseudo'])) {
    echo "<h1>Changelog</h1>";

    $query = $bdd->query('SELECT * FROM changelog ORDER BY id DESC LIMIT 20');

    while ($cl = $query->fetch(PDO::FETCH_ASSOC)) {
        $changelog[$cl['id']] = $cl;
    }

    foreach ($changelog as $val) {
        $type = FALSE;
        displayChangelog($val);
    }

} else {
    echo "Veuillez vous connecter pour poursuivre.";
}

echo "</section>";

include($ROOT.'partial/footer.php');