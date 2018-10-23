<?php
include("config.php");

/**
 * Display admin header
 *
 * @return display
 */
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
                <a href='changelog.php' title='Voir le changelog'><button type='button' class='btn_head' ><i class='fas fa-list-ul'></i></button></a>
                </div>";
    }
    echo "
            </div>\n";
}

/**
 * Logout function
 *
 * @return display javascript alerting
 */
function logout() {
    if($_SERVER['QUERY_STRING'] == "logout") {
        session_destroy();
        echo "<script>
            alert('Vous avez bien été déconnecté.');
            location.href='index.php';
        </script>";
    }
}

/**
 * Display period tab
 *
 * @param string $period
 * @param array $ARlineID
 * @return display
 */
function displayPeriod($period, $ARlineID) {
    $period_format = strtolower(str_replace(" ", "_", $period));
    $arc_count = count($ARlineID);
    echo "
            <div class='period'>
                <h2 class='title_period btn_$period_format'>".$period."</h2>
                <span class='arc_count'>$arc_count arcs</span>
                <div class='content_period' id='$period_format'>";
                $i = 1;
                $p = 1;
                foreach ($ARlineID as $lineID=>$ARinfo) {
                    if ($i % 20 == 0) {
                        displayLine($ARinfo, $p);
                        $i++;
                        $p++;
                    } else {
                        displayLine($ARinfo, $p);
                        $i++;
                    }
                }
                $i--;
    if ($p > 1) {
    echo "<br />";
    echo "
                <div class='btn_pagination'>
                    <button class='btn_prev' name='pagination' style='display: none'><i class='fas fa-chevron-circle-left'></i></button>";
                    displayBTNpagination($i);
    echo "          <button class='btn_next' name='pagination'><i class='fas fa-chevron-circle-right'></i></button>
                </div>";
    }
    echo "
                <button class='btn_hide down btn_$period_format' name='hide'>Fermer</button>
                </div>
            </div>";
}

/**
 * Display pagination button
 *
 * @param integer $p : number of pages
 * @return display
 */
function displayBTNpagination($i) {
    $p = ceil($i/20);
    for ($i=1;$i<=$p;$i++) {
        echo "<button class='btn_page' id='btn_page_$i' name='pagination'>$i</button>";
    }
}

/**
 * Display each line for comics arc
 *
 * @param array $ARinfo
 * @param integer $p : number of pages (optionnal)
 * @return display
 */
function displayLine($ARinfo, $p = FALSE) {
    $id = $ARinfo['id'];
    $era_current = str_replace('/', '', str_replace('.php', '', $_SERVER['SCRIPT_NAME']));
    echo "
                    <table class='page_$p'>
                        <tr class='line' id='".$id."'>
                            <td class='nolog'>".$id."</td>
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
                            <td class='nolog'>
                                <a href='modify.php?era=$era_current&id=$id' title='Modifier'>
                                    <button type='button' class='btn_head'><i class='fas fa-pen-fancy'></i></button>
                                </a>
                            </td>
                        </tr>
                    </table>";
}

/**
 * Display changelog page
 *
 * @param array $val = informations from db
 * @return display
 */
function displayChangelog($val) {
    if ($val['cl_type'] == "add") {
        $type = "ajouté à ".$val['name_era']." dans ".$val['name_period']." en position ".$val['new_position'].".";
    } else {
        $type = "modifié dans ".$val['name_era'].".";
    }
    $date_time = explode('_', $val['id']);
    $date = explode('-', $date_time[0]);
    $date = implode('/', array_reverse($date));
    $time = $date_time[1];
    echo "<div class='cl_line'>
    <div class='cl_date'>
        <p>Le $date à $time</p>
    </div>
    <div class='cl_content'>
    <p><strong>".ucfirst($val['title'])."</strong> a été $type";
    if ($val['cl_type'] == "modify") {
        echo "<ul class='cl_list'>";
        if (!empty($val['new_title'])) {
            echo "<li>le titre a été modifié pour : ".$val['new_title'].".</li>";
        }
        if (!empty($val['new_position'])) {
            echo "<li>a été replacé en position : ".$val['new_position'].".</li>";
        }
        if ($val['cover'] == 1) {
            echo "<li>la cover a été mise à jour.</li>";
        }
        if ($val['content'] == 1) {
            echo "<li>le contenu de l'arc a été mis à jour.</li>";
        }
        if (!empty($val['urban'])) {
            if ($val['urban'] == 1) {
                echo "<li>l'option Urban a été sélectionnée.</li>";
            } else {
                echo "<li>l'option Urban a été désélectionnée.</li>";
            }
        }
        if (!empty($val['dctrad'])) {
            if ($val['dctrad'] == 1) {
                echo "<li>l'option DCtrad a été sélectionnée.</li>";
            } else {
                echo "<li>l'option DCtrad a été désélectionnée.</li>";
            }
        }
        if ($val['link_urban'] == 1) {
            echo "<li>le lien Urban a été mis à jour.</li>";
        }
        if ($val['topic'] == 1) {
            echo "<li>le lien DCTrad a été mis à jour.</li>";
        }
        echo "</ul>";
    }
    echo "</div>\n</p>
    <div class='cl_author'>
        <p>Par ".$val['author']."</p>
    </div>\n</div>\n";
}

/**
 * Display back to top button
 *
 * @return display
 */
function displayBtnUp() {
    echo "<a href='#up'><div class='btnup'><i class='fas fa-arrow-circle-up'></i></div></a>";
}

/**
 * d function
 * debug tools : pretty print and die.
 *
 * @param [string] $msg : message to display usng print_r
 * @param [boolean] $die : die after display if set to TRUE, default TRUE.
 * @param [string] $pre : string to add before the $msg, default '<pre>
 * @return void
 */
function d($msg, $die=TRUE, $pre='<pre>'){
	echo $pre.print_r($msg,1)."</pre>";
	if ($die) die();
}

/**
 * Upload cover
 *
 * @return void : if return TRUE, return root of cover (string). Else return error.
 */
function uploadCover() {
    // global $name_ext;
    $error = FALSE;
// VERIF UPLOAD
    if ($_FILES['cover']['error'] > 0) $error[1] = "Pas de cover transférée.\n";
// VERIF WEIGHT
    $maxsize = 1048576;
    if ($_FILES['cover']['size'] > $maxsize) $error[2] = "Le fichier est trop gros.\n";
// VERIF EXTENSION
    $img_ext_ok = array( 'jpg' , 'jpeg' , 'png' );
    $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
    if ( !in_array($ext_upload,$img_ext_ok) ) $error[3] = "Extension incorrecte.\n";
// AFFICHAGE DE L'ERREUR OU ENVOI
    if (!empty($error)) {
        return [FALSE, @$error];
    } else {
// SAUVEGARDE DE L'IMAGE SUR LE FTP
        $image = ResizeCover($_FILES['cover']['tmp_name'], "W", 150);
        $name       = md5(uniqid(rand(), true));
        $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
        $name_ext   = "assets/img/covers/{$name}.{$ext_upload}";
        $resultat   = imagejpeg($image, $name_ext, 70);
        if (!$resultat) {
            return [FALSE, "Transfert échoué.\n"];
        } else {
            imagedestroy($image);
            return [TRUE, $name_ext];
        }
    }
}

/**
 * Resize cover
 * http://memo-web.fr/categorie-php-197.php
 *
 * @param resource $source
 * @param string $type_value
 * @param integer $new_value
 * @return resource
 */
function ResizeCover($source, $type_value = "W", $new_value) {
// Récupération des dimensions de l'image
    if( !( list($source_largeur, $source_hauteur) = @getimagesize($source) ) ) {
      return false;
    }

// Calcul de la valeur dynamique en fonction des dimensions actuelles
// de l'image et de la dimension fixe que nous avons précisée en argument.
    if( $type_value == "H" ) {
      $nouv_hauteur = $new_value;
      $nouv_largeur = ($new_value / $source_hauteur) * $source_largeur;
    } else {
      $nouv_largeur = $new_value;
      $nouv_hauteur = ($new_value / $source_largeur) * $source_hauteur;
    }

// Création du conteneur.
    $image = imagecreatetruecolor($nouv_largeur, $nouv_hauteur);

// Importation de l'image source.
    $source_image = imagecreatefromstring(file_get_contents($source));

// Copie de l'image dans le nouveau conteneur en la rééchantillonant.
    imagecopyresampled($image, $source_image, 0, 0, 0, 0, $nouv_largeur, $nouv_hauteur, $source_largeur, $source_hauteur);

// Libération de la mémoire allouée aux deux images (sources et nouvelle).
    imagedestroy($source_image);

    return $image;
  }

?>