<?php
require_once "../../config.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// Retrieve required launch data from session
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;

header("Content-type: text/plain");
echo("Date,Comparison_Type\n");
// Set timezone
 date_default_timezone_set('UTC');
 
 // Start date
 $date = '2017-01-01';
 // End date
 $end_date = '2017-12-31';

$results = $PDOX->allRowsDie("SELECT attend, user_id FROM {$p}clicker"); 

$num = 0;
$results_size = sizeof($results);

$date_num = array();


while($num < $results_size){
	
	$attend_date = str_replace("-","",$results[$num]['attend']);
	if(!array_key_exists($attend_date, $date_num)){
		$date_num[$attend_date] = 1;
		//$date_num[$attend_date]['users'] = array();
		//$date_num[$attend_date]['users'][0] = $results[$num]['user_id'];
	}else{
		$date_num[$attend_date]++;
		//array_push($date_num[$attend_date]['users'], $results[$num]['user_id']);
	}
	$num++;

}

foreach ($date_num as $key => $value) {
    echo "$key,$value\n";
}
