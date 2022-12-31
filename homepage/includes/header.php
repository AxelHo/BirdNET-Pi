<!-- TODO NEEDS REFACTORING -->

<head>
    <title>
        <?php echo $site_name; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css?v=<?php echo rand(); ?>">
    <link rel="stylesheet" type="text/css" href="../static/dialog-polyfill.css" />
    <script src="static/jquery-3.6.3.min.js"></script>
    <script type="text/javascript">
        window.birdnet = window.birdnet || {};
        window.birdnet.refresh = "<?php echo $config['RECORDING_LENGTH']; ?>"
        window.birdnet.dividedrefresh = (window.birdnet.refresh / 4) != 0 ? (window.birdnet.refresh / 4) : 1;
        window.birdnet.isMobile = window.innerWidth < 500;
        window.birdnet.mobileGetParam = window.birdnet.isMobile ? "&mobile=true" : "";
        document.querySelector("html").dataset.resolution = (window.birdnet.isMobile ? 'ci-mobile' : 'ci-desktop');
    </script>
    <script type="text/javascript" src="static/xhr.js"></script>
    <script src="static/dialog-polyfill.js"></script>
    <script type="text/javascript" src="static/dialog.js"></script>
    <script type="text/javascript" src="static/search.js"></script>
</head>