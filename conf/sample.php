<?php
try {
        $bdd = new PDO('mysql:host=HOST;port=PORT;dbname=DBNAME;charset=utf8mb4', 'USER', 'PASSWORD');
} catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
}

include($ROOT.'php/func.inc.php');
