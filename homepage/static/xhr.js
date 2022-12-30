function loadDetectionIfNewExists(previous_detection_identifier = undefined) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        // if there's a new detection that needs to be updated to the page
        if (this.responseText.length > 0 && !this.responseText.includes("Database is busy") && !this.responseText.includes("No Detections") || previous_detection_identifier == undefined) {
            document.getElementById("most_recent_detection").innerHTML = this.responseText;

            // only going to load left chart & 5 most recents if there's a new detection
            // loadLeftChart();
            loadFiveMostRecentDetections();
            refreshTopTen();
        }
    }
    xhttp.open("GET", "overview.php?ajax_detections=true&previous_detection_identifier=" + previous_detection_identifier, true);
    xhttp.send();
}

function loadLeftChart() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        if (this.responseText.length > 0 && !this.responseText.includes("Database is busy")) {
            document.getElementsByClassName("left-column")[0].innerHTML = this.responseText;
        }
    }
    xhttp.open("GET", "overview.php?ajax_left_chart=true", true);
    xhttp.send();
}

function refreshTopTen() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        if (this.responseText.length > 0 && !this.responseText.includes("Database is busy") && !this.responseText.includes("No Detections") || previous_detection_identifier == undefined) {
            document.getElementById("chart").src = "/Charts/" + this.responseText + "?nocache=" + Date.now();
        }
    }
    xhttp.open("GET", "overview.php?fetch_chart_string=true", true);
    xhttp.send();
}

// script from overview.php

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
    console.log('window.birdnet.dividedrefresh: ' + window.birdnet.dividedrefresh);
}, window.birdnet.refresh * 1000);
// was set to , but why ?window.birdnet.dividedrefresh


function loadFiveMostRecentDetections() {
    const xhttp = new XMLHttpRequest();
    const xhrToday = "todays_detections.php?ajax_detections=true&display_limit=undefined&hard_limit=5" + window.birdnet.mobileGetParam;
    xhttp.onload = function () {
        if (this.responseText.length > 0 && !this.responseText.includes("Database is busy")) {
            document.getElementById("detections_table").innerHTML = this.responseText;
        }
    }
    // if (window.innerWidth > 500) {
    //     xhttp.open("GET", "todays_detections.php?ajax_detections=true&display_limit=undefined&hard_limit=5", true);
    // } else {
    //     xhttp.open("GET", "todays_detections.php?ajax_detections=true&display_limit=undefined&hard_limit=5&mobile=true", true);
    // }
    xhttp.open("GET", xhrToday, true);
    xhttp.send();
}
window.addEventListener("load", function () {
    loadDetectionIfNewExists();
});

// every $refresh seconds, this loop will run and refresh the spectrogram image
window.setInterval(function () {
    document.getElementById("spectrogramimage").src = "/spectrogram.png?nocache=" + Date.now();
}, window.birdnet.refresh * 1000);