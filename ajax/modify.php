<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if (isset($_SESSION['pseudo'])) {
    if (isset($_REQUEST['id']) && isset($_REQUEST['id_era']) && isset($_REQUEST['id_period'])) {
        $era     = $_REQUEST['id_era'];
        $period  = $_REQUEST['id_period'];
        $id      = $_REQUEST['id_arc'];
        $periods = fetchPeriods($era);
        $n       = count($periods);
        $i       = 0;
        foreach ($periods as $period) {
            ++$i;
            $where_clause .= "id_period = '$period'";
            if ($i < $n) {
                $where_clause .= " OR ";
            }
        }
        $info = $bdd->query("SELECT * FROM arc WHERE id_arc = $id")->fetch(PDO::FETCH_ASSOC);
        $position = $info['position'];

        $isEvent = 0;
        if (isset($_REQUEST['isEvent']) && $_REQUEST['isEvent'] == "on") {
            $isEvent = 1;
        }
        // Update event
        $bdd->exec("UPDATE arc SET is_event = '$isEvent' WHERE id_arc = $id");

        echo "<div class='form'>";
        if (!isset($_REQUEST['noCover']) && ($_FILES['cover']['error'] == 0))  {
            // Delete image if new image uploaded
            $old_cover = $bdd->query("SELECT cover FROM arc WHERE id_arc = $id")->fetch(PDO::FETCH_COLUMN);
            unlink("assets/img/covers/".$old_cover);
            // Upload new image
            $upload = uploadCover($_FILES['cover']);
        }
        // Update image
        $update_img = false;
        if (isset($upload) && ($upload[0] === true)) {
            if (!empty($upload[1])) {
                $bdd->exec("UPDATE arc SET cover = '{$upload[1]}' WHERE id_arc = $id");
                $update_img = true;
            }
        } else {
            d($upload[1], false);
        }
        // Update title
        $new_title = "";
        if (!empty($_REQUEST['new_title'])) {
            $new_title = htmlentities($_REQUEST['new_title'], ENT_QUOTES);
            $bdd->exec("UPDATE arc SET title = '$new_title' WHERE id_arc = '$id'");
        }
        // Update content
        $update_content = false;
        if (!empty($_REQUEST['new_content'])) {
            $new_content = htmlentities($_REQUEST['new_content'], ENT_QUOTES);
            $bdd->exec("UPDATE arc SET content = '$new_content' WHERE id_arc = $id");
            $update_content = true;
        }
        // Update Urban's link
        $update_urban = false;
        if (isset($_REQUEST['new_urban']) && ($_REQUEST['new_urban'] != $info['urban'])) {
            $link_a = $_REQUEST['new_urban'];
            $bdd->exec("UPDATE arc SET link_a = '$link_a' WHERE id_arc = $id");
            $update_urban = true;
        }
        // Update DCTrad's link
        $update_dct = false;
        if (isset($_REQUEST['new_dctrad']) && ($_REQUEST['new_dctrad'] != $info['dctrad'])) {
            $link_b = $_REQUEST['new_dctrad'];
            $bdd->exec("UPDATE arc SET link_b = '$link_b' WHERE id_arc = $id");
            $update_dct = true;
        }
        // Update Id
        if ((isset($_REQUEST['new_id'])) && (!empty($_REQUEST['new_id']) && ($_REQUEST['new_id'] != 0))) {
            $new_id = $_REQUEST['new_id'];
            if ($new_id > $position) {
                $bdd->exec("UPDATE arc SET position = -1 WHERE id_arc = $id");
                $bdd->exec("UPDATE arc SET position = position - 1 WHERE $where_clause AND position BETWEEN $position AND $new_id");
                $bdd->exec("UPDATE arc SET position = $new_id WHERE id_arc = $id");
            } elseif ($new_id < $position) {
                $bdd->exec("UPDATE arc SET position = -1 WHERE id_arc = $id");
                $bdd->exec("UPDATE arc SET position = position + 1 WHERE $where_clause AND position BETWEEN $new_id AND $position");
                $bdd->exec("UPDATE arc SET position = $new_id WHERE id_arc = $id");
            }
        }

        // Changelog
        $clIsEvent = "0";                                                                           // Isn't event before submit
        if (isset($_REQUEST['isEventReturn']) && ($_REQUEST['isEventReturn'] == "checked")) {       // If is event before submit
            $clIsEvent = "1";
        }
        if (($isEvent == 1) && ($clIsEvent == "0")) {                                               // If isn't event before submit and update to isEvent = TRUE
            $clIsEvent = "+1";
        } elseif (($isEvent == 0) && ($clIsEvent == "1")) {                                         // If is event before submit and update to isEvent = FALSE
            $clIsEvent = "-1";
        }
        $universe = $bdd->query("SELECT name FROM universe WHERE id_universe = (SELECT id_universe FROM era WHERE id_era = '$era')")->fetch(PDO::FETCH_COLUMN);
        $name_era = $bdd->query("SELECT name FROM era WHERE id_era = '$era'")->fetch(PDO::FETCH_COLUMN);
        $changelog = [
            'universe' => $universe,
            'era'      => $name_era,
            'position' => ['old' => $position],
            'title'    => ['old' => $info['title']],
            'cover'    => $update_img,
            'content'  => $update_content,
            'urban'    => $update_urban,
            'dctrad'   => $update_dct
        ];
        $changelog['position']['new'] = $new_id ?? "";
        $changelog['title']['new']    = $new_title;

        $query = $bdd->prepare('INSERT INTO changelog(author, cl_type, name_universe, name_era, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, isEvent) 
                            VALUES(:author, :cl_type, :name_era, :name_universe, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :isEvent)');
        $query->execute([
        'author'        => $_SESSION['pseudo'],
        'cl_type'       => 'modify',
        'name_universe' => $changelog['universe'],
        'name_era'      => $changelog['era'],
        'name_period'   => '',
        'old_position'  => $changelog['position']['old'],
        'new_position'  => $changelog['position']['new'],
        'title'         => $changelog['title']['old'],
        'new_title'     => $changelog['title']['new'],
        'cover'         => $changelog['cover'],
        'content'       => $changelog['content'],
        'urban'         => $changelog['urban'],
        'dctrad'        => $changelog['dctrad'],
        'isEvent'       => $clIsEvent
        ]);

        $count = $bdd->query('SELECT count(*) FROM changelog')->fetch(PDO::FETCH_ASSOC);

        if ($count['count(*)'] > 100) {
            $bdd->exec('DELETE FROM changelog ORDER BY id ASC LIMIT 1');
        }
    }
}