<?php
if( ! defined('CUBBY_THEME_BASE_URL' ) ) 	 { 	define( 'CUBBY_THEME_BASE_URL', get_template_directory_uri()); }
if( ! defined('CUBBY_OPTIONS_FRAMEWORK' ) ) 	 { 	define( 'CUBBY_OPTIONS_FRAMEWORK', get_template_directory().'/admin/' ); }
if( ! defined('CUBBY_OPTIONS_FRAMEWORK_URI' ) ){	define( 'CUBBY_OPTIONS_FRAMEWORK_URI',  CUBBY_THEME_BASE_URL. '/admin/'); }
if( ! defined('CUBBY_OPTIONS_PREFIXED' ) ){    define('CUBBY_OPTIONS_PREFIXED' ,'cubby_');}

require_once( CUBBY_OPTIONS_FRAMEWORK.'options-framework.php' );
require_once( 'includes/metabox-options.php' );
require_once( 'includes/register-widget.php' );
require_once( 'includes/class-breadcrumb.php' );
if ( ! isset( $content_width ) || $content_width == "" || $content_width == 0) $content_width = 960;
if( ! defined('CUBBY_THEME_CONTENT_WIDTH' ) ) 	 { 	define( 'CUBBY_THEME_CONTENT_WIDTH', $content_width); }
/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 */

if ( ! function_exists( 'cubby_setup' ) ) :
function cubby_setup(){
	$lang = CUBBY_THEME_BASE_URL. '/languages';
	load_theme_textdomain('cubby', $lang);
	add_theme_support( 'post-thumbnails' ); 
	$args = array();
	$header_args = array( 
	    'default-image'          => '',
        'default-text-color'     => '555555',
        'width'                  => 960,
        'height'                 => 60,
        'flex-height'            => true
     );
	add_theme_support( 'custom-background', $args );
	add_theme_support( 'custom-header', $header_args );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('nav_menus');
	register_nav_menus( array('primary' => __( 'Header Menu', 'cubby' )));
	register_nav_menus( array('footer_menu' => __( 'Footer Menu', 'cubby' )));
	add_editor_style("editor-style.css");
	
	add_image_size( 'blog-list', 571, 999999 , true);  
	add_image_size( 'home-news', 110, 70 , true); 
	add_image_size( 'home-carousel', 205, 150, true ); //(cropped)
	add_image_size( 'sidebar-posts', 60, 45 , true); 

	
}
endif; // cubby_setup
add_action( 'after_setup_theme', 'cubby_setup' );



   

     
if ( !function_exists( 'cubby_of_get_option' ) ) {
function cubby_of_get_option($name, $default = false) {
	
	$optionsframework_settings = get_option(CUBBY_OPTIONS_PREFIXED.'optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( isset($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}

if ( !function_exists( 'cubby_of_get_options' ) ) {
function cubby_of_get_options($default = false) {
	
	$optionsframework_settings = get_option(CUBBY_OPTIONS_PREFIXED.'optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( isset($options) ) {
		return $options;
	} else {
		return $default;
	}
}
}
global $cubby_options;
$cubby_options = cubby_of_get_options();

function cubby_options_array($name){
	global $cubby_options;
	if(isset($cubby_options[$name]))
	return $cubby_options[$name];
	else
	return "";
}
// set default options
function cubby_on_switch_theme(){
global $cubby_options;
 $optionsframework_settings = get_option( CUBBY_OPTIONS_PREFIXED.'optionsframework' );
 if(!get_option($optionsframework_settings['id'])){
 $config = array();
 $output = array();
 $location = apply_filters( 'options_framework_location', array('admin-options.php') );

	        if ( $optionsfile = locate_template( $location ) ) {
	            $maybe_options = require_once $optionsfile;
	            if ( is_array( $maybe_options ) ) {
					$options = $maybe_options;
	            } else if ( function_exists( 'optionsframework_options' ) ) {
					$options = optionsframework_options();
				}
	        }
	    $options = apply_filters( 'of_options', $options );
		$config  =  $options;
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
				$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
		}
		add_option($optionsframework_settings['id'],$output);

		
}
$cubby_options = cubby_of_get_options();
}
add_action( 'after_setup_theme', 'cubby_on_switch_theme' );
add_action('after_switch_theme', 'cubby_on_switch_theme');

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'cubby_optionsframework_custom_scripts');

function cubby_optionsframework_custom_scripts() { 

}


add_filter('options_framework_location','cubby_options_framework_location_override');

function cubby_options_framework_location_override() {
	return array('includes/admin-options.php');
}

add_action('wp_head', 'cubby_style_wp_head');
function cubby_style_wp_head() {
    global $content_width,$post;
	echo "\n <style type='text/css'>\n ";
	
	
	//// tagline typography
/*	$tagline_typography = cubby_options_array('tagline_typography');
	if ($tagline_typography) { 
	echo '.logo span.tagline {font-family: ' . $tagline_typography['face']. '; font-size:'.$tagline_typography['size'] . '; font-style: ' . $tagline_typography['style'] . '; color:'.$tagline_typography['color'].';;font-weight:'.$tagline_typography['style'] . ';line-height:'.$tagline_typography['size'] . ';}';
	}*/
	
	//// header menu typography
	$menu_fonts_color = cubby_options_array('menu_fonts_color');
	if ($menu_fonts_color) { 
	echo '.nav_menu ul:first-child > li > a > span{font-family: ' . $menu_fonts_color['face']. '; font-size:'.$menu_fonts_color['size'] . '; font-style: ' . $menu_fonts_color['style'] . '; color:'.$menu_fonts_color['color'].';font-weight:'.$menu_fonts_color['style'] . '}';
	}
	
	
	//// header background
	$header_image = get_header_image();
	if (isset($header_image) && ! empty( $header_image )) {
	echo ".header .top2{background:url(".$header_image. ") repeat;}\n";
	}
    if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
            $blog_title_style = ' display:none;';
        else
            $blog_title_style = ' color:#' . get_header_textcolor() . ';';
		echo ".header .top2 .site-name,.header .top2 .tagline{".$blog_title_style."}\n";	
	
	//// breadcrumb background
	
	$breadcrumb_background = cubby_options_array('breadcrumb_background');
	if ($breadcrumb_background) {
	if (isset($breadcrumb_background['image']) && $breadcrumb_background['image']!="") {
	echo ".breadcrumb{background:url(".$breadcrumb_background['image']. ")  ".$breadcrumb_background['repeat']." ".$breadcrumb_background['position']." ".$breadcrumb_background['attachment']."}\n";
	}
	else
	{
	if(isset($breadcrumb_background['color']) && $breadcrumb_background['color'] !=""){
	echo "body .breadcrumb{ background:".$breadcrumb_background['color'].";}\n";
	}
	}
	}
	//// body background
	/*
	$body_background = cubby_options_array('body_background');
	if ($body_background) {
	if (isset($body_background['image']) && $body_background['image']!="") {
	echo "body{background:url(".$body_background['image']. ")  ".$body_background['repeat']." ".$body_background['position']." ".$body_background['attachment']."}\n";
	}else
	{
	if(isset($body_background['color']) && $body_background['color'] !=""){
	echo "body{ background:".$body_background['color'].";}\n";
	}}}
	*/
	//// content typography
	$content_typography = cubby_options_array('content_typography');
	if ($content_typography) { 
	echo '.post-content {font-family: ' . $content_typography['face']. '; font-size:'.$content_typography['size'] . '; font-style: ' . $content_typography['style'] . '; color:'.$content_typography['color'].';font-weight:'.$content_typography['style'] . ';}';
	}
	
	
	// footer background
	
	//// header background
	$footer_widget_background = cubby_options_array('footer_widget_background');
	if ($footer_widget_background) {
	if (isset($footer_widget_background['image']) && $footer_widget_background['image']!="") {
	echo ".footer .footer-content{background:url(".$footer_widget_background['image']. ")  ".$footer_widget_background['repeat']." ".$footer_widget_background['position']." ".$footer_widget_background['attachment']."}\n";
	}
	else
	{
	if(isset($footer_widget_background['color']) && $footer_widget_background['color'] !=""){
	echo ".footer .footer-content{ background:".$footer_widget_background['color'].";}\n";
	}
	}
	}
	//Footer fonts color
	$footer_fonts_color = cubby_options_array('footer_fonts_color');
	if ($footer_fonts_color) {
	echo ".footer .footer-content{ color:".$footer_fonts_color.";}\n";
	}
	
	////
	if(is_numeric($content_width)){echo "body div.main_content,body div.container{width:".$content_width."px;}";}
	
	 $cubby_top_slider_width = get_post_meta( $post->ID, '_cubby_top_slider_width', true );
	 if($cubby_top_slider_width == "boxed"){
	 echo ".banner{width:".$content_width."px;overflow: hidden;}"; 
	 }
     echo "</style>\n \n ";
    
}

// Add custom css
function cubby_add_custom_css_header(){
  $custom_css = cubby_options_array('header_code');
  if(isset($custom_css) && $custom_css != ""){echo "<style type='text/css'>".$custom_css."</style>"; }
 } 

add_action('wp_head', 'cubby_add_custom_css_header');


/* 
 * Change the menu title name and slug
 */
 
 
function cubby_optionscheck_options_menu_params( $menu ) {
	
	$menu['page_title'] = __( 'Cubby Options', 'cubby');
	$menu['menu_title'] = __( 'Cubby Options', 'cubby');
	$menu['menu_slug'] = 'cubby-options';
	return $menu;
}

add_filter( 'optionsframework_menu', 'cubby_optionscheck_options_menu_params' );


function cubby_wp_title( $title, $sep ) {
	global $paged, $page;
 
	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'cubby' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'cubby_wp_title', 10, 2 );


function cubby_title( $title ) {
if ( $title == '' ) {
return 'Untitled';
} else {
return $title;
}
}
add_filter( 'the_title', 'cubby_title' );

add_action( 'wp_head', 'cubby_favicon' );
if(!function_exists('cubby_favicon'))
{
	function cubby_favicon()
	{
	    $url =  cubby_options_array('favicon');
		$icon_link = "";
		if($url)
		{
			$type = "image/x-icon";
			if(strpos($url,'.png' )) $type = "image/png";
			if(strpos($url,'.gif' )) $type = "image/gif";
		
			$icon_link = '<link rel="icon" href="'.esc_url($url).'" type="'.$type.'">';
		}
		
		echo $icon_link;
	}
}

////register styles & scripts
  function cubby_custom_scripts(){
    global $post;
    wp_enqueue_script('jquery');
	wp_enqueue_script('cubby-default', CUBBY_THEME_BASE_URL.'/js/cubby.js', false, '', false);
	if ( is_singular() ){
	wp_enqueue_script( 'comment-reply' );}

 }
 function cubby_custom_styles(){

	wp_enqueue_style('main-style', CUBBY_THEME_BASE_URL.'/style.css', false, '', false);
	wp_enqueue_style('media-style', CUBBY_THEME_BASE_URL.'/media.css', false, '', false);
	wp_enqueue_style( 'Yanone-Kaffeesatz', 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz', false, '', false );
	wp_enqueue_style( 'Open-Sans', 'http://fonts.googleapis.com/css?family=Open+Sans', false, '', false );

 }
   if (!is_admin()) {
   add_action('wp_print_scripts', 'cubby_custom_scripts');
   add_action('wp_print_styles', 'cubby_custom_styles');
  }
  
 
  function cubby_nivo_styles(){
  global $post;
   if(isset($post->ID) && is_numeric($post->ID) ){
	$top_slider      = get_post_meta( $post->ID, '_cubby_top_slider', true );
	if($top_slider !="" && $top_slider!=0){
	wp_enqueue_style('nivo-styles', CUBBY_THEME_BASE_URL.'/js/nivo-theme/default.css', false, '', false);
	}
	}
  }
 
    add_action('wp_print_styles', 'cubby_nivo_styles');

  	/*-------------------------------------------------------------------------------------------*/
/* Get  carousel*/
/*-------------------------------------------------------------------------------------------*/
 function cubby_get_carousel($id='features',$container="cubby-carousel",$size="",$num=5){
    $return = "";
	$image_size = "";
    $return = '<div id="'.$container.'" class="cubby-owl-carousel owl-carousel">';
  
	wp_enqueue_script('owl-carousel', CUBBY_THEME_BASE_URL.'/js/owl.carousel.min.js', false, '', false );
	
	if($size !=""){
	$size_pice = explode("x",strtolower($size));
	if(is_numeric($size_pice[0]) && is_numeric($size_pice[1])){
	$image_size = 'width="'.$size_pice[0].'" height="'.$size_pice[1].'"';
	}
	}
	
	 for($i=1;$i<=$num;$i++){
	 $title = cubby_options_array('cubby_'.$id.'_slide_title_'.$i);
	 $image = cubby_options_array('cubby_'.$id.'_slide_image_'.$i);
	 $link  = cubby_options_array('cubby_'.$id.'_slide_link_'.$i);
	
	 if(isset($image) && strlen($image)>10){
	 if($link!=""){
	   $return .= '<div class="item"><a href="'.$link.'"><img '.$image_size.' src="'.$image.'" alt="'.$title.'" /></a></div>';
	}else{
	   $return .= '<div class="item"><img '.$image_size.' src="'.$image.'" alt="'.$title.'" /></div>';
	   }
	 }
	 
	 }
			
    $return .= '</div>'; 

	return  $return ;
 
 }
  


/*
*  page navigation
*
*/
function cubby_native_pagenavi($echo,$wp_query){
    if(!$wp_query){global $wp_query;}
    global $wp_rewrite;      
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $pagination = array(
    'base' => @add_query_arg('paged','%#%'),
    'format' => '',
    'total' => $wp_query->max_num_pages,
    'current' => $current,
    'prev_text' => '« ',
    'next_text' => ' »'
    );
 
    if( $wp_rewrite->using_permalinks() )
        $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');
 
    if( !empty($wp_query->query_vars['s']) )
        $pagination['add_args'] = array('s'=>get_query_var('s'));
    if($echo == "echo"){
    echo '<p class="page_navi">'.paginate_links($pagination).'</p>'; 
	}else
	{
	
	return '<p class="page_navi">'.paginate_links($pagination).'</p>';
	}
}
   
   //// Get header social network icon list 
   
   function cubby_get_social_network($args){
   $return = "";
   if(is_array($args)){
   $return = '<ul class="follow">';
   foreach($args as $social){
   $social_link = cubby_options_array('social_'.$social);
   if($social_link!=""){
    $return .=  '<li><a href="'.$social_link.'" target="_blank" title="'.ucwords(str_replace("_"," ",$social)).'"><img src="'.CUBBY_THEME_BASE_URL.'/images/social/'.$social.'.png" /></a></li>';
	}
   }
   $return .= '</ul>';
   }
   return $return;
   }
   // Get sidebar
   function cubby_get_sidebar($sidebar,$default=false){
   if ( function_exists('dynamic_sidebar')){
	if(is_active_sidebar($sidebar)){
	   dynamic_sidebar($sidebar);
	}
	else{
	if($default==true){
	dynamic_sidebar(1) ;
	}
	
	}
	}else{wp_link_pages(); } 
   }
   
   //// Custom comments list
   
   function cubby_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ;?>">
     <div id="comment-<?php comment_ID(); ?>">
	 
	 <div class="comment-avatar"><?php echo get_avatar($comment,'52','' ); ?></div>
			<div class="comment-info">
			<div class="reply-quote">
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ;?>
			</div>
      <div class="comment-author vcard">
        
			<span class="fnfn"><?php printf(__('%s </cite><span class="says">says:</span>','cubby'), get_comment_author_link()) ;?></span>
								<span class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ;?>">
<?php printf(__('%1$s at %2$s','cubby'), get_comment_date(), get_comment_time()) ;?></a>
<?php edit_comment_link(__('(Edit)','cubby'),'  ','') ;?></span>
				<span class="comment-meta">
					<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ;?>">-#<?php echo $depth?></a>				</span>

      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.','cubby') ;?></em>
         <br />
      <?php endif; ?>

     

      <?php comment_text() ;?>
</div>
   
     </div>
<?php
        }
	// get 	excerpt length
function cubby_cover_excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
    }
	
	
//// get breadcrumb wrapper and slider
function cubby_get_breadcrumb(){
   global $post;
   $show_breadcrumb = "";
   if(isset($post->ID) && is_numeric($post->ID)){
    $show_breadcrumb = get_post_meta( $post->ID, '_cubby_show_breadcrumb', true );
	}
	if($show_breadcrumb == 1 || $show_breadcrumb==""){
	
	echo  '<div class="box-nav breadcrumb"><div class="container"><div class="crumb">';
    new cubby_breadcrumb;
    echo '</div><div class="input hidden cubby-searchform"><form action="'.esc_url(home_url('/')).'" id="cse-search-box"><input type="text" value="Search" onFocus="if(this.value==\'Search\'){this.value=\'\'}" id="s" onBlur="if(this.value==\'\'){this.value=\'Search\'}" name="s" class="search_r_text"><input type="submit" name="sa" class="search-btn" value=""></form></div> <div class="clear"></div></div></div>';

	}
}
function cubby_get_slider(){


    $slide_caption = "";
	$slides = "";
	wp_register_script( 'nivo-slider', CUBBY_THEME_BASE_URL.'/js/jquery.nivo.slider.pack.js', false, '', false );
	wp_enqueue_script('nivo-slider');
	
	$return = '<div class="banner"><div class="slider-wrapper theme-bar"><div id="top-slider" class="nivoSlider">';
	 for($i=1;$i<=5;$i++){
	 $title = cubby_options_array('cubby_slide_title_'.$i);
	 $text  = cubby_options_array('cubby_slide_text_'.$i);
	 $image = cubby_options_array('cubby_slide_image_'.$i);
	 $link  = cubby_options_array('cubby_slide_link_'.$i);
	
	 if(isset($image) && strlen($image)>10){
	 
	 
	$thumb     = $image;
	$slide_img = $image;
	
	if($link!=""){ $slides .=  '<a href="'.$link.'"><img src="'.$slide_img.'" data-thumb="'.$thumb.'" alt="'.$title.'" title="#htmlcaption-'.$i.'" /></a>';}
	else{
	   $slides .=  '<img src="'.$slide_img.'" data-thumb="'.$thumb.'" alt="'.$title.'" title="#htmlcaption-'.$i.'" />';
	}
	if($title != "" || $text != ""){
	$slide_caption .= '<div id="htmlcaption-'.$i.'" class="nivo-html-caption"><h4>'.$title.'</h4><p>'.$text.'</p></div>';
	}
	
			}

	}
	    $return .= $slides;
	    $return .= '</div>';
		$return .=  $slide_caption;
		$return .= '</div></div>';
	
	$return .= '<script type="text/javascript">
    jQuery(window).load(function() {
        jQuery("#top-slider").nivoSlider( {prevText: "",nextText: "",controlNav:false});
    });
    </script>';
		echo $return;

	
   }