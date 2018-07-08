<div >


			<form  style="border:2px; text-align:center";  class="upay_form extform" method="POST" id='<?php bloginfo('url'); ?>/pay_card/' name="pay" action ='<?php bloginfo('url'); ?>'>


				<div class="formbnb">
				<fieldset>
					<h5>2017 MARATHON REGISTRATION  CONFIRMATION </h5>
					<br>
					<p>Thank you for registering! An email with the registration details has been sent to the email address specified. Please take a moment to check the entries for correctness before proceeding. Thank you once again. </p>
					<br>
					<p>You chose <b>Bank payment</b>; please pay in the amount specified below for the selected race. Bank details provided below.</p>

					<input type="Hidden" name="currCode" value="566" readonly>

					<br>

					<label for="race"> Race Type: </label>
					<input style='width:90%;text-align:center;border:0px' type="text" name="race" id="race"	 value="<?php echo $race_type; ?>"  readonly>

					<label for="amt"> Price (NGN): </label>
					<input style='width:90%;text-align:center;border:0px' type="text" name="price" id="price"	 value="<?php echo $amt; ?>"  readonly>

					<input type="hidden" name="orderId" value=""readonly="true" >
					
					<input type="hidden" name="prod" value="Pacer Marathon Race" required>

					<label for="email">Email  Address: </label>
					<input style='width:90%;text-align:center;border:0px' type="text" name="email"  id="email" value=" <?php echo $email; ?>"  readonly>
					<br>

					<hr>

					<div style="text-align-last: auto" >
						
						
						<p><b>Account Name:</b> BRIDGE PACERS SPORT CLUB</p>
						<p><b>Account Number:</b> 4417932010 </p>
						<p><b>Bank:</b> First City Monument Bank</p>
						<p><b>After payment, send an email</b> with subject "MARATHON 2017 PAYMENT CONFIRMATION" and payment details to info@pacershalfmarathon.org</p>
						<a  href="http://pacershalfmarathon.org/" style="color:white;"> <button>Go Home</button></a>
					</div>
				</div>
			</fieldset>

			</form>








</div>
