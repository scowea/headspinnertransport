<?php
/**
 * The template for displaying all pages.
 * @package WordPress
 */

get_header(); ?>
<?php 
   cubby_get_breadcrumb();
   $is_custom_home = 0;
   $content_width = "";
   $enable_home_page = cubby_options_array('enable_home_page');
   if($enable_home_page == 1 && (is_home() || is_front_page())){
   $is_custom_home = 1;
   $content_width = "width:100%;";
    cubby_get_slider();
   }
  
 ?>
<div id="post-<?php the_ID(); ?>" <?php post_class("clear"); ?>>
<div class="main_content" style="<?php echo $content_width;?>">
<?php
 if (have_posts()) :	while ( have_posts() ) : the_post();
   $enable_home_page = cubby_options_array('enable_home_page');
   if($is_custom_home == 1){
   get_template_part("content","home");
   }else{
    $enable_right_sidebar = get_post_meta( $post->ID, '_cubby_right_sidebar', true );
	
?>
<div class="content_left left <?php if($enable_right_sidebar == 0){ echo "full-width";}?>">
<div class="page_content_wrapper">
<h1 class="title-h1 post-title p_b20"><?php the_title();?></h1>
<div class="page_content the_content">
 <?php the_content();?>
 <?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'cubby' ),
				'after'  => '</div>',
			) );

		echo '<div class="comment-wrapper">';
		comments_template(); 
		echo '</div>';
	?>	
 </div>
 <?php edit_post_link( __( 'Edit', 'cubby' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
</div>
</div>
<?php if($enable_right_sidebar == 1){?>
<div class="content_right right">
<?php cubby_get_sidebar(3,true); ?>
</div>
<?php }?>
<?php
}
?>
<?php endwhile;endif;?>
<div class="clear"></div>
</div>

</div>
<?php get_footer(); ?>