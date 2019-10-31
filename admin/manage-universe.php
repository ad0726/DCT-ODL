<?php
$ROOT = "../";
include($ROOT.'partial/header.php');

echo "<section id='manage_universe_page'>";

if (isset($_SESSION['pseudo'])) {
    if (isset($_REQUEST['universe']) && ($_REQUEST['universe'] != "")) {
        $id_universe = $_REQUEST['universe'];                                                                                  // Todo: sanitize
        $universe    = $bdd->query("SELECT name FROM universe WHERE id_universe = '$id_universe'")->fetch(PDO::FETCH_COLUMN);
        $logos       = $bdd->query("SELECT * FROM links WHERE id_universe = '$id_universe'")->fetch(PDO::FETCH_ASSOC);
        $query       = $bdd->query("SELECT * FROM era WHERE id_universe = '$id_universe'");
        $eras        = [];
        while ($era = $query->fetch(PDO::FETCH_ASSOC)) {
            $eras[$era['id_era']] = $era;
        }
        echo "\t<div>";
        echo "\t\t<h2>$universe</h2>";
        echo "\t\t<form action='?' method='POST' enctype='multipart/form-data'>\n";
        echo "\t\t\t<input type='hidden' name='formfilled' value='universe'><br>\n";
        echo "\t\t\t<input type='hidden' name='id_universe' value='$id_universe'><br>\n";
        echo "\t\t\t<div class='era'>\n";
        echo "\t\t\t\t<h3>Général</h3>\n";
        echo "\t\t\t\t\t<div>\n";
        echo "\t\t\t\t\t\t<h4>Modifier le nom de l'univers</h4>\n";
        echo "\t\t\t\t\t\t<input type='text' name='name' value='$universe'><br>\n";
        echo "\t\t\t\t\t</div>\n";
        echo "\t\t\t\t<div class='two columns'>\n";
        echo "\t\t\t\t\t<div>\n";
        echo "\t\t\t\t\t\t<h4>Modifier les liens externes des arcs</h4>\n";
        echo "\t\t\t\t\t\t<div class='two columns'>\n";
        echo "\t\t\t\t\t\t\t<div>\n";
        echo "\t\t\t\t\t\t\t\t<input type='text' name='link_a[name]' placeholder='Label du premier lien' value='{$logos['name_a']}'><br>\n";
        echo "\t\t\t\t\t\t\t\t<input type='url' name='link_a[url]' placeholder='URL du premier lien' value='{$logos['url_a']}'><br>\n";
        echo "\t\t\t\t\t\t\t\t<label>Logo du premier lien</label><br>\n";
        echo "\t\t\t\t\t\t\t\t<img src='/assets/img/logos/{$logos['logo_a']}' ><br>\n";
        echo "\t\t\t\t\t\t\t\t<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />";
        echo "\t\t\t\t\t\t\t\t<input type='file' class='file' name='logo_a' accept='image/*'>";
        echo "\t\t\t\t\t\t\t</div>\n";
        echo "\t\t\t\t\t\t\t<div>\n";
        echo "\t\t\t\t\t\t\t\t<input type='text' name='link_b[name]' placeholder='Label du second lien' value='{$logos['name_b']}'><br>\n";
        echo "\t\t\t\t\t\t\t\t<input type='url' name='link_b[url]' placeholder='URL du second lien' value='{$logos['url_b']}'><br>\n";
        echo "\t\t\t\t\t\t\t\t<label>Logo du second lien</label><br>\n";
        echo "\t\t\t\t\t\t\t\t<img src='/assets/img/logos/{$logos['logo_b']}' ><br>\n";
        echo "\t\t\t\t\t\t\t\t<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />";
        echo "\t\t\t\t\t\t\t\t<input type='file' class='file' name='logo_b' accept='image/*'>";
        echo "\t\t\t\t\t\t\t</div>\n";
        echo "\t\t\t\t\t\t</div>\n";
        echo "\t\t\t\t\t</div>\n";
        echo "\t\t\t\t\t<div>\n";
        echo "\t\t\t\t\t</div>\n";
        echo "\t\t\t\t</div>\n";
        echo "\t\t\t</div>\n";
        echo "\t\t\t<br><input type='submit' value='Enregistrer'>\n";
        echo "\t\t</form>";
        echo "\t\t<form action='?' method='POST' enctype='multipart/form-data'>\n";
        echo "\t\t\t<input type='hidden' name='formfilled' value='era'><br>\n";
        foreach ($eras as $id=>$era) {
            echo "\t\t\t<div class='era'>\n";
            echo "\t\t\t\t<p>".$era['name']."</p>\n";
            echo "\t\t\t\t<input type='text' name='$id' placeholder='Modifier titre'><br>\n";
            echo "\t\t\t\t<img src='/assets/img/sections/{$era['image']}' ><br>\n";                 // todo: default image
            echo "\t\t\t\t<input type='hidden' name='MAX_FILE_SIZE' value='1048576' />";
            echo "\t\t\t\t<input type='file' class='file' name='$id' accept='image/*'>";
            echo "\t\t\t</div>\n";
        }
        echo "\t\t\t<br><input type='submit' value='Enregistrer'>\n";
        echo "\t\t</form>";
        echo "\t</div>";
        echo "</div>";

    } else if (isset($_REQUEST['formfilled']) && ($_REQUEST['formfilled'] == 'universe')) {
        echo "<div class='form'>";
        $id_universe     = $_REQUEST['id_universe'];
        $name_universe   = $_REQUEST['name'];
        $link_a          = $_REQUEST['link_a'];
        $link_b          = $_REQUEST['link_b'];
        $logo_to_update = $_FILES;

        $actual_name_universe = $bdd->query("SELECT name FROM universe WHERE id_universe = '$id_universe'")->fetch(PDO::FETCH_COLUMN);
        if ($actual_name_universe != $name_universe) {
            $update_name_universe = $bdd->prepare("UPDATE universe SET name = :name, clean_name = :clean_name WHERE id_universe = '$id_universe'");
            $update_name_universe->execute([
                'name'       => $name_universe,
                'clean_name' => str_replace(' ', '_', strtolower($name_universe)),
            ]);
        }

        $update_name  = $bdd->prepare("UPDATE links SET name_a = :name_a, name_b = :name_b, url_a = :url_a, url_b = :url_b WHERE id_universe = :id_universe");
        $update_name->execute([
            'name_a'      => $link_a['name'],
            'name_b'      => $link_b['name'],
            'url_a'       => $link_a['url'],
            'url_b'       => $link_b['url'],
            'id_universe' => $id_universe
        ]);

        $update_logo_a = $bdd->prepare("UPDATE links SET logo_a = :logo WHERE id_universe = '$id_universe'");
        $update_logo_b = $bdd->prepare("UPDATE links SET logo_b = :logo WHERE id_universe = '$id_universe'");
        $old_logo      = $bdd->query("SELECT logo_a, logo_b FROM links WHERE id_universe = '$id_universe'")->fetch(PDO::FETCH_ASSOC);

        foreach($logo_to_update as $key=>$value) {
            // Fetch old image
            $logo_to_remove = $old_logo[$key];
            $path           = $ROOT."assets/img/logos/";
            $upload         = uploadCover($value, 50, $path);
            if ($upload[0] !== false) {
                $update = "update_".$key;
                $$update->execute([
                    'logo'   => $upload[1]
                ]);
                if (!empty($logo_to_remove)) {
                    // Delete image if new image uploaded
                    unlink($path.$logo_to_remove);
                }
            }
        }

        echo "<a href='/admin/manage-universe.php'><button type='button' class='btn_head'>Retour au formulaire</button></a>";
        echo "<a href='/index.php'><button type='button' class='btn_head'>Retour à l'accueil</button></a>";
        echo "</div>";

    } else if (isset($_REQUEST['formfilled']) && ($_REQUEST['formfilled'] == 'era')) {
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
        $old_image    = $bdd->prepare("SELECT image FROM era WHERE id_era = :id_era");

        foreach($image_to_update as $key=>$value) {
            // Fetch old image
            $old_image->execute([
                'id_era' => $key
            ]);
            $image_to_remove = $old_image->fetch(PDO::FETCH_COLUMN);

            $path      = $ROOT."assets/img/sections/";
            $upload    = uploadCover($value, 950, $path);
            if ($upload[0] !== false) {
                $update_image->execute([
                    'image'  => $upload[1],
                    'id_era' => $key
                ]);
                if (!empty($image_to_remove)) {
                    // Delete image if new image uploaded
                    unlink($path.$image_to_remove);
                }
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
