<?php
/**
 * The template for displaying 404 pages (Not Found).
 * @package WordPress
 */

get_header(); ?>

<?php 
   cubby_get_breadcrumb();
?>
<div class="main_content">

<div class="main_content page_404">
  <div class="border-top"></div>
  <div class="title-404 width600"> 
    <h1>
      <?php _e('Whoops!', 'cubby'); ?>
    </h1>
    <h2>
      <?php _e('There is nothing here.', 'cubby'); ?>
    </h2>
    <p>
      <?php _e('Perhaps you were given the wrong URL?', 'cubby'); ?>
    </p>
  </div>
  <div class="border-top"></div>
  <div class="title-404 width600">
   
  </div>
  <div class="clear"></div>
</div>
<!--main_content-->
<div class="main_content">
  <div class="border-top"></div>
</div>
</div>
<!--main_content-->
<?php get_footer(); ?>
