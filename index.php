<?php
//ainclude "heatmap.php";
require_once "../../config.php";
require_once $CFG->dirroot."/pdo.php";
require_once $CFG->dirroot."/lib/lms_lib.php";
//echo('1');
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
// Retrieve required launch data from session
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
date_default_timezone_set("America/New_York");
$today = date("Y-m-d");
//Handling post data

$numA = 0;
$numB = 0;
$numC = 0;
$numD = 0;
$numE = 0;
$taken = 0;

$results = $PDOX->allRowsDie("SELECT guess, COUNT(guess) AS total FROM {$p}clicker WHERE attend = :TODAY GROUP BY guess ORDER BY guess ASC",
  array(':TODAY' => $today
    ));

$size = sizeof($results);
while($taken < $size){

  if(@$results[$taken]['total'] != null){

    $totalTaken = $results[$taken]['total'];
    $choice = @$results[$taken]['guess'];

    switch($choice){
      case 0:
      $numA = 0 + $results[$taken]['total'];
      break;
      case 1:
      $numB = 0 + $results[$taken]['total'];
      break;
      case 2:
      $numC = 0 + $results[$taken]['total'];
      break;
      case 3:
      $numD = 0 + $results[$taken]['total'];
      break;
      case 4:
      $numE = 0 + $results[$taken]['total'];
      break;
    }    
  }
  $taken ++;
}

if(!isset($_POST['reset']) ){
  if(isset($_POST['sendA']) ){
    $PDOX->queryDie("INSERT INTO {$p}clicker
      (link_id, user_id, guess, attend, ipaddr, count)
      VALUES ( :LI, :UI, :GU, NOW(), :IP, 1)
      ON DUPLICATE KEY UPDATE  guess = :GU, ipaddr = :IP, count = count + 1",
      array(
        ':LI' => $LINK->id,
        ':UI' => $USER->id,
        ':GU' => 0,
        ':IP' => $_SERVER["REMOTE_ADDR"]
        ));

    $_SESSION['success'] = 'Guess Recorded';
    header('Location: '.addSession('index.php') ) ;
    return;
  }else if(isset($_POST['sendB']) ){
    $PDOX->queryDie("INSERT INTO {$p}clicker
      (link_id, user_id, guess, attend, ipaddr, count)
      VALUES ( :LI, :UI, :GU, NOW(), :IP, 1)
      ON DUPLICATE KEY UPDATE guess = :GU, ipaddr = :IP, count = count + 1",
      array(
        ':LI' => $LINK->id,
        ':UI' => $USER->id,
        ':GU' => 1,
        ':IP' => $_SERVER["REMOTE_ADDR"]
        ));

    $_SESSION['success'] = 'Guess Recorded';
    header('Location: '.addSession('index.php') ) ;
    return;
  }else if(isset($_POST['sendC']) ){
    $PDOX->queryDie("INSERT INTO {$p}clicker
      (link_id, user_id, guess, attend, ipaddr, count)
      VALUES ( :LI, :UI, :GU, NOW(), :IP, 1)
      ON DUPLICATE KEY UPDATE guess = :GU, ipaddr = :IP, count = count + 1",
      array(
        ':LI' => $LINK->id,
        ':UI' => $USER->id,
        ':GU' => 2,
        ':IP' => $_SERVER['REMOTE_ADDR']
        ));

    $_SESSION['success'] = 'Guess Recorded';
    header('Location: '.addSession('index.php') ) ;
    return;
  }
  else if(isset($_POST['sendD']) ){
    $PDOX->queryDie("INSERT INTO {$p}clicker
      (link_id, user_id, guess, attend, ipaddr, count)
      VALUES ( :LI, :UI, :GU, NOW(), :IP, 1)
      ON DUPLICATE KEY UPDATE guess = :GU, ipaddr = :IP, count = count + 1",
      array(
        ':LI' => $LINK->id,
        ':UI' => $USER->id,
        ':GU' => 3,
        ':IP' => $_SERVER["REMOTE_ADDR"]
        ));

    $_SESSION['success'] = 'Guess Recorded';
    header('Location: '.addSession('index.php') ) ;
    return;
  }
  else if(isset($_POST['sendE']) ){
    $PDOX->queryDie("INSERT INTO {$p}clicker
      (link_id, user_id, guess, attend, ipaddr, count)
      VALUES ( :LI, :UI, :GU, NOW(), :IP, 1)
      ON DUPLICATE KEY UPDATE guess = :GU, ipaddr = :IP, count = count + 1",
      array(
        ':LI' => $LINK->id,
        ':UI' => $USER->id,
        ':GU' => 4,
        ':IP' => $_SERVER["REMOTE_ADDR"]
        ));

    $_SESSION['success'] = 'Guess Recorded';
    header('Location: '.addSession('index.php') ) ;
    return;
  }
}
else if($USER->instructor){
  $PDOX->queryDie("UPDATE {$p}clicker SET guess = 5 WHERE attend = :TODAY",
    array(':TODAY' => $today
      ));
  $PDOX->queryDie("UPDATE {$p}clicker SET count = count - 1 WHERE count > 0 AND attend = :TODAY",
    array(':TODAY' => $today
      ));
  header('Location: '.addSession('index.php') ) ;
  return;
}

$OUTPUT->header(); // Start the document and begin the <head>
$OUTPUT->bodyStart(); // Finish the </head> and start the <body>
$OUTPUT->flashMessages(); // Print out the $_SESSION['success'] and error messages
// A partial form styled using Twitter Bootstrap; button setup

echo('<form method="post">');
echo("Enter your answer:\n");
echo('<input type="submit" class="btn btn-primary" name="sendA" value="'._('A').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendB" value="'._('B').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendC" value="'._('C').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendD" value="'._('D').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendE" value="'._('E').'"> ');

$table = array();
    // convert data into JSON format
$jsonTable = json_encode($table);
if ( $USER->instructor) {
  ?>

  <input type="submit" name="reset" class="btn btn-danger"  value="Reset" > 
</form>

<script type="text/javascript" src = "script.js?x=<?= time() ?>"></script>

<div style = "position:absolute; top:0px; right:0px">
  <button type="submit" class="btn btn-success" name="toggle" id = "showAnswer"  onclick="toggle('chart_div');"> Chart</button>  
  <a href="attendance.php" class="btn btn-info">Check Attendance</a>
</div>

<!---Google chart-->
<script type="text/javascript" src = "https://www.google.com/jsapi"></script>
<script type="text/javascript">

  // Load the Visualization API and the piechart package.
  google.load('visualization', '1.0', {'packages':['corechart']});
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawChart);
  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Element', 'Quantity', { role: 'style' }, {role:'annotation'}],
      ['A', <?=$numA?>, 'red', 'A'],            
      ['B', <?=$numB?>, 'blue', 'B'],           
      ['C', <?=$numC?>, 'grey', 'C'],
      ['D', <?=$numD?>, 'green', 'D'], 
      ['E', <?=$numE?>, 'purple', 'E'], 
      ]);
        // Set chart options
        
        var options = {
          legend:'none',
          title: 'Answer Distribution',
          width: 800,
          height: 600,
          bar:{groupwidth:'95%'},
          vAxis:{title:'Quantity', viewWindow:{min:0}},
          hAxis:{title:'Answers',}
        };
          // Instantiate and draw our chart, passing in some options.
          // Do not forget to check your div ID
          var colchart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
          
          colchart.draw(data, options);
          
        }    
        </script>

        <div id="chart_div" style="display:none"></div>
        <!--End of Google chart-->
        <?
      }
// Finish the body (including loading JavaScript for JQUery and Bootstrap)
// And put out the common footer material
      $OUTPUT->footer();