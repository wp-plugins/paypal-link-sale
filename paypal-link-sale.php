<?php
/**
 * Plugin Name: Paypal Sell Link Ads
 * Plugin URI: http://wordpress.org/plugins/paypal-link-sale/
 * Description: Monetize your blog by selling text links using paypal subscriptions
 * Version: 1.1
 * Text Domain: ppls
 * Author: Sunny Verma
 * Author URI: http://99webtools.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
function ppls_activate() {
		global $wpdb;
		$ipn_table = $wpdb->prefix . 'pplsipn';
		$orders_table = $wpdb->prefix . 'pplsorders';
		$q1='CREATE TABLE IF NOT EXISTS '.$ipn_table.' (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `ipn` text NOT NULL,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
		
		$q2='CREATE TABLE IF NOT EXISTS '.$orders_table.' (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `ltext` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `status` tinyint(1) NOT NULL,
		  `sid` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2000 ;';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($q1);
		dbDelta($q2);
		add_option('ppls_email','your-paypal-email');
		add_option('ppls_sandbox',0);
		add_option('ppls_currency','USD');
		add_option('ppls_price',10);
		add_option('ppls_size',30);
		add_option('ppls_total',10);
		add_option('ppls_term','M');
}
function ppls_init(){
		global $wpdb;
		$dwmy=array('D'=>__('Daily','ppls'),'W'=>__('Weekly','ppls'),'M'=>__('Monthly','ppls'),'Y'=>__('Yearly','ppls'));
		$total=get_option('ppls_total',10);
		$currency=get_option('ppls_currency','USD');
		$price=get_option('ppls_price',10);
		$size=get_option('ppls_size',30);
		$term=get_option('ppls_term','M');
		$sandbox=get_option('ppls_sandbox',0);
		$pp_email=get_option('ppls_email','your-paypal-email');
		$pp_url=$sandbox?'https://www.sandbox.paypal.com/cgi-bin/webscr':'https://www.paypal.com/cgi-bin/webscr';
		 if(isset($_GET['ppls_page'])&&$_GET['ppls_page']=='ipn') {
		   require(plugin_dir_path( __FILE__ ).'ipn.php');
		   exit;
		 }
		 else  if(isset($_GET['ppls_page'])&&$_GET['ppls_page']=='form') {
		   require(plugin_dir_path( __FILE__ ).'pages/form.php');
		   exit;
		 }
		else  if(isset($_GET['ppls_page'])&&$_GET['ppls_page']=='return') {
		   require(plugin_dir_path( __FILE__ ).'pages/thankyou.php');
		   exit;
		 }
		 else  if(isset($_GET['ppls_page'])&&$_GET['ppls_page']=='payment'&&isset($_GET['ppls_id'])) {
		   require(plugin_dir_path( __FILE__ ).'pages/payment.php');
		   exit;
		 }
}
function ppls_load_textdomain(){
	load_plugin_textdomain( 'ppls', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
function ppls_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'admin.php?page=ppls-settings' ) . '">'.__('Settings','ppls').'</a>',
 );
return array_merge( $links, $mylinks );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ppls_action_links' );
require(plugin_dir_path( __FILE__ ).'admin-menu.php');
require(plugin_dir_path( __FILE__ ).'widget.php');
add_action( 'plugins_loaded', 'ppls_load_textdomain' );
add_action('init', 'ppls_init');
register_activation_hook( __FILE__, 'ppls_activate' );
?>