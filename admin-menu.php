<?php
function ppls_settings(){
		register_setting('ppls_options','ppls_email');
		register_setting('ppls_options','ppls_sandbox');
		register_setting('ppls_options','ppls_currency');
		register_setting('ppls_options','ppls_price');
		register_setting('ppls_options','ppls_size');
		register_setting('ppls_options','ppls_total');
		register_setting('ppls_options','ppls_term');
}
function ppls_create_menu() {
		$ppls_stats=add_menu_page(__('Paypal Link Sale Stats','ppls'),__('Paypal Link Sale','ppls'),'manage_options','ppls-stats','ppls_admin_stats',plugins_url( 'images/paypal.png' , __FILE__));
		add_submenu_page('ppls-stats',__('Paypal Link Sale Settings','ppls'), __('Settings','ppls'), 'manage_options','ppls-settings', 'ppls_admin_settings');
		add_action( 'load-' . $ppls_stats, 'ppls_load_js_css' );
}
function ppls_admin_settings(){
		?>
		<div class="wrap">
				<h2>Paypal Link Sale Settings</h2>
				<?php if ( isset( $_GET['settings-updated'] ) ) {
			echo '<div class="updated"><p>'.__('<b>Paypal Link Sale</b> Settings updated successfully.','ppls').'</p></div>';
		} ?>
				<form method="post" action="options.php">
		<?php
		 settings_fields('ppls_options');
		 $currency=get_option('ppls_currency');
		 $term=get_option('ppls_term');
		 $dwmy=array('D'=>__('Daily','ppls'),'W'=>__('Weekly','ppls'),'M'=>__('Monthly','ppls'),'Y'=>__('Yearly','ppls'));
		?>
		<table class="form-table">
		<tbody>
		<tr>
		<td><?php _e('Your Paypal Email','ppls'); ?></td><td><input type="text" value="<?php echo get_option('ppls_email'); ?>" name="ppls_email" ></td>
		</tr>
		<tr>
		<td><?php _e('Enable Sandbox','ppls'); ?></td><td><?php _e('YES','ppls'); ?> <input type="radio" value="1" name="ppls_sandbox" <?php if(get_option('ppls_sandbox')==1){echo 'checked';} ?> ><?php _e('No','ppls'); ?> <input type="radio" value="0" name="ppls_sandbox" <?php if(get_option('ppls_sandbox')==0){echo 'checked';} ?>></td>
		</tr>
		<tr>
		<td><?php _e('Currency','ppls'); ?></td><td><select name="ppls_currency" >
		<option value="USD" <?php if ($currency == "USD") {echo " selected ";} ?> >U.S. Dollar</option>
		<option value="GBP" <?php if ($currency == "GBP") {echo " selected ";} ?> >Pound Sterling</option>
		<option value="JPY" <?php if ($currency == "JPY") {echo " selected ";} ?> >Japanese Yen</option>
		<option value="EUR" <?php if ($currency == "EUR") {echo " selected ";} ?> >Euro</option>
		<option value="CAD" <?php if ($currency == "CAD") {echo " selected ";} ?> >Canadian Dollar</option>
		<option value="AUD" <?php if ($currency == "AUD") {echo " selected ";} ?>>Australian Dollar</option>
		</select></td>
		</tr>
		<tr>
		<td><?php _e('Payment Term','ppls'); ?></td><td><select name="ppls_term" >
		<?php
		foreach($dwmy as $k=>$v)
		{
		?>
		<option value="<?php echo $k; ?>" <?php if($term==$k){echo " selected";} ?>><?php echo $v; ?></option>';
		<?php
		}
		?>
		</select></td>
		</tr>
		<tr>
		<td><?php _e('Price','ppls'); ?></td><td><input type="number" value="<?php echo get_option('ppls_price'); ?>" name="ppls_price" ></td>
		</tr>
		<tr>
		<td><?php _e('Anchor Text Size','ppls'); ?></td><td><input type="number" value="<?php echo get_option('ppls_size'); ?>" name="ppls_size" ></td>
		</tr>
		<tr>
		<td><?php _e('Link To Be Sold','ppls'); ?></td><td><input type="number" value="<?php echo get_option('ppls_total'); ?>" name="ppls_total" ></td>
		</tr>
		</tbody>
		</table>
		<?php submit_button(); ?>
		<form>
		<div>
		<?php
}
function ppls_admin_stats(){
		global $wpdb;
		$stat=array(__('pending','ppls'),__('active','ppls'),__('canceled','ppls'),__('expired','ppls'));
		$class=array('pending','active','canceled','expired');
		if(isset($_POST['submit'])&&isset($_POST['deleteorder']))
		{
		$ids=implode(",",$_POST['deleteorder']);
		$wpdb->query('delete from '.$wpdb->prefix . 'pplsorders where id in ('.$ids.') and status IN (0,3)');
		}
		$results=$wpdb->get_results('select * from '.$wpdb->prefix . 'pplsorders');
		?>
		<h2><?php _e('Orders List','ppls'); ?></h2>
		<form method="post">
		<table class="wp-list-table widefat">
		<thead>
		<tr>
		<th><input type="checkbox" id="selectall"></th>
		<th><?php _e('ID','ppls'); ?></th>
		<th><?php _e('Link','ppls'); ?></th>
		<th><?php _e('Name','ppls'); ?></th>
		<th><?php _e('E-mail','ppls'); ?></th>
		<th><?php _e('Date','ppls'); ?></th>
		<th><?php _e('Status','ppls'); ?></th>
		<th><?php _e('Subscription ID','ppls'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($results as $r)
		{
		?>
		<tr class="<?php echo $class[$r->status]; ?>">
		<td><?php if($r->status==0||$r->status==3){ ?><input type="checkbox" name="deleteorder[]" value="<?php echo $r->id; ?>" ><?php } ?></td>
		<td><?php echo $r->id; ?></td>
		<td><a href="<?php echo $r->url; ?>" target="_blank"><?php echo $r->ltext; ?></a></td>
		<td><?php echo $r->name; ?></td>
		<td><a href="mailto:<?php echo $r->email; ?>"><?php echo $r->email; ?></a></td>
		<td><?php echo date("D, d M Y H:i", strtotime($r->date)); ?></td>
		<td><?php echo $stat[$r->status]; ?></td>
		<td><?php echo empty($r->sid)?"N/A":$r->sid; ?></td>
		</tr>
		<?php
		}
		?>
		</tbody>
		</table><p>
		<input type="submit" class="button-primary" name="submit" value="<?php esc_attr_e('Delete Orders','ppls'); ?>"></p>
		</form>
		<?php
}
function ppls_load_js_css(){
		add_action( 'admin_enqueue_scripts', 'ppls_stats_js' );
}
function ppls_stats_js(){
		wp_enqueue_script( 'ppls_script', plugin_dir_url( __FILE__ ) . 'ppls.js',array( 'jquery' ) );
		wp_enqueue_style( 'ppls_style', plugin_dir_url( __FILE__ ) . 'ppls.css' );
}
function my_admin_notice() {
		if(!is_active_widget( '', '', 'ppls_widget')){
			?>
			<div class="error">
				<p><?php _e('<b>Paypal Link Sale</b> Widget is not active. Please add this widget to any sidebar','ppls'); ?></p>
			</div>
			<?php
		}
}
function ppls_add_dashboard_widgets() {
	add_meta_box( 'ppls_dashboard_widget', __('Paypal Link Sale','ppls'), 'ppls_dashboard_widget_function', 'dashboard', 'side', 'high' );
}
function ppls_dashboard_widget_function() {
	global $wpdb;
	$rs=$wpdb->get_results('select status,count(*) as num from '.$wpdb->prefix . 'pplsorders group by status');
	if($rs)
	{
	$stat=array(__('pending','ppls'),__('active','ppls'),__('canceled','ppls'),__('expired','ppls'));
	echo '<table style="width:50%">';
	foreach($rs as $r)
	{
	echo '<tr><th>'.__('Orders','ppls').' '.$stat[$r->status].'</th><td>'.$r->num.'</td></tr>';
	}
	echo '</table>';
	echo '<a href="'.admin_url( 'admin.php?page=ppls-stats' ).'" style="display:block;text-align:right">'.__('View Full Orders List','ppls').'</a>';
	}
	else
	{
	_e('No orders placed yet','ppls');
	}
}
add_action( 'wp_dashboard_setup', 'ppls_add_dashboard_widgets' );
add_action( 'admin_notices', 'my_admin_notice' );
add_action('admin_init', 'ppls_settings' );
add_action('admin_menu', 'ppls_create_menu');
?>