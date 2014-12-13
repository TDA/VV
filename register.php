<?php
require_once 'dbconn.php';
require_once 'backend/keylemon.php';

$uname=$_POST['uname'];
$pass=$_POST['pass'];

$query="select * from user where username='$uname'";
//echo "select password from users where uname=$uname";
$res=mysqli_query($dbconn,$query) or die('error in query');

$storedpass=mysqli_fetch_array($res);
if($pass==$storedpass['pin']){
	$id=$storedpass['kl_id'];
	$keylemon = new KeyLemon();
	//if(!isset($_SESSION['uname']))
	$_SESSION['uname']=$uname;
	$query2="select * from mrn where username='$uname' AND kl_id='$id'";
	$res2=mysqli_query($dbconn,$query2) or die('error in query');
	$row2=mysqli_fetch_array($res2);
	foreach($row2 as $key => $value){
	//echo $key;	
	}
	$mrn=$row2['MRN'];
	//if(!isset($_SESSION['mrn']))
	//$_SESSION['mrn']=$mrn;
	
   
	$keylemon->set_identity($id);
	 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Idlinc - Register</title>
<link rel="stylesheet" href="normalize.css">

<script src="frontend/keylemon.min.js"></script>
</head>

<body>
<div id="keylemon"></div>

<div id="mrn-form" class="center">
<form action="registration.php" method="post">
	<label for="mrn">MRN:</label>
	<input type="text" id="mrn" name="mrn"><br>
	<label for="iid">Institution ID</label>
	<input type="text" id="iid" name="iid">
	<input type="submit" value="Register">
</form>
</div>

<script type="text/javascript">
	$('#mrn-form').addClass('hidden');
        require(['keylemon'], function (keylemon) {
              keylemon.init({
                  modality : "face",
                  func : "authenticate",
                  target : '#keylemon',
                  backendUrl : "backend/keylemon.php",
             	  bundlePath : "frontend",
			      /*floating : {
					  closable:true,
					  title:'modal-window',
					  modalId:'modal-window',
					  onClose:function(){
						  alert('closed');
						  }
					  },*/
                  preview : {
                      width : 466,
                      height: 350
                  },
                  faceDetect :  true,
                  eyeBlink :    true,
                  nbAuthenticationMax : 5,
                  bootstrap :   true,
                  fontawesome : true,
                  onFinish : function(authenticated, liveness){ 
                      alert("authenticated : " + authenticated + "\nliveness : " + liveness);
					  if(authenticated){
					  $('#mrn-form').removeClass('hidden').addClass('visible');
					  $('#keylemon').hide();
					  }
                  },
                  onInitialized : function(){
                    // Library completely loaded and intialized
                  }
            })
        })
</script>
<link rel="stylesheet" href="vv.css">

</body>
</html>

    
    
    <?php
}
else
echo "Invalid password. Please try again";



?>