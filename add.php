<?php
include('header.php');
?>
<section>
<?php
$blop = "Urban Comics (Librairie) : Superman - L'homme de Demain #01.";
    if (!empty($_REQUEST['name_period']) && !empty($_REQUEST['titre_arc']) && !empty($_REQUEST['contenu']) && ($_REQUEST['urban'] != "") && ($_REQUEST['dctrad'] != "")) {
        uploadCover();
        $maxid = $bdd->query('SELECT id FROM odldc_rebirth WHERE id = (SELECT MAX(id) FROM odldc_rebirth)')->fetch(PDO::FETCH_ASSOC);
        $id    = ++$maxid['id'];
        $req   = $bdd->prepare('INSERT INTO odldc_rebirth(id, name_period, arc, cover, contenu, urban, dctrad, link_urban, topic) 
                            VALUES(:id, :name_period, :arc, :cover, :contenu, :urban, :dctrad, :link_urban, :topic)');
        $req->execute(array(
            'id'          => $id,
            'name_period' => htmlentities($_REQUEST['name_period']),
            'arc'         => htmlentities($_REQUEST['titre_arc']),
            'cover'       => $name_ext,
            'contenu'     => htmlentities($_REQUEST['contenu']),
            'urban'       => $_REQUEST['urban'],
            'dctrad'      => $_REQUEST['dctrad'],
            'link_urban'  => $_REQUEST['link_urban'],
            'topic'       => $_REQUEST['topic'],
            ));
        echo $_REQUEST['titre_arc']." a bien été ajouté à l'ODL";

        if (($_REQUEST['id'] != '0') && ($_REQUEST['id'] != NULL)) { // isset ?
            $newid = $_REQUEST['id'];
            $bdd->query('UPDATE odldc_rebirth SET id=id + 1 WHERE id>='.$newid);
            $maxid = $bdd->query('SELECT id FROM odldc_rebirth WHERE id = (SELECT MAX(id) FROM odldc_rebirth)')->fetch(PDO::FETCH_ASSOC);
            $oldid = $maxid['id'];
            $bdd->exec('UPDATE odldc_rebirth SET id = \''.$newid.'\' WHERE id = \''.$oldid.'\'');
            $bdd->exec('ALTER TABLE odldc_rebirth ORDER BY id ASC');
            echo " en position ".$newid;
        }
        $date = new DateTime();
        if (!isset($newid)) {
            $pos = $id;
        } else {
            $pos = $newid;
        }
        $backlog = array(
            'id'          => $date->format('Y-m-d_H:i:s'),
            'name_period' => $_REQUEST['name_period'],
            'position'    => $pos,
            'title'       => $_REQUEST['titre_arc'],
        );
        $req_bl   = $bdd->prepare('INSERT INTO odldc_backlog(id, name_period, new_position, title) 
                            VALUES(:id, :name_period, :new_position, :title)');
        $req_bl->execute(array(
            'id'           => $backlog['id'],
            'name_period'  => $backlog['name_period'],
            'new_position' => $backlog['position'],
            'title'        => $backlog['title']
        ));
        echo ".";
?>
        <button type="button" ><a href="add.php">Retour au formulaire</a></button>
<?php
    } elseif (isset($_SESSION['pseudo'])) {
?>
    <div class="form">
        <h2>Ajouter un arc</h2>
        <form action="?" method="post" enctype="multipart/form-data">
            <input type="hidden" name="formfilled" value="42" />
            <select name="name_period">
                <option value="">Période</option>
                <option value="Road to Rebirth">Road to Rebirth</option>
                <option value="Rebirth">Rebirth</option>
                <option value="Metal">Metal</option>
                <option value="Post-Metal">Post-Metal</option>
                <option value="New Justice">New Justice</option>
            </select> *<br />
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