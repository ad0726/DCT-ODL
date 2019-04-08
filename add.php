<?php
include('header.php');
?>
<section>
<?php
    if (isset($_REQUEST['id_period']) && ($_REQUEST['id_period'] != "") && !empty($_REQUEST['titre_arc']) && !empty($_REQUEST['contenu']) && isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        $era = strtolower($_REQUEST['name_era']);

        $isEvent = 0;
        if (isset($_REQUEST['isEvent']) && $_REQUEST['isEvent'] == "on") $isEvent = 1;

        echo "<div class='form'>";

        $upload = uploadCover();

        if ($upload[0] === TRUE) {
            $maxid = $bdd->query("SELECT id FROM odldc_$era WHERE id = (SELECT MAX(id) FROM odldc_$era)")->fetch(PDO::FETCH_ASSOC);
            $id    = ++$maxid['id'];
            $req   = $bdd->prepare("INSERT INTO odldc_$era(id, id_period, arc, cover, contenu, urban, dctrad, isEvent) 
                                VALUES(:id, :id_period, :arc, :cover, :contenu, :urban, :dctrad, :isEvent)");
            $req->execute(array(
                'id'        => $id,
                'id_period' => $_REQUEST['id_period'],
                'arc'       => htmlentities($_REQUEST['titre_arc']),
                'cover'     => $upload[1],
                'contenu'   => htmlentities($_REQUEST['contenu']),
                'urban'     => $_REQUEST['urban'],
                'dctrad'    => $_REQUEST['dctrad'],
                'isEvent'   => $isEvent
                ));
            echo $_REQUEST['titre_arc']." a bien été ajouté à l'ODL";

            if (($_REQUEST['id'] != '0') && ($_REQUEST['id'] != NULL)) { // isset ?
                $newid = $_REQUEST['id'];
                $bdd->query("UPDATE odldc_$era SET id=id + 1 WHERE id>=".$newid);
                $maxid = $bdd->query("SELECT id FROM odldc_$era WHERE id = (SELECT MAX(id) FROM odldc_$era)")->fetch(PDO::FETCH_ASSOC);
                $oldid = $maxid['id'];
                $bdd->exec("UPDATE odldc_$era SET id = $newid WHERE id = $oldid");
                $bdd->exec("ALTER TABLE odldc_$era ORDER BY id ASC");
                echo " en position $newid";
            }

            // Changelog
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone('+0100'));

            (!isset($newid)) ? $pos = $id : $pos = $newid;

            $query    = $bdd->query("SELECT name FROM odldc_period WHERE id_period = \"".$_REQUEST['id_period']."\"")->fetch(PDO::FETCH_ASSOC);
            $period   = $query['name'];

            $changelog = array(
                'id'          => $date->format('Y-m-d_H:i:s'),
                'name_period' => $period,
                'position'    => $pos,
                'title'       => htmlentities($_REQUEST['titre_arc']),
            );

            $query = $bdd->prepare("INSERT INTO odldc_changelog(id, author, cl_type, name_era, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, isEvent) 
                                VALUES(:id, :author, :cl_type, :name_era, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :isEvent)");
            $query->execute(array(
                'id'           => $changelog['id'],
                'author'       => $_SESSION['pseudo'],
                'cl_type'      => 'add',
                'name_era'     => $_REQUEST["name_era"],
                'name_period'  => $changelog['name_period'],
                'old_position' => '',
                'new_position' => $changelog['position'],
                'title'        => $changelog['title'],
                'new_title'    => '',
                'cover'        => '',
                'content'      => '',
                'urban'        => '',
                'dctrad'       => '',
                'isEvent'      => $isEvent
            ));

            $count = $bdd->query("SELECT count(*) FROM odldc_changelog")->fetch(PDO::FETCH_ASSOC);
            if ($count['count(*)'] > 100) {
                $bdd->exec("DELETE FROM odldc_changelog ORDER BY id ASC LIMIT 1");
            }

            echo ".";
    ?>
                <a href="add.php"><button type="button" class="btn_head">Retour au formulaire</button></a>
                <a href="index.php"><button type="button" class="btn_head">Retour à l'accueil</button></a>
            </div>
<?php
        } else {
            print_r($upload[1]);
            echo "<a href='add.php'><button type='button' class='btn_head'>Retour au formulaire</button></a>";
        }
    } elseif (isset($_SESSION['pseudo'])) {
        $eras       = $bdd->query('SELECT * FROM odldc_era');
        $periods    = $bdd->query('SELECT * FROM odldc_period');
        $lastPeriod = $bdd->query('SELECT clean_name FROM odldc_period WHERE id_period IN 
                                    (SELECT id_period FROM odldc_rebirth WHERE id IN 
                                        (SELECT MAX(id) FROM odldc_rebirth))'
                            )->fetch(PDO::FETCH_ASSOC)['clean_name'];
?>
    <div class="form">
        <h2>Ajouter un arc</h2>
        <form action="?" method="post" enctype="multipart/form-data">
            <div class="head_form">
                <div class="info_sections">
                    <input type="hidden" name="formfilled" value="42" />
                    <select name="name_era" required>
                        <option value="Rebirth">Rebirth</option>
                    </select><br />
                    <select name="id_period" required>
                        <option value="">Période</option>
                    <?php
                    while ($namePeriod = $periods->fetch(PDO::FETCH_ASSOC)) {
                        $namePeriodFormat = strtolower(str_replace(" ", "_", $namePeriod['name']));
                        $selected         = ($namePeriodFormat == $lastPeriod) ? "selected" : "";

                        echo "<option value='".$namePeriod['id_period']."' $selected >".$namePeriod['name']."</option>\n";
                    }
                    ?>
                    </select>
                </div>
                <div class="div_checkbox">
                    <label for="checkboxIsEvent">Event ?</label><br />
                    <input type="checkbox" name="isEvent" id="checkboxIsEvent" <?php if(isset($_REQUEST['isEvent'])) echo "checked"; ?>>
                </div>
            </div>
            <input type="text" class="input" name="titre_arc" placeholder="Titre de l'arc" value="<?= @$_REQUEST['titre_arc'] ?>" required><br />
            <label for="cover">Cover</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" class="file" name="cover" required><br />
            <textarea class="content" name="contenu" placeholder="Liste des issues de l'arc" required><?= @$_REQUEST['contenu'] ?></textarea><br />
            <div class="isUrban_DCT">
                <div class="isUrban">
                    <label for="CBisUrban">Urban</label>
                    <input type="checkbox" name="isUrban" id="CBisUrban" <?php if(isset($_REQUEST['urban']) && !empty($_REQUEST['urban'])) echo "checked"; ?>>
                </div>
                <div class="isDCT">
                    <label for="CBisDCT">DCTrad</label>
                    <input type="checkbox" name="isDCT" id="CBisDCT" <?php if(isset($_REQUEST['dctrad']) && !empty($_REQUEST['dctrad'])) echo "checked"; ?>>
                </div>
            </div>
            <input type="url" class="input" name="urban" id="LinkUrban"
            <?php 
            if (isset($_REQUEST['urban']) && !empty($_REQUEST['urban'])) {
                echo "style='display: block;'";
            } else { 
                echo "style='display: none;'";
            }
            ?> 
            placeholder="https://www.mdcu-comics.fr/comics-vo/comics-vo-44779" value="<?= @$_REQUEST['urban'] ?>">
            <input type="url" class="input" name="dctrad" id="LinkDCT"
            <?php
            if (isset($_REQUEST['dctrad']) && !empty($_REQUEST['dctrad'])) {
                echo "style='display: block;'";
            } else {
                echo "style='display: none;'";
            }
            ?>
            placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234" value="<?= @$_REQUEST['dctrad'] ?>">
            <div class="tooltip">
                <span class="tooltiptext">À utiliser pour rajouter entre deux arcs déjà présents. Sinon ne pas remplir.</span>
                <label for="id">Position dans l'ODL</label>
                <input type="number" class="pos" min="0" name="id">
            </div>
            <input type="submit" class="btn_send" value="Envoyer">
        </form>
    </div>
<?php 
echo "<div id='preview' class='content_period' style='display: block;'>\n";
echo "\t<table>\n";
echo "\t\t<tr class='line'>\n";
echo "\t\t\t<td class='cel_img'><img src=\"\" style='width: 150px;'></td>\n";
echo "\t\t\t<td class='cel_title'><span><h3></h3></span></td>\n";
echo "\t\t\t<td class='cel_content'><p></p></td>\n";
echo "\t\t\t<td class='cel_publi'>\n";
echo "\t\t\t\t<h4>Disponible chez</h4>\n";
echo "\t\t\t\t<div class='img_publi'>\n";
echo "\t\t\t\t\t<img src='assets/img/logo_urban_mini.png' id='logoUrban' class='logo_opacity'>\n";
echo "\t\t\t\t\t<img src='assets/img/logo_dct_mini.png' id='logoDCT' class='logo_opacity'>\n";
echo "\t\t\t\t</div>\n";
echo "\t\t\t</td>\n";
echo "\t\t</tr>\n";
echo "\t</table>\n";
echo "</div>\n";
} else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>
<?php include("footer.php"); ?>