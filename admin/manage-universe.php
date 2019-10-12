<?php
$ROOT = "../";
include($ROOT.'partial/header.php');

echo "<section id='manage_universe_page'>";

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
        echo "\t\t\t<input type='hidden' name='formfilled' value='true'><br>\n";
        foreach ($eras as $id=>$era) {
            echo "\t\t\t<div class='era'>\n";
            echo "\t\t\t\t<p>".$era['name']."</p>\n";
            echo "\t\t\t\t<input type='text' name='$id' placeholder='Modifier titre'><br>\n";
            echo "\t\t\t\t<img src='/assets/img/sections/{$era['image']}' ><br>\n";
            echo "\t\t\t\t<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />";
            echo "\t\t\t\t<input type='file' class='file' name='$id' accept='image/*'>";
            echo "\t\t\t</div>\n";
        }
        echo "\t\t\t<br><input type='submit' value='Enregistrer'>\n";
        echo "\t\t</form>";
        echo "\t</div>";
        echo "</div>";

    } else if (isset($_REQUEST['formfilled'])) {
        echo "<div class='form'>";
        $name_to_update  = $_REQUEST;
        $image_to_update = $_FILES;

        unset($name_to_update['formfilled']);
        unset($name_to_update['MAX_FILE_SIZE']);

        $update_name  = $bdd->prepare("UPDATE era SET name = :name, clean_name = :clean_name WHERE id_era = :id_era");

        foreach($name_to_update as $key=>$value) {
            if (!empty($value)) {
                $update_name->execute([
                    'name'       => ucfirst($value),
                    'clean_name' => str_replace(' ', '_', strtolower($value)),
                    'id_era'     => $key
                ]);
            }
        }

        $update_image = $bdd->prepare("UPDATE era SET image = :image WHERE id_era = :id_era");

        foreach($image_to_update as $key=>$value) {
            // Fetch old image
            $old_image = $bdd->query("SELECT image FROM era WHERE id_era = '$key'")->fetch(PDO::FETCH_COLUMN);
            $path      = $ROOT."assets/img/sections/";
            $upload    = uploadCover($value, 950, $path);
            if ($upload[0] !== false) {
                $update_image->execute([
                    'image'  => $upload[1],
                    'id_era' => $key
                ]);
                // Delete image if new image uploaded
                unlink($ROOT."assets/img/sections/".$old_image);

            }
        }

        echo "<a href='/admin/manage-universe.php'><button type='button' class='btn_head'>Retour au formulaire</button></a>";
        echo "<a href='/index.php'><button type='button' class='btn_head'>Retour à l'accueil</button></a>";
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
