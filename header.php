<?php 
session_start(); 
include('function.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>DCTrad - Ordre de lecture DC</title>
        <link rel="icon" href="assets/img/icon_dct.png" type="image/png" sizes="16x16">
        <link rel="icon" href="assets/img/icon_dct.png" type="image/png" sizes="32x32">
        <link rel="stylesheet" href="assets/css/style.css?1.2.0">
        <link rel="stylesheet" href="assets/css/general.css?1.0.1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/script.js"></script>
    <head>
    <body>
        <header>
            <!-- <div class="title_header">
                <img src="assets/img/banniere_header.png">
            </div> -->
            <?php displayHeader(); ?>
        </header>
<?php
logout();
    // print_r($_SERVER);
?>