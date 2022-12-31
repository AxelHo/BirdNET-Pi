<?php
if (isset($_GET['ajax_detections']) && $_GET['ajax_detections'] == "true") {
    // legacy mode
    if (isset($_GET['hard_limit']) && is_numeric($_GET['hard_limit'])) {
        $statement0 = $db->prepare('SELECT Time, Com_Name, Sci_Name, Confidence, File_Name FROM detections WHERE Date == Date(\'now\', \'localtime\') ' . $searchquery . ' ORDER BY Time DESC LIMIT ' . $_GET['hard_limit']);
    } else {
        $statement0 = $db->prepare('SELECT Time, Com_Name, Sci_Name, Confidence, File_Name FROM detections WHERE Date == Date(\'now\', \'localtime\') ' . $searchquery . ' ORDER BY Time DESC');
    }


    if ($statement0 == False) {
        echo "Database is busy";
        header("refresh: 0;");
    }
    $result0 = $statement0->execute();

    ?>
    <table>
        <?php

        if (!isset($_SESSION['images'])) {
            $_SESSION['images'] = [];
        }
        $iterations = 0;
        $lines;

        if (file_exists('./scripts/thisrun.txt')) {
            $config = parse_ini_file('./scripts/thisrun.txt');
        } elseif (file_exists('./scripts/firstrun.ini')) {
            $config = parse_ini_file('./scripts/firstrun.ini');
        }

        while ($todaytable = $result0->fetchArray(SQLITE3_ASSOC)) {
            $iterations++;

            $comname = preg_replace('/ /', '_', $todaytable['Com_Name']);
            $comname = preg_replace('/\'/', '_', $comname);
            $filename = "/By_Date/" . date('Y-m-d') . "/" . $comname . "/" . $todaytable['File_Name'];
            $sciname = preg_replace('/ /', '_', $todaytable['Sci_Name']);
            $args = "&license=2%2C3%2C4%2C5%2C6%2C9&orientation=square,portrait";
            $comnameprefix = "%20bird";

            if (!empty($config["FLICKR_API_KEY"]) && (isset($_GET['display_limit']) || isset($_GET['hard_limit']))) {

                if (!empty($config["FLICKR_FILTER_EMAIL"])) {
                    if (!isset($_SESSION["FLICKR_FILTER_EMAIL"])) {
                        unset($_SESSION['images']);
                        $_SESSION['FLICKR_FILTER_EMAIL'] = json_decode(file_get_contents("https://www.flickr.com/services/rest/?method=flickr.people.findByEmail&api_key=" . $config["FLICKR_API_KEY"] . "&find_email=" . $config["FLICKR_FILTER_EMAIL"] . "&format=json&nojsoncallback=1"), true)["user"]["nsid"];
                    }
                    $args = "&user_id=" . $_SESSION['FLICKR_FILTER_EMAIL'];
                    $comnameprefix = "";
                } else {
                    if (isset($_SESSION["FLICKR_FILTER_EMAIL"])) {
                        unset($_SESSION["FLICKR_FILTER_EMAIL"]);
                        unset($_SESSION['images']);
                    }
                }

                // if we already searched flickr for this species before, use the previous image rather than doing an unneccesary api call
                $key = array_search($comname, array_column($_SESSION['images'], 0));
                if ($key !== false) {
                    $image = $_SESSION['images'][$key];
                } else {
                    // only open the file once per script execution
                    if (!isset($lines)) {
                        $lines = file($home . "/BirdNET-Pi/model/labels_flickr.txt");
                    }
                    // convert sci name to English name
                    foreach ($lines as $line) {
                        if (strpos($line, $todaytable['Sci_Name']) !== false) {
                            $engname = trim(explode("_", $line)[1]);
                            break;
                        }
                    }
                    $flickrjson = json_decode(file_get_contents("https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=" . $config["FLICKR_API_KEY"] . "&text=" . str_replace(" ", "%20", $engname) . $comnameprefix . "&sort=relevance" . $args . "&per_page=5&media=photos&format=json&nojsoncallback=1"), true)["photos"]["photo"][0];
                    $modaltext = "https://flickr.com/photos/" . $flickrjson["owner"] . "/" . $flickrjson["id"];
                    $authorlink = "https://flickr.com/people/" . $flickrjson["owner"];
                    $imageurl = 'https://farm' . $flickrjson["farm"] . '.static.flickr.com/' . $flickrjson["server"] . '/' . $flickrjson["id"] . '_' . $flickrjson["secret"] . '.jpg';
                    array_push($_SESSION['images'], array($comname, $imageurl, $flickrjson["title"], $modaltext, $authorlink));
                    $image = $_SESSION['images'][count($_SESSION['images']) - 1];
                }
            }
            ?>
            <?php if (isset($_GET['display_limit']) && is_numeric($_GET['display_limit'])) { ?>
                <tr class="relative" id="<?php echo $iterations; ?>">
                    <td class="relative"><a target="_blank" href="index.php?filename=<?php echo $todaytable['File_Name']; ?>"><img
                                class="copyimage" title="Open in new tab" width=25 src="images/copy.png"></a>

                        <div class="centered_image_container">
                            <?php if (!empty($config["FLICKR_API_KEY"]) && strlen($image[2]) > 0) { ?>
                                <img onclick='setModalText(<?php echo $iterations; ?>,"<?php echo urlencode($image[2]); ?>",  "<?php echo $image[3]; ?>", "<?php echo $image[4]; ?>", "<?php echo $image[1]; ?>")'
                                    src="<?php echo $image[1]; ?>" class="img1">
                                <?php } ?>

                            <?php echo $todaytable['Time']; ?><br>
                            <b><a class="a2" href="https://allaboutbirds.org/guide/<?php echo $comname; ?>" target="top">
                                    <?php echo $todaytable['Com_Name']; ?>
                                </a></b><br>
                            <a class="a2" href="https://wikipedia.org/wiki/<?php echo $sciname; ?>" target="top"><i>
                                    <?php echo $todaytable['Sci_Name']; ?>
                                </i></a><br>
                            <b>Confidence:</b>
                            <?php echo round((float) round($todaytable['Confidence'], 2) * 100) . '%'; ?><br>
                        </div><br>
                        <video onplay='setLiveStreamVolume(0)' onended='setLiveStreamVolume(1)' onpause='setLiveStreamVolume(1)'
                            controls poster="<?php echo $filename . ".png"; ?>" preload="none" title="<?php echo $filename; ?>">
                            <source preload="none" src="<?php echo $filename; ?>">
                        </video>
                    </td>
                    <?php } else { //legacy mode ?>
                <tr class="relative" id="<?php echo $iterations; ?>">
                    <td>
                        <?php echo $todaytable['Time']; ?><br>
                    </td>
                    <td id="recent_detection_middle_td">
                        <div>
                            <div>
                                <?php if (!empty($config["FLICKR_API_KEY"]) && isset($_GET['hard_limit']) && strlen($image[2]) > 0) { ?>
                                    <img style="float:left;height:75px;"
                                        onclick='setModalText(<?php echo $iterations; ?>,"<?php echo urlencode($image[2]); ?>",  "<?php echo $image[3]; ?>", "<?php echo $image[4]; ?>", "<?php echo $image[1]; ?>")'
                                        src="<?php echo $image[1]; ?>" id="birdimage" class="img1">
                                    <?php } ?>
                            </div>
                            <div>
                                <b><a class="a2" href="https://allaboutbirds.org/guide/<?php echo $comname; ?>" target="top">
                                        <?php echo $todaytable['Com_Name']; ?>
                                    </a></b><br>
                                <a class="a2"
                                    href="https://<?php echo substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) ?>.wikipedia.org/wiki/<?php echo $sciname; ?>"
                                    target="top"><i>
                                        <?php echo $todaytable['Sci_Name']; ?>
                                    </i></a><br>
                    </td>
                    </div>
                    </div>
                    <td><b>Confidence:</b>
                        <?php echo round((float) round($todaytable['Confidence'], 2) * 100) . '%'; ?><br>
                    </td>
                    <?php if (!isset($_GET['mobile'])) { ?>
                        <td style="min-width:180px"><audio controls preload="none" title="<?php echo $filename; ?>">
                                <source preload="none" src="<?php echo $filename; ?>"></video>
                                <?php } ?>
                    </td>
                    <?php } ?>
                <?php } ?>
        </tr>
    </table>

    <?php
    if ($iterations == 0) {
        echo "<h3>No Detections For Today.</h3>";
    }

    // don't show the button if there's no more detections to be displayed, we're at the end of the list
    if ($iterations >= 40 && isset($_GET['display_limit']) && is_numeric($_GET['display_limit'])) { ?>
        <center>
            <button class="loadmore" onclick="loadDetections(<?php echo $_GET['display_limit'] + 40; ?>, this);"
                value="Today's Detections">Load 40 More...</button>
        </center>
    <?php }

    die();
}