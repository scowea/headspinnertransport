<?php
/*
 * Template Name: Custom Home Page
 * Description: A home page with featured slider and widgets.
 *
 * @package Bota
 * @since Bota 1.0
 */

get_header(); ?>

	<div class="flex-container">
              <div class="flexslider">
                <ul class="slides">
                <?php
                query_posts(array('category_name' => 'featured', 'posts_per_page' => 3));
                if(have_posts()) :
                    while(have_posts()) : the_post();
                ?>
                  <li>
                    <?php the_post_thumbnail(); ?>
                  </li>
                <?php
                    endwhile;
                endif;
                wp_reset_query();
                ?>
                </ul>
              </div>
	</div>	
        
        <div class="featuretext_top">
			 <h3><?php echo get_theme_mod( 'featured_textbox' ); ?></h3>
             <p><?php echo get_theme_mod( 'featured_textbox_text_head' ); ?></p>
		</div>
        
        <div id="primary" class="content-area">
			<div id="content" class="fullwidth" role="main">
  
   <div class="section group">
	<div class="col span_1_of_3">         
    <div class="featuretext">
			 <h3><?php echo get_theme_mod( 'featured_textbox_header_one' ); ?></h3>
             <p><?php echo get_theme_mod( 'featured_textbox_text_one' ); ?></p>
	</div>
    </div>
    
    <div class="col span_1_of_3">         
     <div class="featuretext">
			 <h3><?php echo get_theme_mod( 'featured_textbox_header_two' ); ?></h3>
             <p><?php echo get_theme_mod( 'featured_textbox_text_two' ); ?></p>
	</div>
    </div>
    
   <div class="col span_1_of_3">         
     <div class="featuretext">
			 <h3><?php echo get_theme_mod( 'featured_textbox_header_three' ); ?></h3>
             <p><?php echo get_theme_mod( 'featured_textbox_text_three' ); ?></p>
	</div>
    </div>
    
    </div>
    
    
    <div class="section_thumbnails group">
	<h3>Recent Posts</h3>
<!-- LOOP START -->
<?php $the_query = new WP_Query(array(
  'showposts' => 3,
  'post__not_in' => get_option("sticky_posts"),
  ));
 ?>
    <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
      <div class="col span_1_of_3">
      <ul>
      <!-- THIS DISPLAYS THE POST THUMBNAIL, The array allows the image to has a custom size but is always kept proportional -->
      <li class="post-thumbnail"> <?php the_post_thumbnail( array(70,70) );?></li>
      <!-- THIS DISPLAYS THE POST TITLE AS A LINK TO THE MAIN POST -->
      <li class="blog-lists-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
      <!-- THIS DISPLAYS THE EXCERPT OF THE POST -->
      <li class="blog-lists-title"><?php echo get_excerpt(); ?><a href="<?php the_permalink() ?>"> More...</a></li>
      </ul>
      </div>
    <?php endwhile;?>
<!-- LOOP FINNISH -->
	
    </div>

    
			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>