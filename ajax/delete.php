<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if (isset($_GET['rm'])) {
    // Infos
    $id          = $_GET['rm'];
    $id_era      = $_GET['from'];
    $query       = $bdd->query("SELECT name, id_universe FROM era WHERE id_era = '$era'")->fetch(PDO::FETCH_ASSOC);
    $id_universe = $query['id_universe'];
    $era         = $query['name'];
    $query       = $bdd->query("SELECT name FROM universe WHERE id_universe = '$id_universe'")->fetch(PDO::FETCH_COLUMN);
    $universe    = $query['name'];

    $where_clause = "";
    $periods      = fetchPeriods($id_era);
    $n            = count($periods);
    $i            = 0;
    foreach ($periods as $period) {
        ++$i;
        $where_clause .= "id_period = '$period'";
        if ($i < $n) {
            $where_clause .= " OR ";
        }
    }

    $maxID       = $bdd->query("SELECT MAX(id) FROM arc")->fetch(PDO::FETCH_COLUMN);
    $lineDeleted = $bdd->query("SELECT * FROM arc WHERE id_arc = $id")->fetch(PDO::FETCH_ASSOC);
    $period      = $bdd->query('SELECT name FROM period WHERE id_period = "'.$lineDeleted['id_period'].'"')->fetch(PDO::FETCH_COLUMN);

    // Delete
    $position = $lineDeleted['position'];
    $bdd->exec("DELETE FROM arc WHERE id_arc = $id");
    $bdd->exec("UPDATE arc SET position = position - 1 WHERE $where_clause AND position BETWEEN ".($position+1)." AND $maxID");

    unlink("assets/img/covers/".$lineDeleted['cover']);

    // Changelog
    $changelog = array(
        'name_period' => $period,
        'position'    => $position,
        'title'       => $lineDeleted['title']
    );
    $query = $bdd->prepare('INSERT INTO odldc_changelog(author, cl_type, name_universe, name_era, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, isEvent)
                        VALUES(:author, :cl_type, :name_universe, :name_era, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :isEvent)');
    $query->execute(array(
        'author'        => $_SESSION['pseudo'],
        'cl_type'       => 'delete',
        'name_universe' => $universe,
        'name_era'      => $era,
        'name_period'   => $changelog['name_period'],
        'old_position'  => '',
        'new_position'  => $changelog['position'],
        'title'         => $changelog['title'],
        'new_title'     => '',
        'cover'         => '',
        'content'       => '',
        'urban'         => '',
        'dctrad'        => '',
        'isEvent'       => 0,
    ));

    $count = $bdd->query('SELECT count(*) FROM changelog')->fetch(PDO::FETCH_COLUMN);

    if ($count > 100) {
        $bdd->exec('DELETE FROM changelog ORDER BY id ASC LIMIT 1');
    }

    echo "200";
}