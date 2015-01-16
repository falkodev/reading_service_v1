<?php

include("../../app/utils.php");

if (isset($_POST['email'])){
    $email=mysqli_real_escape_string($mysqli, $_POST['email']);
    $sql = "SELECT * FROM user WHERE user_mail='$email'";
    if (isset($_POST['account'])) $sql .= " and user_id <>".$_POST['account'];
    //$account = mysqli_real_escape_string($mysqli, $_POST['account']);
    //echo "<script>console.log('arrivee dans la page checkEmail.php /// email = $email /// account = $account');</script>";
    $select=mysqli_query($mysqli, $sql) or die("fail");
    $row = mysqli_num_rows($select);

    if ($row > 0) echo 1;
    else echo 0;

}
else
echo "post error";

?>
