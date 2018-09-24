<?php
include('../config.php');

$i = 20;    // Limit number of backlog's lines

$query = $bdd->query('SELECT * FROM odldc_backlog');      // Fetch all lines
while($bckl = $query->fetch(PDO::FETCH_ASSOC)) {
    $backlog[] = $bckl;
}
$count = count($backlog);

if ($count > $i) {
    rsort($backlog);
    $oldest = array_slice($backlog, $i);        // Fetch oldest lines

    $date = new DateTime();
    $date->setTimezone(new DateTimeZone("+0200"));
    $date = $date->format('Ymd');
    $file = ROOT."/backlog/".$date."_backlog.json";
    $put = file_put_contents($file, json_encode($oldest, JSON_PRETTY_PRINT));      // Create a backup of oldest lines

    $x = count($oldest);
    $min = $oldest[$x-1]['id'];
    $max = $oldest[0]['id'];
    $query = $bdd->query('DELETE FROM odldc_backlog WHERE id BETWEEN "'.$min.'" and "'.$max.'"');   // Delete oldest lines

    $bdd = NULL;
}