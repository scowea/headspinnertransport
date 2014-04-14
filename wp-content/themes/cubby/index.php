<?php
/**
 * The main template file.
 *
 * @package WordPress
 */

get_header(); ?>
<?php 

   $is_custom_home = 0;
   $content_width = "";
   $enable_home_page = cubby_options_array('enable_home_page');
   if($enable_home_page == 1 &&  is_front_page()){
   $is_custom_home = 1;
   $content_width = "width:100%;";
    cubby_get_slider();
   }
  
?>
<div class="main_content" style="<?php echo $content_width;?>">
 <?php if (have_posts()) :?>
 <?php
 
   $enable_home_page = cubby_options_array('enable_home_page');
   if($is_custom_home == 1){
   get_template_part("content","home");
   }else{
 
?>
<div class="content_left left">

 <div class="post_author_timeline">
<?php while ( have_posts() ) : the_post();
   
	get_template_part("content",get_post_format());
	
	 endwhile;
 ?>
   <div class="clear"></div>
</div><!--post_author_timeline-->
 <div class="patt border"><?php if(function_exists("cubby_native_pagenavi")){cubby_native_pagenavi("echo",$wp_query);}?></div>
 
		

<div class="clear"></div>
</div>
<div class="content_right right">
<?php cubby_get_sidebar(4,true); ?>
 </div>
<div class="clear"></div>

<?php } else : 
get_template_part( 'content', 'none' );  endif; ?>
</div><!--main_content-->
<div class="main_content"><div class="border-top"></div></div><!--main_content-->
<?php get_footer(); ?>