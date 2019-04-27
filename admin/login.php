<?php
$ROOT = '../';
include($ROOT.'partial/header.php');

echo "<style>
        button.log {
            display: none;
        }
    </style>";
echo "<section>";

if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
    $login    = htmlentities(strtolower($_REQUEST['login']));
    $password = md5($_REQUEST['password']);
    // Check if the login is set in db
    $verif_login = $bdd->query('SELECT COUNT(user_name_clean) FROM odldc_users WHERE user_name_clean = \''.$login.'\'');
    if($verif_login->fetchColumn() == 0 ) {
        echo "Mauvais identifiant ou mot de passe !";
    } else {
        // Select password for the filled login
        $user_password = $bdd->query('SELECT user_password FROM odldc_users WHERE user_name_clean = \''.$login.'\' LIMIT 1')->fetch();
        if ($password == $user_password['user_password']) { // Check if the filled password and the password in db are equals
            $_SESSION['pseudo'] = $_REQUEST['login'];
            echo "<script>
            location.href='/index.php';
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
