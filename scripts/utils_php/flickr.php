<?php
function getFlickr()
{
if (!empty($config["FLICKR_API_KEY"])) {

if(!empty($config["FLICKR_FILTER_EMAIL"])) {
  if(!isset($_SESSION["FLICKR_FILTER_EMAIL"])) {
    unset($_SESSION['images']);
    $_SESSION['FLICKR_FILTER_EMAIL'] = json_decode(file_get_contents("https://www.flickr.com/services/rest/?method=flickr.people.findByEmail&api_key=".$config["FLICKR_API_KEY"]."&find_email=".$config["FLICKR_FILTER_EMAIL"]."&format=json&nojsoncallback=1"), true)["user"]["nsid"];
  }
  $args = "&user_id=".$_SESSION['FLICKR_FILTER_EMAIL'];
  $comnameprefix = "";
} else {
  if(isset($_SESSION["FLICKR_FILTER_EMAIL"])) {
    unset($_SESSION["FLICKR_FILTER_EMAIL"]);
    unset($_SESSION['images']);
  }
}


// if we already searched flickr for this species before, use the previous image rather than doing an unneccesary api call
$key = array_search($comname, array_column($_SESSION['images'], 0));
if($key !== false) {
  $image = $_SESSION['images'][$key];
} else {
  // only open the file once per script execution
  if(!isset($lines)) {
    $lines = file($home."/BirdNET-Pi/model/labels_flickr.txt");
  }
  // convert sci name to English name
  foreach($lines as $line){ 
    if(strpos($line, $mostrecent['Sci_Name']) !== false){
      $engname = trim(explode("_", $line)[1]);
      break;
    }
  }

 $flickrjson = json_decode(file_get_contents("https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=".$config["FLICKR_API_KEY"]."&text=".str_replace(" ", "%20", $engname).$comnameprefix."&sort=relevance".$args."&per_page=5&media=photos&format=json&nojsoncallback=1"), true)["photos"]["photo"][0];
  $modaltext = "https://flickr.com/photos/".$flickrjson["owner"]."/".$flickrjson["id"];
  $authorlink = "https://flickr.com/people/".$flickrjson["owner"];
  $imageurl = 'https://farm' .$flickrjson["farm"]. '.static.flickr.com/' .$flickrjson["server"]. '/' .$flickrjson["id"]. '_'  .$flickrjson["secret"].  '.jpg';
  array_push($_SESSION['images'], array($comname,$imageurl,$flickrjson["title"], $modaltext, $authorlink));
  $image = $_SESSION['images'][count($_SESSION['images'])-1];
}
}

}
?>