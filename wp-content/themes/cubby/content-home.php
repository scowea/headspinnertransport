<?php
 	/*	
	*	The Template for displaying custom home page.
	*   @package WordPress
	*/
?>
<div class="slogan-box">
  <div class="container">
    <div class="client-says left">Client says</div>
    <div class="slogan-right left slogan">
      <ul class="testimonial">
<?php
	 for($i = 1;$i <= 5 ;$i++){
	 $slogan_author  = cubby_options_array('slogan_author_'.$i);
	 $slogan_content = cubby_options_array('slogan_content_'.$i);
	 if(trim($slogan_content) != ""){
 ?>
        <li>
          <h4><?php echo $slogan_author;?></h4>
          <p><?php echo $slogan_content;?></p>
        </li>
        <?php }}?>
      </ul>
    </div>
    <div class="arrow right"><a href="javascript:;" id="scroll_down"><img src="<?php echo CUBBY_THEME_BASE_URL;?>/images/Arrow-top.png" alt="" /></a> <a href="javascript:;" id="scroll_up"><img src="<?php echo CUBBY_THEME_BASE_URL;?>/images/Arrow-bottom.png" /></a></div>
  </div>
  <div class="clear"></div>
</div>
<div class="center-content">
  <div class="container">
    <div class="columns-3 left">
      <div class="title">
        <h3><?php echo cubby_options_array('content_slideshow_title');?></h3>
      </div>
      <div class="feature-slidercontainer"> <?php echo cubby_get_carousel('features',"feature-slider","340x280",5);?> </div>
    </div>
    <div class="columns-31 left">
      <div class="title">
        <h3><?php echo cubby_options_array('latest_news_title');?></h3>
      </div>
      <ul class="news-content">
        <?php
$args = array(
	'cat'      => cubby_options_array('latest_news'),
	'posts_per_page' => 2,
);
// The Query
$the_query = new WP_Query( $args );
// The Loop

if ( $the_query->have_posts() ) {

        echo '<ul>';

	while ( $the_query->have_posts() ) {

		$the_query->the_post();

		$cubby_feat_image  = "";

		$cubby_excerpt_css = "";

		//$feat_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
        $feat_image = wp_get_attachment_image( get_post_thumbnail_id(get_the_ID()), 'home-news');
		
		

		if($feat_image != ""){ 

		$cubby_feat_image  = '<span class="img"><a href="'.get_permalink().'">'.$feat_image.'</a></span>';

		$cubby_excerpt_css = "p-128";

		}

		echo '<li>'.$cubby_feat_image.'<a href="'.get_permalink().'"><h4>' . get_the_title() . '</h4></a><p class="'.$cubby_excerpt_css.'"><span class="d-block">'.get_the_date('F dS, Y').'</span>'.cubby_cover_excerpt(22).'</p></li>';

		echo '';
	}
       echo '</ul>';

} else {
	// no posts found
}

/* Restore original Post Data */

wp_reset_postdata();

?>
      </ul>
    </div>
    <div class="clear"></div>
    <?php if( cubby_options_array('home_purchase')){?>
    <div class=" purchase"> <?php echo cubby_options_array('home_purchase');?> </div>
    <?php }?>
  
    <div class="columns1">
      <div class="title">
        <h3><?php echo cubby_options_array('bottom_carousel_title');?></h3>
      </div>
      <div class="features">
        <div class="arrow-top left"><a href="javascript:;" class="carousel-prev"><img src="<?php echo CUBBY_THEME_BASE_URL;?>/images/Arrow-left.png" alt=""/></a></div>
        <div class="partners-content left"> <?php echo cubby_get_carousel('carousel',"partners-slider","205x150",10);?> </div>
        <div class="arrow-top last right"><a href="javascript:;" class="carousel-next right"><img src="<?php echo CUBBY_THEME_BASE_URL;?>/images/Arrow-right.png" alt="" /></a></div>
      </div>
      <div class="clear"></div>
    </div>
   
  </div>
</div>