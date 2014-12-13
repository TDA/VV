<?php
require_once 'dbconn.php';
$tablename=$_GET['tname'];
$mrn=$_GET['mrn'];

//echo $tablename;
//echo $mrn;
$query="select id,first_name,last_name,MRN from $tablename where MRN='$mrn'";
$res=mysqli_query($dbconn,$query) or die('error in query');
//$count=mysqli_num_rows($res);
//echo $count;
$i=1;
while($row=mysqli_fetch_assoc($res))
foreach($row as $key=>$value){
	echo "<tr><td>".$key."</td><td>".$value."</td></tr>";	
	

}

?>