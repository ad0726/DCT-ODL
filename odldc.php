<?php
include('function.php');

echo "<section>\n";
echo "<table>\n";
$answer = $bdd->query('SELECT * FROM odl');
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
function displayHeader(){

}

function displayLine($line){
 echo "
    <td>
        <tr>".$line['arc']."</tr>
    </td>";
}