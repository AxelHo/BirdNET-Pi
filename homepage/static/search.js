
var timer = '';
searchterm = "";

// Jquery read<
$(function () {
    document.getElementById("searchterm").onkeydown = (function (e) {
        if (e.key === "Enter") {
            clearTimeout(timer);
            searchDetections(document.getElementById("searchterm").value);
            document.getElementById("searchterm").blur();
        } else {
            clearTimeout(timer);
            timer = setTimeout(function () {
                searchDetections(document.getElementById("searchterm").value);

                setTimeout(function () {
                    // search auto submitted and now the user is probably scrolling, get the keyboard out of the way & prevent browser from jumping to the top when a video is played
                    document.getElementById("searchterm").blur();
                }, 2000);
            }, 1000);
        }
    });
});

function switchViews(element) {
    if (searchterm == "") {
        document.getElementById("detections_table").innerHTML = "<h3>Loading <?php echo $todaycount['COUNT(*)']; ?> detections...</h3>";
    } else {
        document.getElementById("detections_table").innerHTML = "<h3>Loading...</h3>";
    }
    if (element.innerHTML == "Legacy view") {
        element.innerHTML = "Normal view";
        loadDetections(undefined);
    } else if (element.innerHTML == "Normal view") {
        element.innerHTML = "Legacy view";
        loadDetections(40);
    }
}
function searchDetections(searchvalue) {
    document.getElementById("detections_table").innerHTML = "<h3>Loading...</h3>";
    searchterm = searchvalue;
    if (document.getElementsByClassName('legacyview')[0].innerHTML == "Normal view") {
        loadDetections(undefined, undefined);
    } else {
        loadDetections(40, undefined);
    }
}
function loadDetections(detections_limit, element = undefined) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        if (typeof element !== "undefined") {
            element.remove();
            document.getElementById("detections_table").innerHTML += this.responseText;
        } else {
            document.getElementById("detections_table").innerHTML = this.responseText;
        }

    }
    if (searchterm != "") {
        xhttp.open("GET", "todays_detections.php?ajax_detections=true&display_limit=" + detections_limit + "&searchterm=" + searchterm, true);
    } else {
        xhttp.open("GET", "todays_detections.php?ajax_detections=true&display_limit=" + detections_limit, true);
    }
    xhttp.send();
}
