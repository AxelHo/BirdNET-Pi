<?php
$caddypwd = $config['CADDY_PWD'];
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo '<table><tr><td>You cannot access the web terminal</td></tr></table>';
    exit;
} else {
    $submittedpwd = $_SERVER['PHP_AUTH_PW'];
    $submitteduser = $_SERVER['PHP_AUTH_USER'];
    if ($submittedpwd == $caddypwd && $submitteduser == 'birdnet') {
        #ACCESS THE WEB TERMINAL
        echo "<iframe src='/terminal'></iframe>";
    } else {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo '<table><tr><td>You cannot access the web terminal</td></tr></table>';
        exit;
    }
}

?>