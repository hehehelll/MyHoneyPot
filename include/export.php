<?php

$fileName = "Export_" . $_GET['type'] . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

require_once('../config.php');
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/sql.php'); 
require_once(DIR_ROOT . '/include/misc/xss_clean.php');

R::setup('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

// create the varaiable name from the URL Query string which should match SQL.php, than pass it as db_query
$db_query = ${"db_" . xss_clean($_GET['type'])};

$rows = R::getAll($db_query);

$first = true; // flag for column titeles

// open file without writing to disk
$out = fopen('php://output', 'w');

foreach ($rows as $row) {
    if ($first) {
        $titles = array();
        foreach ($row as $key => $val) {
            $titles[] = $key;
        }
        fputcsv($out, $titles); // write the titles
        $first = false; // no longer on the column titles
    }
    fputcsv($out, $row); // write all other rows
}
fclose($out);

R::close();

?>
