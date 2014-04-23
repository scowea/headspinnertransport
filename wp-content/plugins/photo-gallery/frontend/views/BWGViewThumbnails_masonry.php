<?php

class BWGViewThumbnails_masonry {
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
  public function display($params, $from_shortcode = 0, $bwg = 0) {
    global $wp;
    $current_url = $wp->query_string;
    global $WD_BWG_UPLOAD_DIR;
    require_once(WD_BWG_DIR . '/framework/WDWLibrary.php');
    if ($params['image_enable_page'] && $params['images_per_page']) {
      $page_nav = $this->model->page_nav($params['gallery_id'], $params['images_per_page'], $bwg);
    }
    if (!isset($params['popup_fullscreen'])) {
      $params['popup_fullscreen'] = 0;
    }
    if (!isset($params['popup_autoplay'])) {
      $params['popup_autoplay'] = 0;
    }
    if (!isset($params['order_by'])) {
      $order_by = 'asc'; 
    }
    else {
      $order_by = $params['order_by'];
    }
    $theme_row = $this->model->get_theme_row_data($params['theme_id']);
    if (!$theme_row) {
      echo WDWLibrary::message(__('There is no theme selected or the theme was deleted.', 'bwg'), 'error');
      return;
    }
    $gallery_row = $this->model->get_gallery_row_data($params['gallery_id']);
    if (!$gallery_row) {
      echo WDWLibrary::message(__('There is no gallery selected or the gallery was deleted.', 'bwg'), 'error');
      return;
    }
    $image_rows = $this->model->get_image_rows_data($params['gallery_id'], $params['images_per_page'], $params['sort_by'], $order_by,  $bwg);
    if (!$image_rows) {
      echo WDWLibrary::message(__('There are no images in this gallery.', 'bwg'), 'error');
    }
    $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->masonry_thumbs_bg_color);
    ?>
    <style>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?> {
        visibility: hidden;
        text-align: center;
        display: inline-block;
        vertical-align: middle;
        <?php
        if ($params['masonry_hor_ver'] == 'vertical') {
          ?>
          width: <?php echo $params['thumb_width']; ?>px !important;
          <?php
        }
        else {
          ?>
          height: <?php echo $params['thumb_height']; ?>px !important;
          <?php
        }
        ?>
        border-radius: <?php echo $theme_row->masonry_thumb_border_radius; ?>;
        border: <?php echo $theme_row->masonry_thumb_border_width; ?>px <?php echo $theme_row->masonry_thumb_border_style; ?> #<?php echo $theme_row->masonry_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->masonry_thumb_padding; ?>px !important;
        opacity: <?php echo $theme_row->masonry_thumb_transparent / 100; ?>;
        filter: Alpha(opacity=<?php echo $theme_row->masonry_thumb_transparent; ?>);
        <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        z-index: 100;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_<?php echo $bwg; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
        -moz-box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo $theme_row->masonry_thumb_bg_transparent / 100; ?>);
        box-sizing: border-box;
        display: inline-block;
        font-size: 0;
        <?php
        if ($params['masonry_hor_ver'] == 'vertical') {
          ?>
          /*width: <?php echo $params['image_column_number'] * ($params['thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;*/
          width: 100%;
          <?php
        }
        else {
          ?>
          height: <?php echo $params['image_column_number'] * ($params['thumb_height'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px !important;
          width: inherit;
          <?php
        }
        ?>
        position: relative;
        text-align: <?php echo $theme_row->masonry_thumb_align; ?>;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> a {
        cursor: pointer;
        text-decoration: none;
      }
      /*pagination styles*/
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> {
        text-align: <?php echo $theme_row->page_nav_align; ?>;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin: 6px 0 4px;
        display: block;
        height: 30px;
        line-height: 30px;
      }
      @media only screen and (max-width : 320px) {
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
          display: none;
        }
      }
      <?php
      if ($params['masonry_hor_ver'] == 'vertical') {
        ?>
        @media only screen and (max-width : <?php echo $params['image_column_number'] * ($params['thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
            width: inherit;
          }
        }
        <?php
      }
      else {
        ?>
        @media only screen and (max-height : <?php echo $params['image_column_number'] * ($params['thumb_height'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumbnails_<?php echo $bwg; ?> {
            height: inherit;
          }
        }
        <?php
      }
      ?>
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .displaying-num_<?php echo $bwg; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin-right: 10px;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .paging-input_<?php echo $bwg; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:hover,
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a.disabled:focus {
        cursor: default;
        color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .tablenav-pages_<?php echo $bwg; ?> a {
        cursor: pointer;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->page_nav_padding; ?>;
        margin: <?php echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo $theme_row->page_nav_button_bg_transparent / 100; ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo $theme_row->lightbox_overlay_bg_transparent / 100; ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_masonry_thumb_spun_<?php echo $bwg; ?> {
        position: absolute;
      }
    </style>

    <div id="bwg_container1_<?php echo $bwg; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <form id="gal_front_form_<?php echo $bwg; ?>" method="post" action="#">
          <div id="bwg_masonry_thumbnails_div_<?php echo $bwg; ?>" style="background-color: rgba(0, 0, 0, 0); text-align: <?php echo $theme_row->masonry_thumb_align; ?>; width: 100%;">
            <?php
            if ($params['image_enable_page']  && $params['images_per_page'] && ($theme_row->page_nav_position == 'top')) {
              WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $params['images_per_page'], $bwg, 'bwg_masonry_thumbnails_' . $bwg);
            }
            ?>
            <div id="bwg_masonry_thumbnails_<?php echo $bwg; ?>" class="bwg_masonry_thumbnails_<?php echo $bwg; ?>">
              <div id="ajax_loading_<?php echo $bwg; ?>" style="position:absolute;">
                <div id="opacity_div_<?php echo $bwg; ?>" style="display:none; background-color:#FFFFFF; opacity:0.7; filter:Alpha(opacity=70); position:absolute; z-index:105;"></div>
                <span id="loading_div_<?php echo $bwg; ?>" style="display:none; text-align:center; position:relative; vertical-align:middle; z-index:107">
                  <img src="<?php echo WD_BWG_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading" style="float: none; width:50px;">
                </span>
              </div>
              <?php
              foreach ($image_rows as $image_row) {
                $params_array = array(
                  'action' => 'GalleryBox',
                  'current_view' => $bwg,
                  'image_id' => (isset($_POST['image_id']) ? esc_html($_POST['image_id']) : $image_row->id),
                  'gallery_id' => $params['gallery_id'],
                  'theme_id' => $params['theme_id'],
                  'thumb_width' => $params['thumb_width'],
                  'thumb_height' => $params['thumb_height'],
                  'open_with_fullscreen' => $params['popup_fullscreen'],
                  'open_with_autoplay' => $params['popup_autoplay'],
                  'image_width' => $params['popup_width'],
                  'image_height' => $params['popup_height'],
                  'image_effect' => $params['popup_effect'],
                  'sort_by' => $params['sort_by'],
                  'order_by' => $order_by,
                  'enable_image_filmstrip' => $params['popup_enable_filmstrip'],
                  'image_filmstrip_height' => $params['popup_filmstrip_height'],
                  'enable_image_ctrl_btn' => $params['popup_enable_ctrl_btn'],
                  'enable_image_fullscreen' => $params['popup_enable_fullscreen'],
                  'slideshow_interval' => $params['popup_interval'],
                  'enable_comment_social' => $params['popup_enable_comment'],
                  'enable_image_facebook' => $params['popup_enable_facebook'],
                  'enable_image_twitter' => $params['popup_enable_twitter'],
                  'enable_image_google' => $params['popup_enable_google'],
                  'watermark_type' => $params['watermark_type'],
                  'current_url' => urlencode($current_url)
                );
                if ($params['watermark_type'] != 'none') {
                  $params_array['watermark_link'] = $params['watermark_link'];
                  $params_array['watermark_opacity'] = $params['watermark_opacity'];
                  $params_array['watermark_position'] = $params['watermark_position'];
                }
                if ($params['watermark_type'] == 'text') {
                  $params_array['watermark_text'] = $params['watermark_text'];
                  $params_array['watermark_font_size'] = $params['watermark_font_size'];
                  $params_array['watermark_font'] = $params['watermark_font'];
                  $params_array['watermark_color'] = $params['watermark_color'];
                }
                elseif ($params['watermark_type'] == 'image') {
                  $params_array['watermark_url'] = $params['watermark_url'];
                  $params_array['watermark_width'] = $params['watermark_width'];
                  $params_array['watermark_height'] = $params['watermark_height'];
                }
                $is_video = $image_row->filetype == "YOUTUBE" || $image_row->filetype == "VIMEO";
                ?>
                <span class="bwg_masonry_thumb_spun_<?php echo $bwg; ?>">
                  <a style="font-size: 0;" onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwg; ?>', '<?php echo $params['popup_width']; ?>', '<?php echo $params['popup_height']; ?>', 1, 'testpopup', 5); return false;">
                    <img class="bwg_masonry_thumb_<?php echo $bwg; ?>" id="<?php echo $image_row->id; ?>" src="<?php echo ($is_video ? "" : site_url() . '/' . $WD_BWG_UPLOAD_DIR) . $image_row->thumb_url; ?>" alt="<?php echo $image_row->alt; ?>" style="max-height: none !important;  max-width: none !important;" />
                  </a>
                </span>
                <?php
              }
              ?>
            </div>
            <script>
              <?php
              if ($params['masonry_hor_ver'] == 'vertical') {
                ?>
                function bwg_masonry_<?php echo $bwg; ?>() {
                  var image_width = <?php echo $params['thumb_width']; ?>;
                  /* var cont_div_width = jQuery("#bwg_masonry_thumbnails_div_<?php echo $bwg; ?>").width();*/
                  var cont_div_width = <?php echo $params['image_column_number'] * ($params['thumb_width'] + 2 * ($theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>;
                  if (cont_div_width > jQuery("#bwg_masonry_thumbnails_div_<?php echo $bwg; ?>").width()) {
                    cont_div_width = jQuery("#bwg_masonry_thumbnails_div_<?php echo $bwg; ?>").width();
                  }
                  var col_count = parseInt(cont_div_width / image_width);
                  if (!col_count) {
                    col_count = 1;
                  }
                  var top = new Array();
                  var left = new Array();
                  for (var i = 0; i < col_count; i++) {
                    top[i] = 0;
                    left[i] = i * image_width;
                  }
                  var div_width = col_count * image_width;
                  if (div_width > jQuery(window).width()) {
                    div_width = jQuery(window).width();
                    jQuery(".bwg_masonry_thumb_<?php echo $bwg; ?>").attr("style", "max-width: " + div_width + "px");
                  }
                  else {
                    div_width = col_count * image_width;
                  }
                  jQuery(".bwg_masonry_thumb_spun_<?php echo $bwg; ?>").each(function() {
                    min_top = Math.min.apply(Math, top);
                    index_min_top = jQuery.inArray(min_top, top);
                    jQuery(this).css({left: left[index_min_top], top: top[index_min_top]});
                    top[index_min_top] += jQuery(this).height();
                  });
                  jQuery(".bwg_masonry_thumbnails_<?php echo $bwg; ?>").width(div_width);
                  jQuery(".bwg_masonry_thumbnails_<?php echo $bwg; ?>").height(Math.max.apply(Math, top));
                  jQuery(".bwg_masonry_thumb_<?php echo $bwg; ?>").css({visibility: 'visible'});
                }
                <?php
              }
              else {
                ?>
                function bwg_masonry_<?php echo $bwg; ?>() {
                  var image_height = <?php echo $params['thumb_height']; ?>;
                  var cont_div_height = <?php echo $params['image_column_number'] * ($params['thumb_height'] + 2 * ($theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>;
                  var col_count = parseInt(cont_div_height / image_height);
                  if (!col_count) {
                    col_count = 1;
                  }
                  var top = new Array();
                  var left = new Array();
                  for (var i = 0; i < col_count; i++) {
                    left[i] = 0;
                    top[i] = i * image_height;
                  }
                  jQuery(".bwg_masonry_thumb_spun_<?php echo $bwg; ?>").each(function() {
                    min_left = Math.min.apply(Math, left);
                    index_min_left = jQuery.inArray(min_left, left);
                    jQuery(this).css({top: top[index_min_left], left: left[index_min_left]});
                    left[index_min_left] += jQuery(this).width();
                  });
                  jQuery(".bwg_masonry_thumbnails_<?php echo $bwg; ?>").css({maxWidth: Math.max.apply(Math, left)});
                  jQuery(".bwg_masonry_thumb_<?php echo $bwg; ?>").css({visibility: 'visible'});
                }
                <?php
              }
              ?>
              jQuery(window).load(function() {
                bwg_masonry_<?php echo $bwg; ?>();
              });
              jQuery(window).resize(function() {
                bwg_masonry_<?php echo $bwg; ?>();
              });
            </script>
            <?php
            if ($params['image_enable_page']  && $params['images_per_page'] && ($theme_row->page_nav_position == 'bottom')) {
              WDWLibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'gal_front_form_' . $bwg, $params['images_per_page'], $bwg, 'bwg_masonry_thumbnails_' . $bwg);
            }
            ?>
          </div>
        </form>
        <div id="spider_popup_loading_<?php echo $bwg; ?>" class="spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <script>
      var bwg_current_url = '<?php echo add_query_arg($current_url, '', home_url($wp->request)); ?>';
    </script>
    <?php
    if ($from_shortcode) {
      return;
    }
    else {
      die();
    }
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