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

echo("<br><br>");
?>

<!--Printing out return buttons-->
<div style = "position:absolute; top:0px; right:0px">
  <a href="attendance.php" class="btn btn-info">All Year Calendar</a>
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

  <?php 
  
  if ( isset($_GET['check']) ) {
    echo('var student_dataurl = "'.addSession('db_check_student.php?check='.$_GET['check']).'";');
  } else {
    echo('var student_dataurl = "'.addSession('db_check_student.php').'";');
  }
  ?>

  </script>
  <script type="text/javascript" src="check.js?x=<?= time() ?>"></script>


  <!--End of Calendar HeatMap-->
  <?
  echo("<br><br>");

  if(isset($_GET["date"])){
    $check_date = $_GET["date"];
    $check_date = substr($check_date, 0,4) .'-' . substr($check_date, 4,2) .'-' . substr($check_date, 6,2);

    $results = $PDOX->allRowsDie("SELECT user_id, attend, ipaddr FROM {$p}clicker WHERE attend = :check_date ORDER BY user_id ASC",
      array( ':check_date' => $check_date)
      );
    $_SESSION["check_date"] = $results;
  }


  //Printing out table of user_id, Attendance date, IP address and cheking button
  $table = $_SESSION["check_date"];

  echo('<table border="1" style="margin-left:auto; margin-right:auto;text-align:center ">'."\n");
  echo("<tr><th>"._("User")."</th><th>"._("Attendance")."</th><th>"._("IP Address")."</th><th>"._("Attendance")."</th></tr>\n");
  foreach ( $table as $row ) {
    echo "<tr><td>";
    echo($row['user_id']);
    echo("</td><td>");
    echo($row['attend']);
    echo("</td><td>");
    echo(htmlent_utf8($row['ipaddr']));
    echo("</td><td>");
    echo('<form action="" method="get">');
    echo('<input type="hidden" name="check" value='.$row['user_id'].'> ');
    echo('<input type="submit" value="Check">');

    echo("</form>");
    echo("</td></tr>\n");
  }
  echo("</table>\n");
// Finish the body (including loading JavaScript for JQUery and Bootstrap)
// And put out the common footer material
  $OUTPUT->footer();
