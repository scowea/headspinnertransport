<?php

class BWGControllerComments_bwg {
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
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html($_POST['task']) : '');
    $id = ((isset($_POST['current_id'])) ? esc_html($_POST['current_id']) : 0);
    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_BWG_DIR . "/admin/models/BWGModelComments_bwg.php";
    $model = new BWGModelComments_bwg();

    require_once WD_BWG_DIR . "/admin/views/BWGViewComments_bwg.php";
    $view = new BWGViewComments_bwg($model);
    $view->display();
  }

  public function delete($id) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_comment WHERE id="%d"', $id);
       
    if ($wpdb->query($query)) {
      echo WDWLibrary::message('Item Succesfully Deleted.', 'updated');
    }
    else {
      echo WDWLibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwg_image_comment');
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {      
        $flag = TRUE;
        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_comment WHERE id="%d"', $tag_id);
        $wpdb->query($query);
      }
    }
    if ($flag) {
      echo WDWLibrary::message('Items Succesfully Deleted.', 'updated');
    }
    else {
      echo WDWLibrary::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  public function publish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'bwg_image_comment', array('published' => 1), array('id' => $id));
    if ($save !== FALSE) {
      echo WDWLibrary::message('Item Succesfully Published.', 'updated');
    }
    else {
      echo WDWLibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function publish_all() {
    global $wpdb;
    $flag = FALSE;
    $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwg_image_comment');
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {
        $flag = TRUE;
        $wpdb->update($wpdb->prefix . 'bwg_image_comment', array('published' => 1), array('id' => $tag_id));
      }
    }
    if ($flag) {
      echo WDWLibrary::message('Items Succesfully Published.', 'updated');
    }
    else {
      echo WDWLibrary::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  public function unpublish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'bwg_image_comment', array('published' => 0), array('id' => $id));
    if ($save !== FALSE) {
      echo WDWLibrary::message('Item Succesfully Unpublished.', 'updated');
    }
    else {
      echo WDWLibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwg_image_comment');
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {
        $flag = TRUE;
        $wpdb->update($wpdb->prefix . 'bwg_image_comment', array('published' => 0), array('id' => $tag_id));
      }
    }
    if ($flag) {
      echo WDWLibrary::message('Items Succesfully Unpublished.', 'updated');
    }
    else {
      echo WDWLibrary::message('You must select at least one item.', 'error');
    }
    $this->display();
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