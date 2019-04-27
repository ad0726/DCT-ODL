<?php
$ROOT = './';
include($ROOT.'partial/header.php');

if (isset($_REQUEST['search']) && !empty($_REQUEST['search']) && isset($_REQUEST['era']) && !empty($_REQUEST['era']) && ($_REQUEST['era'] !== "all")) {
    $era = $_REQUEST['era'];
    $id  = (int) $_REQUEST['search'];
    if (is_int($id) && $id != 0) {
        $id  = $_REQUEST['search'];
        $qry = $bdd->query("SELECT * FROM odldc_$era WHERE id = '$id'");
        while ($IDresult = $qry->fetch(PDO::FETCH_ASSOC)) {
            $IDresults[] = $IDresult;
        }
    } else {
        $search  = "%".$_REQUEST['search']."%";
        $arc     = $bdd->query("SELECT * FROM odldc_$era WHERE arc LIKE '$search'");
        $content = $bdd->query("SELECT * FROM odldc_$era WHERE contenu LIKE '$search'");
        $results = [];
        $ids     = [];

        while ($result = $arc->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($result['id'], $ids)) continue;
            $results[] = $result;
            $ids    [] = $result['id'];
        }
        while ($result = $content->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($result['id'], $ids)) continue;
            $results[] = $result;
            $ids    [] = $result['id'];
        }
    }

    if (isset($IDresults)) {
        array_multisort($IDresults, SORT_ASC);
        $n = count($IDresults);
        echo "<section class='results_page' id='$era'>";
        echo "<p class='text-result'>$n résultat(s) de la recherche.</p>";
        echo "<div class='content_period content_result'>";
        foreach ($IDresults as $k=>$line) {
            displayLine($line, false, "/".$line['cover']);
        }
        echo "</div>";
        echo "</section>";
    } elseif (isset($results)) {
        array_multisort($results, SORT_ASC);
        $n = count($results);
        echo "<section class='results_page' id='$era'>";
        echo "<p class='text-result'>$n résultat(s) pour :<br />".$_REQUEST['search']."</p>";
        echo "<div class='content_period content_result'>";
        foreach ($results as $k=>$line) {
            displayLine($line, false, "/".$line['cover']);
        }
        echo "</div>";
        echo "</section>";
    } else {
        echo "<section class='results_page' id='$era'>";
        echo "<p class='text-result'>Aucun résultat trouvé.</p>";
        echo "</section>";
    }
} elseif (isset($_REQUEST['search']) && !empty($_REQUEST['search']) && (!isset($_REQUEST['era']) || empty($_REQUEST['era']) || ($_REQUEST['era'] === "all"))) {
    $id = (int) $_REQUEST['search'];
    if (is_int($id) && $id != 0) {
        $id  = $_REQUEST['search'];
        $qry = $bdd->query("SELECT * FROM odldc_rebirth WHERE id = '$id'");
        while ($IDresult = $qry->fetch(PDO::FETCH_ASSOC)) {
            $IDresults[] = $IDresult;
        }
    } else {
        $search  = "%".$_REQUEST['search']."%";
        $arc     = $bdd->query("SELECT * FROM odldc_rebirth WHERE arc LIKE '$search'");
        $content = $bdd->query("SELECT * FROM odldc_rebirth WHERE contenu LIKE '$search'");
        $results = [];
        $ids     = [];

        while ($result = $arc->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($result['id'], $ids)) continue;
            $results[] = $result;
            $ids    [] = $result['id'];
        }
        while ($result = $content->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($result['id'], $ids)) continue;
            $results[] = $result;
            $ids    [] = $result['id'];
        }
    }

    if (isset($IDresults)) {
        array_multisort($IDresults, SORT_ASC);
        $n = count($IDresults);
        echo "<section>";
        echo "<p class='text-result'>$n résultat(s).</p>";
        echo "<div class='content_period content_result'>";
        foreach ($IDresults as $k=>$line) {
            displayLine($line, false, "/".$line['cover']);
        }
        echo "</div>";
        echo "</section>";
    } elseif (isset($results)) {
        array_multisort($results, SORT_ASC);
        $n = count($results);
        echo "<section>";
        echo "<p class='text-result'>$n résultat(s) pour :<br />".$_REQUEST['search']."</p>";
        echo "<div class='content_period content_result'>";
        foreach ($results as $k=>$line) {
            displayLine($line, false, "/".$line['cover']);
        }
        echo "</div>";
        echo "</section>";
    } else {
        echo "<section>";
        echo "<p class='text-result'>Aucun résultat trouvé.</p>";
        echo "</section>";
    }
} else {
    echo "<section>";
    echo "<p class='text-result'>Aucun résultat trouvé.</p>";
    echo "</section>";
}

displayBtnUp();

include($ROOT.'partial/footer.php');
