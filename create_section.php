<?php
include("header.php");

echo "<section>";
if (isset($_REQUEST['formfilled']) && $_REQUEST['formfilled'] == 42) {

} else {
    echo "<div class='form'>\n";
    echo "\t<input type='checkbox'";
    echo "</div>";
}

echo "</section>";
include("footer.php");