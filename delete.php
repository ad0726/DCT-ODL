<?php
include_once('config.php');
if (isset($_GET['rm'])) {
    $id    = $_GET['rm'];
    $maxID = $bdd->query("SELECT MAX(id) FROM odldc_rebirth")->fetch(PDO::FETCH_ASSOC);

    $bdd->exec("DELETE FROM odldc_rebirth WHERE id = $id");
    $bdd->exec('UPDATE odldc_rebirth SET id = id - 1 WHERE id BETWEEN '.($id+1).' AND '.$maxID['MAX(id)']);

    header('Location: index.php');
}