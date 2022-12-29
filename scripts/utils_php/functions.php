<?php
function getConfig()
{
    if (file_exists('../scripts/thisrun.txt')) {
        return parse_ini_file('../scripts/thisrun.txt');
    } elseif (file_exists('../scripts/firstrun.ini')) {
        return parse_ini_file('../scripts/firstrun.ini');
    }
}
?>