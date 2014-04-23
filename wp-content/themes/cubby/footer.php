<?php
/**
 * The template for displaying the footer.
 * @package WordPress
 */
?>
<div class="footer">
<?php if(is_active_sidebar(8) || is_active_sidebar(9) ||is_active_sidebar(10)){?>
  <div class="footer-content">
    <div class="container">
      <div class="columns3 left">
       <?php cubby_get_sidebar(8,false); ?>
      </div>
      <div class="columns3 left">
      <?php cubby_get_sidebar(9,false); ?>
      </div>
      <div class="columns3 left last">
        <?php cubby_get_sidebar(10,false); ?>
      </div>
    </div>
	<div class="clear"></div>
  </div>
<?php }?>
 <div class="copyright">
    <div class="container">
      <div class="left wordpress"> Copyright <?php echo '&copy; ' . date("Y") ;?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name'); ?></a>, All Rights Reserved - <a href="../wp-login.php"><font color=lightgrey>admin</font></a>
	  <?php if( is_home() || is_front_page() ): ?>
	 </div>
	    <?php endif; ?>
      <div class="right footer-menu">
<?php

wp_nav_menu(array('theme_location'=>'footer_menu','depth'=>1,'container'=>'','container_class'=>'footer-menu','menu_id'=>'menu-footer','menu_class'=>'footer-nav','link_before' => '<span>', 'link_after' => '</span>'));

?> </div>
    </div>
	<div class="clear"></div>
  </div>
</div>
<?php wp_footer(); ?>
</body></html>