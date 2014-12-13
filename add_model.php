<?php
require_once('dbconn.php');

$uname=$_POST['uname'];
$pass=$_POST['pass'];
$insert_array=compact("uname","pass");
$qstring='';
foreach($insert_array as $key => $value) {
  //  echo "$key - $value <br />";
	if($key!='pass')
	$qstring.='\''.trim($value).'\',';
	else
	$qstring.='\''.trim($value).'\'';

}
//echo $qstring;
	$q1="select * from 	user where username='$uname'";
	$res=mysqli_query($dbconn,$q1) or die("query error");
	if(mysqli_num_rows($res)!=0)
	{
	echo 'Duplicate entry,please go back and try different values <a href="vv.htm">Signup</a>';
	die();
	}


$query="insert into user values ('',$qstring,'')";
$res=mysqli_query($dbconn,$query) or die('query gone');
echo 'Success';
header("location:add_model.htm?uname=$uname");
