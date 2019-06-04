<?php
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/libchart/classes/libchart.php');
require_once(DIR_ROOT . '/include/qgooglevisualapi/config.inc.php');
require_once(DIR_ROOT . '/include/geoplugin/geoplugin.class.php');
require_once(DIR_ROOT . '/include/maxmind/geoip2.phar');
require_once(DIR_ROOT . '/include/misc/ip2host.php');

class GeoDataObject
{
    public $city = "N/A";
    public $region = "N/A";
    public $countryName = "N/A";
    public $countryCode = "N/A";
    public $latitude = "N/A";
    public $longitude = "N/A";

    function __construct($KippoGeoObject, $ip)
    {
        if (GEO_METHOD == 'LOCAL') {

            try {
               $geodata = $KippoGeoObject->maxmind->city($ip);
           } catch (GeoIp2\Exception\GeoIp2Exception $e) {
              return;
            }

            $this->city = $geodata->city->name;
            $this->region = $geodata->mostSpecificSubdivision->name;
            $this->countryName = $geodata->country->name;
            $this->countryCode = $geodata->country->isoCode;
            $this->latitude = $geodata->location->latitude;
            $this->longitude = $geodata->location->longitude;

        } else if (GEO_METHOD == 'GEOPLUGIN') {

            $KippoGeoObject->geoplugin->locate($ip);

            $this->city = $KippoGeoObject->geoplugin->city;
            $this->region = $KippoGeoObject->geoplugin->region;
            $this->countryName = $KippoGeoObject->geoplugin->countryName;
            $this->countryCode = $KippoGeoObject->geoplugin->countryCode;
            $this->latitude = $KippoGeoObject->geoplugin->latitude;
            $this->longitude = $KippoGeoObject->geoplugin->longitude;

        } else {
            echo "Error validating selected GEO_METHOD.";
            exit();
        }
    }
}

class KippoGeo
{
    public $geoplugin;
    public $maxmind;

    function __construct()
    {
        $this->geoplugin = new geoPlugin();
        $this->maxmind = new GeoIp2\Database\Reader(DIR_ROOT . '/include/maxmind/GeoLite2-City.mmdb');

        R::setup('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    }

    function __destruct()
    {
        R::close();
    }

    public function printKippoGeoData()
    {
        $db_query = "SELECT ip, COUNT(ip)
          FROM sessions
          GROUP BY ip
          ORDER BY COUNT(ip) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {

            $verticalChart = new VerticalBarChart(600, 300);
            $pieChart = new PieChart(600, 300);
            $dataSet = new XYDataSet();


            $intensityPieChart = new PieChart(600, 300);
            $intensityDataSet = new XYDataSet();

            //地图
            $gMapTop10 = new QMapGoogleGraph;
            $gMapTop10->addColumns(
                array(
                    array('number', 'Lat'),
                    array('number', 'Lon'),
                    array('string', 'IP')
                )
            );

            //地图
            $intensityMap = new QIntensitymapGoogleGraph;
            $intensityMap->addDrawProperties(
                array(
                    "title" => 'IntensityMap',
                )
            );
            $intensityMap->addColumns(
                array(
                    array('string', '', 'Country'),
                    array('number', '#Probes', 'a'),
                )
            );

            //临时数据库表
            $temp_table = 'CREATE TEMPORARY TABLE temp_ip (ip VARCHAR(15), counter INT, country VARCHAR(3))';
            R::exec($temp_table);

            //虚拟计数器
            $counter = 1;

            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>IP Address</th>';
            echo '<th>Counts</th>';
            echo '<th>City</th>';
            echo '<th>Region</th>';
            echo '<th>Country Name</th>';
            echo '<th>Code</th>';
            echo '<th>Latitude</th>';
            echo '<th>Longitude</th>';
            echo '<th>Hostname</th>';
            //echo '<th colspan="9">IP Lookup</th>';
            echo '</tr></thead><tbody>';

            //虚拟索引变量
            $col = 0;

            foreach ($rows as $row) {

                $geodata = new GeoDataObject($this, $row['ip']);

                $label = $row['ip'] . " - " . $geodata->countryCode;
                $dataSet->addPoint(new Point($label, $row['COUNT(ip)']));

                //在谷歌地图中标记
                $tooltip = "<strong>TOP $counter/10:</strong> " . $row['ip'] . "<br />"
                    . "<strong>Probes:</strong> " . $row['COUNT(ip)'] . "<br />"
                    . "<strong>City:</strong> " . $geodata->city . "<br />"
                    . "<strong>Region:</strong> " . $geodata->region . "<br />"
                    . "<strong>Country:</strong> " . $geodata->countryName . "<br />"
                    //."<strong>Country Code:</strong> ".$geodata->countryCode."<br />"
                    . "<strong>Latitude:</strong> " . $geodata->latitude . "<br />"
                    . "<strong>Longitude:</strong> " . $geodata->longitude . "<br />";

                //把标记添加到地图上
                $gMapTop10->setValues(
                    array(
                        array($col, 0, (float)$geodata->latitude),
                        array($col, 1, (float)$geodata->longitude),
                        array($col, 2, $tooltip)
                    )
                );

                //准备插入临时表中的数据
                $ip = $row['ip'];
                $ip_count = $row['COUNT(ip)'];
                $country_code = $geodata->countryCode;
                $country_query = "INSERT INTO temp_ip VALUES('$ip', '$ip_count', '$country_code')";
                R::exec($country_query);

                echo '<tr class="light">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . $row['ip'] . '</td>';
                echo '<td>' . $row['COUNT(ip)'] . '</td>';
                echo '<td>' . $geodata->city . '</td>';
                echo '<td>' . $geodata->region . '</td>';
                echo '<td>' . $geodata->countryName . '</td>';
                echo '<td>' . $geodata->countryCode . '</td>';
                echo '<td>' . $geodata->latitude . '</td>';
                echo '<td>' . $geodata->longitude . '</td>';
                echo '<td>' . get_host($row['ip']) . '</td>';
//                echo '<td class="icon"><a href="http://www.dshield.org/ipinfo.html?ip=' . $row['ip'] . '" target="_blank"><img class="icon" src="images/dshield.ico"/></a></td>';
//                echo '<td class="icon"><a href="http://www.ipvoid.com/scan/' . $row['ip'] . '" target="_blank"><img class="icon" src="images/ipvoid.ico"/></a></td>';
//                echo '<td class="icon"><a href="http://www.robtex.com/ip/' . $row['ip'] . '.html" target="_blank"><img class="icon" src="images/robtex.ico"/></a></td>';
//                echo '<td class="icon"><a href="http://www.fortiguard.com/ip_rep/index.php?data=' . $row['ip'] . '&lookup=Lookup" target="_blank"><img class="icon" src="images/fortiguard.ico"/></a></td>';
//                echo '<td class="icon"><a href="https://www.alienvault.com/open-threat-exchange/ip/' . $row['ip'] . '" target="_blank"><img class="icon" src="images/alienvault.ico"/></a></td>';
//                echo '<td class="icon"><a href="http://www.reputationauthority.org/lookup.php?ip=' . $row['ip'] . '" target="_blank"><img class="icon" src="images/watchguard.ico"/></a></td>';
//                echo '<td class="icon"><a href="http://www.mcafee.com/threat-intelligence/ip/default.aspx?ip=' . $row['ip'] . '" target="_blank"><img class="icon" src="images/mcafee.ico"/></a></td>';
//                echo '<td class="icon"><a href="http://www.ip-adress.com/ip_tracer/' . $row['ip'] . '" target="_blank"><img class="icon" src="images/ip_tracer.png"/></a></td>';
//                echo '<td class="icon"><a href="https://www.virustotal.com/en/ip-address/' . $row['ip'] . '/information/" target="_blank"><img class="icon" src="images/virustotal.ico"/></a></td>';
                echo '</tr>';

                //Lastly, we increase the index used by maps to indicate the next row,
                //and the dummy counter that indicates the next IP index (out of 10)
                $col++;
                $counter++;
            }


            echo '</tbody></table>';
            echo '<hr /><br />';


            $verticalChart->setDataSet($dataSet);
            $verticalChart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP_CC);

            $verticalChart->getPlot()->setGraphPadding(new Padding(5, 50, 100, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $verticalChart->render(DIR_ROOT . "/generated-graphs/connections_per_ip_geo.png");
            echo '<p>VerticalChart_Number of Connections per unique IP(TOP 10)+Country Codes</p>';
            echo '<img src="generated-graphs/connections_per_ip_geo.png">';

            $pieChart->setDataSet($dataSet);
            $pieChart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP_CC);
            $pieChart->render(DIR_ROOT . "/generated-graphs/connections_per_ip_geo_pie.png");
            echo '<p>PieChart_Number of Connections per unique IP(TOP 10)+Country Codes</p>';
            echo '<img src="generated-graphs/connections_per_ip_geo_pie.png">';
            echo '<hr /><br />';


            echo '<p>The following zoomable world map marks the geographic locations of the top 10 IPs <p>';
            echo $gMapTop10->render();
            echo '<br/><hr /><br />';


            $db_query_map = "SELECT country, SUM(counter)
              FROM temp_ip
              GROUP BY country
              ORDER BY SUM(counter) DESC ";
            //LIMIT 10 ";

            $rows = R::getAll($db_query_map);

            if (count($rows)) {
                $col = 0; 
               
                foreach ($rows as $row) {
                    $countryProbes = $row['country'] . " - " . $row['SUM(counter)'];
                    $intensityDataSet->addPoint(new Point($countryProbes, $row['SUM(counter)']));
                    $intensityMap->setValues(
                        array(
                            array($col, 0, (string)$row['country']),
                            array($col, 1, (int)$row['SUM(counter)']),
                        )
                    );
                    $col++;
                }
            }

            echo '<p></p>';
            echo $intensityMap->render();
            //echo '<br/>';

            
            $intensityPieChart->setDataSet($intensityDataSet);
            $intensityPieChart->setTitle(NUMBER_OF_CONNECTIONS_PER_COUNTRY);
            $intensityPieChart->render(DIR_ROOT . "/generated-graphs/connections_per_country_pie.png");
            echo '<p>IntensityPieChart_Number of Connections per Country</p>';
            echo '<img src="generated-graphs/connections_per_country_pie.png">';

//            if (GEO_METHOD == 'LOCAL') {
//                echo '<hr /><small><a href="http://www.maxmind.com">http://www.maxmind.com</a></small><br />';
//            } else if (GEO_METHOD == 'GEOPLUGIN') {
//                echo '<hr /><small><a href="http://www.geoplugin.com/geolocation/" target="_new">IP Geolocation</a> by <a href="http://www.geoplugin.com/" target="_new">geoPlugin</a></small><br />';
//            } else {
//                //TODO
//            }

        } //END IF
    }
}

?>