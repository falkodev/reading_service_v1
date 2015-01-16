<?php
/*$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if($lang == "fr") $tab_lang = include('../app/lang/fr.php');
else $tab_lang = include('../app/lang/en.php');

function __($name) {
    global $tab_lang;
    return $tab_lang[$name];
}*/
include("../app/utils.php");
$tab_lang=getLang();
header('Content-Type: text/html; charset=utf-8'); 
?>
<!DOCTYPE html>
<html lang="<?=__('language')?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport"    content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author"      content="Sergey Pozhilov (GetTemplate.com)">
	<title><?=__('unsubscribe_page_title')?></title>

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
	
	<!-- js scripts -->
    <script src="/assets/js/jquery-1.11.1.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/modernizr.custom.72241.js"></script>
    <!-- Custom template scripts -->
	<script src="/assets/js/magister.js"></script>
	
	<script>	
	$('#unsubscribeConfirm').hide();	
	var lang = $('html').attr('lang');
	function showUnsubscribeConfirm(param, name, mail, jours, comment, pass)
	{    
		$('#unsubscribeFirst').hide();
		$('#unsubscribeConfirm').show();
		$('#messageConfirm').hide();
		if(param == "ok")
		{ 
			if(lang == 'fr') $('#messageConfirm').append('<p style=\'text-align:center\'><br>Votre compte JW Reading a été supprimé. Vous allez recevoir un email de confirmation dans quelques instants. Ce sera donc le dernier..snif. <br><br>Nous espérons vous revoir bientôt tout de même.<br><br>L\'équipe JW Reading</p>');
			else $('#messageConfirm').append('<p style=\'text-align:center\'><br>Your JW Reading account has been deleted. You will receive a confirmation email in a few minutes. It will be the last one..snif. <br><br>We hope to see you again soon though.<br><br>The JW Reading team</p>');
		}
		else if(param == "koHash")
		{
			if(lang == 'fr') $("#messageConfirm").html('<p style="text-align:center"><br>Aïe, problème<br><br>Le process de validation de votre adresse email a échoué et votre compte n\'a pas été supprimé. Contactez-moi grâce au formulaire de contact pour me le signaler.<br><br> Désolé pour cet incident et à bientôt.<br><br>L\'équipe JW Reading</p>');
			else $("#messageConfirm").html('<p style="text-align:center"><br>Ouch, problem<br><br>The validation process of your email address failed and your account is not deleted. Please, inform me about this through the contact form.<br><br> Sorry for this and we hope to see you soon.<br><br>The JW Reading team</p>');
		}
		else if(param == "koDB")
		{
			if(lang == 'fr') $("#messageConfirm").html('<p style="text-align:center"><br>Aïe, problème<br><br>Apparemment, il y a eu un problème avec la base de données et votre compte n\'a pas été supprimé. Contactez-moi grâce au formulaire de contact pour me le signaler.<br><br> Désolé pour cet incident et à bientôt.<br><br>L\'équipe JW Reading</p>');
			else $("#messageConfirm").html('<p style="text-align:center"><br>Ouch, problem<br><br>Apparently, there has been a problem during the unsuscribing process with the database and your account is not deleted. Please, inform me about this through the contact form.<br><br> Sorry for this and we hope to see you soon.<br><br>The JW Reading team</p>');
		}
		 $('#messageConfirm').slideDown(400);
	}
	</script>
</head>
<body class="text-shadows">
	<div class="container">
		<h2 class="text-center title"><?=__('unsubscribe_title')?></h2>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 text-center" id="unsubscribeFirst">	
			<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
				<p>
				<?=__('unsubscribe_text')?> <label><?php echo $_GET['email']; ?></label><br><br>
				<input type="hidden" name="hash" value="<?php echo $_GET['hash']?>">
				<input type="hidden" name="email" value="<?php echo $_GET['email']?>">
				<button class="btn"><?=__('unsubscribe_btn')?></button>
				</p>
			</form>
			</div>
			<div class="col-sm-8 col-sm-offset-2 text-center" id="unsubscribeConfirm">  
                <div id="messageConfirm"></div>
            </div>
		</div>
	</div>
<?php
if (!empty($_POST)) {  
    //print_r($_POST);

    //connexion BD
    //include("../app/connectDB.php");
	//fichier d'envoi de mail
	//include("../app/sendMail.php");
	
	$expected = md5($_POST['email']."yoplamapoule");
	if($expected != $_POST['hash']) {
		echo '<script type="text/javascript">showUnsubscribeConfirm("koHash","");</script>';
	}
	else {
		$query = "DELETE FROM user where user_mail = '".$_POST['email']."'";
		//echo "<br>query : ".$query;
		$result = mysqli_query($mysqli, $query);
		//echo "<br>result : ".$result;
		if(!$result) echo '<script type="text/javascript">showUnsubscribeConfirm("koDB","");</script>';
		else {
			$message_body = __("unsubscribe_mail_content");
			sendMail($_POST['email'], __("unsubscribe_mail_title"), $message_body,__("unsubscribe_title"),null,null,1);
			
			//mail d'info à l'admin
			$message = "Bonjour,<br><br>".$_POST['email']." s'est désinscrit du site JW Reading.";
			$headers = "Content-Type: text/html; charset=iso-8859-1\r\n";
			$headers .= "From:noreply@jwreading.com";
			mail("anthony.tarlao@gmail.com","Notification de désinscription sur JW Reading", $message, $headers);
			echo '<script type="text/javascript">showUnsubscribeConfirm("ok","'.$_POST['email'].'");</script>';
		}
	}
}
?>
</body>
</html>

