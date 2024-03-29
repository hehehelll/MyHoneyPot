﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>MyHoneyPot</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
</head>
<body id="top">
<div class="wrapper">
    <div id="header">
        <h1><a href="index.php">MyHoneyPot</a></h1>
        <br/>


    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topbar">
        <div class="fl_left">By 150104010065 HeChun</a>
        </div>
        <br class="clear"/>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topnav">
        <ul class="nav">
            <li class="active"><a href="index.php">Homepage</a></li>
            <li><a href="kippo-graph.php">Honey-Graph</a></li>
            <li><a href="kippo-input.php">Honey-Input</a></li>
            <li><a href="kippo-playlog.php">Honey-PlayLog</a></li>
            <li><a href="kippo-ip.php">Honey-Ip</a></li>
            <li><a href="kippo-geo.php">Honey-Geo</a></li>
            <li class="last"><a href="gallery.php">Graph Gallery</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>IP information</h2>
            <hr/>

            <?php
            # Author: ikoniaris

            require_once('config.php');
            require_once(DIR_ROOT . '/class/KippoGeo.class.php');

            $kippoGeo = new KippoGeo();

            //-----------------------------------------------------------------------------------------------------------------
            //KIPPO-GEO DATA
            //-----------------------------------------------------------------------------------------------------------------
            $kippoGeo->printKippoGeoData();
            //-----------------------------------------------------------------------------------------------------------------
            //END
            //-----------------------------------------------------------------------------------------------------------------

            ?>
            <!-- ####################################################################################################### -->
            <div class="clear"></div>
        </div>
    </div>
</div>
<!-- ####################################################################################################### -->

<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">
    jQuery(function () {
        jQuery('ul.nav').superfish();
    });
</script>
</body>
</html>