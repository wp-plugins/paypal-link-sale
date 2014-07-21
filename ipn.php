<?php
// STEP 1: Read POST data
// reading posted data from directly from $_POST causes serialization 
// issues with array data in POST
// reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
$keyval = explode ('=', $keyval);
if (count($keyval) == 2)
$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 
foreach ($myPost as $key => $value) {        
   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
   } else {
        $value = urlencode($value);
   }
   $req .= "&$key=$value";
}
// STEP 2: Post IPN data back to paypal to validate
$ch = curl_init($pp_url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
    // error_log("Got " . curl_error($ch) . " when processing IPN data");
	echo curl_error($ch);
    curl_close($ch);
    exit;
}
curl_close($ch);
// STEP 3: Inspect IPN validation result and act accordingly
if (strcmp ($res, "VERIFIED") == 0) {
$ipn=serialize($_POST);
$wpdb->query($wpdb->prepare('insert into '.$wpdb->prefix . 'pplsipn (`ipn`) value (%s)',$raw_post_data));
$o_id=$_POST['invoice'];
$s_id=$_POST['subscr_id'];
if($_POST['business']==$pp_email&&$price==$_POST['mc_amount3']&&$_POST['mc_currency']==$currency&&$_POST['txn_type']=='subscr_signup')
{
$wpdb->query($wpdb->prepare("update ".$wpdb->prefix . "pplsorders set `status`=1,`sid`=%s where `id`=%s",$s_id,$o_id));
}
else if($_POST['business']==$pp_email&&$_POST['mc_currency']==$currency&&$_POST['txn_type']=='subscr_eot')
{
$wpdb->query($wpdb->prepare("update ".$wpdb->prefix . "pplsorders set `status`=3 where `id`=%s and `sid`=%s",$o_id,$s_id));
}
else if($_POST['business']==$pp_email&&$_POST['mc_currency']==$currency&&$_POST['txn_type']=='subscr_cancel')
{
$wpdb->query($wpdb->prepare("update ".$wpdb->prefix . "pplsorders set `status`=2 where `id`=%s and `sid`=%s",$o_id,$s_id));
}
} 
else if (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
}
?>