<?php
include('header.php');
?>
<section class="changelog">
<h1>Changelog</h1>
<?php
if (isset($_SESSION['pseudo'])) {
    $query = $bdd->query('SELECT * FROM odldc_changelog ORDER BY id DESC LIMIT 20');

    while ($cl = $query->fetch(PDO::FETCH_ASSOC)) {
        $changelog[$cl['id']] = $cl;
    }
    
    foreach ($changelog as $val) {
        $type = FALSE;
        displayChangelog($val);
    }
} else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>
<?php include("footer.php"); ?>
