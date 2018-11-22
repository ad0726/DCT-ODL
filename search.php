<?php
$period = str_replace(['.php', '/'], '', $_SERVER['REQUEST_URI']);
echo "<div class='search'>
    <form class='search-bar' action='results.php' method='post'>
        <input class='prompt' type='text' name='search' placeholder='Reherche...' autocomplete='off'>
        <input class='hide-input' type='text' name='period' value='".$period."'>
        <i class='fas fa-search'></i>
        <input type='submit' class='hide-input search-submit'>
        <div class='autocompletion'></div>
    </form>
</div>";