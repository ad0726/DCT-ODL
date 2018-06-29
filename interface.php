<?php
include('function.php');
?>
<section>
<?php
    if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        // if ($_REQUEST['id'] == 0 || NULL) { // isset or empty ?
        //     $_REQUEST['id'] = NULL;
        //     $id = $bdd->query('SELECT id FROM odl WHERE id = (SELECT MAX(id) FROM odl)')->fetch(PDO::FETCH_ASSOC);
        //     $id = ++$id['id'];
        // }
        // else {
        //     $id = $bdd->query('SELECT id FROM odl WHERE id = (SELECT MAX(id) FROM odl)')->fetch(PDO::FETCH_ASSOC);
        //     $id = ++$id['id'];
        // }
        $maxid = $bdd->query('SELECT id FROM odl WHERE id = (SELECT MAX(id) FROM odl)')->fetch(PDO::FETCH_ASSOC);
        $id = ++$maxid['id'];
        $req = $bdd->prepare('INSERT INTO odl(id, arc, cover, contenu, urban, dctrad, topic) 
                            VALUES(:new_id, :new_arc, :new_cover, :new_contenu, :new_urban, :new_dctrad, :new_topic)');
        $req->execute(array(
            'new_id' => $id,
            'new_arc'     => $_REQUEST['titre_arc'],
            'new_cover'   => $_REQUEST['cover'],
            'new_contenu' => $_REQUEST['contenu'],
            'new_urban'   => $_REQUEST['urban'],
            'new_dctrad'  => $_REQUEST['dctrad'],
            'new_topic'   => $_REQUEST['topic'],
            ));
        echo "L'ODL a bien été mis à jour.";
        if (($_REQUEST['id'] != '0') && ($_REQUEST['id'] != NULL)) { // isset ?
            // echo $_REQUEST['id']."blop";
            $maxid = $bdd->query('SELECT id FROM odl WHERE id = (SELECT MAX(id) FROM odl)')->fetch(PDO::FETCH_ASSOC);
            // echo $maxid['id'];
            $oldid = $maxid['id'];
            $newid = $_REQUEST['id'];
            // echo $newid;
            $bdd->exec('UPDATE odl SET id = \''.$newid.'\' WHERE id = \''.$oldid.'\''); // mets bien à jour mais ne range pas le reste de la table
        }
    } else {
?>
    <form action="?" method="post">
        <input type="hidden" name="formfilled" value="42" />
        <label for="titre">Titre de l'arc</label><br />
        <input type="text" name="titre_arc" placeholder="Titre de l'arc"><br />
        <label for="cover">URL de la cover</label><br />
        <input type="url" name="cover" placeholder="http://xooimage.com/image"><br />
        <label for="contenu">Contenu</label><br />
        <textarea name="contenu" name="contenu" placeholder="Liste des issues de l'arc"></textarea><br />
        <label for="publication">Publié chez :</label><br />
        <input type="hidden" name="urban" value="0">
        <input type="checkbox" name="urban" value="1"> Urban<br />
        <input type="hidden" name="dctrad" value="0">
        <input type="checkbox" name="dctrad" value="1"> DCTrad<br />
        <label for="topic">URL du topic</label><br />
        <input type="url" name="topic" placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234"><br />
        <label for="id">Position dans l'ODL</label><br />
        <input type="number" min="0" name="id"><br />
        <input type="submit" value="Envoyer">
    </form>
    <?php } ?>
</section>