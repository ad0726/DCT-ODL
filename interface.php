<?php
include('function.php');
?>
<section>
<?php
    if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        $req = $bdd->prepare('INSERT INTO odl(arc, cover, contenu, urban, dctrad, topic) 
                            VALUES(:new_arc, :new_cover, :new_contenu, :new_urban, :new_dctrad, :new_topic)');
        $req->execute(array(
            'new_arc'     => $_REQUEST['titre_arc'],
            'new_cover'   => $_REQUEST['cover'],
            'new_contenu' => $_REQUEST['contenu'],
            'new_urban'   => $_REQUEST['urban'],
            'new_dctrad'  => $_REQUEST['dctrad'],
            'new_topic'   => $_REQUEST['topic'],
            ));
        echo "L'ODL a bien été mis à jour.";;
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
        <input type="submit" value="Envoyer">
    </form>
    <?php } ?>
</section>