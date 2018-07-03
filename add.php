<?php
include('header.php');
?>
<section>
<?php
    if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        uploadCover();
        $maxid = $bdd->query('SELECT id FROM rebirth WHERE id = (SELECT MAX(id) FROM rebirth)')->fetch(PDO::FETCH_ASSOC);
        $id = ++$maxid['id'];
        $req = $bdd->prepare('INSERT INTO rebirth(id, name_period, arc, cover, contenu, urban, dctrad, topic) 
                            VALUES(:id, :name_period, :arc, :cover, :contenu, :urban, :dctrad, :topic)');
        $req->execute(array(
            'id' => $id,
            'name_period'     => $_REQUEST['name_period'],
            'arc'     => $_REQUEST['titre_arc'],
            'cover'   => $name_ext,
            'contenu' => $_REQUEST['contenu'],
            'urban'   => $_REQUEST['urban'],
            'dctrad'  => $_REQUEST['dctrad'],
            'topic'   => $_REQUEST['topic'],
            ));
        echo $_REQUEST['titre_arc']." a bien été ajouté à l'ODL";

        if (($_REQUEST['id'] != '0') && ($_REQUEST['id'] != NULL)) { // isset ?
            $newid = $_REQUEST['id'];
            $bdd->query('UPDATE rebirth SET id=id + 1 WHERE id>='.$newid);
            $maxid = $bdd->query('SELECT id FROM rebirth WHERE id = (SELECT MAX(id) FROM rebirth)')->fetch(PDO::FETCH_ASSOC);
            $oldid = $maxid['id'];
            $bdd->exec('UPDATE rebirth SET id = \''.$newid.'\' WHERE id = \''.$oldid.'\'');
            $bdd->exec('ALTER TABLE rebirth ORDER BY id ASC');
            echo " en position ".$newid;
        }
        echo ".";
?>
        <button type="button" ><a href="add.php">Retour au formulaire</a></button>
<?php
    } elseif (isset($_SESSION['pseudo'])) {
?>
    <div class="form">
        <form action="?" method="post" enctype="multipart/form-data">
            <input type="hidden" name="formfilled" value="42" />
            <label for="name_period">Période</label><br />
            <select name="name_period">
                <option value="Road to Rebirth">Road to Rebirth</option>
                <option value="Rebirth">Rebirth</option>
                <option value="Metal">Metal</option>
                <option value="Post-Metal">Post-Metal</option>
                <option value="New Justice">New Justice</option>
            </select><br />
            <label for="titre_arc">Titre de l'arc</label><br />
            <input type="text" class="input" name="titre_arc" placeholder="Titre de l'arc"><br />
            <label for="cover">Cover</label><br />
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" class="input" name="cover"><br />
            <label for="contenu">Contenu</label><br />
            <textarea class="content" name="contenu" placeholder="Liste des issues de l'arc"></textarea><br />
            <label for="publication">Publié chez :</label><br />
            <div>
                <select name="urban">
                    <option value="">Urban</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
                <select name="dctrad">
                    <option value="">DCTrad</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select><br />
            </div>
            <label for="topic">URL du topic</label><br />
            <input type="url" class="input" name="topic" placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234"><br />
            <label for="id">Position dans l'ODL</label><br />
            <input type="number" class="pos" min="0" name="id"><br />
            <input type="submit" class="btn_send" value="Envoyer">
        </form>
    </div>
<?php } else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>