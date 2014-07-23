<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="refresh" content="5; url=<?php bloginfo( 'url' ); ?>" />
<title><?php _e('Thank you for buying link','ppls'); ?></title>
<link href="<?php echo plugins_url( 'pages/style.css' , dirname(__FILE__)); ?>" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
	  <div class="header">
        <h3 class="text-muted"><?php _e('Thank you for buying link','ppls'); ?></h3>
      </div>
	  <div class="alert-success" role="alert"><?php _e('<strong>Thank You!</strong> your order has been placed and link will be activated in next couple of minutes.','ppls'); ?> </div>
<div class="footer">
<p>&copy; <?php bloginfo( 'name' ); echo ' '.date('Y');?></p>
</div>
</div>
</body>
</html>
