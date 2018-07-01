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
        echo "<button class='login' type='button' ><a href='?login'>Connexion</a></button>";
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
                button.login {
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
    <tr>
        <td><img src=\"".$line['cover']."\" ></td>
        <td>".$line['arc'];
        if (isset($_SESSION['pseudo']))
        {
            button(1, 0, $line);
        }
        echo "</td>
        <td>".nl2br($line['contenu'])."</td>
        <td>";
        if (isset($_SESSION['pseudo']))
        {
            button(0, 1, $line);
        }
    echo "</td>
    </tr>";
}

function button($modify, $delete, $line) {
    global $bdd;
    if (!empty($modify)) {
        echo "<button type='button' ><a href=\"?modify".$line['id'];
        echo"\">modifier</a></button>";
        if ($_SERVER['QUERY_STRING'] == "modify".$line['id']) {
            echo "<form action=\"?modify".$line['id']."\" method=\"post\">
                <br /><input type=\"text\" name=\"new_title\" placeholder=\"Nouveau titre\">
                <input type=\"submit\" value=\"Envoyer\">
                </form>";
                if (!empty($_REQUEST['new_title'])) {
                    $bdd->exec('UPDATE odl SET arc = \''.$_REQUEST['new_title'].'\' WHERE id = \''.$line['id'].'\'');
                    header('Location: '.$_SERVER['HTTP_HOST']);
                }
        }
    }
    if (!empty($delete)) {
        echo "<button type='button' ><a href=\"?delete".$line['id'];
        echo"\">X</a></button>";
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
                    $bdd->exec('DELETE FROM odl WHERE id = \''.$line['id'].'\'');
                    $bdd->query('UPDATE odl SET id=id - 1 WHERE id>'.$newid);
                    header('Location: '.$_SERVER['HTTP_HOST']);
                } elseif (isset($_REQUEST['cancel'])) {
                    header('Location: '.$_SERVER['HTTP_HOST']);
                }
        }
    }
}
?>