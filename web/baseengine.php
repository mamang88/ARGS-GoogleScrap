<?php
	function getKeywordSuggestionsFromGoogle(string $keyword) {
	    $keywords = array();
	    $data = file_get_contents('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode($keyword));
	    
	    if (($data = json_decode($data, true)) !== null) {
	        $keywords = $data[1];
	    }
	    return $keywords;
	}
?>