<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
//define variables and set to empty values


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
            process_uploadfile($lines);
        }
     } else {
             echo "No file selected <br />";
     }
}
function process_uploadfile($csv){
  $firstline=array_shift($csv);
  $newarray=array();
  foreach($csv as $lines){
      $kwd=$lines[0];
      echo "base kw:".$kwd."<br>";
      $kw=getKeywordSuggestionsFromGoogle($kwd);
      
      //array_shift($kws);
      array_push($newarray,$lines);
      //echo $newarray;
      foreach ($kw as $kws) {
        $val=array();
        $val[]=$kws;
        array_push($newarray, $val);
      }
  }
  for ($row = 0; $row < sizeof($newarray); $row++) {
    for ($col = 0; $col < sizeof($newarray[$row]); $col++) {
      echo $newarray[$row][$col]."<br>";
    }
  }
  
 

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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  
  Base Keyword: <br>
  <textarea name="comment" rows="20" cols="100"><?php
  if($comment!=null) 
foreach ($comment as $line) {
    echo $line."\n";
}; 
  ?></textarea>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<h2>Result:</h2>
<textarea name="comment" rows="20" cols="100"><?php
  if($comment!=null) 
    foreach ($comment as $line) {
      $kw=getKeywordSuggestionsFromGoogle($line);
      echo $line."\n";
      foreach ($kw as $keywords) {
        echo $keywords."\n";
      }
    }; 
  ?></textarea>
<table width="600">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

<tr>
<td width="20%">Select file</td>
<td width="80%"><input type="file" name="file" id="file" /></td>
</tr>

<tr>
<td>Submit</td>
<td><input type="submit" name="submit" /></td>
</tr>

</form>
</table>
<h5> Creator : Vlaus </h5>
<h5>Last Update: 11/7/18 4:30PM GMT+7 </h5>
</body>
</html>