<nav id="top-bar" class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="./">Open Semantic Search</a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

    <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
<?php if ( $cfg['solr']['admin']['uri'] ) { ?>
    <li><a target="_blank" rel="noopener noreferrer" href="<?=$cfg['solr']['admin']['uri']?>"><?php echo t("Admin"); ?></a></li>
<?php } ?>
      <li><a target="_blank" rel="noopener noreferrer" href="<?=$uri_help?>"><?php echo t("Help"); ?></a></li>
            </ul>

    <!-- Left Nav Section -->
    <ul class="left">
      <li><a href="./"><?php echo t("Newest documents"); ?></a></li>
    </ul>
  </section>
  
</nav>