<?php
require_once "../../config.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

// Retrieve required launch data from session
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;

if ( ! $USER->instructor ) die("must be instructor");

header("Content-type: text/plain");
echo("Date,Comparison_Type\n");
// Set timezone
date_default_timezone_set('UTC');


if(isset($_GET["check"])){
	$student = $_GET["check"];

	// Start date
	$date = '2016-01-01';
	// End date
	$end_date = '2016-12-31';

	$results = $PDOX->allRowsDie("SELECT attend, user_id FROM {$p}clicker 
        WHERE user_id = :STUDENT AND link_id = :link",
		array(':STUDENT' => $student,
            ":link" => $LINK->id
        )
	); 

	$num = 0;
	$results_size = sizeof($results);

	$date_num = array();


	while($num < $results_size){

		$attend_date = str_replace("-","",$results[$num]['attend']);
		if(!array_key_exists($attend_date, $date_num)){
			$date_num[$attend_date] = 1;
		}else{
			$date_num[$attend_date]++;
		}
		$num++;

	}

	foreach ($date_num as $key => $value) {
		echo "$key,$value\n";
	}
}
