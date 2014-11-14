<?php
/**
Plugin Name: ark-commenteditor
Author: Александр Каратаев
Plugin URI: http://blog.ddw.kz/plagin-ark-wysiwyg-comment-editor-vizualnyj-redaktor-kommentariev.html
Description: Wysiwyg CommentEditor TinyMce Advanced
Version: 1.3
Author URI: http://blog.ddw.kz
License: GPL2
*/
?>
<?php
/*  Copyright 2014  Александр Каратаев  (email : ddw2@yandex.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
register_activation_hook(__FILE__, 'ark_commenteditor_activation');
 
function ark_commenteditor_activation() {
// действие при активации
ark_wce_init_option();
// регистрируем действие при удалении
register_uninstall_hook(__FILE__, 'ark_commenteditor_uninstall');
}
 
function ark_commenteditor_uninstall(){
//действие при удалении
delete_option( 'ark_wce' ); 
}
add_action('plugins_loaded', 'init_lang');
function init_lang() {
	 load_plugin_textdomain( 'arkcommenteditor', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
//Remove comment form HTML tags and attributes
function ark_remove_comment_form_allowed_tags( $defaults ) {
$defaults['comment_notes_after'] = '';
return $defaults;
}
add_filter( 'comment_form_defaults', 'ark_remove_comment_form_allowed_tags' );
// Админ панель
//Опции по умолчанию
function ark_wce_init_option() {
$ark_wce_option = array(
'btn_undo' => '1',
'btn_redo' => '1',
'btn_bold' => '1',
'btn_italic' => '1',
'btn_underline' => '0',
'btn_strikethrough' => '0',
'btn_forecolor' => '0',
'btn_backcolor' => '0',
'btn_link' => '0',
'btn_image' => '0',
'btn_blockquote' => '1',
'btn_code' => '1',
'btn_bullist' => '0',
'btn_numlist' => '0',
'btn_table' => '0',
'btn_emoticons' => '1',
'btn_arkemoticons' => '0',
'btn_arkemoticonssk' => '0',
'btn_arkkbabe' => '0',
'btn_preview' => '1',
'wce_lang' => 'русский',
);
add_option('ark_wce', $ark_wce_option,'','no');
}
// Хук вставки в админ меню
add_action('admin_menu', 'ark_wce_add_pages');
// Акция предыдущено хука
function ark_wce_add_pages() {
    // Добавляем новое субменю в Options:
    add_options_page('ark_commenteditor', 'ARK WYSIWYG Comment Editor', 8, 'ark_wce_ostoptions', 'ark_wce_options_page');
}
// Вывод страницы опций в субменю
function ark_wce_options_page() {
	screen_icon('users');
    echo '<h2>'.__('Plugin','arkcommenteditor').'&nbsp;ARK WYSIWYG Comment Editor&nbsp;1.3</h2><div style="clear: both;float:right; padding-right:20px;"><noindex><a rel="nofollow" href="http://blog.ddw.kz/podderzhka-proektov-avtora-etogo-bloga
" target="_blank"><img align="right" src="' . plugins_url( '/img/donate.png', __FILE__ ) . '" alt="Пожертвовать" border="0" /></a></noindex></div>';
?>	
<div class="wrap">
<h2><?php _e('Settings visual editor comments','arkcommenteditor'); ?></h2>
<?php // Пошла обработка запроса
if (isset($_POST['save'])) {
$ark_wce_option = array(
'btn_undo' => $_POST['btn_undo'],
'btn_redo' => $_POST['btn_redo'],
'btn_bold' => $_POST['btn_bold'],
'btn_italic' => $_POST['btn_italic'],
'btn_underline' => $_POST['btn_underline'],
'btn_strikethrough' => $_POST['btn_strikethrough'],
'btn_forecolor' => $_POST['btn_forecolor'],
'btn_backcolor' => $_POST['btn_backcolor'],
'btn_link' => $_POST['btn_link'],
'btn_image' => $_POST['btn_image'],
'btn_blockquote' => $_POST['btn_blockquote'],
'btn_code' => $_POST['btn_code'],
'btn_bullist' => $_POST['btn_bullist'],
'btn_numlist' => $_POST['btn_numlist'],
'btn_table' => $_POST['btn_table'],
'btn_emoticons' => $_POST['btn_emoticons'],
'btn_arkemoticons' => $_POST['btn_arkemoticons'],
'btn_arkemoticonssk' => $_POST['btn_arkemoticonssk'],
'btn_arkkbabe' => $_POST['btn_arkkbabe'],
'btn_preview' => $_POST['btn_preview'],
'wce_lang' => $_POST['wce_lang'],
);
update_option('ark_wce', $ark_wce_option);
echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><b>'.__('Settings saved.','arkcommenteditor').'</b></p></div>';
	
} else if ( isset($_POST['reset']) ) {   
      // При сбросе: удаляем записи опций из БД  
 	     delete_option( 'ark_wce' ); 
		 ark_wce_init_option();
  	  echo '<div id="message" class="updated fade"><p><strong>' . 
               __('Settings successfully restored the default.','arkcommenteditor') .
               '</strong></p></div>';
 
      } 
	  
?>
<form method="post">
<?php wp_nonce_field('update-options'); 
$result = get_option('ark_wce');
?>
<h3><?php _e('Language frontend Editor','arkcommenteditor'); ?></h3>
<table>
<tr>
<td>
<select size="1" name="wce_lang">
    <option <?php if ($result['wce_lang'] == "русский") { echo "selected"; } ?> value="русский">Русский</option>
    <option <?php if ($result['wce_lang'] == "english") { echo "selected"; } ?> value="english">English</option>
</select>
</td><tr></table><hr>
<h3><?php _e('Editor button','arkcommenteditor'); ?></h3>
<table>
<tr>
<td>
<img src="<?php echo plugins_url( '/img/undo.png', __FILE__ ); ?>" title="<?php _e('Undo','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_undo" value="1" <?php if ($result['btn_undo'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td><td>
<img src="<?php echo plugins_url( '/img/redo.png', __FILE__ ); ?>" title="<?php _e('Redo','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_redo" value="1" <?php if ($result['btn_redo'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/bold.png', __FILE__ ); ?>" title="<?php _e('Bold','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_bold" value="1" <?php if ($result['btn_bold'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/italic.png', __FILE__ ); ?>" title="<?php _e('Italic','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_italic" value="1" <?php if ($result['btn_italic'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/underline.png', __FILE__ ); ?>" title="<?php _e('Underline','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_underline" value="1" <?php if ($result['btn_underline'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/strikethrough.png', __FILE__ ); ?>" title="<?php _e('Strikethrough','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_strikethrough" value="1" <?php if ($result['btn_strikethrough'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/forecolor.png', __FILE__ ); ?>" title="<?php _e('Forecolor','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_forecolor" value="1" <?php if ($result['btn_forecolor'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/backcolor.png', __FILE__ ); ?>" title="<?php _e('Backcolor','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_backcolor" value="1" <?php if ($result['btn_backcolor'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
</tr></table><br><hr><table><tr>
<td>
<img src="<?php echo plugins_url( '/img/link.png', __FILE__ ); ?>" title="<?php _e('Link','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_link" value="1" <?php if ($result['btn_link'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/image.png', __FILE__ ); ?>" title="<?php _e('Image','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_image" value="1" <?php if ($result['btn_image'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/blockquote.png', __FILE__ ); ?>" title="<?php _e('Blockquote','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_blockquote" value="1" <?php if ($result['btn_blockquote'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/code.png', __FILE__ ); ?>" title="<?php _e('Code','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_code" value="1" <?php if ($result['btn_code'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/bullist.png', __FILE__ ); ?>" title="<?php _e('Bullist','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_bullist" value="1" <?php if ($result['btn_bullist'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/numlist.png', __FILE__ ); ?>" title="<?php _e('Numlist','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_numlist" value="1" <?php if ($result['btn_numlist'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/table.png', __FILE__ ); ?>" title="<?php _e('Table','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_table" value="1" <?php if ($result['btn_table'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/emoticons.png', __FILE__ ); ?>" title="<?php _e('Emoticons','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_emoticons" value="1" <?php if ($result['btn_emoticons'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<img src="<?php echo plugins_url( '/img/preview.png', __FILE__ ); ?>" title="<?php _e('Preview','arkcommenteditor'); ?>" valign="top">  <input type="checkbox" name="btn_preview" value="1" <?php if ($result['btn_preview'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td></tr>
</table><hr>
<h3><?php _e('Additional set of smileys','arkcommenteditor'); ?></h3>
<table>
<tr>
<td>
<img src="<?php echo plugins_url( '/img/qip.png', __FILE__ ); ?>" title="qip" valign="top">
</td>
<td>
<img src="<?php echo plugins_url( '/img/skype.png', __FILE__ ); ?>" title="skype" valign="top">
</td>
<td>
<img src="<?php echo plugins_url( '/img/k-babe.png', __FILE__ ); ?>" title="k-babe" valign="top">
</td>
</tr>
<tr>
<td>
<?php _e('From a set of emoticons QIP','arkcommenteditor'); ?> <input type="checkbox" name="btn_arkemoticons" value="1" <?php if ($result['btn_arkemoticons'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<?php _e('From a set of emoticons Skype','arkcommenteditor'); ?> <input type="checkbox" name="btn_arkemoticonssk" value="1" <?php if ($result['btn_arkemoticonssk'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
<td>
<?php _e('From a set of emoticons k-babe','arkcommenteditor'); ?><input type="checkbox" name="btn_arkkbabe" value="1" <?php if ($result['btn_arkkbabe'] == 1) { echo "checked"; } ?>/> &nbsp;&nbsp;
</td>
</tr>
</table><hr>
<p class="submit">
<input type="submit" name="save" class="button-primary" value="<?php _e('Save Changes','arkcommenteditor') ?>" />
<input name="reset" type="submit" class="button-primary" value="<?php _e('Restore Default Settings','arkcommenteditor') ?>" />
</p>

</form>
</div>	
<?php
}
// Функции плагина
/*Tiny MCE for comment*/
add_action( 'comment_form', 'tinyMCE_comment_form');
function tinyMCE_comment_form() {
$result = get_option('ark_wce');
$wceplugins = "paste, autoresize";
$wceexplugins = '';
$kvobtn = 0;
$bar1 = '';
if ($result['btn_undo'] == 1) { $bar1 = $bar1 . ' undo '; $kvobtn++; }
if ($result['btn_redo'] == 1) { $bar1 = $bar1 . ' redo'; $kvobtn++; }
if ($bar1!='') { $bar1 = $bar1 .  ' | '; }
$bar2 = '';
if ($result['btn_bold'] == 1) { $bar2 = $bar2 . ' bold'; $kvobtn++; }
if ($result['btn_italic'] == 1) { $bar2 = $bar2 . ' italic'; $kvobtn++; }
if ($result['btn_underline'] == 1) { $bar2 = $bar2 . ' underline'; $kvobtn++; }
if ($result['btn_strikethrough'] == 1) { $bar2 = $bar2 . ' strikethrough'; $kvobtn++; }
if ($result['btn_forecolor'] == 1) { $bar2 = $bar2 . ' forecolor'; $kvobtn++; $wceplugins = $wceplugins . ', textcolor'; }
if ($result['btn_backcolor'] == 1) { $bar2 = $bar2 . ' backcolor'; $kvobtn++; }
if ($bar2!='') { $bar2 = $bar2 .  ' | '; }
$bar3 = '';
if ($result['btn_link'] == 1) { $bar3 = $bar3 . ' link'; $kvobtn++; $wceplugins = $wceplugins . ', link'; }
if ($result['btn_image'] == 1) { $bar3 = $bar3 . ' image'; $kvobtn++; $wceplugins = $wceplugins . ', image'; }
if ($bar3!='') { $bar3 = $bar3 .  ' | '; }
if ($kvobtn >=10) { $bar4 = '","'; $kvobtn = 0;} else { $bar4 = ''; }
if ($result['btn_blockquote'] == 1) { $bar4 = $bar4 . ' blockquote'; $kvobtn++; }
if ($result['btn_code'] == 1) { $bar4 = $bar4 . ' code'; $kvobtn++; $wceplugins = $wceplugins . ', code'; }
if ($bar4!='","' && $bar4!='') { $bar4 = $bar4 .  ' | '; }
// Вторая панель
if ($kvobtn >=10) { $bar5 = '","'; $kvobtn = 0;} else { $bar5 = ''; }
if ($result['btn_bullist'] == 1) { $bar5 = $bar5 . ' bullist'; $kvobtn++; }
if ($result['btn_numlist'] == 1) { $bar5 = $bar5 . ' numlist'; $kvobtn++; }
if ($bar5!='","' && $bar5!='') { $bar5 = $bar5 .  ' | '; }
if ($kvobtn >=10) { $bar6 = '","'; $kvobtn = 0;} else { $bar6 = ''; }
if ($result['btn_table'] == 1) { $bar6 = $bar6 . ' table'; $kvobtn++; $wceplugins = $wceplugins . ', table'; }
if ($bar6!='","' && $bar6!='') { $bar6 = $bar6 .  ' | '; }
$bar7 = '';
if ($result['btn_emoticons'] == 1) { $bar7 = $bar7 . ' emoticons'; $kvobtn++; $wceplugins = $wceplugins . ', emoticons'; }
if ($result['btn_arkemoticons'] == 1) { $bar7 = $bar7 . ' arkemoticons'; $kvobtn++; $wceplugins = $wceplugins . ', arkemoticons'; 
$wceexplugins = '"arkemoticons" :  "'.plugins_url( '/plugins/arkemoticons/plugin.min.js',__FILE__ ) .'"';
}
if ($result['btn_arkemoticonssk'] == 1) { $bar7 = $bar7 . ' arkemoticonssk'; $kvobtn++; $wceplugins = $wceplugins . ', arkemoticonssk'; 
if ($wceexplugins != '') {$wceexplugins = $wceexplugins . ', ';}
$wceexplugins = $wceexplugins . '"arkemoticonssk" :  "'.plugins_url( '/plugins/arkemoticonssk/plugin.min.js',__FILE__ ) .'"';
}
if ($result['btn_arkkbabe'] == 1) { $bar7 = $bar7 . ' arkkbabe'; $kvobtn++; $wceplugins = $wceplugins . ', arkkbabe'; 
if ($wceexplugins != '') {$wceexplugins = $wceexplugins . ', ';}
$wceexplugins = $wceexplugins . '"arkkbabe" :  "'.plugins_url( '/plugins/arkkbabe/plugin.min.js',__FILE__ ) .'"';
}
if ($result['btn_preview'] == 1) { $bar7 = $bar7 . ' preview'; $kvobtn++; $wceplugins = $wceplugins . ', preview'; }
if ($bar7!='') { $bar7 = $bar7 .  ' | '; }
// Формируем тулбары
$toolbar = $bar1 . $bar2 . $bar3 . $bar4 . $bar5 . $bar6 . $bar7 ;
$toolbar = '["' . $toolbar . '"]';
//echo $toolbar;
// формируем external_plugins
if ($wceexplugins != '') {
$wceexplugins = 'external_plugins :  { ' . $wceexplugins . ' }, ';
}
// Формируем языковые настройки
if ($result['wce_lang']=="english") { $wcelang = '';} else {$wcelang = 'language_url : "'.plugins_url( '/js/ru.js',__FILE__ ) .'",';}

$myeditor = '
	<script type="text/javascript" src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
	<script type="text/javascript">
	tinymce.init({
		selector: "textarea#comment",
		' . $wcelang . '
		menubar : false,
		statusbar : false,
		convert_urls : false,
		browser_spellcheck : true,
		paste_as_text: true,
		plugins: "' . $wceplugins . '",
		' . $wceexplugins . '
		toolbar: ' . $toolbar . '
	});
</script>
';
echo $myeditor;
}

add_action( 'wp_enqueue_scripts', 'graphene_scripts' );
function graphene_scripts() {
	wp_enqueue_script('jquery');
}

add_filter( 'comment_reply_link', 'graphene_comment_reply_link' );
function graphene_comment_reply_link($link) {
	return str_replace( 'onclick=', 'data-onclick=', $link );
}

add_action( 'wp_head', 'graphene_wp_head' );
function graphene_wp_head() {
?>
	<script type="text/javascript">
		jQuery(function($){
			$('.comment-reply-link').click(function(e){
				e.preventDefault();
				var args = $(this).data('onclick');
				args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
				args = args.split(',');
				tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
				addComment.moveForm.apply( addComment, args );
				tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
			});
		});

	</script>
<?php
}
/*END OF Tiny MCE for comment*/

function ark_pre_kses( $string ) {
   global $allowedtags;
	$allowedtags['img'] = array( 'src' => true, 'height' => true,'width' => true, 'alt' => true, 'title' => true, );
	$allowedtags['table'] = array( 'border' => true, 'style' => array() );
	$allowedtags['tbody'] = array('style' => array() );
	$allowedtags['tr'] = array('style' => array() );
	$allowedtags['td'] = array('style' => array() );
	$allowedtags['sub'] = array( );
	$allowedtags['sup'] = array();
	$allowedtags['pre'] = array('lang'  =>  true, 'line'  =>  true );	
	$allowedtags['ul'] = array('style' => array());
	$allowedtags['ol'] = array('style' => array());
	$allowedtags['li'] = array('style' => array());
	$allowedtags['span'] = array('class' => true, 'style' => array() );
	$allowedtags['noindex'] = array();
	$allowedtags['strong'] = array(); 
	$allowedtags['a'] = array('target' => true, 'href' => true, 'title' => true, ); 	
	return $string;
}
add_filter('pre_kses', 'ark_pre_kses');

?>