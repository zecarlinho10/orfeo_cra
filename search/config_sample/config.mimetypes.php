<?php

// todo nach query: foreach mimetypes add group to groupfiltermime
// unter mimetypegroups gruppieren (> öffenbar? damit doc und pdf unterschiedlich)

$mimetypes=array(

		// Groups
		'images' => array ('name'=>'Bilder', 'icon'=>'jpeg.png', 'group'=>''),
		'videos' => array ('name'=>'Videos', 'icon'=>'jpeg.png', 'group'=>''),
		'presentations'  => array ('name'=>'Präsentationen', 'icon'=>'archive.png', 'group'=>''),
		'table'  => array ('name'=>'Tabellen', 'icon'=>'archive.png', 'group'=>''),
		'docs'  => array ('name'=>'Textbasiert', 'icon'=>'doc.png', 'group'=>''),
		'archives'  => array ('name'=>'Archive', 'icon'=>'doc.png', 'group'=>''),
		'net' => array ('name'=>'Internetseite', 'icon'=>'jpeg.png', 'group'=>''),

		// Mimetypes
		'image' => array ('name'=>'Image', 'icon'=>'jpeg.png', 'group'=>'images'),
		'video' => array ('name'=>'Video', 'icon'=>'jpeg.png', 'group'=>'videos'),
		'text' => array ('name'=>'Text', 'icon'=>'jpeg.png', 'group'=>'docs'),
		'html' => array ('name'=>'HTML', 'icon'=>'html.png', 'group'=>'docs'),
		'text/html' => array ('name'=>'HTML', 'icon'=>'html.png', 'group'=>'docs'),
		'text/plain' => array ('name'=>'TXT', 'icon'=>'txt.png', 'group'=>'docs'),
		'text/xml' => array ('name'=>'XML', 'icon'=>'xml.png', 'group'=>'docs'),
		'image/gif' => array ('name'=>'GIF', 'icon'=>'gif.png', 'group'=>'images'),
		'image/png' => array ('name'=>'PNG', 'icon'=>'png.png', 'group'=>'images'),
		'image/jpeg' => array ('name'=>'JPEG', 'icon'=>'jpeg.png', 'group'=>'images'),
		'application/xml' => array ('name'=>'XML', 'icon'=>'xml.png', 'group'=>'docs'),
		'application/pdf' => array ('name'=>'PDF', 'icon'=>'pdf.png', 'group'=>'docs'),
		'application/vnd.ms-powerpoint' => array ('name'=>'Powerpoint', 'icon'=>'archive.png', 'group'=>'presentations'),
		'application/vnd.ms-excel' => array ('name'=>'Excel', 'icon'=>'archive.png', 'group'=>'table'),
		'application/vnd.oasis.opendocument.presentation' => array ('name'=>'OpenOffice Presenter', 'icon'=>'archive.png', 'group'=>'presentations'),
		'application/zip' => array ('name'=>'ZIP', 'icon'=>'archive.png', 'group'=>'archives'),
		'application/msword' => array ('name'=>'Word', 'icon'=>'doc.png', 'group'=>'docs'),
		'application/rtf' => array ('name'=>'RTF', 'icon'=>'rtf.png', 'group'=>'docs'),
		'application/vnd.oasis.opendocument.text' => array ('name'=>'OpenOffice Text', 'icon'=>'odf.png', 'group'=>'docs')


);
?>