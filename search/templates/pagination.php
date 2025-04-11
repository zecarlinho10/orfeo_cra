<?php

//
// Pagination
//

?>

<div class="row"><hr /></div>

<div class="row">
<div class="pages" class="row">

<span class="prev small-2 columns"><a class="<?php if (! $is_prev_page): print 'disabled'; endif;?>" onclick="<?php (! $is_prev_page) ? print 'return false;' : print 'waiting_on();';?>" href="<?php print $link_prev ?>">&laquo;&nbsp;<?php print t('prev') ?></a></span>

<?php
 


	print '<span class="page small-8 columns">';

	if (empty($query) and $start == 1 and !$path) {
		?>
	    <?php echo t('newest_documents')?>
	    
	    <?= $limit ?>
	    
        <?php echo t('newest_documents_of')?>
	
	    <?= $total ?>

	    <?php echo t('newest_documents_of_total')?>
	  <?php
	
	  } else {
	
	    ?>
	    
	    <?php if ($view=="preview") { ?>

	    <?php echo t('result'); ?>
	    <?= $page ?>
	    <?php echo t('page of'); ?>
	    <?= $total ?>
	    
	    
	    <?php } else { ?>
	    <?php echo t('page'); ?>
	    <?= $page ?>
	    <?php echo t('page of'); ?>
	    <?= $pages ?>
	    (<?php echo t('results'); ?>
	    
	    <?= $start ?>
	    <?php echo t('result to'); ?>
	    <?= $end ?>
	    <?php echo t('result of'); ?>
	    <?= $total ?>
	    )
	    <?php
	    }
	    
	  }
	  print '</span>';
		
	
?>
	  <span class="next small-2 columns"><a class="<?php if (! $is_next_page): print 'disabled'; endif;?>" onclick="<?php (! $is_next_page) ? print 'return false;' : print 'waiting_on();';?>" href="<?php print $link_next ?>"><?php print t('next') ?>&nbsp;&raquo;</a></span>
	  
	  
</div>
</div>

<div class="row"><hr /></div>