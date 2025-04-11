<?php
  
// Show sort selectors
  
if ($view != "words" && $view != 'trend') {
	?>
	<div id="sort" class="row">
		<dl class="sub-nav">

		<?php
		print '<dt>' . t('sort') .'</dt>';
		if ($sort == '') {
			print '<dd class="active"><a href="#">' . t('Relevance') . '</a></dd>';
		} else {
			print '<dd><a onclick="waiting_on();" href="' . buildurl($params, "sort", '', 's', 1) . '">' . t('Relevance') . '</a></dd>';
		}
		if ($sort == 'newest') {
			print '<dd class="active"><a href="#">' . t('Newest') . '</a></dd>';
		} else {
			print '<dd><a onclick="waiting_on();" href="' . buildurl($params, "sort", 'newest', 's', 1) . '">' . t('Newest') . '</a></dd>';
		}
		if ($sort == 'oldest') {
			print '<dd class="active"><a href="#">' . t('Oldest') . '</a></dd>';
		} else {
			print '<dd><a onclick="waiting_on();" href="' . buildurl($params, "sort", 'oldest', 's', 1) . '">' . t('Oldest') . '</a></dd>';
		}
		?>
		</dl>
	</div>		
		<?php
 }

?>
