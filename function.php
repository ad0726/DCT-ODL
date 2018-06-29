<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=odldc;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

function displayHeader(){

}

function displayLine($line){
 echo "
    <tr>
        <td><img src=\"".$line['cover']."\" ></td>
        <td>".$line['arc']."</td>
        <td>".$line['contenu']."</td>
    </tr>";
}
?>