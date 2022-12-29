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