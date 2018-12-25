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
function getKeywordSuggestionsFromGoogle($keyword) {
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

                 //if file already exists
             if (file_exists("tmp/" . $_FILES["file"]["name"])) {
            echo $_FILES["file"]["name"] . " already exists. ";
             }
             else {
                    //Store file in directory "tmp" with the name of "tmped_file.txt"
            $storagename = "tmped_file.txt";
            move_uploaded_file($_FILES["file"]["tmp_name"], "tmp/" . $storagename);
            echo "Stored in: " . "tmp/" . $_FILES["file"]["name"] . "<br />";
            }
        }
     } else {
             echo "No file selected <br />";
     }
}
if ( $file = fopen( "tmp/" . $storagename , 'r+' ) ) {

    echo "File opened.<br />";

    $firstline = fgets ($file, 4096 );
        //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
    $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));

        //save the different fields of the firstline in an array called fields
    $fields = array();
    $fields = explode( ";", $firstline, ($num+1) );

    $line = array();
    $i = 0;

        //CSV: one line is one record and the cells/fields are seperated by ";"
        //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
    while ( $line[$i] = fgets ($file, 4096) ) {

        $dsatz[$i] = array();
        $dsatz[$i] = explode( ";", $line[$i], ($num+1) );

        $i++;
    }

        echo "<table>";
        echo "<tr>";
    for ( $k = 0; $k != ($num+1); $k++ ) {
        echo "<td>" . fields[$k] . "</td>";
    }
        echo "</tr>";

    foreach ($dsatz as $key => $number) {
                //new table row for every record
        echo "<tr>";
        foreach ($number as $k => $content) {
                        //new table cell for every field of the record
            echo "<td>" . $content . "</td>";
        }
    }

    echo "</table>";
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