<?php
include("header.php");

echo "<section>";
echo "<div class='form'>\n";
if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {
    $maintaining = (isset($_REQUEST['maintaining']) && $_REQUEST['maintaining'] == "on") ? 1 : 0;

    $bdd->exec("UPDATE odldc_admin SET value = $maintaining WHERE param = 'isMaintaining'");

    header("Location: index.php");

} else {
    $query = $bdd->query("SELECT value FROM odldc_admin WHERE param = 'isMaintaining'")->fetch(PDO::FETCH_ASSOC);
    $checked = ($query['value'] == TRUE) ? "checked" : "";

    echo "\t<form action='?' method='POST'>\n";
    echo "\t\t<input type='hidden' name='formfilled' value='42'>\n";
    echo "\t\t<label>Maintenance</label>\n";
    echo "\t\t<input type='checkbox' name='maintaining' $checked >\n";
    echo "\t\t<input type='submit' class='btn_send' value='Envoyez'>\n";
    echo "\t</form>\n";
}
echo "</div>";
echo "</section>";
include("footer.php");