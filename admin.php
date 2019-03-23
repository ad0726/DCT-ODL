<?php
include('header.php');

echo "<section>\n";
echo "\t<div class='form'>\n";
echo "\t\t<div class='flex row'>\n";
echo "\t\t\t<div class='last_element'>\n";
echo "\t\t\t\t<a href='create-section.php' title='Ajouter ère / période'><button type='button' class='btn_admin' ><i class='fas fa-plus-circle'></i><br />Ère / période</button></a>\n";
echo "\t\t\t</div>\n";
echo "\t\t\t<div class='last_element'>\n";
echo "\t\t\t\t<a href='add.php' title='Ajouter un arc'><button type='button' class='btn_admin' ><i class='fas fa-plus-circle'></i><br />Arc</button></a>\n";
echo "\t\t\t</div>\n";
echo "\t\t</div>\n";
echo "\t\t<div class='flex row'>\n";
echo "\t\t\t<div class='last_element'>\n";
echo "\t\t\t\t<a href='manage.php' title='Maintenance'><button type='button' class='btn_admin' ><i class='fas fa-tools'></i><br />Maintenance</button></a>\n";
echo "\t\t\t</div>\n";
echo "\t\t\t<div class='last_element'>\n";
echo "\t\t\t\t<a href='changelog.php' title='Changelog'><button type='button' class='btn_admin' ><i class='fas fa-clipboard'></i><br />Changelog</button></a>\n";
echo "\t\t\t</div>\n";
echo "\t\t</div>\n";
echo "\t</div>\n";
echo "</section>\n";

include('footer.php');