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

   $key = "1dUAvz7wbU3Uw2La";
   
   
  
   $iv = "1dUAvz7wbU3Uw2La";
    
   
	$rijndael = new rijndael;
	 $plainPrivateKey = privatekey();
	

	$encryptedkey = base64_encode($rijndael -> encrypt($key, $iv, $plainPrivateKey));

$orderid = time();
	$hashdata = $_POST['mercId'].$orderid.$_POST["amt"].$_POST['currCode'].$plainPrivateKey;
$hashValue = hash('sha256', $hashdata ); 
			
			
                      
?>




<form style="border:2px; text-align:center" method="POST" id="upay_form" name="upay_form" action="https://fcmbwebpay.firstcitygrouponline.com/customerportal/MerchantServices/MakePayment.aspx" target="_top">


<div class="" style="border:2px; text-align:center" >
 <p>Note: You are about to be redirected to our external payment gateway, your credit card will be authorized for payment at the time you enter details on next page.</p>
</div>


<input type="hidden" name="mercId" value="<?php echo  $_POST['mercId'] ?>"> 

<input type="hidden" name="currCode" value="<?php echo  $_POST['currCode'] ?>"> 

<input type="hidden" name="amt" value="<?php echo  $_POST['amt'] ?>">

 <input type="hidden" name="orderId" value="<?php echo $orderid ?>">
 
 <input type="hidden" name="prod" value="<?php echo  $_POST['prod'] ?>">
 
<input type="hidden" name="email" value="<?php echo  $_POST['email'] ?>">

<input type="hidden" name="hash" value="<?php echo $hashValue ?>">

 <input type="hidden" name="encryptedKey" value="<?php echo $encryptedkey ?>">
 
<input type="submit" name= "proceed" id ="proceed" value ="proceed">
</form>

