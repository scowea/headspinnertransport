<?php

class BWGViewWidgetFrontEnd {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function view_tags($params) {
    global $WD_BWG_UPLOAD_DIR;
    global $bwg;

    $type = isset($params["type"]) ? $params["type"] : 'text';
    $show_name = isset($params["show_name"]) ? $params["show_name"] : 0;
    $open_option = isset($params["open_option"]) ? $params["open_option"] : 'page';
    $count = isset($params["count"]) ? $params["count"] : 0;
    $width = isset($params["width"]) ? $params["width"] : 250;
    $height = isset($params["height"]) ? $params["height"] : 250;
    $background_transparent = isset($params["background_transparent"]) ? $params["background_transparent"] : 1;
    $background_color = isset($params["background_color"]) ? $params["background_color"] : "000000";
    $text_color = isset($params["text_color"]) ? $params["text_color"] : "ffffff";
    $theme_id = isset($params["theme_id"]) ? $params["theme_id"] : 0;

    $tags = $this->model->get_tags_data($count);
    $theme_row = $this->model->get_theme_row_data($theme_id);
    $options_row = $this->model->get_options_row_data();
    ?>
    <style type="text/css" media="screen">
      @media screen and (max-width: <?php echo $width ?>px) {
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> { 
          display: none;
        }
      }
			#bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> {
				width:<?php echo $width ?>px;
				height:<?php echo $height ?>px;
				margin:0 auto;
				overflow: hidden;
				position: relative;		
        background-color: <?php echo $background_transparent ? 'transparent' : '#' . $background_color ?>;
        color: #<?php echo $text_color ?>;
			}
			#bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> ul {
				list-style-type: none;
			}
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #tags_cloud_item_<?php echo $bwg; ?> a {
        text-decoration: none;
        color: #<?php echo $text_color ?> !important;
        cursor: pointer;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo $theme_row->lightbox_overlay_bg_transparent / 100; ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
		</style>

    <script type="text/javascript">
      jQuery(document).ready(function() {
        var container = jQuery("#tags_cloud_item_<?php echo $bwg; ?>")
        var camera = new Camera3D();
        camera.init(0, 0, 0, (container.width() + container.height()) / 2);
        var tags_cloud_item = new Object3D(container);
        radius = (container.height() > container.width() ? container.width() : container.height());
        tags_cloud_item.addChild(new Sphere(radius * 0.35, <?php echo sqrt(count($tags)) ?>, <?php echo count($tags) ?>));
        var scene = new Scene3D();
        scene.addToScene(tags_cloud_item);        
        var mouseX = 20;
        var mouseY = 30;
        var offsetX = container.offset().left;
        var offsetY = container.offset().top;
        var speed = 6000;
        container.mousemove(function(e){
          mouseX = e.clientX + jQuery(window).scrollLeft() - offsetX - (container.width() / 2);
          mouseY = e.clientY + jQuery(window).scrollTop() - offsetY - (container.height() / 2);
        });
        var animateIt = function(){
          if (mouseX != undefined){
            axisRotation.y += (mouseX) / speed
          }
          if (mouseY != undefined){
            axisRotation.x -= mouseY / speed;
          }
          scene.renderCamera(camera);          
        };
        setInterval(animateIt, 20);
      });
      jQuery(window).load(function () {
        jQuery("#tags_cloud_item_<?php echo $bwg; ?>").attr("style", "visibility: visible;");
      });
    </script>
    <div id="bwg_container1_<?php echo $bwg; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <div id="tags_cloud_item_<?php echo $bwg; ?>" style="visibility: hidden;">
          <ul>
          <?php
            foreach ($tags as $tag) {
              if ($open_option == 'lightbox') {
                $params_array = array(
                  'tag_id' => $tag->term_id,
                  'action' => 'GalleryBox',
                  'open_with_fullscreen' => $options_row->popup_fullscreen,
                  'open_with_autoplay' => $options_row->popup_autoplay,
                  'current_view' => $bwg,
                  'image_id' => $tag->image_id,
                  'gallery_id' => 1,
                  'theme_id' => $theme_id,
                  'thumb_width' => $options_row->thumb_width,
                  'thumb_height' => $options_row->thumb_height,
                  'image_width' => $options_row->popup_width,
                  'image_height' => $options_row->popup_height,
                  'image_effect' => $options_row->popup_type,
                  'sort_by' => 'order',
                  'enable_image_filmstrip' => $options_row->popup_enable_filmstrip,
                  'image_filmstrip_height' => $options_row->popup_filmstrip_height,
                  'enable_image_ctrl_btn' => $options_row->popup_enable_ctrl_btn,
                  'enable_image_fullscreen' => $options_row->popup_enable_fullscreen,
                  'slideshow_interval' => $options_row->popup_interval,
                  'enable_comment_social' => $options_row->popup_enable_comment,
                  'enable_image_facebook' => $options_row->popup_enable_facebook,
                  'enable_image_twitter' => $options_row->popup_enable_twitter,
                  'enable_image_google' => $options_row->popup_enable_google,
                  'watermark_type' => $options_row->watermark_type
                );
                if ($params_array['watermark_type'] != 'none') {
                  $params_array['watermark_link'] = $options_row->watermark_link;
                  $params_array['watermark_opacity'] = $options_row->watermark_opacity;
                  $params_array['watermark_position'] = $options_row->watermark_position;
                }
                if ($params_array['watermark_type'] == 'text') {
                  $params_array['watermark_text'] = $options_row->watermark_text;
                  $params_array['watermark_font_size'] = $options_row->watermark_font_size;
                  $params_array['watermark_font'] = $options_row->watermark_font;
                  $params_array['watermark_color'] = $options_row->watermark_color;
                }
                elseif ($params_array['watermark_type'] == 'image') {
                  $params_array['watermark_url'] = $options_row->watermark_url;
                  $params_array['watermark_width'] = $options_row->watermark_width;
                  $params_array['watermark_height'] = $options_row->watermark_height;
                }
                if ($type == 'text') {
                  ?>
                  <li><a onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwg; ?>', '800', '600', 1, 'testpopup', 5); return false;"><?php echo $tag->name; ?></a></li>
                  <?php
                } else {
                  $is_video = $tag->filetype == "YOUTUBE" || $tag->filetype == "VIMEO";
                  ?>
                  <li style="text-align: center;">
                    <a onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwg; ?>', '800', '600', 1, 'testpopup', 5); return false;">
                      <img id="imgg" src="<?php echo ($is_video ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $tag->thumb_url;?>" alt="<?php echo $tag->name ?>" title="<?php echo $show_name ? '' : $tag->name; ?>" /><?php echo $show_name ? '<br />' . $tag->name : ''; ?>
                  </a></li>
                  <?php
                }
              }
              else {
                if ($type == 'text') {
                  ?>
                  <li><a href="<?php echo $tag->permalink; ?>"><?php echo $tag->name; ?></a></li>
                  <?php
                } else {
                  $is_video = $tag->filetype == "YOUTUBE" || $tag->filetype == "VIMEO";
                  ?>
                  <li style="text-align: center;">
                    <a href="<?php echo $tag->permalink; ?>"">
                      <img id="imgg" src="<?php echo ($is_video ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $tag->thumb_url;?>" alt="<?php echo $tag->name ?>" title="<?php echo $show_name ? '' : $tag->name; ?>" /><?php echo $show_name ? '<br />' . $tag->name : ''; ?>
                  </a></li>
                  <?php
                }
              }
            }
          ?>
          </ul>
        </div>
        <div id="spider_popup_loading_<?php echo $bwg; ?>" class="spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <?php
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}