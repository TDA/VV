<?php
//require_once 'backend/keylemon.php';
require_once 'dbconn.php';

    
//$keylemon = new KeyLemon();

//$identity_id = $keylemon->get_identity();
if(@$_GET['uname']){
$uname=$_GET['uname'];

}
else{
$uname=$_POST['uname'];
$identity_id=$_POST['id'];
}
/*echo $uname;
echo 'end';

echo $identity_id;
*/
$updatequery="UPDATE user SET kl_id='$identity_id' WHERE username='$uname'";

echo $updatequery;

$res=mysqli_query($dbconn,$updatequery) or die('bust21');

$updatequery="UPDATE `mrn` SET kl_id='$identity_id' WHERE username='$uname'";
$res=mysqli_query($dbconn,$updatequery) or die('bust22');


?>