<?php
include('header.php');

$query = $bdd->query('SELECT * FROM odldc_backlog');

while ($bl = $query->fetch(PDO::FETCH_ASSOC)) {
    $backlog[$bl['id']] = $bl;
}
print_r($backlog);

include("footer.php"); 