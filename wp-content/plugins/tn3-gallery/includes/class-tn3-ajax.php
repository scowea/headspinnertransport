<?php

require_once (TN3::$dir . 'includes/class-tn3-options.php');

class TN3_Ajax
{
    var $db;

    function __construct()
    {
	add_action('init', array($this, 'wp_init'));
	add_action('wp_ajax_tn3_admin', array($this, 'init'));	
	add_action('wp_ajax_tn3_alt', array($this, 'return_image'));	
	add_action('wp_ajax_nopriv_tn3_alt', array($this, 'return_image'));	
	add_action('wp_ajax_tn3_post_dialog', array($this, 'print_tn3_dialog'));	
    }
    function wp_init()
    {
	load_plugin_textdomain( 'tn3-gallery', false, 'tn3-gallery/lang/' );
	TN3::$o = TN3_Options::get( is_admin() );
    }
    function init()
    {
	extract($_POST);

	if ( isset($bulk_type) ) {
	    if (! wp_verify_nonce($_wpnonce, 'bulk-tn3-'.$bulk_type) )
		$this->jsonit("Not Authorized");

	    //$wpdb->hide_errors();
	    if ( $tn3_action == "save_data" ) $this->save_data($_POST['data']);
	    else if ( $tn3_action == "make_thumb" ) $this->make_thumb($_POST);
	    else call_user_func(array($this, 'do_' . $bulk_type), $_POST);
	} else if ( $tn3_action == 'select' ) {
	    set_current_screen();
	    $this->list_table($doc_type, isset($multi), isset($no_sel_btns), isset($noempty));
	} else if ( $tn3_action == 'load_form' ) {
	    $this->load_form($form);
	}
    }
    function jsonit($v, $err = true)
    {
	$a = array('jsonrpc' => '2.0');
	$a[$err? "error" : "result"] = $v;//$err? TN3::$db->db->last_query : $v;
	die(json_encode($a));
    }
    function do_images($a)
    {
	switch ($a['tn3_action']) {
	case 'add':
	    $r = TN3::$db->relate($a['id'], $a['parent']);
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	case 'arem':
	    $r = TN3::$db->unrelate($a['id'], $a['aid']);
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	case 'del':
	    $r = TN3::$db->delete($a['id'], true);
	    if (false !== $r) {
		//tn3log::w('paths:');
		$s = range(0,5);
		foreach ($r as $path) {
		    $ex = ( ABSPATH . TN3::$o['general']['path'] . $path );
		    if (file_exists($ex)) 
			if (! unlink($ex)) $this->jsonit(__( "Error Deleting File", 'tn3-gallery' ) . ": $ex");
		    foreach ($s as $k) {
			$ex = ( ABSPATH . TN3::$o['general']['path'] . "/$k$path" );
			if (file_exists($ex)) 
			    if (! unlink($ex)) $this->jsonit(__( "Error Deleting File", 'tn3-gallery' ) . ": $ex");
		    }
		}
	    }
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	default:
	    break;
	}
    }
    function do_albums($a)
    {
	switch ($a['tn3_action']) {
	case 'add':
	    $r = TN3::$db->relate($a['id'], $a['parent']);
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	case 'grem':
	    $r = TN3::$db->unrelate($a['id'], $a['gid']);
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	case 'del':
	    $r = TN3::$db->delete($a['id']);
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	default:
	    break;
	}
    }
    function do_galleries($a)
    {
	switch ($a['tn3_action']) {
	case 'del':
	    $r = TN3::$db->delete($a['id']);
	    $this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
	    break;
	default:
	    break;
	}
    }
    // $data - array with keys equal to ids
    function save_data($data)
    {
	$flds = array();
	foreach( $data as $k => $v ) {
	    $flds[$k] = array();
	    foreach( $v as $fname => $d ) {
		$flds[$k][$fname] = $d;
	    }
	}
	$r = TN3::$db->insert_fields( $flds, true );
	$this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
    }
    function make_thumb($data)
    {
	$path = ABSPATH.TN3::$o['general']['path'].$data['path'];
	
	    require_once (TN3::$dir.'includes/admin/class-tn3-image-creator.php');
	    $imgr = new TN3_Image_Creator($path);
	    $imgr->createWithCrop($data['size'], $data['data']);

	    $imgr->destroy();

	$this->jsonit(__( "OK" ), false);
    }
    function do_sort($a)
    {
	$r = TN3::$db->update_rels( $a['parentID'], $a['data'] );
	$this->jsonit(__( (false === $r)? "DB Error" : "OK" ), (false === $r));
    }
    function list_table($doc_type, $multi = false, $no_btns = false, $noempty)
    {
	require_once (TN3::$dir . 'includes/admin/class-tn3-select-list-table.php');
	$sel = new TN3_Select_List_Table($doc_type, $multi, $noempty);
	$sel->prepare_items();
	$sel->search_box( __( 'Search' ), 'tn3' );
	$sel->display();
	$sel->print_tn3_js();
	die();
    }
    function load_form($name)
    {
	$f = file_get_contents(TN3::$dir."includes/forms/$name");
	if ($f === FALSE) $this->jsonit(__('Form file reading error', 'tn3-gallery'), true);
	else $this->jsonit( $f, false );
    }


    function return_image()
    {
	$url = $_GET['u'];
	$c_dir = TN3::$o['general']['path'];
	$url = explode($c_dir, $url);
	$file = ABSPATH.$c_dir.$url[1];

	if ( ! is_file($file) ) {
	    $isize = explode("/", $url[1]);
	    $size = $isize[1];
	    $ofile = ABSPATH.$c_dir.DIRECTORY_SEPARATOR.$isize[2];
	    
	    require_once (TN3::$dir.'includes/admin/class-tn3-image-creator.php');
	    $imgr = new TN3_Image_Creator($ofile);
	    $imgr->create((int)$size, TN3::$o['general']['size_'.$size]);

	    $imgr->destroy();
	}

	$fp = fopen($file, 'rb');

	// send the right headers
	header("Content-Type: image/jpeg");
	header("Content-Length: " . filesize($file));

	// dump the picture and stop the script
	fpassthru($fp);
	exit;
    }

    function print_tn3_dialog()
    {
	$sel_skins = array();
	$r = get_option('tn3_presets_skin');
	foreach ($r as $k => $v) {
	    $sel_skins .= "<option value='$k'>$k</option>";
	}
	$sel_trans = array();
	$rt = get_option('tn3_presets_transition');
	foreach ($rt as $k => $v) {
	    $sel_trans .= "<option value='$k'>$k</option>";
	}
	$popts = get_option('tn3_admin_plugins');
?>
<div id="tn3-dialog" tabindex="-1"><div id="tn3-tabs"> 
	<ul>
	<li><a href="#tn3-tab-source"><?php _e("Source", "tn3-gallery"); ?></a></li>
	<li><a href="#tn3-tab-options"><?php _e("Options", "tn3-gallery"); ?></a></li>
	</ul>

<div id="tn3-tab-source">
    <ul class="tn3-source-nav">
	<li>Images</li>
	<li>Albums</li>

    </ul>
    <div class="tn3-source">
    </div>
</div>

<div id="tn3-tab-options">
<div id="tn3-post-options">
    <div class="left tn3-form-cont">
	<div class="tn3-form-elem">
	<span class="title"><?php _e("Skin preset:", "tn3-gallery"); ?></span>
	    <select id="tn3-select-skin" name="tn3-post-skin"><?php echo $sel_skins; ?></select>
	</div>

	<div class="tn3-form-elem">
	<span class="title"><?php _e("Dimensions:", "tn3-gallery"); ?></span>
	    <span class="input-text-wrap">
		<input type="text" name="tn3-post-width" class="ptitle" value="<?php echo $r['default']['width']; ?>" size="4" /> x
		<input type="text" name="tn3-post-height" class="ptitle" value="<?php echo $r['default']['height']; ?>" size="4" />
	    </span>
	</div>
	<div class="tn3-form-elem">
	<input value="1" type="checkbox" name="tn3-post-responsive" id="tn3-post-responsive"><label for="tn3-post-responsive">  <?php _e("Responsive", "tn3-gallery"); ?></label>
	</div>
    </div>
    <div class="right tn3-form-cont">
	<div class="tn3-form-elem">
	<input value="1" type="checkbox" name="tn3-post-autoplay" id="tn3-post-autoplay"><label for="tn3-post-autoplay"> <?php _e("Slideshow Autoplay", "tn3-gallery"); ?></label>
	</div>
	<div class="tn3-form-elem">
	<input value="1" type="checkbox" name="tn3-post-startWithAlbums" id="tn3-post-startWithAlbums"><label for="tn3-post-startWithAlbums">  <?php _e("Display Albums First", "tn3-gallery"); ?></label>
	</div>

	<div class="tn3-form-elem-click">
	<span class="title"><?php _e("Image click action:", "tn3-gallery"); ?></span>
	    <select id="tn3-image-click" name="tn3-post-imageClick">
	    <option value='next'><?php _e("Next Image", "tn3-gallery"); ?></option>
	    <option value='url'><?php _e("Open URL", "tn3-gallery"); ?></option>
	    <option value='fullscreen'><?php _e("Go Full Screen", "tn3-gallery"); ?></option>
	    </select>
	</div>
    </div>
</div>
</div>

<div class="submitbox">
	<div id="wp-link-cancel" style="font-size:11px;">
	<a class="submitdelete deletion" href="#"><?php _e("Cancel", "tn3-gallery"); ?></a>
	</div>
	<div id="tn3-submit-ok">
	<input type="submit" name="tn3-ok" id="tn3-ok" class="button-primary" value="<?php _e("Insert TN3", "tn3-gallery"); ?>" tabindex="100">
	</div>
</div>
</div></div> 

<?php
	$this->print_tn3_js();
	die();
    }
    function print_tn3_js() {
	$tn = array();	

	echo "\n<script type='text/javascript'>";
	foreach($tn as $k => $v) {
	    echo "tn3.".$k."=".json_encode($v).";";
	}
	echo "tn3.pluginPath=".json_encode(TN3::$url).";";
	require_once (TN3::$dir . 'includes/class-tn3-presets.php');
	echo "tn3.defaults=".json_encode($tn3_plugin_defaults).";";
	$sp = get_option('tn3_presets_skin');
	echo "tn3.skinPresets=".json_encode($sp).";";
	
	echo "</script>\n";
    }

}


?>
