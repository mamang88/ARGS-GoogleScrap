<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php  
	include "baseengine.php";
	$comment =  "";
	if (empty($_POST["comment"])) {
		$comment = "";
	} else {
		$comment = test_input($_POST["comment"]);
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
<h5> Creator : Vlaus </h5>
<h5>Last Update: 11/7/18 4:30PM GMT+7 </h5>
</body>
</html>