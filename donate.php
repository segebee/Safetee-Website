<?php

/**
 * Description of PaymentGateway
 *
 * @author Abiola.Adebanjo
 */
require_once './interswitch_php/lib/Interswitch.php';
use Interswitch\Interswitch as InterswitchAPI;

const CLIENT_ID = "IKIA9614B82064D632E9B6418DF358A6A4AEA84D7218";
const CLIENT_SECRET = "XCTiBtLy1G9chAnyg0z3BcaFK4cVpwDg/GTw2EmjTZ8=";
const PURCHASE_RESOURCE_URL = "api/v2/purchases";
const HTTP_CODE = "HTTP_CODE";
const RESPONSE_BODY = "RESPONSE_BODY";
const CERTIFICATE_FILE_PATH = "paymentgateway.crt";

if (isset($_POST['amount']))
{
  $interswitchAPI = new InterswitchAPI(CLIENT_ID, CLIENT_SECRET);
    $authData = $interswitchAPI->getAuthData(CERTIFICATE_FILE_PATH, '1', '6280511000000095', '5004', '111', '1111');

  $result = doPurchase($authData, "segebee@gmail.com", $_POST['amount'], $interswitchAPI);
  //print_r($result);
  if ($result['HTTP_CODE'] == 200) //its a success
  {
    $body = json_decode($result['RESPONSE_BODY']);
    //print_r($body); 

    $transactionIdentifier = $body->transactionIdentifier;
    $token = $body->token;
    $tokenExpiryDate = $body->tokenExpiryDate;
    $panLast4Digits = $body->panLast4Digits;
    $cardType = $body->cardType;
    $message = $body->message;
    $amount = $body->amount;
    $status = 1;
    $transactionRef = $body->transactionRef;

    $message = "Donation was successful";
    //print_r($body);
  }
  else
  {
    $message = "Donation failed";
  }
}

function doPurchase($authData, $customerEmail, $amount, $interswitchAPI) {

    $httpMethod = "POST";
    $transactionRef = generateRef();
    //$customerId = "api-jam@interswitchgroup.com";
    $currency = "NGN";

    $data = array(
        "customerId" => $customerEmail,
        "amount" => $amount,
        "transactionRef" => $transactionRef,
        "currency" => $currency,
        "authData" => $authData
    );

    $request = json_encode($data);
    return $interswitchAPI->send(PURCHASE_RESOURCE_URL, $httpMethod, $request);
}

function doTransactionQuery($amount, $transactionRef, $interswitchAPI) {

    $httpMethod = "GET";
    $headers = array(
        'amount: ' . $amount,
        'transactionRef: ' . $transactionRef
    );

    return $interswitchAPI->send(PURCHASE_RESOURCE_URL, $httpMethod, null, $headers);
}

function generateRef() {
    $transRef = "ISW|API|JAM|" . mt_rand(0, 65535);
    return $transRef;
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
<div id="logo" class="two_thirds">
<hgroup>
<h1><img src="images/logo.png" alt="Off the Shelf eBook Landing Page" title="Off the Shelf eBook Landing Page" height="41" width="41"/>Safetee</h1>
</hgroup>
</div>
<!--End of Header Logo-->

<!--Start of Social Elements-->
<aside id="social_elements" class="one_third last">
<ul>
<li><a class="twitter" target="_blank" title="Twitter" href=""><span>Twitter</span></a></li>
<li><a class="facebook" target="_blank" title="Facebook" href=""><span>Facebook</span></a></li>
<li><a class="googleplus" target="_blank" title="Google Plus" href=""><span>Google Plus</span></a></li>
<li><a class="feedback" target="_blank" title="Feedback Form" href=""><span>Feedback Form</span></a></li>
</ul>
</aside>
<!--End of Social Elements-->

</div>
</section>
<!--End of Product Banner-->

<!--Start of Main Content-->
<article role="main">
<div id="main_content">

<section>
<div class="row">

<h1>Support An NGO</h1>

<p>Supporting Victims of Abuse requires a lot of resources and most times NGOs are overwhelmed. Your money helps these NGOs 
to continue their charitable work.</p>

<p>To Donate, please fill the form below.</p>

<div id="formwrapper">
        <div style="font-weight: bold; font-size: 16px; color: green"><?php if (isset($message)) echo $message; ?></div>
        <form method="post" action="donate.php">
            <label>Your Name</label>
            <input id="contactname" name="name" required>
            <label>Your Email</label>
            <input id="contactemail" name="email" type="email" required>
            <label>Amount</label>
            <input id="contactemail" name="amount" type="number" required>
            
            <input id="submitcontact" name="submit" type="submit" value="Donate" class="gradient">
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