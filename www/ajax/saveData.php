<?php

include("../../app/utils.php");

// var_dump($_POST);

if (isset($_POST['accountId'])){ //modification de compte
	if(isset($_POST['toggleAccountDailyText'])) { $user_daily_comment = 1; }
	else { $user_daily_comment = 0; }
    if(isset($_POST['toggleAccountDay1'])){ $user_day_1 = 1; }
    else { $user_day_1 = 0; }
    if(isset($_POST['toggleAccountDay2'])){ $user_day_2 = 1; }
    else { $user_day_2 = 0; }
    if(isset($_POST['toggleAccountDay3'])){ $user_day_3 = 1; }
    else { $user_day_3 = 0; }
    if(isset($_POST['toggleAccountDay4'])){ $user_day_4 = 1; }
    else { $user_day_4 = 0; }
    if(isset($_POST['toggleAccountDay5'])){ $user_day_5 = 1; }
    else { $user_day_5 = 0; }
    if(isset($_POST['toggleAccountDay6'])){ $user_day_6 = 1; }
    else { $user_day_6 = 0; }
    if(isset($_POST['toggleAccountDay7'])){ $user_day_7 = 1; }
    else { $user_day_7 = 0; }
    
    $user_first_name = $_POST['nameAccount'];
    $user_mail = $_POST['emailAccount'];
    $user_cycle_first_day = $_POST['firstDayRadio'];
    $user_reading_param = $_POST['hiddenReadingLang'];
    $user_daily_comment_param = $_POST['hiddenCommentLang'];
    $user_sending_id = $_POST['accountTimeId'];
    $user_id = $_POST['accountId'];
    
	$queryChange = 'UPDATE user SET user_first_name = "'.$user_first_name.'", 
        user_mail = "'.$user_mail.'", user_daily_comment = '.$user_daily_comment.', 
        user_cycle_first_day = '.$user_cycle_first_day.', user_day_1 = '.$user_day_1.', 
        user_day_2 = '.$user_day_2.', user_day_3 = '.$user_day_3.', user_day_4 = '.$user_day_4.', 
        user_day_5 = '.$user_day_5.', user_day_6 = '.$user_day_6.', user_day_7 = '.$user_day_7.', 
        user_reading_param = "'.$user_reading_param.'", user_daily_comment_param = "'.$user_daily_comment_param.'", 
        user_sending_id = '.$user_sending_id.'  WHERE user_id = '.$user_id;
        // echo "<br>query : ".$queryChange;
        $result = mysqli_query($mysqli, $queryChange);
        if(!$result) {echo 0;}
	    else {echo "OK";}
}
else //création d'utilisateur
{
	var_dump($_POST);
	$jours_reception = "";

  	if(isset($_POST['toggleDailyText'])) { $user_daily_comment = 1; }
	else { $user_daily_comment = 0; }
    if(isset($_POST['toggleDay1'])){ 
    	$user_day_1 = 1;
    	$jours_reception .= ", ".__('subscribe_mail_monday');
    }
    else { $user_day_1 = 0; }
    if(isset($_POST['toggleDay2'])){ 
    	$user_day_2 = 1;
    	$jours_reception .= ", ".__('subscribe_mail_tuesday');
    }
    else { $user_day_2 = 0; }
    if(isset($_POST['toggleDay3'])){ 
    	$user_day_3 = 1; 
    	$jours_reception .= ", ".__('subscribe_mail_wednesday');
    }
    else { $user_day_3 = 0; }
    if(isset($_POST['toggleDay4'])){ 
    	$user_day_4 = 1; 
    	$jours_reception .= ", ".__('subscribe_mail_thursday');
    }
    else { $user_day_4 = 0; }
    if(isset($_POST['toggleDay5'])){ 
    	$user_day_5 = 1;
    	$jours_reception .= ", ".__('subscribe_mail_friday');
    }
    else { $user_day_5 = 0; }
    if(isset($_POST['toggleDay6'])){ 
    	$user_day_6 = 1; 
    	$jours_reception .= ", ".__('subscribe_mail_saturday');
    }
    else { $user_day_6 = 0; }
    if(isset($_POST['toggleDay7'])){ 
    	$user_day_7 = 1;
    	$jours_reception .= ", ".__('subscribe_mail_sunday');
    }
    else { $user_day_7 = 0; }
    
    $user_first_name = $_POST['name'];
    $user_mail = $_POST['email'];
    $user_cycle_first_day = $_POST['firstDayRadio'];
    $user_reading_param = $_POST['radiosLangReading'];
    $user_daily_comment_param = $_POST['radiosLangText'];
    $user_sending_id = $_POST['subscribeChosenTime'];
    $jours_reception = substr($jours_reception, 1);

    $user_password = generateRandomString(); 
    $hash = password_hash($user_password, PASSWORD_DEFAULT); //securité PHP5.5 : hash + salt
    //echo "<br>password : ".$user_password;
    $user_registration_day = date('Y-m-d') ;
    $unsubscribe_hash = md5($user_mail."yoplamapoule");
    $query = 'INSERT INTO user(user_first_name, user_mail, user_password, user_cycle_first_day, user_registration_day, user_day_1,  user_day_2, user_day_3, user_day_4, user_day_5, user_day_6, user_day_7, user_daily_comment, unsubscribe_hash, user_daily_comment_param, user_reading_param, user_sending_id) VALUES ("'.$user_first_name.'", "'.$user_mail.'", "'.$hash.'" , '.$user_cycle_first_day.', "'.$user_registration_day.'", '.$user_day_1.', '.$user_day_2.', '.$user_day_3.', '.$user_day_4.', '.$user_day_5.', '.$user_day_6.', '.$user_day_7.', '.$user_daily_comment.', "'.$unsubscribe_hash.'", "'.$user_daily_comment_param.'", "'.$user_reading_param.'", '.$user_sending_id.')';
    //echo "<br>query : ".$query;
    $result = mysqli_query($mysqli, $query);
    //echo "<br>result : ".$result;
    $response = array();
    if(!$result) $response[] = 'KO';//echo '<script type="text/javascript">showSubscribeConfirm("ko","","","","","");</script>';//$_SESSION['subscribe'] = "ko"; //header('Location: index.html?subscribe=ko');
    else {
        $message_body = __("subscribe_mail_thks").' '.$user_first_name.' '.__("subscribe_mail_content_1").' '.$jours_reception;
        if($user_daily_comment == 1) $message_body .= ' '.__("subscribe_mail_content_2");
         $message_body .= __("subscribe_mail_content_3").' <b>'.$user_password.'</b>'.__("subscribe_mail_content_4");
        sendMail($user_mail, __("subscribe_mail_title"), $message_body,__('subscribe_title'), $user_mail, $unsubscribe_hash); 
        $response[] = 'OK';
        $response[] = $user_first_name;
        $response[] = $user_mail;
        $response[] = $jours_reception;
        $response[] = $user_daily_comment;
        $response[] = $user_password;
        // echo '<script type="text/javascript">showSubscribeConfirm("ok", "'.$user_first_name.'", "'.$user_mail.'", "'.$jours_reception.'", "'.$user_daily_comment.'", "'.$user_password.'");</script>';
    }
    return $response;
}


?>