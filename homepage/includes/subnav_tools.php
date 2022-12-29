<?php
$caddypwd = $config['CADDY_PWD'];
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo '<table><tr><td>You cannot edit the settings for this installation</td></tr></table>';
    exit;
} else {
    $submittedpwd = $_SERVER['PHP_AUTH_PW'];
    $submitteduser = $_SERVER['PHP_AUTH_USER'];
    if ($submittedpwd == $caddypwd && $submitteduser == 'birdnet') {
        $url = $_SERVER['SERVER_NAME'] . "/scripts/adminer.php";
        echo "<div class=\"centered\">
          <form action=\"\" method=\"GET\" id=\"views\">
          <button type=\"submit\" name=\"view\" value=\"Settings\" form=\"views\">Settings</button>
          <button type=\"submit\" name=\"view\" value=\"System Info\" form=\"views\">System Info</button>
          <button type=\"submit\" name=\"view\" value=\"System Controls\" form=\"views\">System Controls" . $updatediv . "</button>
          <button type=\"submit\" name=\"view\" value=\"Services\" form=\"views\">Services</button>
          <button type=\"submit\" name=\"view\" value=\"File\" form=\"views\">File Manager</button>
          <a href=\"scripts/adminer.php\" target=\"_blank\"><button type=\"submit\" form=\"\">Database Maintenance</button></a>
          <button type=\"submit\" name=\"view\" value=\"Webterm\" form=\"views\">Web Terminal</button>
          <button type=\"submit\" name=\"view\" value=\"Included\" form=\"views\">Custom Species List</button>
          <button type=\"submit\" name=\"view\" value=\"Excluded\" form=\"views\">Excluded Species List</button>
          </form>
          </div>";
    } else {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo '<table><tr><td>You cannot edit the settings for this installation</td></tr></table>';
        exit;
    }
}
?>