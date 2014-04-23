<?php

class BWGModelComments_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function get_option_row_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_option WHERE id="%d"', 1));
    return $row;
  }

  public function get_rows_data() {
    global $wpdb;
    $where = ((isset($_POST['search_value'])) ? 'WHERE name LIKE "%' . esc_html($_POST['search_value']) . '%"' : '');
    if (isset($_POST['search_select_value']) && ((int) $_POST['search_select_value'])) {
	    $images_ids = esc_html($_POST['search_select_value']);
      if ($where != "") {
        $where .= ' AND image_id=' . $images_ids;
      }
      else {
        $where = ' WHERE image_id=' . $images_ids; 
      }
    }
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html($_POST['asc_or_desc']) : 'asc');
    $order_by = ' ORDER BY ' . ((isset($_POST['order_by']) && esc_html($_POST['order_by']) != '') ? esc_html($_POST['order_by']) : 'name') . ' ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $query = "SELECT * FROM " . $wpdb->prefix . "bwg_image_comment " . $where . $order_by . " LIMIT " . $limit . ",20";
    $rows = $wpdb->get_results($query);
    return $rows;
  }

  public function get_image_for_comments_table($image_id) {
    global $wpdb;
    $preview_image = $wpdb->get_var($wpdb->prepare("SELECT thumb_url FROM " . $wpdb->prefix . "bwg_image WHERE id='%d'", $image_id));
    return $preview_image;
  }

  public function get_image_filename_for_comments_table($image_id) {
    global $wpdb;
    $preview_image_filename = $wpdb->get_var($wpdb->prepare("SELECT filename FROM " . $wpdb->prefix . "bwg_image WHERE id='%d'", $image_id));
    return $preview_image_filename;
  }
  
   public function get_images_for_comments_table (){
    global $wpdb;
    $query = "SELECT * FROM " . $wpdb->prefix . "bwg_image WHERE published=1";
    $rows_object = $wpdb->get_results($query);
    $rows[0] = 'Select  image';
    if ($rows_object) {
      foreach ($rows_object as $row_object) {
        $rows[$row_object->id] = $row_object->filename;
      }
    }
    return $rows;
  }

  public function page_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value'])) ? 'WHERE name LIKE "%' . esc_html($_POST['search_value']) . '%"' : '');
    if (isset($_POST['search_select_value']) && ((int) $_POST['search_select_value'])) {
	    $images_ids = esc_html($_POST['search_select_value']);
      if ($where != "") {
        $where .= ' AND image_id=' . $images_ids ;
      }
      else {
        $where = ' WHERE image_id=' . $images_ids; 
      }
    }
    $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "bwg_image_comment " . $where;
    $total = $wpdb->get_var($query);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
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