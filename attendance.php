<?php
//include "heatmap.php";
require_once "../../config.php";
require_once $CFG->dirroot."/pdo.php";
require_once $CFG->dirroot."/lib/lms_lib.php";
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
  


<div style = "position:absolute; top:0px; right:0px">
  <a href="index.php" class="btn btn-default">Back to Clicker</a>
</div>

<!---Calendar Heatmap-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  
    <link href="bootstrap.css" rel="stylesheet" /> 
    <script type="text/javascript" src="bootstrap.min.js"></script> 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <style>
  .calender-map {
      font: 10px sans-serif;
      shape-rendering: crispEdges;
    }
    .day {
      stroke: #666;
    }
    .month {
      fill: none;
      stroke: #000;
      stroke-width: 2px;
    }
    .RdYlGn .q0-11{fill:rgb(165,0,38)}
    .RdYlGn .q1-11{fill:rgb(215,48,39)}
    .RdYlGn .q2-11{fill:rgb(244,109,67)}
    .RdYlGn .q3-11{fill:rgb(253,174,97)}
    .RdYlGn .q4-11{fill:rgb(254,224,139)}
    .RdYlGn .q5-11{fill:rgb(255,255,191)}
    .RdYlGn .q6-11{fill:rgb(217,239,139)}
    .RdYlGn .q7-11{fill:rgb(166,217,106)}
    .RdYlGn .q8-11{fill:rgb(102,189,99)}
    .RdYlGn .q9-11{fill:rgb(26,152,80)}
    .RdYlGn .q10-11{fill:rgb(0,104,55)}
    </style>
</head>
<body>
<div class="calender-map" id="calendar_div"></div>

<script>
var attend_url = '<?= addSession("attendance.php") ?>';
</script>
<script type="text/javascript" src="calendermap.js?x=<?= time() ?>"></script>


<!--End of Calendar HeatMap-->

<?

echo("<br><br>");


  //CHECK GET[date] and parse through database
if(isset($_GET["date"])){


  $check_date = $_GET["date"];
  $check_date = substr($check_date, 0,4) .'-' . substr($check_date, 4,2) .'-' . substr($check_date, 6,2);
  
  $results = $PDOX->allRowsDie("SELECT user_id, attend, ipaddr FROM {$p}clicker WHERE attend = :check_date ORDER BY user_id ASC",
        array( ':check_date' => $check_date)
        );

  $_SESSION["check_date"] = $results;

  echo('<table border="1" style="margin-left:auto; margin-right:auto;text-align:center ">'."\n");
  echo("<tr><th>"._("User")."</th><th>"._("Attendance")."</th><th>"._("IP Address")."</th><th>"._("Attendance")."</th></tr>\n");

  foreach ( $results as $row ) {
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
}



if ( isset($_GET["check"])) {

echo('<table border="1" style="margin-left:auto; margin-right:auto;text-align:center ">'."\n");
  echo("<tr><th>"._("User")."</th><th>"._("Attendance")."</th><th>"._("IP Address")."</th><th>"._("Attendance")."</th></tr>\n");

  $table = $_SESSION["check_date"];

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

  var_dump($_GET);
  $user = $_GET["check"];

}

// Finish the body (including loading JavaScript for JQUery and Bootstrap)
// And put out the common footer material

$OUTPUT->footer();

