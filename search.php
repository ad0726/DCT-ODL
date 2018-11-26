<?php
if (isset($_REQUEST['era']) && !empty($_REQUEST['era'])) {
    $era = $_REQUEST['era'];
} else {
    $era = "";
}
echo "<div class='search'>
    <form class='search-bar' action='results.php' method='post'>
        <input class='prompt' type='text' name='search' placeholder='Reherche...' autocomplete='off'>
        <input class='hide-input era' type='text' name='era' value='$era'>
        <i class='fas fa-search'></i>
        <input type='submit' class='hide-input search-submit'>
        <div class='autocompletion'></div>
    </form>
</div>";