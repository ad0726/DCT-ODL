<?php
if (isset($_SESSION['pseudo'])) {
    $ROOT = "../";
    include($ROOT.'partial/header.php');
    echo "<section class='changelog'>";
    echo "<h1>Changelog</h1>";

    $query = $bdd->query('SELECT * FROM odldc_changelog ORDER BY id DESC LIMIT 20');

    while ($cl = $query->fetch(PDO::FETCH_ASSOC)) {
        $changelog[$cl['id']] = $cl;
    }

    foreach ($changelog as $val) {
        $type = FALSE;
        displayChangelog($val);
    }
    echo "</section>";
    include($ROOT.'partial/footer.php');
} else {
    header('Location: /index.php');
}