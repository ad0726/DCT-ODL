<?php
$ROOT = './';
include($ROOT.'partial/header.php');
$table_prefix = TABLE_PREFIX;

$sql = "SELECT
            universe.name AS name_universe,
            universe.id_universe AS id_universe,
            era.id_era AS id_era,
            era.name AS name_era,
            era.clean_name AS name_clean_era,
            era.image AS image
        FROM
            {$table_prefix}universe AS universe,
            {$table_prefix}era AS era
        WHERE
            era.id_universe = universe.id_universe";

$query = $bdd->query($sql);
$rows  = [];
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $rows[] = [
        "id"   => $row['id_universe'],
        "name" => $row['name_universe'],
        "era"  => [[
            "id"         => $row['id_era'],
            "name"       => $row['name_era'],
            "name_clean" => $row['name_clean_era'],
            "image"      => $row['image']
        ]]
    ];
}

echo "<section class='odl' id='home_page'>";
foreach ($rows as $universe) {
    echo "
            <div class='universe'>
                <h2 class='title_universe btn_{$universe['id']}'>{$universe['name']}</h2>
                <div class='era_home' id='{$universe['id']}'>";

    foreach ($universe['era'] as $era) {
    echo "<a href='/{$era['name_clean']}.php'>
                    <table class='{$universe['id']}' style='display: table;'>
                        <tr class='line' id='{$era['id']}'>
                            <td class='cel_img'><img src='{$era['image']}' ></td>
                            <td class='cel_title'><h3>{$era['name']}</h3></td>
                        </tr>
                    </table>
            </a>";
}

    echo "
                 <button class='btn_hide down btn_{$universe['id']}' name='hide'>Fermer</button>
                </div>
            </div>";
}
echo "</section>\n";

include($ROOT."partial/footer.php");
