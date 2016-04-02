<?php
//include "heatmap.php";
require_once "../../config.php";
//echo('1');
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
// Retrieve required launch data from session
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$OUTPUT->header(); // Start the document and begin the <head>
$OUTPUT->bodyStart(); // Finish the </head> and start the <body>
$OUTPUT->flashMessages(); // Print out the $_SESSION['success'] and error messages
?>

<!--Returning button-->
<div style = "position:absolute; top:0px; right:0px">
  <a href="index.php" class="btn btn-info">Back to Clicker</a>
</div>

<!---Calendar Heatmap-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<link href="bootstrap.css" rel="stylesheet" /> 
<link href="calendarmap.css" rel="stylesheet" /> 

<script type="text/javascript" src="bootstrap.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>

</head>
<body>

  <div class="calendar-map" id="calendar_div"></div>

  <script>
  var attend_url = '<?= addSession("checking.php") ?>';
  var global_db_calendar_url = '<?= addSession("db_calendar.php") ?>';
  </script>

  <script type="text/javascript" src="calendarmap.js?x=<?= time() ?>"></script>
  <!--End of Calendar HeatMap-->

  <?
// Finish the body (including loading JavaScript for JQUery and Bootstrap)
// And put out the common footer material
  $OUTPUT->footer();
