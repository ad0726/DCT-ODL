<?php
include('header.php');
include('function.php');
?>
<section>
<?php
    if (isset($_SESSION['pseudo']) && isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
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
        echo $_REQUEST['titre_arc']." a bien été ajouté à l'ODL";

        if (($_REQUEST['id'] != '0') && ($_REQUEST['id'] != NULL)) { // isset ?
            $newid = $_REQUEST['id'];
            $bdd->query('UPDATE odl SET id=id + 1 WHERE id>='.$newid);
            $maxid = $bdd->query('SELECT id FROM odl WHERE id = (SELECT MAX(id) FROM odl)')->fetch(PDO::FETCH_ASSOC);
            $oldid = $maxid['id'];
            $bdd->exec('UPDATE odl SET id = \''.$newid.'\' WHERE id = \''.$oldid.'\'');
            $bdd->exec('ALTER TABLE odl ORDER BY id ASC');
            echo " en position ".$newid;
        }
        echo ".";
?>
        <button type="button" ><a href="interface.php">Retour au formulaire</a></button>
        <button type="button" ><a href="odldc.php" target="_blank">Voir l'ODL</a></button>
<?php
    } elseif (isset($_SESSION['pseudo'])) {
        echo "<button type='button' ><a href='?login-out'>Déconnexion</a></button>";
        logout()
?>
    <form action="?" method="post">
        <input type="hidden" name="formfilled" value="42" />
        <label for="titre_arc">Titre de l'arc</label><br />
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
    <?php } else {
        echo "<button class='login' type='button' ><a href='?login'>Connexion</a></button>";
        displayLogin();
    } ?>
</section>