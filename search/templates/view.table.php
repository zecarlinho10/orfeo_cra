<?php

// View results as table with sortable columns

// todo: exclude path*

// todo: if sort spalte, dann diese in tabellenansicht rein, auch wenn kein feld (falls sort asc am anfang ohne werte und sortierung nicht umdrehbar)



// Find all columns (=fields)
$cols=array();

foreach ($results->response->docs as $doc) {

	foreach ($doc as $field => $value) {
		
		if (!is_array($fields) || !in_array($field, $fields)
				and $field!='id'
				and $field!='_version_'
				and $field!='content'
				and $field!='author'
		) {
			
			$fields[] = $field;
		};

	}
}
asort($fields);


// JS for responsive table
print '
<link rel="stylesheet" href="tablesaw/tablesaw.css">
    <script>
    /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
    window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!(!t.document.createElementNS||!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect||!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1")||window.opera&&-1===navigator.userAgent.indexOf("Chrome")),o=function(o){var r=t.document.createElement("link"),a=t.document.getElementsByTagName("script")[0];r.rel="stylesheet",r.href=e[o&&n?0:o?1:2],a.parentNode.insertBefore(r,a)},r=new t.Image;r.onerror=function(){o(!1)},r.onload=function(){o(1===r.width&&1===r.height)},r.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
	    grunticon(["tablesaw/icons/icons.data.svg.css", "tablesaw/icons/icons.data.png.css", "tablesaw/icons/icons.fallback.css"]);
	</script>
    <noscript><link href="tablesaw/icons/icons.fallback.css" rel="stylesheet"></noscript>
<script src="tablesaw/tablesaw.js"></script>
';

// Table header and col names

print '<table class="tablesaw-swipe" data-mode="swipe" data-minimap><thead><tr>'; // Temporary Style displays table in foreground
foreach ($fields as $field) {
	// todo: sort link asc/desc

	print '<th>';

	// if col is sorting col, highlight it
	if ($sort==$field.' asc' or $sort==$field.' desc') {
		print '<b>';
	}

	// build sorting link and print linked column name
	if ($sort==$field.' asc') {
		print '<a title="'.htmlentities($field).'" onclick="waiting_on();" href="'.buildurl($params,"sort",$field.' desc','s',1).'">'.htmlentities( t($field) ).'</a>';
	}
	else {print '<a title="'.htmlentities($field).'" onclick="waiting_on();" href="'.buildurl($params,"sort",$field.' asc','s',1).'">'.htmlentities( t($field) ).'</a>';
	}

	if ($sort==$field.' asc' or $sort==$field.' desc') {
		print '</b>';
	}

	print '</th>';

}

print '</tr></thead><tbody>';

// print documents as rows
//
foreach ($results->response->docs as $doc) {
	print '<tr>';
	foreach ($fields as $field) {
		print '<td>';
		if (isset($doc->$field)) {
						
			if ( is_array ( $doc->$field ) ) {
				foreach ($doc->$field as $value) {
					print '<li>' . htmlspecialchars($value) . '</li>';
				}
					
			} 
			else {
				print htmlspecialchars($doc->$field);
			}
		
		}
		print '</td>';
	}
	print '</tr>';
}

print '</tbody></table>';
?>
