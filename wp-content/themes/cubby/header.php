<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 */
 global $cubby_options;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>
<?php wp_title('|', true, 'right'); ?>
</title>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="header">
  <div class="top">
    <div class="container">
      <div class="contact left">
        <?php  echo cubby_options_array('top_contact_info') ; ?>
      </div>
      <div class="follow right"> <?php echo cubby_get_social_network(array("skype","facebook",'twitter','google_plus','youtube',"linkedin",'pinterest','rss'));?> </div>
    </div>
  </div>
  <div class="top2">
    <div class="container">

      <div class="logo left"><a href="<?php echo esc_url(home_url('/')); ?>">
        <?php if ( cubby_options_array('logo')!="") { ?>
        <img src="<?php echo cubby_options_array('logo'); ?>" alt="<?php bloginfo('name'); ?>" />
        <?php }else{ ?>
        <span class="site-name">
        <?php bloginfo('name'); ?>
        </span>
        <?php }?>
        </a><span class="tagline"><?php echo  get_bloginfo( 'description' );?></span></div>
      <div class="nav_menu menu-main-menu-container">
<?php
wp_nav_menu(array('theme_location'=>'primary','depth'=>0,'container'=>'','container_class'=>'main-menu','menu_id'=>'menu-main','menu_class'=>'main-nav','link_before' => '<span>', 'link_after' => '</span>'));
?>
      </div>
    </div>
  </div>
</div>
<!--header-->