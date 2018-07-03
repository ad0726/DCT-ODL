<?php
include("config.php");
function displayHeader() {
        echo "<div class='admin'>";
    if (!isset($_SESSION['pseudo'])) {
        echo " 
                <button class='log btn_head' type='button' ><a href='?login'><i class='fas fa-sign-in-alt'></i></a></button>
                <style>
                    .nolog {
                        display: none;
                    }
                </style>";
    } else {
        echo "
                <div class='div_btn_head'>
                <p>Hello ".$_SESSION['pseudo']."</p>
                <button type='button' class='btn_head' ><a href='?login-out'><i class='fas fa-sign-out-alt'></i></a></button>
                <button type='button' class='btn_head' ><a href='rebirth.php' target='_blank'><i class='fas fa-glasses'></i></a></button>
                <button type='button' class='btn_head' ><a href='add.php' target='_blank'><i class='fas fa-plus-circle'></i></a></button>
                <button type='button' class='btn_head' ><a href='modify.php' target='_blank'><i class='fas fa-exchange-alt'></i></a></button>
                </div>";
    }
    echo "
            </div>\n";
}

function logout() {
    if($_SERVER['QUERY_STRING'] == "login-out") {
        echo "blop";
        session_destroy();
        header('Location: '.$_SERVER['SCRIPT_NAME']);
    }
}


function displayLine($line) {
 echo "
    <tr class='line' id='".$line['id']."'>
        <td class='nolog'>".$line['id'];
        if (isset($_SESSION['pseudo']))
        {
            echo "<form action=\"#".$line['id']."\" method=\"post\">
                <input type=\"hidden\" name=\"formfilled\" value=\"42\" />
                <br /><input class='modify_form' type=\"number\" name=\"new_id\">";
        }
        echo "</td>
        <td><img src=\"".$line['cover']."\" >";
        if (isset($_SESSION['pseudo']))
        {
            echo "<br /><input class='modify_form' type=\"text\" name=\"new_cover\" placeholder=\"URL\">";
        }
        echo "</td>
        <td><h3>".$line['arc']."</h3>";
        if (isset($_SESSION['pseudo']))
        {
            echo "<br /><input class='modify_form' type=\"text\" name=\"new_title\" placeholder=\"Nouveau titre\">";
        }
        echo "</td>
        <td><p>".nl2br($line['contenu'])."</p>";
        if (isset($_SESSION['pseudo']))
        {
            echo "<textarea class='modify_form' type=\"text\" name=\"new_contenu\" placeholder=\"Nouveau contenu\"></textarea>";
        }
        echo "</td>
        <td class='nolog'>";
        if (isset($_SESSION['pseudo']))
        {
            button(1, 1, $line);
        }
    echo "</td>
    </tr>";
}

function button($modify, $delete, $line) {
    global $bdd;
    if (!empty($modify)) {
        echo "<button class='modify' type='button' ><i class=\"fas fa-wrench\"></i></button>";
        if ($_SERVER['QUERY_STRING'] == $line['id']) {
            echo "<input class='modify_form' type=\"submit\" value=\"Envoyer\">
                </form>";
                if (!empty($_REQUEST['new_id'])) {
                    $bdd->exec('UPDATE rebirth SET id = \'-1\' WHERE id = \''.$line['id'].'\'');
                    $bdd->query('UPDATE rebirth SET id = id - 1 WHERE id BETWEEN '.$line['id'].' AND '.$_REQUEST['new_id']);
                    $bdd->exec('UPDATE rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
                    $bdd->exec('ALTER TABLE rebirth ORDER BY id ASC');
                } elseif (!empty($_REQUEST['new_id'])) {
                    $bdd->exec('UPDATE rebirth SET id = \'-1\' WHERE id = '.$line['id']);
                    $bdd->query('UPDATE rebirth SET id = id + 1 WHERE id BETWEEN '.$_REQUEST['new_id'].' AND '.$line['id']);
                    $bdd->exec('UPDATE rebirth SET id = \''.$_REQUEST['new_id'].'\' WHERE id = \'-1\'');
                    $bdd->exec('ALTER TABLE rebirth ORDER BY id ASC');
                }
                if (!empty($_REQUEST['new_cover'])) {
                    $bdd->exec('UPDATE rebirth SET cover = \''.$_REQUEST['new_cover'].'\' WHERE id = \''.$line['id'].'\'');
                }
                if (!empty($_REQUEST['new_title'])) {
                    $bdd->exec('UPDATE rebirth SET arc = \''.$_REQUEST['new_title'].'\' WHERE id = \''.$line['id'].'\'');
                }
                if (!empty($_REQUEST['new_contenu'])) {
                    $bdd->exec('UPDATE rebirth SET contenu = \''.$_REQUEST['new_contenu'].'\' WHERE id = \''.$line['id'].'\'');
                }
                if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
                    header('Location: '.$_SERVER['HTTP_HOST']);
                }
        }
    }
    if (!empty($delete)) {
        echo "<button class='delete' type='button' ><a href=\"?delete".$line['id'];
        echo"\"><i class=\"fas fa-trash-alt\"></i></a></button>";
        if ($_SERVER['QUERY_STRING'] == "delete".$line['id']) {
            echo "<form action=\"?delete".$line['id']."\" method=\"post\">
            <input type=\"hidden\" name=\"delete\" value=\"0\">Sûr ?<br />
            <input type=\"submit\" value=\"Oui\">
            </form><form action=\"?delete".$line['id']."\" method=\"post\">
            <input type=\"hidden\" name=\"cancel\" value=\"0\">
            <input type=\"submit\" value=\"Non\">
                </form>";
                if (isset($_REQUEST['delete'])) {
                    $newid = $line['id'];
                    $bdd->exec('DELETE FROM rebirth WHERE id = \''.$line['id'].'\'');
                    $bdd->query('UPDATE rebirth SET id=id - 1 WHERE id>'.$newid);
                    header('Location: '.$_SERVER['HTTP_HOST']);
                } elseif (isset($_REQUEST['cancel'])) {
                    header('Location: '.$_SERVER['HTTP_HOST']);
                }
        }
    }
}

function uploadCover() {
    global $name_ext;
    $error = FALSE;
// VERIF UPLOAD
    if ($_FILES['cover']['error'] > 0) $error[1] = "error lors du transfert";
// VERIF WEIGHT
    $maxsize = 1048576;
    if ($_FILES['cover']['size'] > $maxsize) $error[2] = "Le fichier est trop gros";
// VERIF EXTENSION
    $img_ext_ok = array( 'jpg' , 'jpeg' , 'png' );
    $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
    if ( !in_array($ext_upload,$img_ext_ok) ) $error[3] = "Extension incorrecte";
// VERIF SIZE
    $maxwidth = 150;
    $maxheight = 250;
    $image_sizes = getimagesize($_FILES['cover']['tmp_name']);
    if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight) $error[4] = "Image trop grande";
// AFFICHAGE DE L'ERREUR OU ENVOI
    if (!empty($error)) {
        echo @$error[1];
        echo @$error[2];
        echo @$error[3];
        echo @$error[4];
    } else {
        $name = md5(uniqid(rand(), true));
        $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
        $name_ext = "assets/img/covers/{$name}.{$ext_upload}";
        $resultat = move_uploaded_file($_FILES['cover']['tmp_name'],$name_ext);
        if (!$resultat) echo "Transfert échoué\n";
    }
}
?>