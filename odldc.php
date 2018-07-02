<?php
include('header.php');
displayLogin();
logout();
echo "<section>\n";
echo "<table>\n";
$answer = $bdd->query('SELECT * FROM rebirth ORDER BY id DESC');
while ($line = $answer->fetch(PDO::FETCH_ASSOC)) {
    displayLine($line);
}

echo "</table>\n";
echo "</section>\n";
?>