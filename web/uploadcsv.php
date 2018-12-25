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
	if ( isset($_POST["submit"]) ) {

	if ( isset($_FILES["file"])) {

	//if there was an error tmping the file
		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}else {
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
		if(!empty($newarray))array_to_csv_download($tarray,$_FILES["file"]["name"]);
	}
?>
<h2>Google Scrapper BETA V.0.0.1</h2>
<div id="timestamp"></div>

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