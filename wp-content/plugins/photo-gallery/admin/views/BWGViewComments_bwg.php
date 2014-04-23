<?php

class BWGViewComments_bwg {
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
  public function display() {
    global $WD_BWG_UPLOAD_DIR;
    $rows_data = $this->model->get_rows_data();
    $page_nav = $this->model->page_nav();
    $option_row = $this->model->get_option_row_data();
    $search_value = ((isset($_POST['search_value'])) ? esc_html($_POST['search_value']) : '');
    $search_select_value = ((isset($_POST['search_select_value'])) ? (int) $_POST['search_select_value'] : 0);
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html($_POST['asc_or_desc']) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html($_POST['order_by']) : 'name');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $ids_string = '';
    ?>
    <div style="font-size: 14px; font-weight: bold;">
      This section allows you to manage the image comments.
      <a style="color: blue; text-decoration: none;" target="_blank" href="http://web-dorado.com/wordpress-form-maker-guide-2.html">Read More in User Manual</a>
    </div>
    <form class="wrap" id="comments_form" method="post" action="admin.php?page=comments_bwg" style="width:95%;">
      <span class="comment_icon"></span>
      <h2>
        Comments
      </h2>
      <div class="buttons_div">
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'publish_all')" value="Publish" />
        <input class="button-secondary" type="submit" onclick="spider_set_input_value('task', 'unpublish_all')" value="Unpublish" />
        <input class="button-secondary" type="submit" onclick="if (confirm('Do you want to delete selected items?')) {
                                                       spider_set_input_value('task', 'delete_all');
                                                     } else {
                                                       return false;
                                                     }" value="Delete" />
      </div>
      <div class="tablenav top">
        <?php
        WDWLibrary::search_select('Image',$search_select_value, $this->model->get_images_for_comments_table(), 'comments_form');
        WDWLibrary::search('Name', $search_value, 'comments_form');
        WDWLibrary::html_page_nav($page_nav['total'], $page_nav['limit'], 'comments_form');
        ?>
      </div>
      <table class="wp-list-table widefat fixed pages">
        <thead>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" style="margin:0;" /></th>
          <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'id');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'id') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'comments_form')" href="">
              <span>ID</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col <?php if ($order_by == 'image_id') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'image_id');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'image_id') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'comments_form')" href="">
              <span>Image</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="<?php if ($order_by == 'name') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'name');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'name') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'comments_form')" href="">
              <span>Name</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="<?php if ($order_by == 'comment') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'comment');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'comment') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'comments_form')" href="">
              <span>Comment</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <?php
          if ($option_row->popup_enable_email) {
          ?>
          <th class="<?php if ($order_by == 'mail') {echo $order_class;} ?>">
            <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'mail');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'mail') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'comments_form')" href="">
              <span>Email</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <?php
          }
          ?>
          <th class="table_big_col <?php if ($order_by == 'published') {echo $order_class;} ?>">
            <a style="padding:2px" onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'published');
                        spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html($_POST['order_by']) == 'published') && esc_html($_POST['asc_or_desc']) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'comments_form')" href="">
              <span>Published</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col">Delete</th>
        </thead>
        <tbody id="tbody_arr">
          <?php
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              $row_data->preview_image = $this->model->get_image_for_comments_table($row_data->image_id);
              $row_data->preview_image_filename = $this->model->get_image_filename_for_comments_table($row_data->image_id);
              $published_image = (($row_data->published) ? 'publish' : 'unpublish');
              $published = (($row_data->published) ? 'unpublish' : 'publish');
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" type="checkbox" /></td>
                <td class="table_small_col"><?php echo $row_data->id; ?></td>
                <td class="table_big_col ">
                  <?php 
                  if ($row_data->preview_image) {
                  ?>
                  <img title="<?php echo $row_data->preview_image_filename; ?>" style="border: 1px solid #CCCCCC; max-width:60px; max-height:60px;" src="<?php echo site_url() . '/' . $WD_BWG_UPLOAD_DIR . $row_data->preview_image . '?date=' . date('Y-m-y H:i:s'); ?>">
                  <?php
                  }
                  ?>
                </td>
                <td><?php echo $row_data->name; ?></td>
                <td style="cursor:context-menu" ><?php echo $row_data->comment; ?></td>
                <?php
                if ($option_row->popup_enable_email) {
                ?>
                <td><?php echo $row_data->mail; ?></td>
                <?php
                }
                ?>
                <td class="table_big_col"><a onclick="spider_set_input_value('task', '<?php echo $published; ?>');spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');spider_form_submit(event, 'comments_form')" href=""><img src="<?php echo WD_BWG_URL . '/images/' . $published_image . '.png'; ?>" /></a></td>
                <td class="table_big_col"><a onclick="spider_set_input_value('task', 'delete');
                                                      spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                      spider_form_submit(event, 'comments_form')" href="">Delete</a></td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
            }
          }
          ?>
        </tbody>
      </table>
      <input id="task" name="task" type="hidden" value="" />
      <input id="current_id" name="current_id" type="hidden" value="" />
      <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
      <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="<?php echo $asc_or_desc; ?>" />
      <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
    </form>
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