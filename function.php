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
        <td class='nolog'>".$line['id']."</td>
        <td><img src=\"".$line['cover']."\" ></td>
        <td><h3>".$line['arc']."</h3></td>
        <td><p>".nl2br($line['contenu'])."</p></td>
        <td class='nolog'>
    </tr>";
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