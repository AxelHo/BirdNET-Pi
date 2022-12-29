<?php
$caddypwd = $config['CADDY_PWD'];
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You cannot access the web terminal';
    exit;
} else {
    $submittedpwd = $_SERVER['PHP_AUTH_PW'];
    $submitteduser = $_SERVER['PHP_AUTH_USER'];
    $allowedCommands = array(
        'sudo systemctl stop livestream.service && sudo /etc/init.d/icecast2 stop',
        'sudo systemctl restart livestream.service && sudo /etc/init.d/icecast2 restart',
        'sudo systemctl disable --now livestream.service && sudo systemctl disable icecast2 && sudo /etc/init.d/icecast2 stop',
        'sudo systemctl enable icecast2 && sudo /etc/init.d/icecast2 start && sudo systemctl enable --now livestream.service',
        'sudo systemctl stop web_terminal.service',
        'sudo systemctl restart web_terminal.service',
        'sudo systemctl disable --now web_terminal.service',
        'sudo systemctl enable --now web_terminal.service',
        'sudo systemctl stop birdnet_log.service',
        'sudo systemctl restart birdnet_log.service',
        'sudo systemctl disable --now birdnet_log.service',
        'sudo systemctl enable --now birdnet_log.service',
        'sudo systemctl stop extraction.service',
        'sudo systemctl restart extraction.service',
        'sudo systemctl disable --now extraction.service',
        'sudo systemctl enable --now extraction.service',
        'sudo systemctl stop birdnet_server.service',
        'sudo systemctl restart birdnet_server.service',
        'sudo systemctl disable --now birdnet_server.service',
        'sudo systemctl enable --now birdnet_server.service',
        'sudo systemctl stop birdnet_analysis.service',
        'sudo systemctl restart birdnet_analysis.service',
        'sudo systemctl disable --now birdnet_analysis.service',
        'sudo systemctl enable --now birdnet_analysis.service',
        'sudo systemctl stop birdnet_stats.service',
        'sudo systemctl restart birdnet_stats.service',
        'sudo systemctl disable --now birdnet_stats.service',
        'sudo systemctl enable --now birdnet_stats.service',
        'sudo systemctl stop birdnet_recording.service',
        'sudo systemctl restart birdnet_recording.service',
        'sudo systemctl disable --now birdnet_recording.service',
        'sudo systemctl enable --now birdnet_recording.service',
        'sudo systemctl stop chart_viewer.service',
        'sudo systemctl restart chart_viewer.service',
        'sudo systemctl disable --now chart_viewer.service',
        'sudo systemctl enable --now chart_viewer.service',
        'sudo systemctl stop spectrogram_viewer.service',
        'sudo systemctl restart spectrogram_viewer.service',
        'sudo systemctl disable --now spectrogram_viewer.service',
        'sudo systemctl enable --now spectrogram_viewer.service',
        'stop_core_services.sh',
        'restart_services.sh',
        'sudo reboot',
        'update_birdnet.sh',
        'sudo shutdown now',
        'sudo clear_all_data.sh'
    );
    $command = $_GET['submit'];
    if ($submittedpwd == $caddypwd && $submitteduser == 'birdnet' && in_array($command, $allowedCommands)) {
        if (isset($command)) {
            $initcommand = $command;
            if (strpos($command, "systemctl") !== false) {
                $tmp = explode(" ", trim($command));
                $command .= "& sleep 3;sudo systemctl status " . end($tmp);
            }
            if ($initcommand == "update_birdnet.sh") {
                unset($_SESSION['behind']);
            }
            $results = shell_exec("$command 2>&1");
            $results = str_replace("FAILURE", "<span style='color:red'>FAILURE</span>", $results);
            $results = str_replace("failed", "<span style='color:red'>failed</span>", $results);
            $results = str_replace("active (running)", "<span style='color:green'><b>active (running)</b></span>", $results);
            $results = str_replace("Your branch is up to date", "<span style='color:limegreen'><b>Your branch is up to date</b></span>", $results);
            if (strlen($results) == 0) {
                $results = "This command has no output.";
            }
            echo "<table style='min-width:70%;'><tr class='relative'><th>Output of command:`" . $initcommand . "`<button class='copyimage' style='right:40px' onclick='copyOutput(this);'>Copy</button></th></tr><tr><td><pre style='text-align:left'>$results</pre></td></tr></table>";
        } else {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You cannot access the web terminal';
            exit;
        }
    }
}
ob_end_flush();
?>