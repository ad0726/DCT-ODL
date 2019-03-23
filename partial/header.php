<?php
session_start();
include($ROOT.'conf/conf.php');

$query = $bdd->query("SELECT value FROM odldc_admin WHERE param = 'isMaintaining'")->fetch(PDO::FETCH_ASSOC);
if (($query['value'] == TRUE) && ($_SERVER['SCRIPT_NAME'] !== "/manage.php")) {
    echo "<img src='/assets/img/maintaining.jpg' style='width: 100%;'>";
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>DCTrad - Ordre de lecture DC</title>
        <link rel="icon" href="/assets/img/icon_dct.png" type="image/png" sizes="16x16">
        <link rel="icon" href="/assets/img/icon_dct.png" type="image/png" sizes="32x32">
        <link rel="stylesheet" href="/assets/css/general.css?1.1.0">
        <link rel="stylesheet" href="/assets/css/style.css?1.4.8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/js/script.js?2.3.0"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <head>
    <body>
        <main>
            <header id="up">
                <?php displayHeader($ROOT); ?>
            <a href="/index.php" id="header"></a>
            </header>
<?php
logout();
?>