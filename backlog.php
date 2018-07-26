<?php
include('header.php');
?>
<section>
<?php
if (isset($_SESSION['pseudo'])) {
    $query = $bdd->query('SELECT * FROM odldc_backlog');

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
