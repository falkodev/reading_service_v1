<?php
include("../../app/utils.php");

if (isset($_POST['email'])){
	$tab_lang = include('../../app/lang/'.$_POST['lang'].'.php');
	$user_password = generateRandomString(); 
	$hash = password_hash($user_password, PASSWORD_DEFAULT); //securité PHP5.5 : hash + salt
    $user_mail = mysqli_real_escape_string($mysqli, $_POST['email']);
	$unsubscribe_hash = md5($user_mail."yoplamapoule");
	//test si l'adresse est existante
	$sql = "SELECT user_id FROM user WHERE user_mail = '$user_mail'";
	$result=mysqli_query($mysqli, $sql) or die("fail");
	//print_r($result);
	if(!$result) echo 0;
	elseif(mysqli_num_rows($result) > 0)
    {//si adresse existante, on met à jour en BD
		$sql = "UPDATE user SET user_password='$hash', unsubscribe_hash='$unsubscribe_hash' WHERE user_mail = '$user_mail'";
		//echo "<script>console.log('arrivee dans la page checkEmail.php /// email = $email /// account = $account');</script>";
		$result=mysqli_query($mysqli, $sql) or die("fail");
		if(!$result) echo 0;
		else {
			$msg = __('send_new_pwd_content_1').'<b>'.$user_password.'</b>'.__('send_new_pwd_content_2');
			sendMail($user_mail, __('send_new_pwd_subject'), $msg, __('send_new_pwd_title'),$user_mail, $unsubscribe_hash);
			echo "OK";
		}
	}
	else echo 0;//si adresse inexistante, on renvoie une erreur
}
else
echo "post error";

?>		