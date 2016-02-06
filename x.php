<?php
header("Content-type: text/csv");

echo("Date,Comparison_Type\n");
// Set timezone
 date_default_timezone_set('UTC');
 
 // Start date
 $date = '2011-01-01';
 // End date
 $end_date = '2014-12-31';
 
 while (strtotime($date) <= strtotime($end_date)) {
                $out = str_replace("-","",$date);
                echo "$out,".rand(100,1000)."\n";
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
 }
