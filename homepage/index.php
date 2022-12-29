<!-- TODO NEEDS REFACTORING -->
<?php
require_once('includes/functions.php');
$config = getConfig();
$site_name = $config['SITE_NAME'] != "" ? $config['SITE_NAME'] : 'BirdNET-Pi';
?>
<html>
<?php include('includes/header.php') ?>

<body>
  <div class="banner">
    <?php include('includes/logo.php') ?>
    <?php require_once('includes/live_stream.php') ?>
    <iframe src=/views.php <?php
    echo (isset($_GET['filename']) ? '?view=Recordings&' + $_GET['filename'] : '');
    ?> "></iframe>
</html>