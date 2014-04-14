<?php
/**
 * The search template file.
 * @package WordPress
 */
 get_header(); ?>
<?php 
   cubby_get_breadcrumb();
?>

<div class="main_content">
  <div class="content_left left">
    <?php if (have_posts()) :?>
    <div class="post_author_timeline">
      <?php
	while ( have_posts() ) : the_post();
	$comments = get_comments_number(get_the_ID());
   $comment_unit = $comments <= 1?__("Comment","themetify"):__("Comments","cubby");
   $comment_str = $comments." ".$comment_unit;
	?>
      <div class="blog_item">
        <div class="post-date">
<strong><?php echo get_the_time("M");echo " ";echo get_the_time("d"); ?></strong>
<em><?php echo get_the_time("Y");?></em>
</div>
        <div class="blog_item_content">
         <h2 class="sidebar-titile"><a href="<?php the_permalink();?>" title="" ><?php the_title();?></a></h2>
          <div class="blog-info">
<div class="blog-calendar"><?php _e("Categories","cubby");?> : <?php the_category(', '); ?></div>
<div class="blog-author"><?php _e("Tags","cubby");?> : <?php echo get_the_tag_list('',', ');?></div>
<div class="blog-comment"><a href="<?php the_permalink();?>#comments" class="fz_color"><?php echo $comment_str;?></a></div>
<div class="clear"></div>
</div>
          <?php the_excerpt();?>
          <div class="read-more"><a href="<?php the_permalink();?>"><?php _e("Read More »","cubby");?></a></div>
        </div>
      </div>
      <?php endwhile;?>
      <div class="clear"></div>
    </div>
    <!--post_author_timeline-->
    <div class="patt border">
      <?php if(function_exists("cubby_native_pagenavi")){cubby_native_pagenavi("echo",$wp_query);}?>
    </div>
    <?php else : ?>
    <header class="page-header">
      <h1 class="page-title">
        <?php _e( 'Nothing Found', 'cubby' ); ?>
      </h1>
    </header>
    <div class="page-content page_404">
      <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
      <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'cubby' ), admin_url( 'post-new.php' ) ); ?></p>
      <?php elseif ( is_search() ) : ?>
      <p>
        <?php _e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'cubby' ); ?>
      </p>
      <div class="search_form">
        <form id="searchform_404" class="searchform_404" action="<?php echo home_url(); ?>/" method="get" role="search">
          <input type="text" value="Search" onFocus="if(this.value=='Search'){this.value=''}" onBlur="if(this.value==''){this.value='Search'}" name="s" id="s" class="search_text">
          <input name="gy" type="submit" class="search-button">
        </form>
        <div class="clear"></div>
      </div>
      <?php else : ?>
      <p>
        <?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'cubby' ); ?>
      </p>
      <div class="search_form">
        <form id="searchform_404" class="searchform_404" action="<?php echo home_url(); ?>/" method="get" role="search">
          <input type="text" value="Search" onFocus="if(this.value=='Search'){this.value=''}" onBlur="if(this.value==''){this.value='Search'}" name="s" id="s" class="search_text">
          <input name="gy" type="submit" class="search-button">
        </form>
        <div class="clear"></div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif;?>
    <div class="clear"></div>
  </div>
  <div class="content_right right">
    <?php cubby_get_sidebar(6,true); ?>
  </div>
  <div class="clear"></div>
</div>
<!--main_content-->
<div class="main_content">
  <div class="border-top"></div>
</div>
<!--main_content-->
<?php get_footer(); ?>
