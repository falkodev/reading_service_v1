<?php
include("../app/utils.php");
$tab_lang=getLang();
?>
<!DOCTYPE html>
<html lang="<?=__('language')?>">
<head>
	<meta charset="utf-8">
	<title><?=__('error_page_title')?></title>
    
	<!--<link rel="shortcut icon" href="assets/images/gt_favicon.png">-->
	<link rel="icon" href="/assets/images/book.png">
	
	<!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="/assets/css/bootstrap-theme.min.css" rel="stylesheet">
	
	<!-- Fonts -->
	<link href="/assets/css/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Wire+One' rel='stylesheet' type='text/css'>
    
    <!-- Custom styles -->
	<link rel="stylesheet" href="/assets/css/magister.css">
    


</head>
<?php 
//$pos = strrpos(getenv("REQUEST_URI"),"/");
//$request = substr(getenv("REQUEST_URI"), $pos);
$strings = explode("/",getenv("REQUEST_URI"));
$pieces = array();
for($i=0;$i<count($strings);$i++)
{
	if($i <> 0 && $i <> 1) $pieces[] = $strings[$i];
}
$request = implode("/",$pieces);
?>
<body class="text-shadows">
	<div class="container">
		<h2 class="text-center title"><?=__('error_title')?></h2>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 text-center">	
				<p>
				<?=__('error_text_1')?> <?php echo getenv("SERVER_NAME")."/".$request;  ?> <?=__('error_text_2')?>
				</p>				
			</div>
		</div>
	</div>
<?php 
if($request != "favicon.ico") {
	$ip = getenv ("REMOTE_ADDR"); 

	//$requri = getenv ("REQUEST_URI"); 
	$servname = getenv ("SERVER_NAME"); 
	$combine = $ip . " a tenté d'accéder à l'url <b>" . $servname."/".$request."</b>"; 

	$httpref = getenv ("HTTP_REFERER"); 
	$httpagent = getenv ("HTTP_USER_AGENT");

	$today = date("d/m/Y h:m:s"); 

	$message = "Bonjour,<br><br>
	Une page d'erreur a été générée le $today. <br>
	L'adresse IP $combine <br>
	User Agent = $httpagent <br>
	$httpref <br><br>
	L'équipe JW Reading"; 

	$to = "anthony.tarlao@gmail.com"; 
	$subject = "JW Reading - Page d'erreur"; 
	//$from = "From: tarlao-anthony@bbox.fr\r\n"; 

	$headers = "Content-Type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From:noreply@jwreading.com";

	mail($to, $subject, $message, $headers);  
}
?>
</body>
</html>