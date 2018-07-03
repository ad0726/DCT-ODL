<?php 
session_start(); 
include('function.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>DCT-ODL</title>
        <link rel="stylesheet" href="assets/css/style.css?1.0.5">
        <link rel="stylesheet" href="assets/css/general.css?1.0.0">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/script.js"></script>
    <head>
    <body>
        <header>
            <div class="title_header">
                <div>
                    <img src="assets/img/logo_dct.png">
                    <h1>présente</h1>
                </div>
                <h2>L'ordre de lecture DC</h2>
            </div>
            <?php displayHeader(); ?>
        </header>
<?php
logout();
    // print_r($_SERVER);
?>