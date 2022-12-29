function showDialog() {
    document.getElementById('attribution-dialog').showModal();
}

function hideDialog() {
    document.getElementById('attribution-dialog').close();
}

function setModalText(iter, title, text, authorlink, photolink) {
    document.getElementById('modalHeading').innerHTML = "Photo: \"" + decodeURIComponent(title.replaceAll("+", " ")) + "\" Attribution";
    document.getElementById('modalText').innerHTML = "<div><img style='border-radius:5px' src='" + photolink + "'></div><br><div>Image link: <a target='_blank' href=" + text + ">" + text + "</a><br>Author link: <a target='_blank' href=" + authorlink + ">" + authorlink + "</a></div>";
    showDialog();
}

// script view.php 
window.onload = function () {
    var elements = document.querySelectorAll("button[name=view]");

    var setViewsOpacity = function () {
        document.getElementsByClassName("views")[0].style.opacity = "0.5";
    };

    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', setViewsOpacity, false);
    }
};
var topbuttons = document.querySelectorAll("button[form='views']");
if (window.location.search.substr(1) != '') {
    for (var i = 0; i < topbuttons.length; i++) {
        if (topbuttons[i].value == decodeURIComponent(window.location.search.substr(1)).replace(/\+/g, ' ').split('=').pop()) {
            topbuttons[i].classList.add("button-hover");
        }
    }
} else {
    topbuttons[0].classList.add("button-hover");
}
function copyOutput(elem) {
    elem.innerHTML = 'Copied!';
    const copyText = document.getElementsByTagName("pre")[0].textContent;
    const textArea = document.createElement('textarea');
    textArea.style.position = 'absolute';
    textArea.style.left = '-100%';
    textArea.textContent = copyText;
    document.body.append(textArea);
    textArea.select();
    document.execCommand("copy");
}

function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
function setLiveStreamVolume(vol) {
    var audioelement = window.parent.document.getElementsByTagName("audio")[0];
    if (typeof (audioelement) != 'undefined' && audioelement != null) {
        audioelement.volume = vol
    }
}
window.onbeforeunload = function (event) {
    // if the user is playing a video and then navigates away mid-play, the live stream audio should be unmuted again
    var audioelement = window.parent.document.getElementsByTagName("audio")[0];
    if (typeof (audioelement) != 'undefined' && audioelement != null) {
        audioelement.volume = 1
    }
}