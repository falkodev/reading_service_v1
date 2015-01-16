<?php

include("../../app/utils.php");

if (isset($_POST['pass'])){
    $user_password=mysqli_real_escape_string($mysqli, $_POST['pass']);
    $hash = password_hash($user_password, PASSWORD_DEFAULT);
    $user_id = mysqli_real_escape_string($mysqli, $_POST['id']);
    $sql = "UPDATE user SET user_password='$hash' WHERE user_id = $user_id";
    //echo "<script>console.log('arrivee dans la page checkEmail.php /// email = $email /// account = $account');</script>";
    $result=mysqli_query($mysqli, $sql) or die("fail");
    if(!$result) echo 0;
    else echo "OK.".$hash;
}
else
echo "post error";

?>
