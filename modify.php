<?php
include('header.php');
?>
<section>
<?php
    if (!empty($_REQUEST['id']) && isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
        $title = $bdd->exec('SELECT arc FROM odldc_rebirth WHERE id = \''.$_REQUEST['id'].'\'');
        uploadCover();
        if (!empty($_REQUEST['new_title'])) {
            $_REQUEST['new_title'] = htmlentities($_REQUEST['new_title'], ENT_QUOTES);
            $bdd->exec('UPDATE odldc_rebirth SET arc = \''.$_REQUEST['new_title'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        $update_img = FALSE;
        if (!empty($name_ext)) {
            $bdd->exec('UPDATE odldc_rebirth SET cover = \''.$name_ext.'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_img = TRUE;
        }
        $update_content = FALSE;
        if (!empty($_REQUEST['new_content'])) {
            $_REQUEST['new_content'] = htmlentities($_REQUEST['new_content'], ENT_QUOTES);
            $bdd->exec('UPDATE odldc_rebirth SET contenu = \''.$_REQUEST['new_content'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_content = TRUE;
        }
        if ($_REQUEST['new_urban'] != "") {
            $bdd->exec('UPDATE odldc_rebirth SET urban = \''.$_REQUEST['new_urban'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        if ($_REQUEST['new_dctrad'] != "") {
            $bdd->exec('UPDATE odldc_rebirth SET dctrad = \''.$_REQUEST['new_dctrad'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
        }
        $update_link_urban = FALSE;
        if (!empty($_REQUEST['new_link_urban'])) {
            $bdd->exec('UPDATE odldc_rebirth SET link_urban = \''.$_REQUEST['new_link_urban'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_link_urban = TRUE;
        }
        $update_topic = FALSE;
        if (!empty($_REQUEST['new_topic'])) {
            $bdd->exec('UPDATE odldc_rebirth SET topic = \''.$_REQUEST['new_topic'].'\' WHERE id = \''.$_REQUEST['id'].'\'');
            $update_topic = TRUE;
        }
        if (!empty($_REQUEST['new_id'])) {
            $bdd->exec('UPDATE odldc_rebirth SET id = \'-1\' WHERE id = \''.$_REQUEST['id'].'\'');
            $bdd->query('UPDATE odldc_rebirth SET id = id - 1 WHERE id BETWEEN '.$_REQUEST['id'].' AND '.$_REQUEST['new_id']);
            $bdd->exec('UPDATE odldc_rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
            $bdd->exec('ALTER TABLE rebirth ORDER BY id ASC');
        } elseif (!empty($_REQUEST['new_id'])) {
            $bdd->exec('UPDATE odldc_rebirth SET id = \'-1\' WHERE id = '.$_REQUEST['id']);
            $bdd->query('UPDATE odldc_rebirth SET id = id + 1 WHERE id BETWEEN '.$_REQUEST['new_id'].' AND '.$_REQUEST['id']);
            $bdd->exec('UPDATE odldc_rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
            $bdd->exec('ALTER TABLE odldc_rebirth ORDER BY id ASC');
        }
        $date = new DateTime();
        $backlog = array(
            'id'          => $date->format('Y-m-d_H:i:s'),
            'name_period' => $_REQUEST['name_period'],
            'position'    => array(
                'old' => $_REQUEST['id'],
                'new' => $_REQUEST['new_id']
            ),
            'title' => array(
                'old' => $title,
                'new' => $_REQUEST['new_title']
            ),
            'cover'   => $update_img,
            'content' => $update_content,
            'urban'   => $_REQUEST['new_urban'],
            'dctrad'  => $_REQUEST['new_dctrad'],
            'links'   => array(
                'urban'  => $update_link_urban,
                'dctrad' => $update_topic
            ),
        );
        // print_r($backlog);
        // die;
        
        $query   = $bdd->prepare('INSERT INTO odldc_backlog(id, bl_type, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, link_urban, topic) 
                            VALUES(:id, :bl_type, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :link_urban, :topic)');
        $query->execute(array(
        'id'           => $backlog['id'],
        'bl_type'      => 'modify',
        'name_period'  => $backlog['name_period'],
        'old_position' => $backlog['position']['old'],
        'new_position' => $backlog['position']['new'],
        'title'        => $backlog['title']['old'],
        'new_title'    => $backlog['title']['new'],
        'cover'        => $backlog['cover'],
        'content'      => $backlog['content'],
        'urban'        => $backlog['urban'],
        'dctrad'       => $backlog['dctrad'],
        'link_urban'   => $backlog['links']['urban'],
        'topic'        => $backlog['links']['dctrad']
        ));

        echo "<div class='form'>
        L'ODL a bien été mis à jour.";
?>
        <br />
        <a href="modify.php"><button type="button" class="btn_head">Retour au formulaire</button></a>
        <a href="index.php"><button type="button" class="btn_head">Retour à l'accueil</button></a>
    </div>
<?php
    } elseif (isset($_SESSION['pseudo'])) {
?>
    <div class="form">
        <h2>Modifier un arc</h2>
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
            <label for="id">Position actuelle dans l'ODL</label>
            <input type="number" class="pos" min="0" name="id" value="<?= $_GET["id"] ?>"> *<br />
            <input type="text" class="input" name="new_title" placeholder="Titre de l'arc"><br />
            <label for="cover">Cover</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" class="file" name="cover"><br />
            <textarea class="content" name="new_content" placeholder="Liste des issues de l'arc"></textarea><br />
            <div>
                <label for="publication">Publié chez :</label>
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
            <input type="url" class="input" name="new_link_urban" placeholder="https://www.mdcu-comics.fr/comics-vo/comics-vo-44779"><br />
            <input type="url" class="input" name="new_topic" placeholder="http://www.dctrad.fr/viewtopic.php?f=257&t=13234"><br />
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