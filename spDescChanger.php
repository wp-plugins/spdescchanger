<?php
/*
 Plugin Name: spDescChanger
 Plugin URI: http://www.scriptpara.de/skripte/spdescchanger/
 Description: Changes your blog description with js
 Author: Sebastian Klaus
 Version: 0.1
 Author URI: http://www.scriptpara.de
 */

// init the plugin
add_action('init', 'spDescChangerInit');

// add admin menu
add_action('admin_menu', 'spDescChangerMenu');

add_action('wp_head', 'spDescChangerHeader');

add_action('wp_footer', 'spDescChangerFooter');

define('SP_PLUGIN_URI', '/wp-content/plugins/spdescchanger');

// init
function spDescChangerInit() {
	wp_enqueue_script( 'jquery' );

	// load language
	load_plugin_textdomain('spDescChanger', SP_PLUGIN_URI.'/languages/');

	// Add entry to config file
	add_option('spDescChanger','', 'spDescChanger descriptions');
}

function spDescChangerMenu(){
	// Add link
	add_options_page(__('spDescChanger settings','spDescChanger'), 'spDescChanger', 9, 'spDescChanger', 'spDescChangerSettings');
}

// display settings options
function spDescChangerSettings(){
	if($_POST['spDescChangerSettings']){
		spDescChangerShowMessage(__('Settings saved','spDescChanger'));
		spDescChangerSaveSettings();
	}

	$settings = spDescChangerGetSettings();
	?>
	<div class="wrap"><h2><? _e('spDescChanger settings','spDescChanger'); ?></h2></div>
	<form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="spDescChangerDescriptions"><? _e('Blog descriptions', 'spDescChanger'); ?></label></th>
				<td>
					<?php
					$spDescChangerDescriptions = (empty($settings->spDescChangerDescriptions)) ? get_bloginfo('description', 'display') : $settings->spDescChangerDescriptions;
					?>
					<textarea id="spDescChangerDescriptions" name="spDescChangerDescriptions" rows="8" cols="60"><?= $spDescChangerDescriptions; ?></textarea>
					<span class="description"><? _e('Every single description in one row', 'spDescChanger'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="spDescChangerInterval"><? _e('Interval', 'spDescChanger'); ?></label></th>
				<td>
					<?php
					$spDescChangerInterval = (empty($settings->spDescChangerInterval)) ? 10 : $settings->spDescChangerInterval;
					?>
					<input type="text" class="small-text" value="<?= $spDescChangerInterval; ?>" id="spDescChangerInterval" name="spDescChangerInterval"/><?= __('sec', 'spDescChanger'); ?>
					<span class="description"><? _e('In which interval the description should change', 'spDescChanger'); ?></span>
				</td>
			</tr>
		</tbody>
	</table><br/><br/>
	<input type="hidden" name="spDescChangerSettings" value="1" />
	<input class="button-primary" type="submit" value="<? _e('Save Changes'); ?>" />
	</form>
	<?
}

// show message after submit
function spDescChangerShowMessage($aMessage, $aClass = 'updated'){
	$result = '<div class="'.$aClass.' fade"><p>'.$aMessage.'</p></div>';
	echo $result;
}

// save the settings
function spDescChangerSaveSettings(){
	$class = new stdClass();
	foreach ($_POST as $key => $entry) {
		$class->$key = $entry;
	}
	update_option('spDescChanger', serialize($class));
}

// get the saved settings from database
function spDescChangerGetSettings(){
	return unserialize(get_option('spDescChanger'));
}

function spDescChangerHeader(){
	echo '<script type="text/javascript" src="'  . get_option('siteurl') . SP_PLUGIN_URI.'/scripts.js"></script>'."\n";
	echo '<script type="text/javascript" src="'  . get_option('siteurl') . SP_PLUGIN_URI.'/jquery.js"></script>'."\n";
}

function spDescChangerFooter(){
	$settings = spDescChangerGetSettings();
	$duration = (empty($settings->spDescChangerInterval)) ? 10 : $settings->spDescChangerInterval;
	$descs = (empty($settings->spDescChangerDescriptions)) ? get_bloginfo('description', 'display') : $settings->spDescChangerDescriptions;
	$descriptions = explode("\n", $descs);
	$descriptions = json_encode($descriptions);
	?>
	<script type="text/javascript">
		spDescChanger(<?= $descriptions; ?>,<?= $duration; ?>);
	</script>
	<?
}