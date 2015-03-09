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
else {echo "post error";}

// if (isset($_POST['pass'])){
//     $user_password=mysqli_real_escape_string($mysqli, $_POST['pass']);
//     $hash = password_hash($user_password, PASSWORD_DEFAULT);
//     $user_id = mysqli_real_escape_string($mysqli, $_POST['id']);
//     $sql = "UPDATE user SET user_password='$hash' WHERE user_id = $user_id";
//     //echo "<script>console.log('arrivee dans la page checkEmail.php /// email = $email /// account = $account');</script>";
//     $result=mysqli_query($mysqli, $sql) or die("fail");
//     if(!$result) echo 0;
//     else echo "OK.".$hash;
// }
// else
// echo "post error";

?>