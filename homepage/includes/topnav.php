<div class="topnav" id="myTopnav">
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Overview" form="views">Overview</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Today's Detections" form="views">Today's Detections</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Spectrogram" form="views">Spectrogram</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Species Stats" form="views">Best Recordings</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Streamlit" form="views">Species Stats</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Daily Charts" form="views">Daily Charts</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="Recordings" form="views">Recordings</button>
    </form>
    <form action="" method="GET" id="views">
        <button type="submit" name="view" value="View Log" form="views">View Log</button>
    </form>
    <form action="" id="toolsbtn" method="GET" id="views">
        <!-- TODO Needs refactoring do it in one line -->
        <button type="submit" name="view" value="Tools" form="views">Tools<?php if (isset($_SESSION['behind']) && intval($_SESSION['behind']) >= 50 && ($config['SILENCE_UPDATE_INDICATOR'] != 1)) {
        $updatediv = ' <div class="updatenumber">' . $_SESSION["behind"] . '</div>';
    } else {
        $updatediv = "";
    }
    echo $updatediv; ?></button>
    </form>
    <button href="javascript:void(0);" class="icon" onclick="myFunction()"><img src="images/menu.png"></button>
</div>