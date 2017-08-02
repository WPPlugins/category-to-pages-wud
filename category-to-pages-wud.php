<?php
/* 
=== Category to Pages WUD ===
Contributors: wistudat.be
Plugin Name: Category to Pages WUD
Donate Reason: Stand together to help those in need!
Donate link: https://www.icrc.org/eng/donations/
Description: Unique Page Categories and Page Tags.
Author: Danny WUD
Author URI: https://wud-plugins.com
Plugin URI: https://wud-plugins.com
Tags: category pages, categories page, categories pages, category to page, page category, page categories, pages category, pages categories, tags page, tag pages, category, categories, tag, tags, page, pages, tag to page, add category, add tag
Requires at least: 3.6
Tested up to: 4.8
Stable tag: 2.2.0
Version: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: category-to-pages-wud
Domain Path: /languages
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//==============================================================================//
$ctp_version='2.2.0';
// Store the latest version.
if (get_option('pcwud_wud_version')!=$ctp_version) {pcwud_wud_update();}
//==============================================================================//
global $template;
// Actions
		add_action( 'plugins_loaded', 'cattopage_wud_admin' );
		add_action( 'plugins_loaded', 'cattopage_wud_shortcode' );
		add_action( 'plugins_loaded', 'cattopage_wud_regterms' );
		add_action( 'plugins_loaded', 'cattopage_wud_widgets' );
		add_action( 'plugins_loaded', 'cattopage_wud_options' );
		add_action('admin_menu', 'cattopage_wud_create_menu');
		add_filter( 'plugin_action_links', 'cattopage_wud_action_links', 10, 5 );
		add_action('admin_enqueue_scripts', 'cattopage_wud_styling');
		add_action('init', 'cattopage_wud_site_page');
		add_action('plugins_loaded', 'cattopage_wud_languages');
		add_action('wp_head', 'show_wud_cattopage_template');
		add_filter("the_content", "cattopage_change_to_excerpt");
		add_action( 'init', 'my_add_excerpts_to_pages' );
		add_shortcode('wudcatlist', 'cattopage_short_code_cat_list');
		add_shortcode('wudtaglist', 'cattopage_short_code_tag_list');
		add_shortcode('wudcatdrop', 'cattopage_short_code_cat_drop');
		add_shortcode('wudtagdrop', 'cattopage_short_code_tag_drop');
		
//Use the same categories and tags for pages, as it is for post.		
	if(get_option('cattopage_wud_unique')=="0"){
		add_action('init', 'cattopage_wud_reg_cat_tag');
		add_action('plugins_loaded','cattopage_wud_reg_cat_tag');
		if ( ! is_admin()) {
			add_action( 'pre_get_posts', 'cattopage_wud_cat_tag_archives' );
		}		
	}	
	
//Show Category and ord tag link on pages
	if ( ! is_admin()) {
		//below the tiltle
		if (get_option('cattopage_wud_index_pos')==0){
			add_filter( 'the_title', 'cattopage_wud_titles', 10, 2);
		}
		//above the content
		elseif (get_option('cattopage_wud_index_pos')==1){
			add_filter ('the_content', 'cattopage_wud_titles_in_page');
		}
		else{
			add_filter( 'the_title', 'cattopage_wud_titles', 10, 2);
		}
		
	}
	
//Use unique categories and tags for pages	
	 if(get_option('cattopage_wud_unique')=="1"){
		add_action( 'init', 'wud_custom_cats', 0 );
		add_action( 'init', 'wud_custom_tags', 0 );	
	 }

	 
	 
//Load the admin page	 
function cattopage_wud_admin() {
	require_once( plugin_dir_path( __FILE__ ) . '/pages/c2p-wud-admin.php' );
}

//Load the shortcodes
function cattopage_wud_shortcode() {
	require_once( plugin_dir_path( __FILE__ ) . '/pages/c2p-wud-shortcode.php' );
}

//Load the register terms
function cattopage_wud_regterms() {
	require_once( plugin_dir_path( __FILE__ ) . '/pages/c2p-wud-reg-terms.php' );
}

//Load the widgets
function cattopage_wud_widgets() {
	require_once( plugin_dir_path( __FILE__ ) . '/pages/c2p-wud-widgets.php' );
}
//Load the options
function cattopage_wud_options() {
	require_once( plugin_dir_path( __FILE__ ) . '/pages/c2p-wud-options.php' );
}
	 
//Debug used template file	
function show_wud_cattopage_template() {
    global $template;
    $temp = basename($template);
	//echo $temp;
}

// grid-wud languages
function cattopage_wud_languages() {
	load_plugin_textdomain( 'category-to-pages-wud', false, dirname(plugin_basename( __FILE__ ) ) . '/languages' );
}
	 
function cattopage_wud_site_page(){
	wp_enqueue_script('jquery');
	wp_register_script('cattopage_wud_script', plugins_url( 'js/cat-to-page.js', __FILE__ ), array('jquery'), null, true );
	wp_enqueue_script('cattopage_wud_script');	
	wp_enqueue_style( 'cattopage_wud_site_style' );
	wp_enqueue_style( 'cattopage_wud_site_style', plugins_url('css/category-to-pages-wud.css', __FILE__ ), false, null );
}

// CSS for admin
function cattopage_wud_styling($hook) {
	if   ( $hook == "toplevel_page_category-to-pages-wud" ) {
		wp_enqueue_style( 'cattopage_wud_admin_style' );
		wp_enqueue_style( 'cattopage_wud_admin_style', plugins_url('css/admin.css', __FILE__ ), false, null );
     }
}

// Settings page menu item	
function cattopage_wud_create_menu() {
	add_menu_page( 'Page Category WUD', 'Cat to Page WUD', 'manage_options', 'category-to-pages-wud', 'cattopage_wud_settings_page', plugins_url('images/wud_icon.png', __FILE__ ) );
}

// category-to-pages-wud options page (menu options by plugins)
function cattopage_wud_action_links( $actions, $pcwud_set ){
		static $plugin;
		if (!isset($plugin))
			$plugin = plugin_basename(__FILE__);
		if ($plugin == $pcwud_set) {
				$settings_page = array('settings' => '<a href="'.admin_url("admin.php").'?page=category-to-pages-wud">' . __('Settings', 'General') . '</a>');
				$support_link = array('support' => '<a href="https://wordpress.org/support/plugin/category-to-pages-wud" target="_blank">Support</a>');				
					$actions = array_merge($support_link, $actions);
					$actions = array_merge($settings_page, $actions);
			}			
			return $actions;
}


function pcwud_wud_update(){
		global $ctp_version; 
			//Update version number
			update_option('pcwud_wud_version', $ctp_version);
			//Update new fields		
			if (get_option('cattopage_wud_cat')=='') {update_option('cattopage_wud_cat', '');}
			if (get_option('cattopage_wud_unique')=='') {update_option('cattopage_wud_unique', 0);}
			if (get_option('cattopage_wud_tag')=='') {update_option('cattopage_wud_tag', '');}
			if (get_option('cattopage_wud_title')=='') {update_option('cattopage_wud_title', '');}
			if (get_option('cattopage_wud_title_size')=='') {update_option('cattopage_wud_title_size', 16);}
			if (get_option('cattopage_wud_quantity')=='') {update_option('cattopage_wud_quantity', 5);}
			if (get_option('cattopage_wud_title_font')=='') {update_option('cattopage_wud_title_font', 'inherit');}
			if (get_option('cattopage_wud_index_pos')=='') {update_option('cattopage_wud_index_pos', 0);}
			if (get_option('cattopage_wud_widget_option1')=='') {update_option('cattopage_wud_widget_option1', 0);}
			if (get_option('cattopage_wud_widget_option2')=='') {update_option('cattopage_wud_widget_option2', 0);}
			if (get_option('cattopage_wud_widget_parent')=='') {update_option('cattopage_wud_widget_parent', 0);}
			if (get_option('cattopage_wud_exp_yes')=='') {update_option('cattopage_wud_exp_yes', 0);}
			if (get_option('cattopage_wud_hardcoded')=='') {update_option('cattopage_wud_hardcoded', 0);}
			if (get_option('cattopage_wud_exp_lenght')=='') {update_option('cattopage_wud_exp_lenght', 20);}	
			if (get_option('cattopage_wud_widget_title1')=='') {update_option('cattopage_wud_widget_title1', '');}
			if (get_option('cattopage_wud_widget_title2')=='') {update_option('cattopage_wud_widget_title2', '');}			
}

?>
