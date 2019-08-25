<?php
/**
 * Display admin header
 *
 * @return display
 */
function displayHeader($ROOT) {
    global $bdd;
    include($ROOT.'partial/search.php');
    echo "<div class='admin'>";
    if (!isset($_SESSION['pseudo'])) {
        echo "
                <a href='/admin/login.php' title='Connexion'><button class='log btn_head' type='button' ><i class='fas fa-sign-in-alt'></i></button></a>
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
                <a href='/admin/add.php' title='Ajouter un arc'><button type='button' class='btn_head' ><i class='fas fa-plus-circle'></i></button></a>
                <a href='/admin/index.php' title=\"PCA\"><button type='button' class='btn_head' ><i class='fas fa-bars'></i></button></a>
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
    if(isset($_SERVER['QUERY_STRING']) && ($_SERVER['QUERY_STRING'] == "logout")) {
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
                if ($arc_count > 20) {
                echo "<br />";
                echo "
                            <div class='btn_pagination top'>
                                <button class='btn_prev' name='pagination' style='display: none'><i class='fas fa-chevron-circle-left'></i></button>";
                                displayBTNpagination($arc_count);
                echo "          <button class='btn_next' name='pagination'><i class='fas fa-chevron-circle-right'></i></button>
                            </div>";
                }
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
    if ($arc_count > 20) {
    echo "<br />";
    echo "
                <div class='btn_pagination bottom'>
                    <button class='btn_prev' name='pagination' style='display: none'><i class='fas fa-chevron-circle-left'></i></button>";
                    displayBTNpagination($arc_count);
    echo "          <button class='btn_next' name='pagination'><i class='fas fa-chevron-circle-right'></i></button>
                </div>";
    }
    echo "
                <button class='btn_hide down btn_$period_format' name='hide'>Fermer</button>
                </div>
            </div>";
}

/**
 * Display tab on home page
 *
 * @param string name of ODL
 * @param array $ARlineID
 * @return display
 */
function displayHome($period, $ARlineID) {
    $period_format = strtolower(str_replace(" ", "_", $period));
    $arc_count = count($ARlineID);
    echo "
            <div class='period'>
                <h2 class='title_period btn_$period_format'>".$period."</h2>
                <span class='arc_count'>$arc_count arcs</span>
                <div class='content_period' id='$period_format'>";
                $i = 1;
                $p = 1;
                if ($arc_count > 20) {
                echo "<br />";
                echo "
                            <div class='btn_pagination top'>
                                <button class='btn_prev' name='pagination' style='display: none'><i class='fas fa-chevron-circle-left'></i></button>";
                                displayBTNpagination($arc_count);
                echo "          <button class='btn_next' name='pagination'><i class='fas fa-chevron-circle-right'></i></button>
                            </div>";
                }
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
    if ($arc_count > 20) {
    echo "<br />";
    echo "
                <div class='btn_pagination bottom'>
                    <button class='btn_prev' name='pagination' style='display: none'><i class='fas fa-chevron-circle-left'></i></button>";
                    displayBTNpagination($arc_count);
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
        echo "<button class='btn_page btn_page_$i' name='pagination'>$i</button>";
    }
}

/**
 * Display each line for comics arc
 *
 * @param array $ARinfo
 * @param string $period : current period (optionnal)
 * @param integer $p : number of pages (optionnal)
 * @param string $cover : src of cover (optionnal)
 * @return display
 */
function displayLine($ARinfo, $p = FALSE, $cover = "") {
    $id           = $ARinfo['id'];
    $era_current  = str_replace('/', '', str_replace('.php', '', $_SERVER['SCRIPT_NAME']));
    $classIsEvent = "";

    if ($era_current == "results") $era_current   = $_REQUEST['era'];
    if ($ARinfo['isEvent'] == TRUE) $classIsEvent = "isEvent";
    echo "
                    <table class='page_$p'>
                        <tr class='line $classIsEvent' id='".$id."'>
                            <td class='cel_id'><span>".$id."</span></td>
                            <td class='cel_img'><img src=\"$cover\" ></td>
                            <td class='cel_title'><h3>".$ARinfo['arc']."</h3></td>
                            <td class='cel_content'><p>".nl2br($ARinfo['contenu'])."</p></td>
                            <td class='cel_publi'>
                                <h4>Disponible chez</h4>
                                <div class='img_publi'>";
    if (!empty($ARinfo['urban'])) {
        echo "<a class='urlUrban' href='".$ARinfo['urban']."' target='_blank'><img src='/assets/img/logo_urban_mini.png'></a>";
    } else {
        echo "<img src='/assets/img/logo_urban_mini.png' class='logo_opacity'>";
    }
    if (!empty($ARinfo['dctrad'])) {
        echo "<a class='urlDctrad' href='".$ARinfo['dctrad']."' target='_blank'><img src='/assets/img/logo_dct_mini.png'></a>";
    } else {
        echo "<img src='/assets/img/logo_dct_mini.png' class='logo_opacity'>";
    }
    echo "
                                </div>
                            </td>
                            <td class='nolog'>
                                <i id='line_$id' class='fas fa-pen-fancy update_tr'></i>";
    if (whichRole() != "editor") {
        echo "<i class='fas fa-trash-alt btn_trash'></i>";
    }
    echo "                  </td>
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
    $TranscoIsEvent = [
        "+1" => "le tag <i>Event</i> a été ajouté.",
        "-1" => "le tag <i>Event</i> a été retiré."
    ];
    if (isset($TranscoIsEvent[$val['isEvent']])) $isEvent = $TranscoIsEvent[$val['isEvent']];
    if ($val['cl_type'] == "add") {
        $type = "ajouté à ".ucfirst($val['name_era'])." dans ".$val['name_period']." en position ".$val['new_position'].".";
    } elseif ($val['cl_type'] == "modify") {
        $type = "modifié dans ".ucfirst($val['name_era']).".";
    } elseif ($val['cl_type'] == "delete") {
        $type = "supprimé de ".ucfirst($val['name_era']).".";
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
        if (!empty($val['new_title']) && ($val['new_title'] != $val['title'])) {
            echo "<li>le titre a été modifié pour : ".$val['new_title'].".</li>";
        }
        if (!empty($val['new_position']) && ($val['new_position'] != $val['old_position'])) {
            echo "<li>a été replacé en position : ".$val['new_position'].".</li>";
        }
        if ($val['cover'] == 1) {
            echo "<li>la cover a été mise à jour.</li>";
        }
        if ($val['content'] == 1) {
            echo "<li>le contenu de l'arc a été mis à jour.</li>";
        }
        if ($val['urban'] == 1) {
            echo "<li>le lien Urban a été mis à jour.</li>";
        }
        if ($val['dctrad'] == 1) {
            echo "<li>le lien DCTrad a été mis à jour.</li>";
        }
        if (isset($isEvent)) {
            echo "<li>$isEvent</li>";
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
    echo "<div id='btnup'><i class='fas fa-arrow-circle-up'></i></div>";
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
        return (isset($error[1])) ? [FALSE, $error[1]] : [FALSE, @$error];
    } else {
// SAUVEGARDE DE L'IMAGE SUR LE FTP
        $image      = ResizeCover($_FILES['cover']['tmp_name'], "W", 150);
        $name       = md5(uniqid(rand(), true));
        $ext_upload = strtolower(  substr(  strrchr($_FILES['cover']['name'], '.')  ,1)  );
        $name_ext   = "../assets/img/covers/{$name}.{$ext_upload}";
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

function createSection($section) {
    global $bdd;
    $name         = $_REQUEST["name_$section"];
    $nameClean    = strtolower(str_replace(" ", "_", $name));
    $max_position = 0;
    $last_insert  = false;

    if ($section == "universe") {
        $cols    = "name, clean_name, id_universe";
        $values  = ":name, :clean_name, :id_universe";
        $execute = [
            'name'        => $name,
            'clean_name'  => $nameClean,
            'id_universe' => uniqid(),
        ];
    } elseif ($section == "era") {
        $cols    = "position, name, clean_name, id_era, id_universe";
        $values  = ":position, :name, :clean_name, :id_era, :id_universe";
        $execute = [
            'position'   => 1,
            'name'       => $name,
            'clean_name' => $nameClean,
            'id_era'     => uniqid(),
            'id_universe'=> null
        ];
    } else {
        $cols    = "position, name, clean_name, id_era, id_period, id_universe";
        $values  = ":position, :name, :clean_name, :id_era, :id_period, :id_universe";
        $execute = [
            'position'    => 1,
            'name'        => $name,
            'clean_name'  => $nameClean,
            'id_era'      => null,
            'id_period'   => uniqid(),
            'id_universe' => null
        ];
    }

    if (in_array($section, ['era', 'period'])) {
        $where_insert               = $_REQUEST["where_$section"];

        if (!empty($_REQUEST['referer']['eraToUniverse'])) {
            $referer                = $_REQUEST['referer']['eraToUniverse'];
            $where_condition        = "id_universe = '$referer'";
            $execute['id_universe'] = $referer;
        } else {
            $referer_universe       = $_REQUEST['referer']['periodToUniverse'];
            $referer_era            = $_REQUEST['referer']['periodToEra'];
            $where_condition        = "id_universe = '$referer_universe' AND id_era = '$referer_era'";
            $execute['id_universe'] = $referer_universe;
            $execute['id_era']      = $referer_era;
        }

        $sql    = "SELECT MAX(position) FROM (SELECT position FROM odldc_$section WHERE $where_condition) AS $section";
        $return = $bdd->query($sql)->fetch(PDO::FETCH_ASSOC);
        if ($max_position = $return['MAX(position)']) {
            $query        = $bdd->query("SELECT id_$section FROM odldc_$section WHERE position = $max_position")->fetch(PDO::FETCH_ASSOC);
            $last_insert  = $query["id_$section"];
        }

        if (!$last_insert) {
            // Insert in last position
            if ($where_insert == $last_insert) {
                $execute['position'] = ++$max_position;
            }
            $query    = $bdd->prepare("INSERT INTO odldc_$section($cols)
            VALUES($values)");

            $query->execute($execute);

        } else {
            // Insert in first position or after $where_insert
            $position = 1;
            if ($where_insert != "first") {
                $query    = $bdd->query("SELECT position FROM odldc_$section WHERE id_$section = '$where_insert'")->fetch(PDO::FETCH_ASSOC);
                $position = ++$query['position'];
            }

            $bdd->exec("UPDATE odldc_$section SET position = position + 1 WHERE position BETWEEN $position AND $max_position");

            $query = $bdd->prepare("INSERT INTO odldc_$section($cols)
            VALUES($values)");

            $execute['position'] = $position;
            $query->execute($execute);
        }

    } elseif ($section == "universe") {
        $query = $bdd->prepare("INSERT INTO odldc_$section($cols)
        VALUES($values)");
        $query->execute($execute);
        $toto = $bdd->exec("CREATE TABLE IF NOT EXISTS odldc_$nameClean (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `position` int(11) NOT NULL,
            `id_period` varchar(60) NOT NULL,
            `id_era` varchar(60) NOT NULL,
            `arc` text NOT NULL,
            `cover` text NOT NULL,
            `contenu` text NOT NULL,
            `vf` text NOT NULL,
            `dctrad` text NOT NULL,
            `isEvent` tinyint(1) NOT NULL,
            PRIMARY KEY (`id`)
            )");
    } else {
        die("Error");
    }
    echo "$name a bien été créé.";
    echo "<a href='/admin/create-section.php'><button type='button' class='btn_head'>Retour au formulaire</button></a>";
    echo "<a href='/admin/index.php'><button type='button' class='btn_head'>Retour au PCA</button></a>";
    echo "<a href='/index.php'><button type='button' class='btn_head'>Retour à l'accueil</button></a>";
}

function whichRole() {
    global $bdd;

    $login    = strtolower($_SESSION['pseudo']);
    $fetchACL = $bdd->query('SELECT user_acl FROM odldc_users WHERE user_name_clean = \''.$login.'\'')->fetch(PDO::FETCH_ASSOC);

    return $fetchACL['user_acl'];
}
?>