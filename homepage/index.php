<!-- TODO :NEEDS REFACTORING -->
<!-- TODO: MAKE index.php obsolete what is it for? -->
<?php
// Jump directly into view.php
header('Location: views.php' . (isset($_GET['filename']) ? '?view=Recordings&' + $_GET['filename'] : ''));

require('./scripts/utils_php/functions.php');
$config = getConfig();
$site_name = $config['SITE_NAME'] != "" ? $config['SITE_NAME'] : 'BirdNET-Pi';
?>
<html>
<?php include('includes/header.php') ?>

<body>
  <div class="banner">
    <?php include('includes/logo.php') ?>
    <?php require_once('includes/live_stream.php') ?>
  </div>
  <iframe src=/views.php <?php
  echo (isset($_GET['filename']) ? '?view=Recordings&' + $_GET['filename'] : '');
  ?> "></iframe>
</html>