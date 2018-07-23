<?php
include("config.php");
function displayHeader() {
    global $bdd;
        echo "<div class='admin'>";
    if (!isset($_SESSION['pseudo'])) {
        echo " 
                <a href='login.php' title='Connexion'><button class='log btn_head' type='button' ><i class='fas fa-sign-in-alt'></i></button></a>
                <style>
                    .nolog {
                        display: none;
                    }
                </style>";
    } else {
        echo "
                <p>Hello ".$_SESSION['pseudo']."</p>
                <div class='div_btn_head'>
                <a href='?logout' title='Déconnexion'>
                <button type='button' class='btn_head'><i class='fas fa-sign-out-alt'></i></button>
                </a>
                <a href='rebirth.php' title=\"Voir l'ODL\"><button type='button' class='btn_head' ><i class='fas fa-glasses'></i></button></a>
                <a href='add.php' title='Ajouter un arc'><button type='button' class='btn_head' ><i class='fas fa-plus-circle'></i></button></a>
                <a href='modify.php' title='Modifier un arc'><button type='button' class='btn_head' ><i class='fas fa-exchange-alt'></i></button></a>
                </div>";
    }
    echo "
            </div>\n";
}

function logout() {
    if($_SERVER['QUERY_STRING'] == "logout") {
        session_destroy();
        echo "<script>
            alert('Vous avez bien été déconnecté.');
            location.href='index.php';
        </script>";
    }
}

function displayPeriod($period, $ARlineID) {
    $period_format = strtolower(str_replace(" ", "_", $period));
    echo "
            <script>
            $(document).ready(function(){
     
                $('.btn_$period_format').click(function() {
                    $('#$period_format').toggle()
                });
            });            
            </script>
            <div class='period'>
                <h2 class='title_period btn_$period_format'>".$period."</h2>
                <div class='content_period' id='$period_format'>";
                foreach ($ARlineID as $lineID=>$ARinfo) {
                    displayLine($ARinfo);
                }
    echo "
                <br /><button class='btn_hide down btn_$period_format' name='hide'>Fermer</button>
                </div>
            </div>";
}

function displayLine($ARinfo) {
    echo "
                    <table>
                        <tr class='line' id='".$ARinfo['id']."'>
                            <td class='nolog'>".$ARinfo['id']."</td>
                            <td class='cel_img'><img src=\"".$ARinfo['cover']."\" ></td>
                            <td class='cel_title'><span><h3>".$ARinfo['arc']."</h3></span></td>
                            <td class='cel_content'><p>".nl2br($ARinfo['contenu'])."</p></td>
                            <td class='cel_publi'>
                                <h4>Disponible chez</h4>
                                <div class='img_publi'>";
    if ($ARinfo['urban'] == 1) {
        echo "
        <a href='".$ARinfo['link_urban']."' target='_blank'><img src='assets/img/logo_urban_mini.png'></a>";
    } else {
        echo "
            <img src='assets/img/logo_urban_mini.png' class='logo_opacity'>";
    }
    if ($ARinfo['dctrad'] == 1) {
        echo "
            <a href='".$ARinfo['topic']."' target='_blank'><img src='assets/img/logo_dct_mini.png'></a>";
    } else {
        echo "
            <img src='assets/img/logo_dct_mini.png' class='logo_opacity'>";
    }
    echo"
                                </div>
                            </td>
                        </tr>
                    </table>";
}

function uploadCover() {
    global $name_ext;
    $error = FALSE;
// VERIF UPLOAD
    if ($_FILES['cover']['error'] > 0) $error[1] = "Pas de cover transférée.";
// VERIF WEIGHT
    $maxsize = 1048576;
    if ($_FILES['cover']['size'] > $maxsize) $error[2] = "Le fichier est trop gros.";
// VERIF EXTENSION
    $img_ext_ok = array( 'jpg' , 'jpeg' , 'png' );
    $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
    if ( !in_array($ext_upload,$img_ext_ok) ) $error[3] = "Extension incorrecte.";
// VERIF SIZE
    $maxwidth    = 150;
    $maxheight   = 250;
    $image_sizes = @getimagesize($_FILES['cover']['tmp_name']);
    if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight) $error[4] = "Image trop grande.";
// AFFICHAGE DE L'ERREUR OU ENVOI
    if (!empty($error[1])) {
        echo @$error[1]."<br />";
    } elseif (!empty($error)) {
        echo @$error[2]."<br />";
        echo @$error[3]."<br />";
        echo @$error[4]."<br />";
    } else {
        $name       = md5(uniqid(rand(), true));
        $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
        $name_ext   = "assets/img/covers/{$name}.{$ext_upload}";
        $resultat   = move_uploaded_file($_FILES['cover']['tmp_name'],$name_ext);
        if (!$resultat) echo "Transfert échoué\n";
    }
}
?>