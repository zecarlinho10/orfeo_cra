<div id="facets">
			<?php

			// todo: hover durchgestrichen bei (x)-Werten

			// if active facets, show them and possibility to unlink
			if ($selected_facets or $types or $typegroups) {
				?>
			<div id="filter" class="facet">
				<h2 title="<?php echo t('Filter criterias');?>">
					<?php echo t("Selected filters"); ?>
				</h2>
				<ul id="selected" class="no-bullet facet_list">
					<?php

					foreach ($selected_facets as $selected_facet => $facetvalue_array) {
						foreach ($facetvalue_array as $facet_value) {
							print '<li><a onclick="waiting_on();" title="'.t('Remove filter').'" href="'.buildurl_delvalue($params, $selected_facet, $facet_value, 's',1).'">(X)</a> '.$cfg[facets][$selected_facet]['label'].': '.htmlspecialchars($facet_value).'</li>';
						}
					}


					foreach ($types as $type) {
						print '<li><a onclick="waiting_on();" title="'.t('Remove filter').'" href="'.buildurl($params, 'type', false,'s',1).'">(X)</a> Typ: '.htmlspecialchars($type).'</li>';
					}

					foreach ($typegroups as $typegroup) {
						print '<li><a onclick="waiting_on();" title="'.t('Remove filter').'" href="'.buildurl($params, 'typegroup', false,'s',1).'">(X)</a> Form: '.htmlspecialchars($mimetypes[$typegroup]['name']).'</li>';
					}
					?>
				</ul>
			</div>
			<?php
			}
			?>

			<?php
			if ($path) {
				?>
			<div id="path">
				<h2>
					<?php echo t('Path'); ?>
				</h2>

				<?php
				$trimmedpath = trim($path, '/');

				$paths = split('/', $trimmedpath);

				print '<ul><li><a onclick="waiting_on();" href="' . buildurl($params, "path", '', 's', 1) . '">'.t("All paths").'</a>';

			    $fullpath = '';
    			for ($i = 0; $i < count($paths) - 1; $i++) {
			    	$fullpath .= '/' . $paths[$i];
      				echo '<ul><li><a onclick="waiting_on();" href="' . buildurl($params, "path", $fullpath, 's', 1) . '">' . $paths[$i] . '</a>' . "\n";
    			}
				
				echo '<ul><li><b>' . htmlspecialchars($paths[count($paths) - 1]) . '</b></li></ul>';

				for ($i = 0; $i < count($paths) - 1; $i++) {
					echo '</li></ul>' . "\n";
				}

				?>
			</div>
			<?php
			} // if path
			?>

			<?php

				if (isset($results->facet_counts->facet_fields->$pathfacet)) {
					if ($cfg['debug']) {
						print 'path: ' . $path;
						print 'pathfacet: ' . $pathfacet . '<br>';
						print_r($results->facet_counts->facet_fields->$pathfacet);
					}
					?>

			<div id="sub" class="facet">
				<h2>
					<?php if ($path) print t('Subpaths'); else print t('Paths'); ?>
				</h2><ul class="no-bullet facet_list">
				<?php
				foreach ($results->facet_counts->facet_fields->$pathfacet as $subpath => $count) {

					$fullpath = $path . '/' . $subpath;

					print '<li><a onclick="waiting_on();" href="' . buildurl($params, "path", $fullpath, 's', 1) . '">' . htmlspecialchars($subpath) . '</a> (' . $count . ')</li>';
				}
				?>
				</ul>
			</div>

			<?php
				} // if subpaths

				
				
				
				?>
				
				<div id="file_modified_dt" class="facet">
				<h2>
					<?php echo t('Fecha Radicado');  ?>
				</h2>
				
				<?php // navigation up
				
				if ($upzoom_link) {
					print '<a onclick="waiting_on();" href="' . $upzoom_link . '">' . $upzoom_label . '</a> &gt; ' . $date_label;
				}
				 
				?>
				<ul class="no-bullet facet_list">
				
				<?php

				// newest first
				if ($zoom='years') {
					$datevaluessorted = array_reverse($datevalues);
				} else { $datavaluessorted = $datavalues; }
				
				foreach ($datevaluessorted as $value) {

					if ($value['count'] > 0) {
						print '<li><a onclick="waiting_on();" href="'.$value['link'].'">'.$value['label'].'</a> ('.$value['count'].')</li>';
					}
				}
				
					
				?>
				    </ul>
				    
				</div>
				
				<?php 
				

				// Print all configurated facets
				foreach ($cfg['facets'] as $facet => $facet_config) {

					if ($cfg['debug']) {
						print ($facet) . '<br />';
						print_r($facet_config);
					}

					if ($facet != 'text') print_facet($results, $facet, t($facet_config['label']) );
				}


				
				
				// Content type group facet
				if ($results->facet_counts->facet_fields->content_type) {
					?>
			<div id="content_types" class="facet">
				<h2><?php echo t('Content type group'); ?></h2>
				<ul class="no-bullet facet_list">
				<?php

				$group_array = array();

				foreach ($results->facet_counts->facet_fields->content_type as $type => $count) {

					$type_without_charset = explode(';', $type);
					$type_without_charset = $type_without_charset[0];
					$type_array = explode('/', $type_without_charset);
					$type_base = $type_array[0];
					$type_special = $type_array[1];

					if (isset($mimetypes[$type_without_charset])) {
						$typegroup = $mimetypes[$type_without_charset]['group'];
					} elseif (isset($mimetypes[$type_base])) {
						$typegroup = $mimetypes[$type_base]['group'];
					} else {
						$typegroup = $false;

					}


					if ($typegroup) {

						if (empty($group_array[$typegroup])) $group_array[$typegroup] = $count;
						else $group_array[$typegroup] = $group_array[$typegroup] + $count;

					}

				}


				foreach ($group_array as $typegroup => $count) {
					if (isset($mimetypes[$typegroup])) {
						$typegroup_name = $mimetypes[$typegroup]['name'];
					} else {
						$typegroup_name = $typegroup;
					}
					print '<li><a onclick="waiting_on();" href="' . buildurl($params, "typegroup", array($typegroup), 's', 1) . '">' . htmlspecialchars($typegroup_name) . '</a> (' . $count . ')</li>';

				}
				?>
				</ul>
			</div>
			<?php
				} // if typegroups
				?>




			<?php
			if ($results->facet_counts->facet_fields->content_type) {
				?>
			<div id="content_type" class="facet">
				<h2><?php echo t('Content type'); ?></h2>
	
				<ul class="no-bullet facet_list">
	
				<?php

				$group_array = array();

				foreach ($results->facet_counts->facet_fields->content_type as $type => $count) {

					$type_without_charset = explode(';', $type);
					$type_without_charset = $type_without_charset[0];
					$type_array = explode('/', $type_without_charset);
					$type_base = $type_array[0];
					$type_special = $type_array[1];


					$type_group = $type_without_charset;


					if (empty($group_array[$type_group])) $group_array[$type_group] = $count;
					else $group_array[$type_group] = $group_array[$type_group] + $count;

				}


				foreach ($group_array as $typegroup => $count) {

					if (isset($mimetypes[$typegroup])) {
						$type_name = $mimetypes[$typegroup]['name'];
					} else {
						$type_name = $typegroup;
					}

					print '<li><a onclick="waiting_on();" href="' . buildurl($params, "type", array($typegroup), 's', 1) . '">' . htmlspecialchars($type_name) . '</a> (' . $count . ')</li>';

				}
				?>
				</ul>
			</div>
			<?php
			} // if types

			?>

</div>
