<?php
//header("Refresh:0");

session_start();
//var_dump($_SESSION['formdata']);
/*
	Template Name:confirmation
*/
?>

<?php get_header(); the_post(); ?>

<?php
//var_dump($_POST);
$email=''; 
$race_type=''; 
$payment_type=''; 
$amt=0;
//var_dump($_SESSION['formdata']);
foreach ($_SESSION['formdata'] as $key=>$data) :
 
	if ($key=='vfb-20') {
		$email = $data;
	};

	if ($key=='vfb-55') {
		$race_type=$data;
		if($race_type=='10 KM Run') {
			$amt=6000;
		}
		elseif($race_type=='21 KM Half Marathon') {
			$amt=8000;
		}
		else {
			$amt=0;
		}
	};

	if ($key=='vfb-56') {
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






