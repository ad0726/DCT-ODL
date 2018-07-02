<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=odldc;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

function displayHeader() {
    if (!isset($_SESSION['pseudo']))
    {
        echo "<button class='log' type='button' ><a href='?login'>Connexion</a></button>";
        echo "<style>
                .nolog {
                    display: none;
                }
              </style>";
    }
    if (isset($_SESSION['pseudo']))
    {
        echo "<button type='button' ><a href='?login-out'>Déconnexion</a></button>";
        echo "<button type='button' ><a href='interface.php' target='_blank'>Ajouter un arc</a></button>";
    }
}

function displayLogin() {
    global $bdd;
    if($_SERVER['QUERY_STRING'] == "login") {
        echo "<style>
                button.log {
                    display: none;
                }
              </style>";
        if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
            $login = strtolower($_REQUEST['login']);
            $password = md5($_REQUEST['password']);
            //On vérifie que le login existe dans la table
            $verif_login = $bdd->query('SELECT COUNT(user_name_clean) FROM users WHERE user_name_clean = \''.$login.'\'');
            if($verif_login->fetchColumn() == 0) {
                echo "Mauvais identifiant ou mot de passe !";
            } else {
                //Séléction du password pour le login saisi
                $user_password = $bdd->query('SELECT user_password FROM users WHERE user_name_clean = \''.$login.'\' LIMIT 1')->fetch();
                if ($password == $user_password['user_password']) { // Vérification que le mot de passe correspond
                    echo "Bonjour ".$_REQUEST['login']."\n";
                    $_SESSION['pseudo'] = $_REQUEST['login'];
                    header('Location: '.$_SERVER['HTTP_HOST']);
                } else {
                    echo "Mauvais identifiant ou mot de passe !";
                }
            }
        } else {
        ?>
        <form action="?login" method="post">
            <input type="hidden" name="formfilled" value="42" />
            <label for="login">Identifiant</label><br />
            <input type="text" name="login" placeholder="Identifiant"><br />
            <label for="password">Mot de passe</label><br />
            <input type="password" name="password"><br />
            <input type="submit" value="Envoyer">
        </form>
    <?php }}
}

function logout() {
    if($_SERVER['QUERY_STRING'] == "login-out") {
        echo "blop";
        session_destroy();
        header('Location: '.$_SERVER['HTTP_HOST']);
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
?>