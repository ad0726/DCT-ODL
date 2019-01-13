<?php
include('header.php');
?>
<section>
<?php
    if (!empty($_REQUEST['id']) && isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        $era  = $_REQUEST['name_era'];
        $info = $bdd->query('SELECT * FROM odldc_'.$era.' WHERE id = \''.$_REQUEST['id'].'\'')->fetch(PDO::FETCH_ASSOC);

        $isEvent = 0;
        if (isset($_REQUEST['isEvent']) && $_REQUEST['isEvent'] == "on") {
            $isEvent = 1;
        }

        echo "<div class='form'>";
        // Delete image if new image uploaded
        if ($_FILES['cover']['error'] == 0) {
            $old_cover = $bdd->query('SELECT cover FROM odldc_rebirth WHERE id = \''.$_REQUEST['id'].'\'')->fetch(PDO::FETCH_ASSOC);
            unlink($old_cover['cover']);
        }
        // Upload new image
        $upload = uploadCover();
            // Update image
        $update_img = FALSE;
        if ($upload[0] === TRUE) {
            if (!empty($upload[1])) {
                $bdd->exec('UPDATE odldc_rebirth SET cover = \''.$upload[1].'\' WHERE id = \''.$_REQUEST['id'].'\'');
                $update_img = TRUE;
            }
        } else {
            d($upload[1], FALSE);
        }
        // Update period
        if (!empty($_REQUEST['id_period'])) {
            $bdd->exec('UPDATE odldc_rebirth SET id_period = \''.$_REQUEST['id_period'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        // Update title
        if (!empty($_REQUEST['new_title'])) {
            $_REQUEST['new_title'] = htmlentities($_REQUEST['new_title'], ENT_QUOTES);
            $bdd->exec('UPDATE odldc_rebirth SET arc = \''.$_REQUEST['new_title'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        // Update content
        $update_content = FALSE;
        if (!empty($_REQUEST['new_content'])) {
            $_REQUEST['new_content'] = htmlentities($_REQUEST['new_content'], ENT_QUOTES);
            $bdd->exec('UPDATE odldc_rebirth SET contenu = \''.$_REQUEST['new_content'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_content = TRUE;
        }
        // Update Urban's link
        $update_urban = FALSE;
        if ($_REQUEST['new_urban'] != $info['urban']) {
            $bdd->exec('UPDATE odldc_rebirth SET urban = \''.$_REQUEST['new_urban'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_urban = TRUE;
        }
        // Update DCTrad's link
        $update_dct = FALSE;
        if ($_REQUEST['new_dctrad'] != $info['dctrad']) {
            $bdd->exec('UPDATE odldc_rebirth SET dctrad = \''.$_REQUEST['new_dctrad'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_dct = TRUE;
        }
        // Update Id
        if (!empty($_REQUEST['new_id']) && ($_REQUEST['new_id'] > $_REQUEST['id'])) {
            $bdd->exec('UPDATE odldc_rebirth SET id = \'-1\' WHERE id = \''.$_REQUEST['id'].'\'');
            $bdd->exec('UPDATE odldc_rebirth SET id = id - 1 WHERE id BETWEEN '.$_REQUEST['id'].' AND '.$_REQUEST['new_id']);
            $bdd->exec('UPDATE odldc_rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
            $bdd->exec('ALTER TABLE odldc_rebirth ORDER BY id ASC');
        } elseif (!empty($_REQUEST['new_id']) && ($_REQUEST['new_id'] < $_REQUEST['id'])) {
            $bdd->exec('UPDATE odldc_rebirth SET id = \'-1\' WHERE id = '.$_REQUEST['id']);
            $bdd->exec('UPDATE odldc_rebirth SET id = id + 1 WHERE id BETWEEN '.$_REQUEST['new_id'].' AND '.$_REQUEST['id']);
            $bdd->exec('UPDATE odldc_rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
            $bdd->exec('ALTER TABLE odldc_rebirth ORDER BY id ASC');
        }
        // Is Event
        $bdd->exec('UPDATE odldc_rebirth SET isEvent = \''.$isEvent.'\' WHERE id = \''.$_REQUEST['id'].'\'');

        // Changelog
        $title   = $bdd->query('SELECT arc FROM odldc_rebirth WHERE id = \''.$_REQUEST['id'].'\'')->fetch(PDO::FETCH_ASSOC);
        $date    = new DateTime();
        $date->setTimezone(new DateTimeZone('+0100'));
        
        $clIsEvent = "0";                                   // Isn't event before submit
        if ($_REQUEST['isEventReturn'] == "checked") {      // If is event before submit
            $clIsEvent = "1";
        }
        if (($isEvent == 1) && ($clIsEvent == "0")) {       // If isn't event before submit and update to isEvent = TRUE
            $clIsEvent = "+1";
        } elseif (($isEvent == 0) && ($clIsEvent == "1")) { // If is event before submit and update to isEvent = FALSE
            $clIsEvent = "-1";
        }
        $changelog = array(
            'id'       => $date->format('Y-m-d_H:i:s'),
            'era'      => $_REQUEST['name_era'],
            'position' => array(
                'old' => $_REQUEST['id'],
                'new' => $_REQUEST['new_id']
            ),
            'title' => array(
                'old' => $title['arc'],
                'new' => $_REQUEST['new_title']
            ),
            'cover'   => $update_img,
            'content' => $update_content,
            'urban'   => $update_urban,
            'dctrad'  => $update_dct
        );

        $query = $bdd->prepare('INSERT INTO odldc_changelog(id, author, cl_type, name_era, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, isEvent) 
                            VALUES(:id, :author, :cl_type, :name_era, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :isEvent)');
        $query->execute(array(
        'id'           => $changelog['id'],
        'author'       => $_SESSION['pseudo'],
        'cl_type'      => 'modify',
        'name_era'     => $changelog['era'],
        'name_period'  => '',
        'old_position' => $changelog['position']['old'],
        'new_position' => $changelog['position']['new'],
        'title'        => $changelog['title']['old'],
        'new_title'    => $changelog['title']['new'],
        'cover'        => $changelog['cover'],
        'content'      => $changelog['content'],
        'urban'        => $changelog['urban'],
        'dctrad'       => $changelog['dctrad'],
        'isEvent'      => $clIsEvent
        ));

        $count = $bdd->query('SELECT count(*) FROM odldc_changelog')->fetch(PDO::FETCH_ASSOC);

        if ($count['count(*)'] > 100) {
            $bdd->exec('DELETE FROM odldc_changelog ORDER BY id ASC LIMIT 1');
        }

        echo "L'ODL a bien été mis à jour.";
    ?>
            <br />
            <a href="modify.php"><button type="button" class="btn_head">Retour au formulaire</button></a>
            <a href="index.php"><button type="button" class="btn_head">Retour à l'accueil</button></a>
        </div>
<?php
    } elseif (isset($_SESSION['pseudo'])) {
        if (isset($_GET["id"])) {
            $id              = $_GET["id"];
            $era             = $_GET["era"];
            $info            = $bdd->query('SELECT * FROM odldc_'.$era.' WHERE id = \''.$id.'\'')->fetch(PDO::FETCH_ASSOC);
            $periods         = $bdd->query('SELECT * FROM odldc_period');
            $checkboxIsEvent = "";

            if($info['isEvent'] == TRUE) {
                $checkboxIsEvent = "checked";
            }
        }
?>
    <div class="form">
        <h2>Modifier un arc</h2>
        <form action="?" method="post" enctype="multipart/form-data">
            <input type="hidden" name="formfilled" value="42" />
            <input type="hidden" name="name_era" value="<?= @$era ?>" />
            <div class="head_form">
                <div>
                    <label for="id">Position actuelle<br />dans l'ODL</label>
                    <input type="number" class="pos" min="0" name="id" value="<?= @$id ?>" required><br />
                </div>
                <select name="id_period">
                        <option value="">Période</option>
                    <?php
                    while ($namePeriod = $periods->fetch(PDO::FETCH_ASSOC)) {
                        $namePeriodFormat = strtolower(str_replace(" ", "_", $namePeriod['name']));
                        $selected         = ($namePeriodFormat == $_GET['period']) ? "selected" : "";

                        echo "<option value='".$namePeriod['id_period']."' $selected >".$namePeriod['name']."</option>\n";
                    }
                    ?>
                </select>
                <div class="info_isEvent">
                    <label for="checkboxIsEvent">Event ?</label><br />
                    <input type="checkbox" name="isEvent" id="checkboxIsEvent" <?= @$checkboxIsEvent ?>>
                    <input type="hidden" name="isEventReturn" value="<?= @$checkboxIsEvent ?>">
                </div>
            </div>
            <input type="text" class="input" name="new_title" placeholder="Titre de l'arc"  value="<?= @$info['arc'] ?>"><br />
            <label for="cover">Cover</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" class="file" name="cover"><br />
            <textarea class="content" name="new_content" placeholder="Liste des issues de l'arc"><?= @$info['contenu'] ?></textarea><br />
            <div class="isUrban_DCT">
                <div class="isUrban">
                    <label for="CBisUrban">Urban</label>
                    <input type="checkbox" name="isUrban" id="CBisUrban" <?php if(!empty($info['urban'])) echo "checked"; ?>>
                </div>
                <div class="isDCT">
                    <label for="CBisDCT">DCTrad</label>
                    <input type="checkbox" name="isDCT" id="CBisDCT" <?php if(!empty($info['dctrad'])) echo "checked"; ?>>
                </div>
            </div>
            <input type="url" class="input" name="new_urban" id="LinkUrban"
            <?php 
            if (isset($info['urban']) && !empty($info['urban'])) {
                echo "style='display: block;'";
            } else { 
                echo "style='display: none;'";
            }
            ?> 
            placeholder="https://www.mdcu-comics.fr/comics-vo/comics-vo-44779" value="<?= @$info['urban'] ?>">
            <input type="url" class="input" name="new_dctrad" id="LinkDCT"
            <?php
            if (isset($info['dctrad']) && !empty($info['dctrad'])) {
                echo "style='display: block;'";
            } else {
                echo "style='display: none;'";
            }
            ?>
            placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234" value="<?= @$info['dctrad'] ?>">
            <label for="new_id">Position voulue dans l'ODL</label>
            <input type="number" class="pos" min="0" name="new_id"><br />
            <input type="submit" class="btn_send" value="Envoyer">
        </form>
    </div>
<?php } else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>
<?php include("footer.php"); ?>