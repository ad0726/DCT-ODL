<?php
include('header.php');
?>
<section>
<?php
    if (!empty($_REQUEST['name_period']) && !empty($_REQUEST['titre_arc']) && !empty($_REQUEST['contenu']) && ($_REQUEST['urban'] != "") && ($_REQUEST['dctrad'] != "")) {
        $era = $_REQUEST['name_era'];
        $isEvent = 1;
        if (isset($_REQUEST['isEvent']) && $_REQUEST['isEvent'] == "on") {
            $isEvent = 1;
        }
        echo "<div class='form'>";
        $upload = uploadCover();
        if ($upload[0] === TRUE) {
            $maxid = $bdd->query("SELECT id FROM odldc_$era WHERE id = (SELECT MAX(id) FROM odldc_$era)")->fetch(PDO::FETCH_ASSOC);
            $id    = ++$maxid['id'];
            $req   = $bdd->prepare("INSERT INTO odldc_$era(id, name_period, arc, cover, contenu, urban, dctrad, link_urban, topic, isEvent) 
                                VALUES(:id, :name_period, :arc, :cover, :contenu, :urban, :dctrad, :link_urban, :topic, :isEvent)");
            $req->execute(array(
                'id'          => $id,
                'name_period' => htmlentities($_REQUEST['name_period']),
                'arc'         => htmlentities($_REQUEST['titre_arc']),
                'cover'       => $upload[1],
                'contenu'     => htmlentities($_REQUEST['contenu']),
                'urban'       => $_REQUEST['urban'],
                'dctrad'      => $_REQUEST['dctrad'],
                'link_urban'  => $_REQUEST['link_urban'],
                'topic'       => $_REQUEST['topic'],
                'isEvent'     => $isEvent
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
            if (!isset($newid)) {
                $pos = $id;
            } else {
                $pos = $newid;
            }
            $changelog = array(
                'id'          => $date->format('Y-m-d_H:i:s'),
                'name_period' => htmlentities($_REQUEST['name_period']),
                'position'    => $pos,
                'title'       => htmlentities($_REQUEST['titre_arc']),
            );
            $query = $bdd->prepare("INSERT INTO odldc_changelog(id, author, cl_type, name_era, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, link_urban, topic, isEvent) 
                                VALUES(:id, :author, :cl_type, :name_era, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :link_urban, :topic, :isEvent)");
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
                'link_urban'   => '',
                'topic'        => '',
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
?>
    <div class="form">
        <h2>Ajouter un arc</h2>
        <form action="?" method="post" enctype="multipart/form-data">
            <div class="head_form">
                <div class="info_sections">
                    <input type="hidden" name="formfilled" value="42" />
                    <select name="name_era">
                        <option value="Rebirth">Rebirth</option>
                    </select> *<br />
                    <select name="name_period">
                        <option value="">Période</option>
                        <option value="Road to Rebirth">Road to Rebirth</option>
                        <option value="Rebirth">Rebirth</option>
                        <option value="Metal">Metal</option>
                        <option value="Post-Metal">Post-Metal</option>
                        <option value="New Justice">New Justice</option>
                    </select> *
                </div>
                <div class="info_isEvent">
                    <label for="checkboxIsEvent">Event ?</label><br />
                    <input type="checkbox" name="isEvent" id="checkboxIsEvent" <?php if(isset($_REQUEST['isEvent'])) echo "checked"; ?>>
                </div>
            </div>
            <input type="text" class="input" name="titre_arc" placeholder="Titre de l'arc" value="<?= @$_REQUEST['titre_arc'] ?>"> *<br />
            <label for="cover">Cover</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" class="file" name="cover"> *<br />
            <textarea class="content" name="contenu" placeholder="Liste des issues de l'arc"><?= @$_REQUEST['contenu'] ?></textarea> *<br />
            <div>
                <label for="publication">Publié chez :</label>
                <select name="urban">
                    <option value="">Urban</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
                <select name="dctrad">
                    <option value="">DCTrad</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select> *<br />
            </div>
            <input type="url" class="input" name="link_urban" placeholder="https://www.mdcu-comics.fr/comics-vo/comics-vo-44779" value="<?= @$_REQUEST['link_urban'] ?>"><br />
            <input type="url" class="input" name="topic" placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234" value="<?= @$_REQUEST['topic'] ?>"><br />
            <div class="tooltip">
                <span class="tooltiptext">À utiliser pour rajouter entre deux arcs déjà présents. Sinon ne pas remplir.</span>
                <label for="id">Position dans l'ODL</label>
                <input type="number" class="pos" min="0" name="id">
            </div>
            <input type="submit" class="btn_send" value="Envoyer">
        </form>
    </div>
<?php } else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>
<?php include("footer.php"); ?>