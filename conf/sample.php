<?php
try {
        $bdd = new PDO('mysql:host=HOST;port=PORT;dbname=DBNAME;charset=utf8mb4', 'USER', 'PASSWORD');
} catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
}

include('../php/func.inc.php');
