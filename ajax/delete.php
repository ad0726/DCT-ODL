<?php
session_start();
include_once('function.php');

if (isset($_GET['rm'])) {
    // Infos
    $id          = $_GET['rm'];
    $era         = $_GET['from'];
    $maxID       = $bdd->query("SELECT MAX(id) FROM odldc_$era")->fetch(PDO::FETCH_ASSOC);
    $lineDeleted = $bdd->query("SELECT * FROM odldc_$era WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    $period      = ($bdd->query('SELECT name FROM odldc_period WHERE id_period = "'.$lineDeleted['id_period'].'"')->fetch(PDO::FETCH_ASSOC))['name'];

    // Delete
    $bdd->exec("DELETE FROM odldc_$era WHERE id = $id");
    $bdd->exec("UPDATE odldc_$era SET id = id - 1 WHERE id BETWEEN ".($id+1)." AND ".$maxID['MAX(id)']);

    unlink($lineDeleted['cover']);

    // Changelog
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('+0200'));
    $changelog = array(
        'id'          => $date->format('Y-m-d_H:i:s'),
        'name_period' => $period,
        'position'    => $id,
        'title'       => $lineDeleted['arc']
    );
    $query = $bdd->prepare('INSERT INTO odldc_changelog(id, author, cl_type, name_era, name_period, old_position, new_position, title, new_title, cover, content, urban, dctrad, isEvent)
                        VALUES(:id, :author, :cl_type, :name_era, :name_period, :old_position, :new_position, :title, :new_title, :cover, :content, :urban, :dctrad, :isEvent)');
    $query->execute(array(
        'id'           => $changelog['id'],
        'author'       => $_SESSION['pseudo'],
        'cl_type'      => 'delete',
        'name_era'     => $era,
        'name_period'  => $changelog['name_period'],
        'old_position' => '',
        'new_position' => $changelog['position'],
        'title'        => $changelog['title'],
        'new_title'    => '',
        'cover'        => '',
        'content'      => '',
        'urban'        => '',
        'dctrad'       => '',
        'isEvent'      => 0,
    ));

    $count = $bdd->query('SELECT count(*) FROM odldc_changelog')->fetch(PDO::FETCH_ASSOC);

    if ($count['count(*)'] > 100) {
        $bdd->exec('DELETE FROM odldc_changelog ORDER BY id ASC LIMIT 1');
    }

    echo "200";
}