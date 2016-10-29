<?php
require_once './interswitch_php/lib/Interswitch.php';
use Interswitch\Interswitch as InterswitchAPI;

const CLIENT_ID = "IKIAF6C068791F465D2A2AA1A3FE88343B9951BAC9C3";
const CLIENT_SECRET = "FTbMeBD7MtkGBQJw1XoM74NaikuPL13Sxko1zb0DMjI=";
const PURCHASE_RESOURCE_URL = "api/v1/pwm/subscribers/2348090673520/tokens";
const HTTP_CODE = "HTTP_CODE";
const RESPONSE_BODY = "RESPONSE_BODY";

function doRequestToken($amount, $transactionRef, $interswitchAPI) {
    $FEPI = 455;
    $httpMethod = "POST";
    $headers = array(
        'frontEndPartnerId: ' . $FEPI
    );
    $data = array(
        "ttid" => "123",
        "paymentMethodTypeCode" => "MMO",
        "paymentMethodCode" => "WEMA",
        "tokenLifeTimeInMinutes" => 10,
        "payWithMobileChannel" => "ATM",
        "transactionType" => "Withdrawal",
        "codeGenerationChannel" => "Mobile",
        "amount" => $amount,
        "accountNo" => "205015250201000100",
        "accountType" => "20",
        "autoEnroll" => "false",
        "oneTimePin" => "1234"
    );
    $request = json_encode($data);
    return $interswitchAPI->send(PURCHASE_RESOURCE_URL, $httpMethod, $request, $headers);
//    echo $request;
}
$interswitchAPI = new InterswitchAPI(CLIENT_ID, CLIENT_SECRET);

if (isset($_POST['amount']))
{
  //$amount = number_format($_POST['amount'],2,'.',','); 
  $amount = $_POST['amount']*100;

  $ref= time();
  //echo $amount;
  $result = doRequestToken($amount, $ref, $interswitchAPI);
  //print_r($result);
  if ($result['HTTP_CODE'] == 201) //its a success
  {
    $body = json_decode($result['RESPONSE_BODY']);
    $subscriberId = $body->subscriberId;
    $payWithMobileToken = $body->payWithMobileToken;
    $tokenLifeTimeInMinutes = $body->tokenLifeTimeInMinutes;

    $message = "Payment Code is ".$payWithMobileToken.". This has been sent to the Recipient's Email";
    @mail($_POST['email'], 'Safetee Rewards', $message);
  }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">

<!--Page Title-->
<title>Safetee</title>

<!--Device Width Check-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>

<!--Meta Keywords and Description-->
<meta name="keywords" content="">
<meta name="description" content="">

<!--Favicon-->
<link rel="shortcut icon" href="images/favicon.ico" title="Favicon" />

<!--Fixes for Internet Explorer CSS3 and HTML5-->
<!--[if gte IE 9]>
<style type="text/css">
	.gradient { filter: none!important;}
</style>
<![endif]-->

<!--[if lt IE 9]>
<script>
  'article aside footer header nav section time'.replace(/\w+/g,function(n){document.createElement(n)})
</script>
<![endif]-->

<!--Main CSS-->
<link rel="stylesheet" href="css/style.css" media="screen, projection">

<!--Icon Fonts-->
<link rel="stylesheet" href="css/font-awesome.min.css">

<!--Google Webfonts-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300,600|Bree+Serif' rel='stylesheet' type='text/css'>

</head>
<body class="content_page">

<!--Start of Product Banner-->
<section id="banner" role="banner">
<div class="row">

<!--Start of Header Logo-->
<div id="logo" class="one_third">
<hgroup>
<a href="index.php">
  <h1><img src="images/logo.png" alt="" title="Off the Shelf eBook Landing Page" height="41" width="41"/>Safetee</h1>
</a>
</hgroup>
</div>
<!--End of Header Logo-->
<aside id="" class="two_thirds last ">
  <ul style="list-style: none;">
    <li><a class="" title="" href="donate.php"><span style="color: #fff;font-size: 14px">Donate</span></a></li>
    <li><a class="" title="" href="sendmoney.php"><span style="color: #fff;font-size: 14px">Send Money</span></a></li>
  </ul>
</aside>
<!--Start of Social Elements-->
<!-- <aside id="social_elements" class="one_third last">
<ul>
<li><a class="twitter" target="_blank" title="Twitter" href=""><span>Twitter</span></a></li>
<li><a class="facebook" target="_blank" title="Facebook" href=""><span>Facebook</span></a></li>
<li><a class="googleplus" target="_blank" title="Google Plus" href=""><span>Google Plus</span></a></li>
<li><a class="feedback" target="_blank" title="Feedback Form" href=""><span>Feedback Form</span></a></li>
</ul>
</aside> -->
<!--End of Social Elements-->

</div>
</section>
<!--End of Product Banner-->

<!--Start of Main Content-->
<article role="main">
<div id="main_content">

<section>
<div class="row">

<h1>Send Money</h1>

<p>Send Money to support a Victim or Reward a Good Samaritan.</p>

<p>To Send Money, please fill the form below.</p>

<div id="formwrapper">
        <div style="font-weight: bold; font-size: 16px; color: green"><?php if (isset($message)) echo $message; ?></div>
        <form method="post" action="sendmoney.php">
            <label>Recipient Phone</label>
            <input id="contactname" name="phone" required>
            <label>Recipient Email</label>
            <input id="contactemail" name="email" type="email" required>
            <label>Amount</label>
            <input id="contactemail" name="amount" type="number" required>
            
            <input id="submitcontact" name="submit" type="submit" value="Send" class="gradient">
        </form>
    </div>

</div>
</section>

</div>
</article>
<!--End of Main Content-->

<!--Start of Landing Page Footer-->
<footer role="contentinfo">
<div id="page_footer" class="row">
<!-- <ul>
<li><a href="about.html">About</a></li>
<li><a href="#">Press</a></li>
<li><a href="#">Contact</a></li>
</ul> -->

<p>
&copy; Safetee. All rights reserved.
</p>
</div>
</footer>
<!--End of Landing Page Footer-->

<!--Beginning of Contact form-->
<div id="contact" class="reveal-modal medium">
    <a href="javascript:closecontact();" id="contact_close"><span>Close</span></a>
    <div id="contactmessages"></div>
    <div id="formwrapper">
        <form method="post" action="contact.php" id="contactform">
            <label>Your Name</label>
            <input id="contactname" name="name" required>
            <label>Your Email</label>
            <input id="contactemail" name="email" type="email" required>
            <label>Your Message</label>
            <textarea name="message" required></textarea>
            <label>What is <span id="captcha">???</span> (Anti-spam)</label>
            <input id="humanornot" name="humanornot" placeholder="Type Here" required>
            <input name="s" id="s" value="blank" type="hidden">
            <input id="submitcontact" name="submit" type="submit" value="Submit" class="gradient">
        </form>
    </div>
</div>
<!--End of Contact form-->
<a href="#" class="scrollup">Scroll up</a>
<!-- Included JS Files (Compressed) -->
<script src="js/foundation.min.js"></script>
<script src="js/phrases.js"></script>
<script src="js/site.min.js"></script>
</body>
</html>