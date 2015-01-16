<?php
session_start();
include("../../app/utils.php");

if (isset($_POST['loginInput']) && isset($_POST['pwdInput'])){
    $query = 'SELECT user_id, user_password, user_cycle_first_day, '
    .'user_day_1, user_day_2, user_day_3, user_day_4, user_day_5, user_day_6, user_day_7, user_daily_comment, user_first_name, '
    .'user_reading_param, user_daily_comment_param, sending_displayed, time_cities, time_utc, time_zone '
    .'FROM user '
    .'LEFT JOIN sending_time ON user.user_sending_id = sending_time.sending_id '
    .'LEFT JOIN timezone ON sending_time.timezone_id = timezone.time_id '
    .'WHERE user_mail = "'.$_POST['loginInput'].'"';
    //echo "<br>query : ".$query;
    $result = mysqli_query($mysqli, $query);
    //echo "<br>result : "; print_r($result);
    $row = mysqli_fetch_array($result);
    //echo "row0 = ".$row[0]."<br>row1 = ".$row[1]."<br>";
    if(!$row[0]) echo "-1";//si pas de réponse, c'est que l'adresse mail n'existe pas 
    else {
        if (password_verify($_POST['pwdInput'], $row[1])) { //$row[1] contient le hash du password issu de la BD
			$_SESSION['id'] = $row[0]; 
            $_SESSION['password'] = $row[1];
            $_SESSION['firstDay'] = $row[2];
            $_SESSION['day1'] = $row[3];
            $_SESSION['day2'] = $row[4];
            $_SESSION['day3'] = $row[5];
            $_SESSION['day4'] = $row[6];
            $_SESSION['day5'] = $row[7];
            $_SESSION['day6'] = $row[8];
            $_SESSION['day7'] = $row[9];
            $_SESSION['dailyComment'] = $row[10];
            $_SESSION['name'] = $row[11];
            $_SESSION['readingLang'] = $row[12];
            $_SESSION['commentLang'] = $row[13];
            $_SESSION['time_displayed'] = $row[14];
            $_SESSION['time_cities'] = $row[15];
            $_SESSION['time_utc'] = $row[16];
            $_SESSION['time_zone'] = $row[17];
            $_SESSION['email'] = $_POST['loginInput'];  
			//recuperation de la derniere ligne de modif decalée (s'il en existe au moins une)
   //          $query = 'SELECT mod_cycle_first_day,mod_day_1,mod_day_2,mod_day_3,mod_day_4,mod_day_5,mod_day_6,mod_day_7 from modification where user_id = '.$_SESSION['id'].' order by mod_id desc';
   //          $result = mysqli_query($mysqli, $query);
			// $count = mysqli_num_rows($result);
   //          $row = mysqli_fetch_array($result);
   //          if($count > 0){ //si le résultat renvoyé est au moins 1, c'est que cet utilisateur a au moins 1 modif décalée en cours 
			// 	$_SESSION['mod_firstDay'] = $row[0];
			// 	$_SESSION['mod_day1'] = $row[1];
			// 	$_SESSION['mod_day2'] = $row[2];
			// 	$_SESSION['mod_day3'] = $row[3];
			// 	$_SESSION['mod_day4'] = $row[4];
			// 	$_SESSION['mod_day5'] = $row[5];
			// 	$_SESSION['mod_day6'] = $row[6];
			// 	$_SESSION['mod_day7'] = $row[7];
   //              echo '2'; //l'état 2 permet la connexion au compte et affichera un message à propos des modif décalées
			// }
   //          else 
            echo '1'; //l'état 1 permet la connexion au compte simplement
        } else {
            //echo "Le mot de passe est invalide.";
            echo '0';
        }
    }
}
else
echo "post error";

?>