<?php
session_start();
date_default_timezone_set('Europe/Paris');

try
{
        $bdd = new PDO('mysql:host=HOST;port=PORT;dbname=DBNAME;charset=utf8mb4', 'USER', 'PASSWORD');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

define('TABLE_PREFIX', 'odldc_');

include($ROOT.'php/func.inc.php');
