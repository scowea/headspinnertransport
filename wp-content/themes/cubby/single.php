<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 *
 * @since Cubby 1.0.0
 */

get_header(); ?>

<?php 
   cubby_get_breadcrumb();
?>
<div id="post-<?php the_ID(); ?>" <?php post_class("clear"); ?>>
<div class="main_content">
<div class="content_left left">
<?php if (have_posts()) :?>
<?php	while ( have_posts() ) : the_post();?>
	<div class="post_author_timeline">

<?php get_template_part( 'content', get_post_format() ); ?>

</div>
	
<?php endwhile;?>
<?php endif;?>
</div>
<div class="content_right right">
<?php cubby_get_sidebar(2,true); ?>
</div>
<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>