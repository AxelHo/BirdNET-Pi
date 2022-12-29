function loadDetectionIfNewExists(previous_detection_identifier = undefined) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        // if there's a new detection that needs to be updated to the page
        if (this.responseText.length > 0 && !this.responseText.includes("Database is busy") && !this.responseText.includes("No Detections") || previous_detection_identifier == undefined) {
            document.getElementById("most_recent_detection").innerHTML = this.responseText;

            // only going to load left chart & 5 most recents if there's a new detection
            loadLeftChart();
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