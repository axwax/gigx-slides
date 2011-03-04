<?php
/**
 * Create meta box for editing pages in WordPress
 *
 * Compatible with custom post types since WordPress 3.0
 * Support input types: text, textarea, checkbox, checkbox list, radio box, select, wysiwyg, file, image, date, time, color
 *
 * @author: Rilwis
 * @url: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 * @usage: please read document at project homepage and meta-box-usage.php file
 * @version: 3.0
 */

// Ajax delete files on the fly. Modified from a function used by "Verve Meta Boxes" plugin (http://goo.gl/LzYSq)
add_action('wp_ajax_rw_delete_file', 'rw_delete_file');
function rw_delete_file() {
	if (!isset($_POST['data'])) return;
	list($attach_id, $nonce) = explode('-', $_POST['data']);
	if (!wp_verify_nonce($nonce, 'rw_ajax_delete_file')) {
		_e('You don\'t have permission to delete this file.');
	}
	wp_delete_attachment($attach_id);
	_e('File has been successfully deleted.');
	die();
}

/**
 * Meta Box class
 */
class RW_Meta_Box {

	protected $_meta_box;

	// Create meta box based on given data
	function __construct($meta_box) {
		if (!is_admin()) return;

		// assign meta box value to local variable and add it's missed values
		$this->_meta_box = $meta_box;
		$this->add_missed_values();

		add_action('admin_menu', array(&$this, 'add'));	// add meta box
		add_action('save_post', array(&$this, 'save'));	// save meta box's data

		// check for some special fields and add needed actions for them
		$this->check_field_upload();
		$this->check_field_color();
		$this->check_field_date();
		$this->check_field_time();
	}

	/******************** BEGIN UPLOAD **********************/

	// Check field upload and add needed actions
	function check_field_upload() {
		if ($this->has_field('image') || $this->has_field('file')) {
			add_action('post_edit_form_tag', array(&$this, 'add_enctype'));				// add data encoding type for file uploading
			add_action('admin_head-post.php', array(&$this, 'add_script_upload'));		// add scripts for handling add/delete images
			add_action('admin_head-post-new.php', array(&$this, 'add_script_upload'));
			add_action('delete_post', array(&$this, 'delete_attachments'));				// delete all attachments when delete post
		}
	}

	// Add data encoding type for file uploading
	function add_enctype() {
		echo ' enctype="multipart/form-data"';
	}

	// Add scripts for handling add/delete images
	function add_script_upload() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// add more file
			$(".rw-add-file").click(function(){
				var $first = $(this).parent().find(".file-input:first");
				$first.clone().insertAfter($first).show();
				return false;
			});

			// delete file
			$(".rw-delete-file").click(function(){
				var $parent = $(this).parent(),
					data = $(this).attr("rel");
				$.post(ajaxurl, {action: \'rw_delete_file\', data: data}, function(response){
					$parent.fadeOut("slow");
					alert(response);
				});
				return false;
			});
		});
		</script>';
	}

	// Delete all attachments when delete post
	function delete_attachments($post_id) {
		$attachments = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'attachment',
			'post_parent' => $post_id
		));
		if (!empty($attachments)) {
			foreach ($attachments as $att) {
				wp_delete_attachment($att->ID);
			}
		}
	}

	/******************** END UPLOAD **********************/

	/******************** BEGIN COLOR PICKER **********************/

	// Check field color
	function check_field_color() {
		if ($this->has_field('color') && $this->is_edit_page()) {
			wp_enqueue_style('farbtastic');									// enqueue built-in script and style for color picker
			wp_enqueue_script('farbtastic');
			add_action('admin_head', array(&$this, 'add_script_color'));	// add our custom script for color picker
		}
	}

	// Custom script for color picker
	function add_script_color() {
		$ids = array();
		foreach ($this->_meta_box['fields'] as $field) {
			if ('color' == $field['type']) {
				$ids[] = $field['id'];
			}
		}
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
		';
		foreach ($ids as $id) {
			echo "
			$('#picker-$id').farbtastic('#$id');
			$('#select-$id').click(function(){
				$('#picker-$id').toggle();
				return false;
			});
			";
		}
		echo '
		});
		</script>
		';
	}

	/******************** END COLOR PICKER **********************/

	/******************** BEGIN DATE PICKER **********************/

	// Check field date
	function check_field_date() {
		if ($this->has_field('date') && $this->is_edit_page()) {
			// add style and script, must use jQuery UI 1.7.3 to get rid of confliction with WP admin scripts
			wp_enqueue_style('rw-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.3/themes/base/jquery-ui.css');
			wp_enqueue_script('rw-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.3/jquery-ui.min.js', array('jquery'));
			add_action('admin_head', array(&$this, 'add_script_date'));
		}
	}

	// Custom script for date picker
	function add_script_date() {
		$dates = array();
		foreach ($this->_meta_box['fields'] as $field) {
			if ('date' == $field['type']) {
				$dates[$field['id']] = isset($field['format']) ? $field['format'] : 'yy-mm-dd';
			}
		}
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
		';
		foreach ($dates as $id => $format) {
			echo "$('#$id').datepicker();
				  $('#$id').datepicker('option', 'dateFormat', '$format');";
		}
		echo '
		});
		</script>
		';
	}

	/******************** END DATE PICKER **********************/

	/******************** BEGIN TIME PICKER **********************/

	// Check field time
	function check_field_time() {
		if ($this->has_field('time') && $this->is_edit_page()) {
			// add style and script, must use jQuery UI 1.7.3 to get rid of confliction with WP admin scripts
			wp_enqueue_style('rw-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.3/themes/base/jquery-ui.css');
			wp_enqueue_script('rw-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.3/jquery-ui.min.js', array('jquery'));
			wp_enqueue_script('rw-timepicker', 'https://github.com/trentrichardson/jQuery-Timepicker-Addon/raw/master/jquery-ui-timepicker-addon.js', array('rw-jquery-ui'));
			add_action('admin_head', array(&$this, 'add_script_time'));
		}
	}

	// Custom script and style for time picker
	function add_script_time() {
		// style
		echo '
		<style type="text/css">
		.ui-timepicker-div {font-size: 0.9em;}
		.ui-timepicker-div .ui-widget-header {margin-bottom: 8px;}
		.ui-timepicker-div dl {text-align: left;}
		.ui-timepicker-div dl dt {height: 25px;}
		.ui-timepicker-div dl dd {margin: -25px 0 10px 65px;}
		.ui-timepicker-div td {font-size: 90%;}
		</style>
		';

		// script
		$times = array();
		foreach ($this->_meta_box['fields'] as $field) {
			if ('time' == $field['type']) {
				$times[$field['id']] = isset($field['format']) ? $field['format'] : 'hh:mm';
			}
		}
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
		';
		foreach ($times as $id => $format) {
			echo "$('#$id').timepicker({showSecond: true, timeFormat: '$format'})";
		}
		echo '
		});
		</script>
		';
	}

	/******************** END TIME PICKER **********************/

	/******************** BEGIN META BOX PAGE **********************/

	// Add meta box for multiple post types
	function add() {
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
		}
	}

	// Callback function to show fields in meta box
	function show() {
		global $post;

		wp_nonce_field(basename(__FILE__), 'rw_meta_box_nonce');
		echo '<table class="form-table">';

		foreach ($this->_meta_box['fields'] as $field) {
			$meta = get_post_meta($post->ID, $field['id'], !$field['multiple']);
			$meta = !empty($meta) ? $meta : $field['std'];

			echo '<tr>';
			// call separated methods for displaying each type of field
			call_user_func(array(&$this, 'show_field_' . $field['type']), $field, $meta);
			echo '</tr>';
		}
		echo '</table>';
	}

	/******************** END META BOX PAGE **********************/

	/******************** BEGIN META BOX FIELDS **********************/

	function show_field_text($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><input type='text' name='{$field['id']}' value='$meta' size='30' style='width:97%' /><br />{$field['desc']}</td>";
	}

	function show_field_textarea($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><textarea name='{$field['id']}' cols='60' rows='15' style='width:97%'>$meta</textarea><br />{$field['desc']}</td>";
	}

	function show_field_select($field, $meta) {
		if (!is_array($meta)) $meta = (array) $meta;
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><select name='{$field['id']}" . ($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";
		foreach ($field['options'] as $key => $value) {
			echo "<option value='$key'" . selected(in_array($key, $meta), true, false) . ">$value</option>";
		}
		echo "</select><br />{$field['desc']}</td>";
	}

	function show_field_radio($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td>";
		foreach ($field['options'] as $key => $value) {
			echo "<input type='radio' name='{$field['id']}' value='$key'" . checked($meta, $key, false) . " /> $value ";
		}
		echo "<br />{$field['desc']}</td>";
	}

	function show_field_checkbox($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><input type='checkbox' name='{$field['id']}'" . checked(!empty($meta), true, false) . " /> {$field['desc']}</td>";
	}

	function show_field_wysiwyg($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><textarea name='{$field['id']}' class='theEditor' cols='60' rows='15' style='width:97%'>$meta</textarea><br />{$field['desc']}</td>";
	}

	function show_field_file($field, $meta) {
		global $post;

		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td>{$field['desc']}<br />";

		// show attached files
		$attachs = get_posts(array(
			'post_type' => 'attachment',
			'post_parent' => $post->ID,
			'numberposts' => -1
		));
		if (!empty($attachs)) {
			$nonce = wp_create_nonce('rw_ajax_delete_file');

			echo '<div style="margin-bottom: 10px"><strong>' . _('Files attached to this post') . '</strong></div>';
			echo '<ol>';
			foreach ($attachs as $att) {
				if (wp_attachment_is_image($att->ID)) continue; // what's image uploader for?

				echo "<li>" . wp_get_attachment_link($att->ID) . " (<a class='rw-delete-file' href='javascript:void(0)' rel='{$att->ID}-{$nonce}'>Delete</a>)</li>";
			}
			echo '</ol>';
		}

		// show form upload
		echo "<div style='clear: both'><strong>" . _('Upload new files') . "</strong></div>
			<div class='new-files'>
				<div class='file-input'><input type='file' name='{$field['id']}[]' /></div>
				<a class='rw-add-file' href='javascript:void(0)'>" . _('Add more file') . "</a>
			</div>
		</td>";
	}

	function show_field_image($field, $meta) {
		global $post;

		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td>{$field['desc']}<br />";

		// show attached images
		$attachs = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'attachment',
			'post_parent' => $post->ID,
			'post_mime_type' => 'image', // get attached images only
			'output' => ARRAY_A
		));
		if (!empty($attachs)) {
			$nonce = wp_create_nonce('rw_ajax_delete_file');

			echo '<div style="margin-bottom: 10px"><strong>' . _('Images attached to this post') . '</strong></div>';
			foreach ($attachs as $att) {
				$src = wp_get_attachment_image_src($att->ID, 'thumbnail');
				$src = $src[0];
				echo "<div style='margin: 0 10px 10px 0; float: left'><img width='150' src='$src' /><br />
						<a class='rw-delete-file' href='javascript:void(0)' rel='{$att->ID}-{$nonce}'>Delete</a>
					</div>";
			}
		}

		// show form upload
		echo "<div style='clear: both'><strong>" . _('Upload new images') . "</strong></div>
			<div class='new-files'>
				<div class='file-input'><input type='file' name='{$field['id']}[]' /></div>
				<a class='rw-add-file' href='javascript:void(0)'>" . _('Add more image') . "</a>
			</div>
		</td>";
	}

	function show_field_color($field, $meta) {
		if (empty($meta)) $meta = '#';
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
		<td>
			<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='8' />
			<a href='#' id='select-{$field['id']}'>" . _('Select a color') . "</a>
			<div style='display:none' id='picker-{$field['id']}'></div><br />
			{$field['desc']}
		</td>";
	}

	function show_field_checkbox_list($field, $meta) {
		if (!is_array($meta)) $meta = (array) $meta;
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td>";
		foreach ($field['options'] as $key => $value) {
			echo "<input type='checkbox' name='{$field['id']}[]' value='$key'" . checked(in_array($key, $meta), true, false) . " /> $value<br/>";
		}
		echo "{$field['desc']}</td>";
	}

	function show_field_date($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='20' /><br />{$field['desc']}</td>";
	}

	function show_field_time($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
			  <td><input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='20' /><br />{$field['desc']}</td>";
	}

	/******************** END META BOX FIELDS **********************/

	/******************** BEGIN META BOX SAVE **********************/

	// Save data from meta box
	function save($post_id) {
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)						// check autosave
		|| (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])			// check revision
		|| (!check_admin_referer(basename(__FILE__), 'rw_meta_box_nonce'))) {	// verify nonce
			return $post_id;
		}

		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) return $post_id;

		foreach ($this->_meta_box['fields'] as $field) {
			$name = $field['id'];
			$type = $field['type'];
			$old = get_post_meta($post_id, $name, !$field['multiple']);
			$new = isset($_POST[$name]) ? $_POST[$name] : ($field['multiple'] ? array() : '');

			// validate meta value
			if (class_exists('RW_Meta_Box_Validate') && method_exists('RW_Meta_Box_Validate', $field['validate_func'])) {
				$new = call_user_func(array('RW_Meta_Box_Validate', $field['validate_func']), $new);
			}

			// call defined method to save meta value, if there's no methods, call common one
			$save_func = 'save_field_' . $type;
			if (method_exists($this, $save_func)) {
				call_user_func(array(&$this, 'save_field_' . $type), $post_id, $field, $old, $new);
			} else {
				$this->save_field($post_id, $field, $old, $new);
			}
		}
	}

	// Common functions for saving field
	function save_field($post_id, $field, $old, $new) {
		$name = $field['id'];

		// single value
		if (!$field['multiple']) {
			if ('' != $new && $new != $old) {
				update_post_meta($post_id, $name, $new);
			} elseif ('' == $new) {
				delete_post_meta($post_id, $name, $old);
			}
			return;
		}

		// multiple values
		// get new values that need to add and get old values that need to delete
		$add = array_diff($new, $old);
		$delete = array_diff($old, $new);
		foreach ($add as $add_new) {
			add_post_meta($post_id, $name, $add_new, false);
		}
		foreach ($delete as $delete_old) {
			delete_post_meta($post_id, $name, $delete_old);
		}
	}

	function save_field_textarea($post_id, $field, $old, $new) {
		$new = htmlspecialchars($new);
		$this->save_field($post_id, $field, $old, $new);
	}

	function save_field_wysiwyg($post_id, $field, $old, $new) {
		$new = wpautop($new);
		$this->save_field($post_id, $field, $old, $new);
	}

	function save_field_file($post_id, $field, $old, $new) {
		$name = $field['id'];
		if (empty($_FILES[$name])) return;

		$this->fix_file_array($_FILES[$name]);

		foreach ($_FILES[$name] as $position => $fileitem) {
			$file = wp_handle_upload($fileitem, array('test_form' => false));

			if (empty($file['file'])) continue;
			$filename = $file['file'];

			$attachment = array(
				'post_mime_type' => $file['type'],
				'guid' => $file['url'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
				'post_content' => ''
			);
			$id = wp_insert_attachment($attachment, $filename, $post_id);
			if (!is_wp_error($id)) {
				wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $filename));
			}
		}
	}

	// Save images, call save_field_file, cause they use the same mechanism
	function save_field_image($post_id, $field, $old, $new) {
		$this->save_field_file($post_id, $field, $old, $new);
	}

	/******************** END META BOX SAVE **********************/

	/******************** BEGIN HELPER FUNCTIONS **********************/

	// Add missed values for meta box
	function add_missed_values() {
		// default values for meta box
		$this->_meta_box = array_merge(array(
			'context' => 'normal',
			'priority' => 'high',
			'pages' => array('post')
		), $this->_meta_box);

		// default values for fields
		foreach ($this->_meta_box['fields'] as $key => $field) {
			$multiple = ('checkbox_list' == $field['type']) ? true : false;
			$std = $multiple ? array() : '';
			$this->_meta_box['fields'][$key] = array_merge(array(
				'multiple' => $multiple,
				'std' => $std,
				'desc' => '',
				'validate_func' => ''
			), $field);
		}
	}

	// Check if field with $type exists
	function has_field($type) {
		foreach ($this->_meta_box['fields'] as $field) {
			if ($type == $field['type']) return true;
		}
		return false;
	}

	// Check if current page is edit page
	function is_edit_page() {
		global $pagenow;
		if (in_array($pagenow, array('post.php', 'post-new.php'))) return true;
		return false;
	}

	/**
	 * Fixes the odd indexing of multiple file uploads from the format:
	 *     $_FILES['field']['key']['index']
	 * To the more standard and appropriate:
	 *     $_FILES['field']['index']['key']
	 */
	function fix_file_array(&$files) {
		$output = array();
		foreach ($files as $key => $list) {
			foreach ($list as $index => $value) {
				$output[$index][$key] = $value;
			}
		}
		$files = $output;
	}

	/******************** END HELPER FUNCTIONS **********************/
}

?>
