<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

header('Content-Type: application/json');

if (isset($_REQUEST['type']) && isset($_REQUEST['id'])) {
    $type   = $_REQUEST['type'];
    $id     = $_REQUEST['id'];
    $table  = "";
    $search = "";

    if ($type == "era") {
        $search = "period";
        $table  = $search;
    } else if ($type == "universe") {
        $search = "era";
        $table = $search;
    }

    $where  = "id_$type = '$id'";
    $result = [];
    $query  = $bdd->query("SELECT name, id_$search FROM $table WHERE $where ORDER BY position ASC");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $row["id"] = $row["id_$search"];
        unset($row["id_$search"]);
        $result[] = $row;
    }

    if (!empty($result)) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
    }
}