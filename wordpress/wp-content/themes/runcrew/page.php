<?php
/**
 * Single Page
 */

session_start();
//var_dump($_POST); die();
//var_dump(S_SESSION['formdata']);
if(isset($_POST) && $_POST['form_id']== "1"){
//var_dump('here') or die();
//unset($_SESSION['formdata']); 
//$_GET = $_POST;
$_SESSION['formdata'] = $_POST;
$location = "http://pacershalfmarathon.org/confirmation-page/";
wp_redirect($location, $status = 307);
exit();
}

get_template_part('single');
?>
