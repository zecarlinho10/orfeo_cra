<?php ?>

<div id="select_view" class="row">

<ul class="button-group">
<?php

// Show view selector (list, image-gallery, table and so on)

?>
<?php
		
		//echo t('View').': ';
		
		if ($view == 'list') {
			print '<li><a class="button secondary active" href="#">' . t('List') . '</a></li>';
		} else {
			if ($view == "preview") {
				// if switching from preview mode to list, dont reset start to first result
				// but on a regular page
				$pagestart = floor($start / $limit) * limit;
				$link = buildurl($params, "view", '', 's', $pagestart);
			} else { // switching from other view like images or table, so reset start to first result
				$link = buildurl($params, "view", '', 's', 1);
			}
			print '<li><a class="button secondary" onclick="waiting_on();" href="' . $link . '">' . t('List') . '</a></li>';

		}

		if ($view == 'preview') {
			//print '<li><a class="button secondary active" href="#">'.t('Preview').'</a></li>';
		} else {
			//print '<li><a class="button secondary" onclick="waiting_on();" href="' . buildurl($params, "view", 'preview') . '">' . t('Preview') . '</a></li>';
		}

		if ($view == 'images') {
			print '<li><a class="button secondary active" href="#">'.t('Images').'</a></li>';
		} else {
			print '<li><a class="button secondary" onclick="waiting_on();" href="' . buildurl($params, "view", 'images', 's', 1) . '">' . t('Images') . '</a></li>';
		}
		
		if ($view == 'videos') {
			print '<li><a class="button secondary active" href="#">'.t('Videos').'</a></li>';
		} else {
			print '<li><a class="button secondary" onclick="waiting_on();" href="' . buildurl($params, "view", 'videos', 's', 1) . '">' . t('Videos') . '</a></li>';
		}
		
		if ($view == 'table') {
			print '<li><a class="button secondary active" href="#">'.t('Table').'</a></li>';
		} else {
			print '<li><a class="button secondary" onclick="waiting_on();" href="' . buildurl($params, "view", 'table', 's', 1) . '">' . t('Table') . '</a></li>';
		}

		
		
		?>
		
<li>
		
	<a href="#" data-dropdown="drop1" class="button dropdown secondary"><?php echo t('Analyze'); ?></a><br>
	<ul id="drop1" data-dropdown-content class="f-dropdown">
		
		<?php 
		
		if ($view=='words') {
			print '<li><a href="#">'.t('Word cloud').'</a></li>';
		} else {
			print '<li><a onclick="waiting_on();" href="'.buildurl($params,"view",'words','s', false).'">'.t('Word cloud').'</a></li>';
		}
		
		if ($view=='trend') {
			print '<li class="active"><a href="#">'.t('Trend').'</a></dd>';
		} else {
			print '<li><a onclick="waiting_on();" href="'.buildurl($params,"view",'trend','s', false).'">'.t('view trend').'</a></li>';
		}
		
//		if ($view == 'timeline') {
//			print '<dd class="active"><a href="#">'.t('Timeline').'</a></dd>';
//		} else {
//			print '<dd><a onclick="waiting_on();" href="' . buildurl($params, "view", 'timeline', 's', 1) . '">'.t('Timeline').'</a></dd>';
//		}
		
?>
</ul>

</li>
</ul>

</div>
