<?php

require_once __DIR__ . '/utils_php/db_utils.php';
require_once __DIR__ . '/utils_php/functions.php';

$session = getNewSession();
$config = getConfig();
$dataLayer = new DataLayer;
if (!isset($_SESSION['images'])) {
    $_SESSION['images'] = [];
}
$iterations = 0;
$lines;
// hopefully one of the 5 most recent detections has an image that is valid, we'll use that one as the most recent detection until the newer ones get their images created
while ($mostrecent = $dataLayer->$dataLayer->getMostRecentAjax($_GET['hard_limit'])->fetchArray(SQLITE3_ASSOC)) {
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
        <table
            class="ci-overview-mostrecent <?php echo ($_GET['previous_detection_identifier'] == 'undefined') ? '' : 'fade-in'; ?>">
            <h3>Most Recent Detection: <span style="font-weight: normal;">
                    <?php echo $mostrecent['Date'] . " " . $mostrecent['Time']; ?>
                </span></h3>
            <tr>
                <td class="relative"><a target="_blank" href="index.php?filename=<?php echo $mostrecent['File_Name']; ?>"><img
                            class="copyimage" title="Open in new tab" width="25" height="25" src="images/copy.png"></a>
                    <div class="centered_image_container" style="margin-bottom: 0px !important;">
                        <!-- TODO Build Flickr include   -->
                        <?php if (!empty($config["FLICKR_API_KEY"]) && strlen($image[2]) > 0) { ?>
                            <img onclick='setModalText(<?php echo $iterations; ?>,"<?php echo urlencode($image[2]); ?>",  "<?php echo $image[3]; ?>", "<?php echo $image[4]; ?>", "<?php echo $image[1]; ?>")'
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
?>