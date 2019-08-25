<?php
$ROOT = "../";
include($ROOT.'partial/header.php');

echo "<section>";

if (isset($_SESSION['pseudo'])) {
    echo "<div class='form'>\n";
    if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        if (isset($_REQUEST['create']) && ($_REQUEST['create'] != "")) {
            createSection($_REQUEST['create']);
            echo "</div>";
        } else {
            echo "Une erreur est survenue.";
            echo "<a href='create-section.php'><button type='button' class='btn_head'>Retour au formulaire</button></a>";
            echo "<a href='/admin/index.php'><button type='button' class='btn_head'>Retour au PCA</button></a>";
            echo "<a href='index.php'><button type='button' class='btn_head'>Retour à l'accueil</button></a>";
            echo "</div>";
        }
    } else {
        $table_prefix = TABLE_PREFIX;
        $universe     = $bdd->query('SELECT * FROM '.$table_prefix.'universe');
        while ($name_universe = $universe->fetch(PDO::FETCH_ASSOC)) {
            $names_universe[] = $name_universe;
        }
        echo "\t<h2>Créer...</h2>\n";
        echo "\t<form action='?' method='POST'>\n";
        echo "\t\t<input type='hidden' name='formfilled' value='42' />\n";

        echo "\t\t<select name='create' id='selectCreate'>\n";
        echo "\t\t\t<option class='optionCreate'>Cliquez</option>\n";
        echo "\t\t\t<option class='optionCreate' value='universe'>Univers</option>\n";
        echo "\t\t\t<option class='optionCreate' value='era'>Ère</option>\n";
        echo "\t\t\t<option class='optionCreate' value='period'>Période</option>\n";
        echo "\t\t</select>\n";

        // Create Universe
        echo "\t\t<div id='create_universe' style='display: none;'>";
        echo "\t\t\t<input type='text' class='input' name='name_universe' placeholder=\"Nom de l'univers\">\n";
        echo "\t\t</div>";

        // Create Era
        echo "\t\t<div id='create_era' style='display: none;'>";
        echo "\t\t\t<input type='text' class='input' name='name_era' placeholder=\"Nom de l'ère\"><br />";
        echo "\t\t\t<label>Dans l'univers :</label>\n";
        echo "\t\t\t<select id='whichUniverseForEra' name='referer[eraToUniverse]'>\n";
        echo "\t\t\t\t<option class='selectUniverseForEra' value=''>Cliquez</option>\n";
        foreach ($names_universe as $name_universe) {
            echo "\t\t\t\t<option class='selectUniverseForEra' value='".$name_universe['id_universe']."'>".$name_universe['name']."</option>\n";
        }
        echo "\t\t\t</select>\n";
        echo "\t\t\t<div id='selectEraForEra' style='display: none;'>\n";
        echo "\t\t\t\t<label name='where_era'>Emplacement</label>\n";
        echo "\t\t\t\t<select id='whereEra' name='where_era'>\n";
        // echo "\t\t\t\t\t<option value='first'>En premier</option>\n";
        echo "\t\t\t\t</select>\n";
        echo "\t\t\t</div>\n";
        echo "\t\t</div>";

        // Create period
        echo "\t\t<div id='create_period' style='display: none;'>";
        echo "\t\t\t<input type='text' class='input' name='name_period' placeholder='Nom de la période'><br />";
        echo "\t\t\t<label>Dans l'universe :</label>\n";
        echo "\t\t\t<select id='whichUniverseForPeriod' name='referer[periodToUniverse]'>\n";
        echo "\t\t\t\t<option class='selectUniverseForPeriod' value=''>Cliquez</option>\n";
        foreach ($names_universe as $name_universe) {
            echo "\t\t\t\t<option class='selectUniverseForPeriod' value='".$name_universe['id_universe']."'>".$name_universe['name']."</option>\n";
        }
        echo "\t\t\t</select>\n";
        echo "\t\t\t<div id='selectEraForPeriod' style='display: none;'>\n";
        echo "\t\t\t\t<label>Dans l'ère :</label>\n";
        echo "\t\t\t\t<select id='whichEra' name='referer[periodToEra]'>\n";
        echo "\t\t\t\t</select>\n";
        echo "\t\t\t</div>\n";
        echo "\t\t\t<div id='selectPeriod' style='display: none;'>\n";
        echo "\t\t\t\t<label name='where_period'>Emplacement</label>\n";
        echo "\t\t\t\t<select id='wherePeriod' name='where_period'>\n";
        echo "\t\t\t\t\t<option value='first'>En premier</option>\n";
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
