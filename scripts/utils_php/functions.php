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

function getNewSession()
{
    // error_reporting(E_ERROR);
    $maxtime = 7200;
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('session.gc_maxlifetime', $maxtime);
    session_set_cookie_params($maxtime);
    session_start();
    return "New Session maxtime:  " . $maxtime;

}
?>