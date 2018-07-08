<?php

	/*
		Template Name:Pay_Page
	*/
?>

<?php get_header(); the_post(); ?>


<?php
class rijndael {
	protected $mcrypt_cipher = MCRYPT_RIJNDAEL_128;
	protected $mcrypt_mode = MCRYPT_MODE_CBC;

	public function encrypt($key, $iv, $plainPrivateKey)
	{
		$iv_utf = mb_convert_encoding($iv, 'UTF-8');
		$key_utf = mb_convert_encoding($key,'UTF-8');
		return mcrypt_encrypt($this->mcrypt_cipher, $key_utf, $plainPrivateKey, $this->mcrypt_mode, $iv_utf);
	}
}

function privatekey($length = 16) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++)

	{
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

$key = "460V7maqsLwxaQF6";



$iv = "460V7maqsLwxaQF6";


$rijndael = new rijndael;
$plainPrivateKey = privatekey();


$encryptedkey = base64_encode($rijndael -> encrypt($key, $iv, $plainPrivateKey));

$orderid = time();
$hashdata = $_POST['mercId'].$orderid.$_POST["amt"].$_POST['currCode'].$plainPrivateKey;
$hashValue = hash('sha256', $hashdata );



?>



	<form method="POST" id="upay_form" name="upay_form" action="https://webpaytest.fcmb.com/cipgupgrade/MerchantServices/MakePayment.aspx" target="_top">
<input type="hidden" name="mercId" value="<?php echo  $_POST['mercId'] ?>"/>
<input type="hidden" name="currCode" value="<?php echo  $_POST['currCode'] ?>"/>
<label for="upay_select">Race Type</label><select name="amt" id="upay_select" >
<option value="5000">10Km runner </option>
<option value="7000">21km runner </option>
</select>
<input type="hidden" name="orderId" value=""/>
<input type="hidden" name="prod" value="Marathon Race"/> &nbsp;
<input type="hidden" name="hash" value="<?php echo $hashValue ?>" />
<input type="hidden" name="encryptedKey" value="<?php echo $encryptedkey ?>"/>
<label for="emailfield">Verify Email</label>
<input type="email" id="emailfield" name="email" />
<input type="submit" name= "proceed" id ="proceed" value ="proceed">
</form>


<?php get_footer(); ?>