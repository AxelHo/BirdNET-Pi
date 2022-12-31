<?php
// TODO Needs refactoring

require_once('scripts/utils_php/functions.php');
require_once('scripts/utils_php/db_utils.php');


$session = getNewSession();
// IN PHP THIS IS GLOBAL?
$config = getConfig();
$site_name = $config['SITE_NAME'] != "" ? $config['SITE_NAME'] : 'BirdNET-Pi';

$user = shell_exec("awk -F: '/1000/{print $1}' /etc/passwd");
$user = trim($user);
$home = shell_exec("awk -F: '/1000/{print $6}' /etc/passwd");
$home = trim($home);
if (!isset($_SESSION['behind'])) {
  $fetch = shell_exec("sudo -u" . $user . " git -C " . $home . "/BirdNET-Pi fetch 2>&1");
  $_SESSION['behind'] = trim(shell_exec("sudo -u" . $user . " git -C " . $home . "/BirdNET-Pi status | sed -n '2 p' | cut -d ' ' -f 7"));
  if (isset($_SESSION['behind']) && intval($_SESSION['behind']) >= 99) { ?>

  <?php }
}


?>
<html>
<?php include('includes/header.php') ?>


<body>
  <div class="banner">
    <?php include('includes/logo.php') ?>
    <?php require_once('includes/live_stream.php') ?>
  </div>
  <?php include('includes/topnav.php') ?>
  <div class="views">
    <?php
    if (isset($_GET['view'])) {
      if ($_GET['view'] == "System Info") {
        echo "<iframe src='phpsysinfo/index.php'></iframe>";
      }
      if ($_GET['view'] == "System Controls") {
        include('scripts/system_controls.php');
      }
      if ($_GET['view'] == "Services") {
        include('scripts/service_controls.php');
      }
      if ($_GET['view'] == "Spectrogram") {
        include('spectrogram.php');
      }
      if ($_GET['view'] == "View Log") {
        echo "<body style=\"scroll:no;overflow-x:hidden;\"><iframe style=\"width:calc( 100% + 1em);\" src=\"/log\"></iframe></body>";
      }
      if ($_GET['view'] == "Overview") {
        include('overview.php');
      }
      if ($_GET['view'] == "Today's Detections") {
        include('todays_detections.php');
      }
      if ($_GET['view'] == "Species Stats") {
        include('stats.php');
      }
      if ($_GET['view'] == "Weekly Report") {
        include('weekly_report.php');
      }
      if ($_GET['view'] == "Streamlit") {
        echo "<iframe src=\"/stats\"></iframe>";
      }
      if ($_GET['view'] == "Daily Charts") {
        include('history.php');
      }
      if ($_GET['view'] == "Tools") {
        include('includes/subnav_tools.php');

      }
      if ($_GET['view'] == "Recordings") {
        include('play.php');
      }
      if ($_GET['view'] == "Settings") {
        include('scripts/config.php');
      }
      if ($_GET['view'] == "Advanced") {
        include('scripts/advanced.php');
      }
      if ($_GET['view'] == "Included") {
        include('includes/subnav_included.php');
        include('./scripts/include_list.php');
      }
      if ($_GET['view'] == "Excluded") {
        include('includes/subnav_exluded.php');
        include('./scripts/exclude_list.php');
      }
      if ($_GET['view'] == "File") {
        echo "<iframe src='scripts/filemanager/filemanager.php'></iframe>";
      }
      if ($_GET['view'] == "Webterm") {
        include('includes/webterm.php');
      }
    } elseif (isset($_GET['submit'])) {
      include('includes/services_submit.php');

    } else {
      include('overview.php');
    }
    ?>

  </div>
</body>

</html>