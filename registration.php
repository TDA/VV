<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Registration Successful</title>
<link rel="stylesheet" href="normalize.css">
<link rel="stylesheet" href="vv.css">
</head>

<body>
<header><h3>User Registration</h3></header>

<?php
require_once 'dbconn.php';
require_once 'backend/keylemon.php';


$keylemon = new KeyLemon();

$id = $keylemon->get_identity();
$mrn=$_POST['mrn'];
$iid=$_POST['iid'];

$query="SELECT * FROM `user` WHERE kl_id='$id'";
$res=mysqli_query($dbconn,$query) or die('ID doesnt exist');
$count=mysqli_num_rows($res);
$row=mysqli_fetch_assoc($res);
$user=$row['username'];
echo "<div class='center'>";
if($count!=1)
echo "Error registering user, please try again";
else 
echo "Hello, ".$user.". You have been successfully registered into the system with MRN:$mrn and IID:$iid"; 
echo "</div>";
?>
</body>
</html>