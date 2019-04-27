<?php
$ROOT = "../";
include($ROOT.'partial/header.php');

echo "<section>";

if (isset($_SESSION['pseudo'])) {
    echo "<div class='form'>\n";
    if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        if (isset($_REQUEST['name_era']) && ($_REQUEST['name_era'] != "")) {
            createSection("era");
            echo "</div>";
        } elseif (isset($_REQUEST['name_period']) && ($_REQUEST['name_period'] != "")) {
            createSection("period");
            echo "</div>";
        } else {
            echo "Une erreur est survenue.";
            echo "<a href='create-section.php'><button type='button' class='btn_head'>Retour au formulaire</button></a>";
            echo "<a href='/admin/index.php'><button type='button' class='btn_head'>Retour au PCA</button></a>";
            echo "<a href='index.php'><button type='button' class='btn_head'>Retour à l'accueil</button></a>";
            echo "</div>";
        }
    } else {
        $eras = $bdd->query('SELECT * FROM odldc_era');
        while ($nameEra = $eras->fetch(PDO::FETCH_ASSOC)) {
            $namesEra[] = $nameEra;
        }
        echo "\t<h2>Créer...</h2>\n";
        echo "\t<form action='?' method='POST'>\n";
        echo "\t\t<input type='hidden' name='formfilled' value='42' />\n";
        echo "\t\t<select name='create' id='selectCreate'>\n";
        echo "\t\t\t<option class='optionCreate'>Cliquez</option>\n";
        echo "\t\t\t<option class='optionCreate' value='era'>Ère</option>\n";
        echo "\t\t\t<option class='optionCreate' value='period'>Période</option>\n";
        echo "\t\t</select>\n";
        echo "\t\t<div id='create_era' style='display: none;'>";
        echo "\t\t\t<input type='text' class='input' name='name_era' placeholder=\"Nom de l'ère\">\n";
        echo "\t\t\t<label name='where_era'>Emplacement</label>\n";
        echo "\t\t\t<select name='where_era'>\n";
        echo "\t\t\t\t<option class=''>Cliquez</option>\n";
        echo "\t\t\t\t<option class='' value='first'>En premier</option>\n";
        foreach ($namesEra as $nameEra) {
            echo "\t\t\t\t<option class='' value='after_".$nameEra['clean_name']."'>Après ".$nameEra['name']."</option>\n";
        }
        echo "\t\t\t</select>\n";
        echo "\t\t</div>";
        echo "\t\t<div id='create_period' style='display: none;'>";
        echo "\t\t\t<input type='text' class='input' name='name_period' placeholder='Nom de la période'><br />";
        echo "\t\t\t<label name='periodToEra'>Dans l'ère :</label>\n";
        echo "\t\t\t<select id='whichEra' name='periodToEra'>\n";
        echo "\t\t\t\t<option class='selectEra' value=''>Cliquez</option>\n";
        foreach ($namesEra as $nameEra) {
            echo "\t\t\t\t<option class='selectEra' value='".$nameEra['clean_name']."'>".$nameEra['name']."</option>\n";
        }
        echo "\t\t\t</select>\n";
        echo "\t\t\t<div id='selectPeriod' style='display: none;'>\n";
        echo "\t\t\t\t<label name='where_period'>Emplacement</label>\n";
        echo "\t\t\t\t<select id='wherePeriod' name='where_period'>\n";
        echo "\t\t\t\t</select>\n";
        echo "\t\t\t</div>\n";
        echo "\t\t</div>";
        echo "\t\t<input type='submit' class='btn_send' value='Envoyez' style='display: none;'>\n";
        echo "\t</form>\n";
    }
    echo "</div>";
} else {
    echo "Veuillez vous connecter pour poursuivre.";
}

echo "</section>";

include($ROOT.'partial/footer.php');
