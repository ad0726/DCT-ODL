<?php
include('../config.php');

$i = 20;    // Limit number of changelog's lines

$query = $bdd->query('SELECT * FROM odldc_changelog');      // Fetch all lines
while($chglog = $query->fetch(PDO::FETCH_ASSOC)) {
    $changelog[] = $chglog;
}
$count = count($changelog);

if ($count > $i) {
    rsort($changelog);
    $oldest = array_slice($changelog, $i);        // Fetch oldest lines

    $date = new DateTime();
    $date->setTimezone(new DateTimeZone("+0200"));
    $date = $date->format('Ymd');
    $file = ROOT."/changelog/".$date."_changelog.json";
    $put = file_put_contents($file, json_encode($oldest, JSON_PRETTY_PRINT));      // Create a backup of oldest lines

    $x = count($oldest);
    $min = $oldest[$x-1]['id'];
    $max = $oldest[0]['id'];
    $query = $bdd->query('DELETE FROM odldc_changelog WHERE id BETWEEN "'.$min.'" and "'.$max.'"');   // Delete oldest lines

    $bdd = NULL;
}