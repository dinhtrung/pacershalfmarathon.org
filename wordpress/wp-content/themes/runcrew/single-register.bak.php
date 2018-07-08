<?php 
	
	/*
		Template Name: Registration_Page
	*/
?>
<?php get_header(); the_post(); ?>

<form id="form1”>
<div>
<p>
Amount:<input id="txtAmt" type="text" ></input>
</p>
</div>
</form>
<script type="text/javascript" src="https://fcmbwebpay.firstcitygrouponline.com/customerportal/MerchantServices/UPaybutton.ashx?mercId=00121&CurrencyCode=566>
</script>
<script type="text/javascript">
upay_settings.setAmountField('txtAmt');
upay_settings.setOrderId('31-Aug-2009');
upay_settings.setProduct('Donation');
upay_settings.setEmail('john.bull@gmail.com');
</script>
<?php get_footer(); ?>