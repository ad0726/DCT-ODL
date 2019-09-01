<?php
$ROOT = './';
include($ROOT.'partial/header.php');

if (isset($_REQUEST['search']) && !empty($_REQUEST['search']) && isset($_REQUEST['era'])) {
    $era          = $_REQUEST['era'];
    $id           = (int) $_REQUEST['search'];
    $where_clause = "";
    $results      = [];
    $ids          = [];

    if (!empty($_REQUEST['era']) && ($era != "all")) {
        $where_clause = "(";
        $periods      = fetchPeriods($era);
        $n            = count($periods);
        $i            = 0;
        foreach ($periods as $period) {
            ++$i;
            $where_clause .= "id_period = '$period'";
            if ($i < $n) {
                $where_clause .= " OR ";
            }
        }
        $where_clause .= ") AND ";
    }

    if (is_int($id) && $id != 0) {
        $title = "de la recherche";
        $qry   = $bdd->query("SELECT * FROM arc WHERE id_arc = $id");
        while ($result = $qry->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $result;
        }
    } else {
        $title  = "pour :<br />".$_REQUEST['search'];
        $search = "%".$_REQUEST['search']."%";
        $arc    = $bdd->query("SELECT * FROM arc WHERE $where_clause (title LIKE '$search' OR content LIKE '$search')");

        while ($result = $arc->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($result['id_arc'], $ids)) continue;
            $results[] = $result;
            $ids    [] = $result['id_arc'];
        }
    }
    if (!empty($results)) {
        array_multisort($results, SORT_ASC);
        $n = count($results);
        echo "<section class='results_page' id='$era'>";
        echo "<p class='text-result'>$n résultat(s) $title.</p>";
        echo "<div class='content_period content_result'>";
        foreach ($results as $k=>$line) {
            displayLine($line, false, "/assets/img/covers/".$line['cover']);
        }
        echo "</div>";
        echo "</section>";
    } else {
        echo "<section class='results_page' id='$era'>";
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
