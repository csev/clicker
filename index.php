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
if(isset($_POST['guess']) && !isset($_POST['reset']) && !isset($_POST['check'])){

    
    $PDOX->queryDie("INSERT INTO {$p}solution_wiscrowd
            (link_id, user_id, guess, attend)
            VALUES ( :LI, :UI, :GU, NOW() )
            ON DUPLICATE KEY UPDATE guess = :GU, attend = NOW()",
                    array(
                        ':LI' => $LINK->id,
                        ':UI' => $USER->id,
                        ':GU' => $_POST['guess']
                        ));

  
   $_SESSION['success'] = 'Guess Recorded';
   header('Location: '.addSession('index.php') ) ;
   return;
}else if(isset($_POST['reset']) && $USER->instructor){

    $PDOX->queryDie("DELETE FROM {$p}solution_wiscrowd");

    header('Location: '.addSession('index.php') ) ;
    return;
    
}

if ( isset($_POST["check"])) {

    //echo('adfadfasdfadf');
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
echo('<input type="text" name="guess" value=""> ');
echo('<input type="submit" class="btn btn-primary" name="send" value="'._('Guess').'"> ');
   
$result = $PDOX->allRowsDie('SELECT * FROM solution_wiscrowd');


      $rows = array();
      $table = array();
      $table['cols'] = array(

        // Labels for your chart, these represent the column titles.
        /* 
            note that one column is in "string" format and another one is in "number" format 
            as pie chart only required "numbers" for calculating percentage 
            and string will be used for Slice title
        */

        array('label' => 'user_id', 'type' => 'number'),
        array('label' => 'guesses', 'type' => 'number')

    );
        /* Extract the information from $result */
        foreach($result as $r) {

          $temp = array();

          // the following line will be used to slice the Pie chart

          $temp[] = array('v' => (int) $r['user_id']); 

          // Values of each slice

          $temp[] = array('v' => (int) $r['guess']); 
          $rows[] = array('c' => $temp);
        }

    $table['rows'] = $rows;

    $fifty = 50;
    // convert data into JSON format
    $jsonTable = json_encode($table);
    //echo $jsonTable;
   

// Dump out the session information
// This is here for initial debugging only - it should not be part of the final project.
// Note that addSession() is not needed here because PHP autmatically handles
// PHPSESSID on anchor tags and in forms.
if ( $USER->instructor) {

?>
<input type="submit" class="btn btn-info" name="toggle" onclick="$('#chart_div').toggle(); return false;" value="Show answers">
<input type="submit" name="check" class="btn btn-success" value="Check answers">
<input type="submit" name="reset" class="btn btn-danger"  value="Reset answers"><br/>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        /*var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Quantity');
        data.addRows([
          ['A', <?=$fifty?>],
          ['B', 1],
          ['C', 1],
          ['D', 1],
          ['E', 2]
          ]);*/
    var data = google.visualization.arrayToDataTable([
       ['Element', 'Quantity', { role: 'style' }, {role:'annotation'}],
         ['A', <?=$fifty?>, 'red', 'A'],            // RGB value
         ['B', 10.49, 'blue', 'B'],            // English color name
         ['C', 19.30, 'grey', 'C'],
       ['D', 21.45, 'green', 'D'], // CSS-style declaration
       ['E', 21.45, 'purple', 'E'], // CSS-style declaration
      ]);

        // Set chart options
        var options = {'title':'Answer Distribution',
                       'width':800,
                       'height':600
                   };

        // Instantiate and draw our chart, passing in some options.
        //var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        //chart.draw(data, options);
        //var data = new google.visualization.DataTable(<?=$jsonTable?>);
        
          var options = {
            legend: 'none',
            title: 'Answer Distribution',
            width: 800,
            height: 600,
            bar:{groupwidth:'95%'},
            vAxis:{title:'Quantity'},
            hAxis:{title:'Answers'}
          };
          // Instantiate and draw our chart, passing in some options.
          // Do not forget to check your div ID
          var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
          chart.draw(data, options);
    }    
    </script>


    <div id="chart_div"></div>


<?php
    echo("</form>");
    //echo('<p><a href="debug.php" target="_blank">Debug Print Session Data</a></p>');
    //echo("\n");


    $_SESSION['guessNum'] = 0;
        $_SESSION['total'] = 0;
        $_SESSION['average'] = 0;
    $rows = $PDOX->allRowsDie("SELECT link_id, user_id, guess, attend FROM {$p}solution_wiscrowd
            WHERE link_id = :LI ORDER BY attend DESC",
                                  array(':LI' => $LINK->id)
        );
        
        
    
}


// Finish the body (including loading JavaScript for JQUery and Bootstrap)
// And put out the common footer material

$OUTPUT->footer();
