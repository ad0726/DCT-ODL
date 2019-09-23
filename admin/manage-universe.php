<?php
$ROOT = "../";
include($ROOT.'partial/header.php');

echo "<section>";

if (isset($_SESSION['pseudo'])) {
    if (isset($_REQUEST['universe']) && ($_REQUEST['universe'] != "")) {
        $id_universe = $_REQUEST['universe'];                                                                                  // Todo: sanitize
        $universe    = $bdd->query("SELECT name FROM universe WHERE id_universe = '$id_universe'")->fetch(PDO::FETCH_COLUMN);
        $query       = $bdd->query("SELECT * FROM era WHERE id_universe = '$id_universe'");
        $eras        = [];
        while ($era = $query->fetch(PDO::FETCH_ASSOC)) {
            $eras[$era['id_era']] = $era;
        }
        echo "\t<div>";
        echo "\t\t<h2>$universe</h2>";
        echo "\t\t<form action='?' method='POST' enctype='multipart/form-data'>\n";
        foreach ($eras as $id=>$era) {
            echo "\t\t\t<p>".$era['name']."</p>\n";
            echo "\t\t\t<input type='text' name='$id' placeholder='Modifier titre'><br>\n";
            echo "\t\t\t<img src='/assets/img/sections/{$era['image']}' ><br>\n";
            echo "\t\t\t<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />";
            echo "\t\t\t<input type='file' class='file' name='$id' accept='image/*' required>";
        }
        echo "\t\t</form>";
        echo "\t</div>";
        echo "</div>";
    } else {
        $universes = [];
        $query     = $bdd->query("SELECT id_universe, name FROM universe");
        while ($universe = $query->fetch(PDO::FETCH_ASSOC)) {
            $universes[$universe['id_universe']] = $universe;
        }
        echo "<div class='form'>\n";
        echo "\t<h2>Configurer...</h2>\n";
        echo "\t<form method='POST' action=''>";
        echo "\t\t<select name='universe'>\n";
        echo "\t\t\t<option value=''>Cliquez</option>\n";
        foreach ($universes as $id=>$universe) {
            echo "\t\t\t<option value='$id'>".$universe['name']."</option>\n";
        }
        echo "\t\t</select>";
        echo "\t\t<input type='submit' value='Submit'>";
        echo "\t</form>";
        echo "</div>";
    }
} else {
    echo "Veuillez vous connecter pour poursuivre.";
}

echo "</section>";

include($ROOT.'partial/footer.php');
