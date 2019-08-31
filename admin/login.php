<?php
$ROOT = '../';
include($ROOT.'partial/header.php');

if ((isset($_SERVER['HTTP_REFERER'])) && ($_SERVER['HTTP_REFERER'] != $http_domain.$_SERVER['SCRIPT_NAME'])) {
    $_SESSION['HTTP_REFERER'] = str_replace($http_domain, '', $_SERVER['HTTP_REFERER']);
}

echo "<style>
        button.log {
            display: none;
        }
    </style>";
echo "<section>";

if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
    if (isset($_SESSION['HTTP_REFERER']) && !empty($_SESSION['HTTP_REFERER'])) {
        $redirect = $_SESSION['HTTP_REFERER'];
    } else {
        $redirect = "/index.php";
    }
    $login    = htmlentities(strtolower($_REQUEST['login']));
    $password = md5($_REQUEST['password']);
    // Check if the login is set in db
    $verif_login = $bdd->query("SELECT COUNT(pseudo_clean) FROM usr WHERE pseudo_clean = '$login'");
    if($verif_login->fetchColumn() == 0 ) {
        echo "Mauvais identifiant ou mot de passe !";
    } else {
        // Select password for the filled login
        $user_password = $bdd->query("SELECT password FROM usr WHERE pseudo_clean = '$login' LIMIT 1")->fetch();
        if ($password == $user_password['password']) { // Check if the filled password and the password in db are equals
            $_SESSION['pseudo'] = $_REQUEST['login'];
            echo "<script>
            location.href='$redirect';
            </script>";
        } else {
            echo "Mauvais identifiant ou mot de passe !";
        }
    }
} else {
?>
<div class="form login">
    <form action="?login" method="post">
        <input type="hidden" name="formfilled" value="42" />
        <label for="login">Identifiant</label><br />
        <input type="text" name="login" placeholder="Identifiant"><br />
        <label for="password">Mot de passe</label><br />
        <input type="password" name="password"><br />
        <input type="submit" value="Envoyer">
    </form>
</div>
<?php }
echo "</section>";

include($ROOT.'partial/footer.php');
