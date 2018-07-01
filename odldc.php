<?php
// print_r($_SERVER);
// die;
include('header.php');
include('function.php');
displayHeader();
displayLogin();
logout();
echo "<section>\n";
echo "<table>\n";
$answer = $bdd->query('SELECT * FROM odl ORDER BY id DESC');
while ($line = $answer->fetch(PDO::FETCH_ASSOC)) {
    displayLine($line);
}

echo "</table>\n";
echo "</section>\n";
?>