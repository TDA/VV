<?php
require_once 'dbconn.php';
require_once 'backend/keylemon.php';

$uname=$_POST['uname'];
//$pass=$_POST['pass'];

$query="select * from user where username='$uname'";
$res=mysqli_query($dbconn,$query) or die('error in query');

$storedpass=mysqli_fetch_array($res);
	$id=$storedpass['kl_id'];
	$keylemon = new KeyLemon();
	//if(!isset($_SESSION['uname']))
	$_SESSION['uname']=$uname;
	$query2="select * from mrn where username='$uname' AND kl_id='$id'";
	$res2=mysqli_query($dbconn,$query2) or die('error in query');
	$row2=mysqli_fetch_array($res2);
	
	$mrn=$row2['MRN'];
	
	$keylemon->set_identity($id);
	 
	?>
    <!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Idlinc - Authentication</title>
<link rel="stylesheet" href="normalize.css">

<script src="frontend/keylemon.min.js"></script>
</head>

<body>
<div id="keylemon"></div>
<div class="center">

	<label for="pass">PIN:</label>
	<input type="password" placeholder="Password" id="pass" name="pass" required pattern="\d{4}">
    <input type="button" value="Verify">
</div>

<script type="text/javascript">
var auth=false;

        require(['keylemon'], function (keylemon) {
              keylemon.init({
                  modality : "face",
                  func : "authenticate",
                  target : '#keylemon',
                  backendUrl : "backend/keylemon.php",
             	  bundlePath : "frontend",
			      floating : false,
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
					  if(authenticated)
					  auth = true;
					  
                  },
                  onInitialized : function(){
                    // Library completely loaded and intialized
                  }
            })
        })
</script>
<link rel="stylesheet" href="vv.css">
<script>
$('input[type=button]').click(function(e) {
	var pass=$('#pass').val();
	var uname=<?php echo "'$uname'";?>;
	//alert('in');
    $.ajax({
		'url':'check_password.php',
			'data':{
				'uname':uname,
				'pass':pass
				}
			
		}).done(function(res){
			alert(res);
				if(auth==1&&res==1)
				window.location="display_report.php?uname="+<?php echo "'$uname'" ?>+"&mrn="+<?php  echo "'$mrn'" ?>;
				else
				alert('please enter the correct PIN');
				
			});
		//alert('here');
	return false;	
});
</script>
</body>
</html>

    
    
