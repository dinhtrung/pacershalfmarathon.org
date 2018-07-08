<?php

	/*
		Template Name:send_pay
	*/
?>

<?php get_header(); the_post(); ?>


<!--    


<?php
/*$email='bayodesegun@cosmosalliedservices.ng';


global $wpdb;
$result = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM wp_visual_form_builder_entries WHERE sender_email =%s', $email ));
echo $result;
while ($row = mysql_fetch_array ($result)){
    $data=$row['data'];
    $data = unserialize($data);



    $data=$data[16];
    $race_type = '';
    foreach ($data as $key => $value) {

        if ($key=='value')

            $race_type = $value;
    }
    echo $race_type;




}

*/?>

-->


<form  style="border:2px; text-align:center";  class="upay_form extform" method="POST" id='<?php bloginfo('url'); ?>/pay_card/' name="pay" action ='<?php bloginfo('url'); ?>/pay_card/'>


<div class="formbnb">
 <fieldset><h5>2016 MARATHON REGISTRATION  CONFIRMATION </h5></Fieldset>
 <br>
 <input type="hidden" name="mercId" value="00068">


 <input type="Hidden" name="currCode" value="566" readonly="true">

 <label for="amt"> Race Type: </label>
 <select name="amt" id="amt"  required>
  <option value="6000">10 KM Run </option>
  <option value="8000">21 KM Half Marathon </option>
 </select>

 <input type="hidden" name="orderId" value=""readonly="true" >

 <br>
 <br>
 <input type="hidden" name="prod" value="Pacer Marathon Race" required>

 <label for="email">Email  Address: </label>
 <input type="text" name="email"  id="email" value = "" required >
 <br><br>

 <input type = "hidden" name="hash" value= "" readonly="true">

 <input type = "submit" name="pay" value= "pay">
</div>
</form>

<div class="" style="border:2px; text-align:center" >
 <p>Note: If paying by card, kindly select same race type and input same email entered on the registration page</p>
  <p>If making manual payments click</p>  <a  href="http://pacershalfmarathon.org/" style="color:white;"> <button> back</button></a> 
</div>



  <?php get_footer(); ?>
