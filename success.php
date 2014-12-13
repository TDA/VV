<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Enrollment Successful</title>
<link rel="stylesheet" href="normalize.css">
<link rel="stylesheet" href="vv.css">
</head>

<body>
<header><h3>User Enrollment</h3></header>

<?php
require_once 'dbconn.php';
require_once 'backend/keylemon.php';

$id=$_GET['id'];

$keylemon = new KeyLemon();

$identity_id = $keylemon->get_identity();

//echo $identity_id;

//echo "backend/keylemon.php/image/".$id."";
//93c5c146-19d7-4ae6-9e28-e8faf68e10f3
?>
<table>
		<!--<tr>
			<th>Patient ID</th>
			<th>Patient Name</th>
			<th>Patient Address</th>
			<th>Patient DOB</th>
            <th>Patient Gender</th>
			<th>Cholestrol</th>
			<th>Glucose</th>
            <th>B_Chloride</th>
            <th>B_Sodium</th>
            <th>B_Albumin</th>
            <th>B_Globulin</th>
            <th>B_Creatinine</th>
            <th>B_Calcium</th>
            <th>B_Phosphorous</th>
            <th>B_Potassium</th>
			<th>WBC</th>
			<th>RBC</th>
		</tr>
         	--> 		 	
<?php
//echo 'here';

$query="SELECT * FROM `user` WHERE kl_id='$id'";
$res=mysqli_query($dbconn,$query) or die('ID doesnt exist');
$count=mysqli_num_rows($res);
$row=mysqli_fetch_assoc($res);
$user=$row['username'];
echo "<div class='center'>";
if($count!=1)
echo "Error registering user, please try again";
else 
echo "Hello, ".$user.". You have been successfully enrolled into the system"; 
echo "</div>";
/*echo "<tr>";
while($row=mysqli_fetch_assoc($res))
{
	foreach($row as $key=>$value){
	if($key=='kl_id')
	break;
	echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
	}
	
}
*/
?>

</table>

</body>
</html>