<!-- TODO NEEDS REFACTORING -->

<head>
    <title>
        <?php echo $site_name; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css?v=<?php echo rand(); ?>">
    <link rel="stylesheet" type="text/css" href="../static/dialog-polyfill.css" />
    <script src="static/dialog-polyfill.js"></script>
    <script type="text/javascript" src="static/dialog.js"></script>
    <script type="text/javascript">
        window.birdnet = window.birdnet || {};
        window.birdnet.refresh = "<?php echo $config['RECORDING_LENGTH']; ?>"
        window.birdnet.dividedrefresh = (window.birdnet.refresh / 4) != 0 ? (window.birdnet.refresh / 4) : 1;
    </script>
    <script type="text/javascript" src="static/xhr.js"></script>
</head>