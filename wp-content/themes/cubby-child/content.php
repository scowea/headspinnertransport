<?php
/**
 * Posts loop
 *
 * @package WordPress
 */
   $comments = get_comments_number(get_the_ID());
   $comment_unit = $comments <= 1?__("Comment","cubby"):__("Comments","cubby");
   $comment_str = $comments." ".$comment_unit;
	?>
<?php if(!is_singular()):?>

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
  <?php
if ( has_post_thumbnail() && ! post_password_required()) { 
				
				 $feat_image = wp_get_attachment_image( get_post_thumbnail_id(get_the_ID()), 'blog-list');
				  if($feat_image ){ 
				echo "<div class='blog-item-image'><a href='".get_permalink()."'>".$feat_image."</a></div>";
				}

} 
  ?>
<div class="post-content">
<?php the_excerpt();?>
</div>
<div class="read-more"><a href="<?php the_permalink();?>"><?php _e("Read More »","cubby");?></a></div>
</div>
<div class="clear"></div>
</div>

<?php else:?>
<div class="blog_content_container">

<div class="blog_content">
<h2 class="sidebar-titile"><a href="<?php the_permalink();?>" title="" ><?php the_title();?></a></h2>
<div class="blog-info">
<div class="blog-calendar"><?php _e("Categories","cubby");?> : <?php the_category(', '); ?></div>
<div class="blog-author"><?php _e("Tags","cubby");?> : <?php echo get_the_tag_list('',', ');?></div>

<div class="clear"></div>
</div>

  <div class="post-content">
<?php the_content();?>
</div>
<?php
      wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'cubby' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
 
	echo '<div class="comment-wrapper">';
	comments_template(); 
	echo '</div>';

?>

</div>
<div class="clear"></div>
</div>

<?php endif;?>