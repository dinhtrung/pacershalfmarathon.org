<?php

/*
	Template Name:db_test
*/
session_start();
?>

<?php get_header();
// the_post(); 
?>

<?php

//session_start();
$email=''; 
$race_type=''; 
$payment_type=''; 
$amt=0;

foreach ($_SESSION['formdata'] as $key=>$data) :
 
	if ($key=='vfb-75') {
		$email = $data;
	};

	if ($key=='vfb-71') {
		$race_type=$data;
		if($race_type=='10 KM Run') {
			$amt=5000;
		}
		elseif($race_type=='21 KM Half Marathon') {
			$amt=7000;
		}
		else {
			$amt=0;
		}
	};

	if ($key=='vfb-70') {
		$payment_type=$data;
	};




endforeach;

if($payment_type=='Bank Payment') {
	include(TEMPLATEPATH . '/Bank-payment.php');
}

else{
	include(TEMPLATEPATH.'/Card-payment.php');
}

	?>






