	<div>

		<form  style="border:2px; text-align:center";  class="upay_form extform" method="POST" id='<?php bloginfo('url'); ?>/pay_card/' name="pay" action ='<?php bloginfo('url'); ?>/pay_card/'>
			
			<div class="formbnb">
				<fieldset>
				
				<h5>2017 MARATHON REGISTRATION  CONFIRMATION </h5>
				<br>
				
				<p>Thank you for registering! An email with the registration details has been sent to the email address specified. Please take a moment to check the entries for correctness before proceeding. Thank you once again. </p>
				<br>
				<p>You chose <b>Card payment</b>; the information shown below will be used in processing your payment. Click the PROCEED button to continue.</p>				
				
	
				<br>
	
				<label for="race"> Race Type: </label>
				<input style='width:90%;text-align:center;border:0px' type="text" name="race" id="race"	 value="<?php echo $race_type; ?>"  readonly>
	
				<label for="price"> Price (NGN): </label>
				<input style='width:90%;text-align:center;border:0px' type="text" name="price" id="price"	 value="<?php echo $amt; ?>"  readonly>				
	
				<label for="email">Email  Address: </label>
				<input style='width:90%;text-align:center;border:0px' type="text" type="text" name="email"  id="email" value=" <?php echo $email; ?>"  readonly>
				<br><br> 
				
							
	
					
			
			
			
			
			
			<input type="hidden" name="mercId" value="00068"> 
			
			<input type="hidden" name="currCode" value="566"> 
			
			<input type="hidden" name="amt" value="<?php echo $amt; ?>">
			
			 <input type="hidden" name="orderId" value="<?php echo $orderid ?>">
			 
			 <input type="hidden" name="prod" value="Pacers Half Marathon Race">
			 
			<input type="hidden" name="email" value="<?php echo  $email; ?>">
			
			<input type="hidden" name="hash" value="<?php echo $hashValue ?>">
			
			 <input type="hidden" name="encryptedKey" value="<?php echo $encryptedkey ?>">
			 
			<input type="submit" name= "proceed" id="proceed" value ="proceed">
			</fieldset>
			</form>
		
		</div>
		
</div>
