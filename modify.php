<?php
include('header.php');
?>
<section>
<?php
    if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        uploadCover();
        if (!empty($_REQUEST['new_title'])) {
            $bdd->exec('UPDATE rebirth SET arc = \''.$_REQUEST['new_title'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if (!empty($name_ext)) {
            $bdd->exec('UPDATE rebirth SET cover = \''.$name_ext.'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if (!empty($_REQUEST['new_content'])) {
            $bdd->exec('UPDATE rebirth SET contenu = \''.$_REQUEST['new_content'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if ($_REQUEST['new_urban'] != "") {
            $bdd->exec('UPDATE rebirth SET urban = \''.$_REQUEST['new_urban'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if ($_REQUEST['new_dctrad'] != "") {
            $bdd->exec('UPDATE rebirth SET dctrad = \''.$_REQUEST['new_dctrad'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if (!empty($_REQUEST['new_topic'])) {
            $bdd->exec('UPDATE rebirth SET topic = \''.$_REQUEST['new_topic'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if (!empty($_REQUEST['new_id'])) {
            $bdd->exec('UPDATE rebirth SET id = \'-1\' WHERE id = \''.$_REQUEST['id'].'\'');
            $bdd->query('UPDATE rebirth SET id = id - 1 WHERE id BETWEEN '.$_REQUEST['id'].' AND '.$_REQUEST['new_id']);
            $bdd->exec('UPDATE rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
            $bdd->exec('ALTER TABLE rebirth ORDER BY id ASC');
        } elseif (!empty($_REQUEST['new_id'])) {
            $bdd->exec('UPDATE rebirth SET id = \'-1\' WHERE id = '.$_REQUEST['id']);
            $bdd->query('UPDATE rebirth SET id = id + 1 WHERE id BETWEEN '.$_REQUEST['new_id'].' AND '.$_REQUEST['id']);
            $bdd->exec('UPDATE rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
            $bdd->exec('ALTER TABLE rebirth ORDER BY id ASC');
        }
        echo "L'ODL a bien été mis à jour.";
?>
        <button type="button" ><a href="modify.php">Retour au formulaire</a></button>
<?php
    } elseif (isset($_SESSION['pseudo'])) {
?>
    <div class="form">
        <form action="?" method="post" enctype="multipart/form-data">
            <input type="hidden" name="formfilled" value="42" />
            <label for="new_id">Position actuelle dans l'ODL</label><br />
            <input type="number" class="pos" min="0" name="id"><br />
            <label for="new_title">Titre de l'arc</label><br />
            <input type="text" class="input" name="new_title" placeholder="Titre de l'arc"><br />
            <label for="cover">Cover</label><br />
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" class="input" name="cover"><br />
            <label for="new_content">Contenu</label><br />
            <textarea class="content" name="new_content" placeholder="Liste des issues de l'arc"></textarea><br />
            <label for="publication">Publié chez :</label><br />
            <div>
                <select name="new_urban">
                    <option value="">Urban</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
                <select name="new_dctrad">
                    <option value="">DCTrad</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select><br />
            </div>
            <label for="new_topic">URL du topic</label><br />
            <input type="url" class="input" name="new_topic" placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234"><br />
            <label for="new_id">Position voulue dans l'ODL</label><br />
            <input type="number" class="pos" min="0" name="new_id"><br />
            <input type="submit" class="btn_send" value="Envoyer">
        </form>
    </div>
<?php } else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>