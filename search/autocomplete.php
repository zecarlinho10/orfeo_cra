<?php
/*

	autocomplete.php - List of words for autocompletition

   Version 12.01.14 by Markus Mandalka
*/
include "config/config.php";
 header('Content-Type: application/json; charset=utf-8');

  $limit = 15;

  $query = (string)$_GET["query"];

  $uri = 'http://'.$cfg['solr']['host'].":".$cfg['solr']['port'].$cfg['solr']['path'].'/terms?terms.fl=text&terms.limit='.$limit.'&terms.prefix='.urlencode(strtolower($query))."&indent=true&wt=xml";
  $result = file_get_contents($uri);

  $termsxml = simplexml_load_string($result);
  echo "{ query:'" . $query . "', suggestions:[";

  $first = true;
  foreach($termsxml->lst[1]->lst->int as $term) {

	if ($first) { $first=false; } else { echo ","; }
	 echo "'" . $term['name'] . "'";

  }
  
  echo ' ] }';
?>
