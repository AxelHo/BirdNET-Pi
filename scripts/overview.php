<?php
use function mysql_xdevapi\getSession;

require_once __DIR__ . '/utils_php/flickr.php';

$dataLayer = new DataLayer;


$myDate = date('Y-m-d');
$chart = "Combo-$myDate.png";
$hasChart = file_exists('./Charts/' . $chart);

$user = shell_exec("awk -F: '/1000/{print $1}' /etc/passwd");
$home = shell_exec("awk -F: '/1000/{print $6}' /etc/passwd");
$home = trim($home);

if (isset($_GET['fetch_chart_string']) && $_GET['fetch_chart_string'] == "true") {
  $myDate = date('Y-m-d');
  $chart = "Combo-$myDate.png";

  echo $chart;
  die();
}


if (isset($_GET['ajax_detections']) && $_GET['ajax_detections'] == "true" && isset($_GET['previous_detection_identifier'])) {
  require_once __DIR__ . '/utils_php/overview_ajax_detections.php';
  die();

}

if (isset($_GET['ajax_left_chart']) && $_GET['ajax_left_chart'] == "true") {
  require_once __DIR__ . '/utils_php/left_chart.php';
  die();
}
?>

<div class="overview">
  <dialog id="attribution-dialog">
    <h1 id="modalHeading"></h1>
    <p id="modalText"></p>
    <button onclick="hideDialog()">Close</button>
  </dialog>

  <script>
    var dialog = document.querySelector('dialog');
    dialogPolyfill.registerDialog(dialog);

  </script>
  <div class="overview-stats">
    <div class="left-column">
    </div>
    <div class="right-column">
      <div class="chart">
        <img id='chart' src="/Charts/<?= $hasChart ? $chart : '' ?>?nocache=<?= time() ?>" alt="<?= $hasChart ?>" />
      </div>

      <div id="most_recent_detection"></div>
      <br>
      <h3>5 Most Recent Detections</h3>
      <div style="padding-bottom:10px;" id="detections_table">
        <h3>Loading...</h3>
      </div>

      <span class="ci-spectogramimage">
        <h3>Currently Analyzing</h3>
        <img id="spectrogramimage" src="/spectrogram.png?nocache=<?= time() ?>" />
      </span>

    </div>
  </div>