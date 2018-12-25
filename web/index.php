<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php

$comment =  "";


  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }
function getKeywordSuggestionsFromGoogle(string $keyword) {
    $keywords = array();
    $data = file_get_contents('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode($keyword));
    
    if (($data = json_decode($data, true)) !== null) {
        $keywords = $data[1];
    }
    return $keywords;
}
if ( isset($_POST["submit"]) ) {

   if ( isset($_FILES["file"])) {

            //if there was an error tmping the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
                 //Print file details
             echo "tmp: " . $_FILES["file"]["name"] . "<br />";
             echo "Type: " . $_FILES["file"]["type"] . "<br />";
             echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
             echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

            $fh = fopen($_FILES['file']['tmp_name'], 'r+');

            $lines = array();
            while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
              $lines[] = $row;
            }
            if(!empty($lines))process_uploadfile($lines);
        }
     } else {
             echo "No file selected <br />";
     }
}
function array_to_csv_download($array, $filename = "export.csv") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'turunan.csv";');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen('php://output', 'w') or die();
    if (ob_get_contents()) ob_end_clean();
    foreach ($array as $lines) {
      $result = [];
      array_walk_recursive($lines, function($item) use (&$result) {
          $result[] = $item;
      });
      fputcsv($f, $result);
    }
    fclose($f);
    die();
}

function process_uploadfile($csv){
  $firstline=array_shift($csv);
  $newarray=array();
  foreach($csv as $lines){
      $kwd=$lines[0];
      $kw=getKeywordSuggestionsFromGoogle($kwd);
      array_push($newarray,$lines);
      foreach ($kw as $kws) {
        $val=array();
        array_push($val,$kws);
        array_push($newarray, $val);
      }
  }
  $tarray=array();
  array_push($tarray,$firstline);
  $tarray+= $newarray;
  //print_r($tarray);

  if(!empty($newarray))array_to_csv_download($tarray,$_FILES["file"]["name"]);
  
} 
function test_input($data) {
  $text = trim($data);
  //$text = stripslashes($text);
  $textAr = explode("\n", $text);
  $textAr = array_filter($textAr, 'trim'); // remove any extra \r characters left behind
  return $textAr;
}
?>

<h2>Google Scrapper BETA V.0.0.1</h2>
<div id="timestamp"></div>

<br>
<a href="https://args-googlescrapper.herokuapp.com/uploadcsv.php">upload csv file</a>
<a href="https://args-googlescrapper.herokuapp.com/manualinput.php">input keyword manually</a>


<h5> Creator : Vlaus </h5>
<h5>Last Update: 11/7/18 4:30PM GMT+7 </h5>
</body>
</html>