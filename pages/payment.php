<?php
$id=$_GET['ppls_id'];

$results = $wpdb->get_results( $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'pplsorders WHERE id = %d',$id));
if(!count($results))
{
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php _e('Payment Page','ppls'); ?></title>
<link href="<?php echo plugins_url( 'pages/style.css' , dirname(__FILE__)); ?>" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
	  <div class="header">
        <h3><?php printf(__('Buy Link at %s','ppls'),get_bloginfo( 'name' )); ?> </h3>
      </div>
	  <div class="well">
	  <h3 class="text-info" style="text-align:center"><?php _e('Order Details','ppls'); ?></h3>
<dl class="dl-horizontal">
  	<dt><?php _e('Name','ppls'); ?></dt>
  <dd><?php echo $results[0]->name; ?></dd>
    <dt><?php _e('E-mail','ppls'); ?></dt>
  <dd><?php echo $results[0]->email; ?></dd>
    <dt><?php _e('Link Text','ppls'); ?></dt>
  <dd><?php echo $results[0]->ltext; ?></dd>
    <dt><?php _e('URL','ppls'); ?></dt>
  <dd><?php echo $results[0]->url; ?></dd>
    <dt><?php _e('Price','ppls'); ?></dt>
  <dd><?php echo $price.$currency." ".$dwmy[$term]; ?></dd>
</dl>
<?php
if($results[0]->status==0)
{
?>
<p style="text-align:center" class="text-warning">
<?php _e('Please Subscribe Using Paypal To Activate The Link','ppls'); ?>
					<form action="<? echo esc_attr($pp_url); ?>" method="post" style="text-align:center">
					<input type="hidden" name="cmd" value="_xclick-subscriptions">
					<input type="hidden" name="business" value="<?php echo esc_attr($pp_email) ?>">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="no_shipping" value="1">
					<input type="hidden" name="no_note" value="1">
					<input type='hidden' name='handling' value='0'>
					<input type='hidden' name='item_name' value='<?php echo esc_attr('Link at '.get_bloginfo( 'name' )); ?>'>
					<input type='hidden' name='invoice' value='<?php echo esc_attr($results[0]->id); ?>'>
					<input type='hidden' name='cancel_return' value='<?php echo esc_attr(get_bloginfo('url')); ?>'>
					<input type='hidden' name='return' value='<?php echo esc_attr(get_bloginfo('url')."?ppls_page=return"); ?>'>
					<input type='hidden' name='notify_url' value='<?php echo esc_attr(get_bloginfo('url')."?ppls_page=ipn"); ?>'>
					<input type="image" src="<?php echo esc_attr(plugins_url( 'images/btn_subscribeCC_LG.gif' , dirname(__FILE__) )) ?>" border="0" name="submit" alt="<?php esc_attr_e('Make payments with PayPal - its fast, free and secure!','ppls'); ?>">
					<input type="hidden" name="a3" value="<?php echo esc_attr($price); ?>">
					<input type="hidden" name="p3" value="1">
					<input type="hidden" name="t3" value="<?php echo esc_attr($term); ?>">
					<input type="hidden" name="src" value="1">
					<input type="hidden" name="sra" value="0">
					</form>
</p>
<?php
}
else
{
?>
<p style="text-align:center" class="text-success lead"><?php _e('Already Subscribed','ppls'); ?></p>
<?php
}
?>
	  </div>
<div class="footer">
<p>&copy; <?php bloginfo( 'name' ); echo ' '.date('Y');?></p>
</div>
</div>
</body>
</html>
