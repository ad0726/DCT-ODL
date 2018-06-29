<?php
include('function.php');

echo "<section>\n";
echo "<table>\n";
$answer = $bdd->query('SELECT * FROM odl ORDER BY id DESC');
$i=0;
while ($line = $answer->fetch(PDO::FETCH_ASSOC)) {
    // echo "=============[ligne $i]=============\n";
    // echo "key value \n";
    // foreach($line as $k=>$v){
    //     echo "$k=>$v\n";
    // }
    // echo "\n--\n";
    // print_r(array_keys($line));
    displayLine($line);
    $i++;
}
echo "</table>\n";
echo "</section>\n";

// if (isset($_GET['new_id'])) // Si on demande une modif de position
// {
// $position = $_GET['position-agenda'];
 
// mysql_query("UPDATE agenda SET ordre='" . $newposition . "', WHERE ordre ='".$position['ordre']."'");
}
?>