<!-- TODO NEEDS REFACTORING -->
<!-- MAYBE ONE END DIV TO MUCH -->
<div class="stream">
    <?php
    if (isset($_GET['stream'])) {
        if (file_exists('./scripts/thisrun.txt')) {
            $config = parse_ini_file('./scripts/thisrun.txt');
        } elseif (file_exists('./scripts/firstrun.ini')) {
            $config = parse_ini_file('./scripts/firstrun.ini');
        }
        $caddypwd = $config['CADDY_PWD'];
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You cannot listen to the live audio stream';
            exit;
        } else {
            $submittedpwd = $_SERVER['PHP_AUTH_PW'];
            $submitteduser = $_SERVER['PHP_AUTH_USER'];
            if ($submittedpwd == $caddypwd && $submitteduser == 'birdnet') {
                echo "
  <audio controls autoplay><source src=\"/stream\"></audio>
  </div>
  <h1><a href=\"/\"><img class=\"topimage\" src=\"images/bnp.png\"></a></h1>
  </div>";
            } else {
                header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 401 Unauthorized');
                echo 'You cannot listen to the live audio stream';
                exit;
            }
        }
    } else {
        echo "
  <form action=\"\" method=\"GET\">
    <button type=\"submit\" name=\"stream\" value=\"play\">Live Audio</button>
  </form>
  </div>
  <h1><a href=\"/\"><img class=\"topimage\" src=\"images/bnp.png\"></a></h1>
</div><div class=\"centered\"><h3>$site_name</h3></div>";
    }
    ?>
</div>