<?php
$results = $wpdb->get_results( 'SELECT count(*) as sold FROM ' . $wpdb->prefix . 'pplsorders WHERE status = 1');
$sold=$results[0]->sold;
$left=$total-$sold;
if(isset($_POST['submit'])&&$left>0)
{
if (get_magic_quotes_gpc()) {$_POST=array_map('stripslashes',$_POST);}
$name=sanitize_text_field($_POST['name']);
if(empty($name))
{
$err[]=__('Invalid Name','ppls');
}
$email=sanitize_email($_POST['email']);
if(!is_email($email))
{
$err[]=__('Invalid Email','ppls');
}
$text=sanitize_text_field($_POST['text']);
$text=substr($text,0,$size);
if(empty($text))
{
$err[]=__('Invalid Link Text','ppls');
}
$url=esc_url_raw($_POST['url']);
if(!preg_match("~^http[s]?://[a-z0-9-\.]+\.[a-z\.]{2,5}(/[a-z0-9%/\-_\.\?=&@#]*)?$~",$url))
{
$err[]=__('Invalid URL','ppls');
}
if(!isset($err))
{
$wpdb->query($wpdb->prepare('insert into '.$wpdb->prefix . 'pplsorders (`name`,`email`,`ltext`,`url`,`status`) value(%s,%s,%s,%s,%d)',$name,$email,$text,$url,0));
$id = $wpdb->insert_id;
$payment_url=get_bloginfo('url').'?ppls_page=payment&ppls_id='.$id;
header('Location: '.$payment_url);
}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php _e('Order Form','ppls'); ?></title>
<link href="<?php echo plugins_url( 'pages/style.css' , dirname(__FILE__)); ?>" rel="stylesheet" type="text/css">
  </head>
<body>
<div class="container">
	  <div class="header">
        <h3><?php printf(__('Buy Link at %s','ppls'),get_bloginfo( 'name' )); ?> </h3>
      </div>
	  <?php
	  if($left>0)
	  {
	  ?>
	  <div class="well">
	  <?php
	  if(isset($err))
		{
	?>
	  <div class="alert-danger">
	  <strong><?php _e('Error','ppls'); ?></strong>
	  <ol>
	  <?php foreach($err as $e)
	  echo '<li>'.$e.'</li>';
	  ?>
	  </ol>
	  </div>
	  <?php
	  }
	  ?>
<form role="form" name="pplsform" method="post" action="#">
<fieldset>
<legend><?php echo __('Price','ppls').' '.$price.$currency.' '.$dwmy[$term]; ?></legend>
<div class="form-group">
	<label for="name"><?php _e('Full Name','ppls'); ?></label>
	<input name="name" class="form-control"   id="name" type="text" required>
</div>
<div class="form-group">
	<label for="email"><?php _e('E-mail','ppls'); ?></label>
	<input name="email" class="form-control" id="email" type="email" required><p class="help-block"><?php _e('Your email address will not be published or distributed','ppls'); ?></p>
</div>
<div class="form-group">
	<label for="text"><?php _e('Link Text','ppls'); ?></label>
<input name="text" class="form-control" maxlength="<?php echo $size; ?>"  id="text" type="text" required><p class="help-block"><?php printf(__('Max Length %d characters','ppls'),$size); ?></p>
</div>
<div class="form-group">
<label for="url"><?php _e('URL','ppls'); ?></label>
<input name="url" class="form-control"  id="url" type="url" required></div>
</fieldset>
<p class="center">
<input name="submit" class="btn btn-info"  type="submit" value="<?php _e('Place Order','ppls'); ?>">
</p>
</form></div>
<?php
}
else
{
?>
<div class="alert-danger" role="alert"><?php _e('All Link Spot Sold Out.','ppls'); ?></div>
<?php
}
?>
<div class="footer">
<p>&copy; <?php bloginfo( 'name' ); echo ' '.date('Y');?></p>
</div>
</div>
</body>
</html>
