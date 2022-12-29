<?php
function getConfig()
{
    $thisRun = __DIR__ . '/../thisrun.txt';
    $firstRun = __DIR__ . '/../firstrun.ini';
    if (file_exists($thisRun)) {
        return parse_ini_file($thisRun);
    } elseif (file_exists($firstRun)) {
        return parse_ini_file($firstRun);
    } else {
        echo "<h1> No Config file found</h1>";
    }
}
?>