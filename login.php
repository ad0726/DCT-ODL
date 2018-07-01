<?php
///////////////////////////////////////////////////////////////////////////// FICHIER INUTILE
include('function.php');
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
        } else {
            echo "Mauvais identifiant ou mot de passe !";
        }
    }
} else {
?>
<form action="?" method="post">
    <input type="hidden" name="formfilled" value="42" />
    <label for="login">Identifiant</label><br />
    <input type="text" name="login" placeholder="Identifiant"><br />
    <label for="password">Mot de passe</label><br />
    <input type="password" name="password"><br />
    <input type="submit" value="Envoyer">
</form>
<?php } ?>