<?php
require_once __DIR__ . '/utils_php/db_utils.php';
require_once __DIR__ . '/utils_php/flickr.php';
error_reporting(E_ERROR);
ini_set('display_errors', 1);
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();
$myDate = date('Y-m-d');
$chart = "Combo-$myDate.png";

$db = getDb();

if (file_exists('./scripts/thisrun.txt')) {
  $config = parse_ini_file('./scripts/thisrun.txt');
} elseif (file_exists('./scripts/firstrun.ini')) {
  $config = parse_ini_file('./scripts/firstrun.ini');
}

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

  $statement4 = $db->prepare('SELECT Com_Name, Sci_Name, Date, Time, Confidence, File_Name FROM detections ORDER BY Date DESC, Time DESC LIMIT 5');
  if ($statement4 == False) {
    echo "Database is busy";
    header("refresh: 0;");
  }
  $result4 = $statement4->execute();
  if (!isset($_SESSION['images'])) {
    $_SESSION['images'] = [];
  }
  $iterations = 0;
  $lines;
  // hopefully one of the 5 most recent detections has an image that is valid, we'll use that one as the most recent detection until the newer ones get their images created
  while ($mostrecent = $result4->fetchArray(SQLITE3_ASSOC)) {
    $comname = preg_replace('/ /', '_', $mostrecent['Com_Name']);
    $sciname = preg_replace('/ /', '_', $mostrecent['Sci_Name']);
    $comname = preg_replace('/\'/', '', $comname);
    $filename = "/By_Date/" . $mostrecent['Date'] . "/" . $comname . "/" . $mostrecent['File_Name'];
    $args = "&license=2%2C3%2C4%2C5%2C6%2C9&orientation=square,portrait";
    $comnameprefix = "%20bird";

    // check to make sure the image actually exists, sometimes it takes a minute to be created\
    if (file_exists($home . "/BirdSongs/Extracted" . $filename . ".png")) {
      if ($_GET['previous_detection_identifier'] == $filename) {
        die();
      }
      if ($_GET['only_name'] == "true") {
        echo $comname . "," . $filename;
        die();
      }

      $iterations++;


      ?>
      <table class="<?php echo ($_GET['previous_detection_identifier'] == 'undefined') ? '' : 'fade-in'; ?>">
        <h3>Most Recent Detection: <span style="font-weight: normal;">
            <?php echo $mostrecent['Date'] . " " . $mostrecent['Time']; ?>
          </span></h3>
        <tr>
          <td class="relative"><a target="_blank" href="index.php?filename=<?php echo $mostrecent['File_Name']; ?>"><img
                class="copyimage" title="Open in new tab" width="25" height="25" src="images/copy.png"></a>
            <div class="centered_image_container" style="margin-bottom: 0px !important;">
              <!-- TODO Build Flickr include   -->
              <?php if (!empty($config["FLICKR_API_KEY"]) && strlen($image[2]) > 0) { ?>
                <img
                  onclick='setModalText(<?php echo $iterations; ?>,"<?php echo urlencode($image[2]); ?>",  "<?php echo $image[3]; ?>", "<?php echo $image[4]; ?>", "<?php echo $image[1]; ?>")'
                  src="<?php echo $image[1]; ?>" class="img1">
                <?php } ?>
              <!-- TODO Build Flickr include   -->

              <form action="" method="GET">
                <input type="hidden" name="view" value="Species Stats">
                <button type="submit" name="species" value="<?php echo $mostrecent['Com_Name']; ?>">
                  <?php echo $mostrecent['Com_Name']; ?>
                </button></br>
                <a href="https://<?php echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) ?>.wikipedia.org/wiki/<?php echo $sciname; ?>"
                  target="_blank" /><i>
                  <?php echo $mostrecent['Sci_Name']; ?>
                </i></a>
                <br>Confidence: <?php echo $percent = round((float) round($mostrecent['Confidence'], 2) * 100) . '%'; ?><br>
            </div><br>
            <video style="margin-top:10px" onplay='setLiveStreamVolume(0)' onended='setLiveStreamVolume(1)'
              onpause='setLiveStreamVolume(1)' controls poster="<?php echo $filename . ".png"; ?>" preload="none"
              title="<?php echo $filename; ?>">
              <source src="<?php echo $filename; ?>">
            </video>
          </td>
        </tr>
      </table>
      <?php break;
    }
  }
  if ($iterations == 0) {
    echo "<h3>No Detections For Today.</h3>";
  }
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
        <?php
        $refresh = $config['RECORDING_LENGTH'];
        $dividedrefresh = $refresh / 4;
        if ($dividedrefresh == 0) {
          $dividedrefresh = 1;
        }
        $time = time();
        if (file_exists('./Charts/' . $chart)) {
          echo "<img id='chart' src=\"/Charts/$chart?nocache=$time\">";
        }
        ?>
      </div>

      <div id="most_recent_detection"></div>
      <br>
      <h3>5 Most Recent Detections</h3>
      <div style="padding-bottom:10px;" id="detections_table">
        <h3>Loading...</h3>
      </div>

      <h3>Currently Analyzing</h3>
      <?php
      $refresh = $config['RECORDING_LENGTH'];
      $time = time();
      echo "<img id=\"spectrogramimage\" src=\"/spectrogram.png?nocache=$time\">";
      ?>

    </div>
  </div>

  <script>
    window.setInterval(function () {
      var videoelement = document.getElementsByTagName("video")[0];
      if (typeof videoelement !== "undefined") {
        // don't refresh the detection if the user is playing the previous one's audio, wait until they're finished
        if (!!(videoelement.currentTime > 0 && !videoelement.paused && !videoelement.ended && videoelement.readyState > 2) == false) {
          loadDetectionIfNewExists(videoelement.title);
        }
      } else {
        // image or audio didn't load for some reason, force a refresh in 5 seconds
        loadDetectionIfNewExists();
      }
    }, <?php echo intval($dividedrefresh); ?>* 1000);

    function loadFiveMostRecentDetections() {
      const xhttp = new XMLHttpRequest();
      xhttp.onload = function () {
        if (this.responseText.length > 0 && !this.responseText.includes("Database is busy")) {
          document.getElementById("detections_table").innerHTML = this.responseText;
        }
      }
      if (window.innerWidth > 500) {
        xhttp.open("GET", "todays_detections.php?ajax_detections=true&display_limit=undefined&hard_limit=5", true);
      } else {
        xhttp.open("GET", "todays_detections.php?ajax_detections=true&display_limit=undefined&hard_limit=5&mobile=true", true);
      }
      xhttp.send();
    }
    window.addEventListener("load", function () {
      loadDetectionIfNewExists();
    });

    // every $refresh seconds, this loop will run and refresh the spectrogram image
    window.setInterval(function () {
      document.getElementById("spectrogramimage").src = "/spectrogram.png?nocache=" + Date.now();
    }, <?php echo $refresh; ?>* 1000);
  </script>