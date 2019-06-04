<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
<!-- ############################# -->
<h2>Overall honeypot activity</h2>
<hr/>
<?php
# Author: ikoniaris

require_once('config.php');
require_once(DIR_ROOT . '/class/KippoGraph.class.php');

$kippoGraph = new KippoGraph();

if (REALTIME_STATS == 'YES' && PHP_SAPI != 'cli' || (REALTIME_STATS == 'NO' && PHP_SAPI == 'cli') ||
    (REALTIME_STATS == 'NO' && !$kippoGraph->generatedKippoGraphChartsExist())
) {
    $kippoGraph->generateKippoGraphCharts();
}

//-----------------------------------------------------------------------------------------------------------------
//OVERALL HONEYPOT ACTIVITY
//-----------------------------------------------------------------------------------------------------------------
$kippoGraph->printOverallHoneypotActivity();

echo '<br /><br />';
?>
<h2>Graphical statistics generated from MyHoneyPot database<br/>
</h2>

<div class="portfolio">
    <div class="fl_left">
        <h2>Top 10 passwords</h2>
<!--
        <p><a href="include/export.php?type=Pass">CSV of all distinct passwords</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/top10_passwords.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Top 10 usernames</h2>
<!--
        <p><a href="include/export.php?type=User">CSV of all distinct Usernames</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/top10_usernames.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">

        <h2>Top 10 user-pass combos</h2>
<!--
        <p><a href="include/export.php?type=Combo">CSV of all distinct combinations</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/top10_combinations.png" alt=""/></div>
    <div class="fl_left">


    </div>
    <div class="fl_right"><img src="generated-graphs/top10_combinations_pie.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Success ratio</h2>
<!--
        <p><a href="include/export.php?type=Success">CSV of all successfull attacks</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/success_ratio.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Successes per day/week</h2>
<!--
        <p><a href="include/export.php?type=SuccessLogon">CSV of all successful logons</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/most_successful_logins_per_day.png" alt=""/></div>
    <div class="clear"></div>
    <div class="fl_left">
<!--
        <p><a href="include/export.php?type=SuccessDay">CSV of daily successes</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/successes_per_day.png" alt=""/></div>
    <div class="clear"></div>
    <div class="fl_left">
<!--
        <p><a href="include/export.php?type=SuccessWeek">CSV of weekly successes</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/successes_per_week.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Connections per IP</h2>
<!--
        <p><a href="include/export.php?type=IP">CSV of all connections per IP</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/connections_per_ip.png" alt=""/></div>
    <div class="fl_left">

    </div>
    <div class="fl_right"><img src="generated-graphs/connections_per_ip_pie.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Successful logins from the same IP</h2>
<!--
        <p><a href="include/export.php?type=SuccessIP">CSV of all successful IPs</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/logins_from_same_ip.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Probes per day/week</h2>

    </div>
    <div class="fl_right"><img src="generated-graphs/most_probes_per_day.png" alt=""/></div>
    <div class="fl_left">
<!--        
        <p><a href="include/export.php?type=ProbesDay">CSV of all probes per day</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/probes_per_day.png" alt=""/></div>
    <div class="fl_left">

<!--
        <p><a href="include/export.php?type=ProbesWeek">CSV of all probes per week</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/probes_per_week.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Top 10 SSH clients</h2>

<!--
        <p><a href="include/export.php?type=SSH">CSV of all SSH clients</a>
-->
        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/top10_ssh_clients.png" alt=""/></div>
    <div class="clear"></div>
</div>
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
