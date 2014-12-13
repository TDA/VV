<?php
require_once 'dbconn.php';
$uname=$_REQUEST['uname'];
$pass=$_REQUEST['pass'];
$query="select * from user where username='$uname'";
$res=mysqli_query($dbconn,$query) or die('error in query');

$storedpass=mysqli_fetch_array($res);
if($pass==$storedpass['pin'])
echo 1;
else 
echo 0;
?>