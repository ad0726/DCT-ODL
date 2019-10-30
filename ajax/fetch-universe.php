<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

header('Content-Type: application/json');

if (isset($_REQUEST['id']) && isset($_REQUEST['links'])) {
    $id     = $_REQUEST['id'];
    $result = $bdd->query("SELECT * FROM links WHERE id_universe = '$id'")->fetch(PDO::FETCH_ASSOC);

    if (!empty($result)) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
    }

} else if (isset($_REQUEST['id'])) {
    $id     = $_REQUEST['id'];
    $result = [];
    $query  = $bdd->query("SELECT * FROM universe WHERE id_universe = $id");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row;
    }

    if (!empty($result)) {
        $result['links'] = $links;
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
    }
}
