<?php
include('header.php');
?>
<section>
<h1>Les derniers ajouts / modifications de l'ODL</h1>
<?php
if (isset($_SESSION['pseudo'])) {
    $query = $bdd->query('SELECT * FROM odldc_backlog ORDER BY id DESC LIMIT 20');

    while ($bl = $query->fetch(PDO::FETCH_ASSOC)) {
        $backlog[$bl['id']] = $bl;
    }
    
    foreach ($backlog as $val) {
        $type = FALSE;
        displayBacklog($val);
    }
} else {
    echo "Veuillez vous connecter pour poursuivre.";
}?>
</section>
<?php include("footer.php"); ?>
