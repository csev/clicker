<?php
require_once "../../config.php";
require_once $CFG->dirroot."/pdo.php";
require_once $CFG->dirroot."/lib/lms_lib.php";

use \Tsugi\Core\LTIX;

// Retrieve required launch data from session
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;

// Add all of your POST handling code here.  Use $LINK->id
// in each of the entries to keep a separate set of guesses for
// each link.


//TRY TO USE SESSION TO SAVE GUESSNUM AND AVERAGE
if(isset($_POST['sendA']) && !isset($_POST['reset'])){

    
    $PDOX->queryDie("INSERT INTO {$p}solution_wiscrowd
            (link_id, user_id, guess, attend)
            VALUES ( :LI, :UI, :GU, NOW() )
            ON DUPLICATE KEY UPDATE guess = :GU, attend = NOW()",
                    array(
                        ':LI' => $LINK->id,
                        ':UI' => $USER->id,
                        ':GU' => 0
                        ));

  
   $_SESSION['success'] = 'Guess Recorded';
   header('Location: '.addSession('index.php') ) ;
   return;
}else if(isset($_POST['sendB']) && !isset($_POST['reset'])){

    
    $PDOX->queryDie("INSERT INTO {$p}solution_wiscrowd
            (link_id, user_id, guess, attend)
            VALUES ( :LI, :UI, :GU, NOW() )
            ON DUPLICATE KEY UPDATE guess = :GU, attend = NOW()",
                    array(
                        ':LI' => $LINK->id,
                        ':UI' => $USER->id,
                        ':GU' => 1
                        ));

  
   $_SESSION['success'] = 'Guess Recorded';
   header('Location: '.addSession('index.php') ) ;
   return;
}else if(isset($_POST['sendC']) && !isset($_POST['reset'])){

    
    $PDOX->queryDie("INSERT INTO {$p}solution_wiscrowd
            (link_id, user_id, guess, attend)
            VALUES ( :LI, :UI, :GU, NOW() )
            ON DUPLICATE KEY UPDATE guess = :GU, attend = NOW()",
                    array(
                        ':LI' => $LINK->id,
                        ':UI' => $USER->id,
                        ':GU' => 2
                        ));

  
   $_SESSION['success'] = 'Guess Recorded';
   header('Location: '.addSession('index.php') ) ;
   return;
}
else if(isset($_POST['sendD']) && !isset($_POST['reset'])){

    
    $PDOX->queryDie("INSERT INTO {$p}solution_wiscrowd
            (link_id, user_id, guess, attend)
            VALUES ( :LI, :UI, :GU, NOW() )
            ON DUPLICATE KEY UPDATE guess = :GU, attend = NOW()",
                    array(
                        ':LI' => $LINK->id,
                        ':UI' => $USER->id,
                        ':GU' => 3
                        ));

  
   $_SESSION['success'] = 'Guess Recorded';
   header('Location: '.addSession('index.php') ) ;
   return;
}
else if(isset($_POST['sendE']) && !isset($_POST['reset'])){

    
    $PDOX->queryDie("INSERT INTO {$p}solution_wiscrowd
            (link_id, user_id, guess, attend)
            VALUES ( :LI, :UI, :GU, NOW() )
            ON DUPLICATE KEY UPDATE guess = :GU, attend = NOW()",
                    array(
                        ':LI' => $LINK->id,
                        ':UI' => $USER->id,
                        ':GU' => 4
                        ));

  
   $_SESSION['success'] = 'Guess Recorded';
   header('Location: '.addSession('index.php') ) ;
   return;
}
else if(isset($_POST['reset']) && $USER->instructor){

    $PDOX->queryDie("DELETE FROM {$p}solution_wiscrowd");

    header('Location: '.addSession('index.php') ) ;
    return;
    
}

if ( isset($_POST["check"])) {


  $_SESSION['guessNum'] = 0;
  $_SESSION['total'] = 0;
  $_SESSION['average'] = 0;

        //Calculate Average
        //$guessNum = $PDOX->queryDie("SELECT COUNT(user_id) FROM {$p} solution_wiscrowd");
  $rows = $PDOX->allRowsDie("SELECT link_id, user_id, guess, attend FROM {$p}solution_wiscrowd
    WHERE link_id = :LI ORDER BY attend DESC",
    array(':LI' => $LINK->id)
    );
  echo('<div id = "guesses">');
  echo('<table border="0">'."\n");

  foreach ( $rows as $row ) {



    echo("<tr><td>");
    echo(htmlent_utf8($row['guess']));
    echo("</td></tr>");

    $_SESSION['guessNum'] ++;
    $_SESSION['total'] += $row['guess'];
  }
  if($_SESSION['guessNum'] != 0)
    $_SESSION['average'] = $_SESSION['total'] / $_SESSION['guessNum'];
  echo("Guesses = "), $_SESSION['guessNum'];
  echo(" Average = "),$_SESSION['average'];


  echo("</table>\n");
  echo('</div>');


  header('Location: '.addSession('index.php') ) ;

}
// Add the SQL to retrieve all the guesses for the $LINK->id
// here and leave the list of guesses and average in variables to
// fall through to the view below.

$OUTPUT->header(); // Start the document and begin the <head>
$OUTPUT->bodyStart(); // Finish the </head> and start the <body>
$OUTPUT->flashMessages(); // Print out the $_SESSION['success'] and error messages

// A partial form styled using Twitter Bootstrap; button setup
echo('<form method="post">');
echo("Enter guess:\n");
//echo('<input type="text" name="guess" value=""> ');

echo('<input type="submit" class="btn btn-primary" name="sendA" value="'._('A').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendB" value="'._('B').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendC" value="'._('C').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendD" value="'._('D').'"> ');
echo('<input type="submit" class="btn btn-primary" name="sendE" value="'._('E').'"> ');

$results = $PDOX->allRowsDie('SELECT guess, COUNT(guess) AS total FROM solution_wiscrowd GROUP BY guess ORDER BY guess ASC');
$numA = 0;
$numB = 0;
$numC = 0;
$numD = 0;
$numE = 0;
$taken = 0;
$size = sizeof($results);
while($taken < $size){

  //taken == 0
  if(@results[$taken]['total'] != null && @$results[$taken]['guess'] == 0){

    $numA = 0 + $results[$taken]['total'];
    $taken ++;
  }
  elseif (@results[$taken]['total'] != null && @$results[$taken]['guess'] == 1){
    $numB = 0 + $results[$taken]['total'];
    $taken ++;
  }
  elseif (@results[$taken]['total'] != null && @$results[$taken]['guess'] == 2){
    $numC = 0 + $results[$taken]['total'];
    $taken ++;
  }
  elseif (@results[$taken]['total'] != null && @$results[$taken]['guess'] == 3){
    $numD = 0 + $results[$taken]['total'];
    $taken ++;
  }
  elseif (@results[$taken]['total'] != null && @$results[$taken]['guess'] == 4){
    $numE = 0 + $results[$taken]['total'];
    $taken ++;
  }

}

      
      $table = array();
      
    
    // convert data into JSON format
    $jsonTable = json_encode($table);
    //echo $jsonTable;
   

// Dump out the session information
// This is here for initial debugging only - it should not be part of the final project.
// Note that addSession() is not needed here because PHP autmatically handles
// PHPSESSID on anchor tags and in forms.
if ( $USER->instructor) {

?>

<input type="submit" name="reset" class="btn btn-danger"  value="Reset" onclick="reset()">
</form>

<div style = "position:absolute; top:0px; right:0px">
  
<button style="position:none;" class="btn btn-success" id="startPause" onclick="startPause()" >Start</button>
<button type="submit" class="btn btn-info" name="toggle" id = "showAnswer"  onclick=" $('#chart_div').toggle();"> Show answers</button>
<!--<input type="submit" name="check" class="btn btn-success" value="Check answers">-->
</div>




<p id="timerOutput" style = "position:relative; float:right; font-family:verdana; font-size:450%; clear:both">00:00</p>

<script type="text/javascript" src = "script.js"></script>
<!--<script type="text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
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
         ['A', <?=$numA?>, 'red', 'A'],            // RGB value
         ['B', <?=$numB?>, 'blue', 'B'],            // English color name
         ['C', <?=$numC?>, 'grey', 'C'],
       ['D', <?=$numD?>, 'green', 'D'], // CSS-style declaration
       ['E', <?=$numE?>, 'purple', 'E'], // CSS-style declaration
       ]);

        // Set chart options
        
        var options = {
            legend: 'none',
            title: 'Answer Distribution',
            width: 800,
            height: 600,
            bar:{groupwidth:'95%'},
            vAxis:{title:'Quantity', viewWindow:{min:0}},
            hAxis:{title:'Answers',}
          };
          // Instantiate and draw our chart, passing in some options.
          // Do not forget to check your div ID
          var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
          chart.draw(data, options);
        }    
    </script>

    
    
    <div id="chart_div" style="display:none"></div>

    

    <?
    
}


// Finish the body (including loading JavaScript for JQUery and Bootstrap)
// And put out the common footer material

$OUTPUT->footer();
