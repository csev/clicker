<?php
require_once "../../config.php";
require_once $CFG->dirroot."/pdo.php";
require_once $CFG->dirroot."/lib/lms_lib.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// Retrieve required launch data from session
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;

//header("Content-type: text/csv");

header("Content-type: text/plain");
echo("Date,Comparison_Type\n");
// Set timezone
date_default_timezone_set('UTC');

 // Start date
$start_date = '2011-01-01';
 // End date
$end_date = '2017-12-31';

//Select all ATTEND, with qualified IPADDRESS, try to record user_id
/*$results = $PDOX->allRowsDie("SELECT attend, user_id FROM {$p}clicker WHERE attend BETWEEN '2011-01-01' AND '2017-12-31'");
 //echo($results);
$num = 0;
$size = sizeof($results);

echo("$num");
echo("$size");

print_r($results);
while($num < $size){


	$num++;
}*/
//$size = sizeof($date_results);

//echo($results);
while (strtotime($start_date) <= strtotime($end_date)) {
	$out = str_replace("-","",$start_date);
	echo "$out,".rand(100,1000)."\n";
	$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
}
