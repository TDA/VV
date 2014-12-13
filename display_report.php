<?php
require_once 'dbconn.php';
require_once 'backend/keylemon.php';

if(isset($_SESSION['uname']))
$uname=$_SESSION['uname'];
//echo "1".$uname;
if(isset($_SESSION['mrn']))
$mrn=$_SESSION['mrn'];
//echo $mrn;

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>idlink - Display Reports</title>
<!--[if lt IE 9]>  
<script src="html5shiv.js"></script> 
<script src="respond.min.js" type="text/javascript"></script>
<script src="selectivizr-min.js" type="text/javascript"></script>
 
<![endif]--> 

<link rel="stylesheet" href="normalize.css">
<link rel="stylesheet" href="vv.css">

</head>

<body>
<header>
    <img src="Images/1.jpg" alt="St.Josephs" class="half_width">
    <h3 class="">Affiliates</h3>
    <p>	
 	    <a href="affiliates.htm" class="aff"><img src="Images/affiliates.jpg" alt="Affiliate Links"></a>
        <a href="affiliates.htm" class="aff"><img src="Images/affiliates2.jpg" alt="Affiliate links"></a>
        
	</p>

    </header>       
    
<div class="reports">
    
</div>
<div class="nav">
    <p>
    <img src="Images/1.jpg" alt="St.Josephs" class="icon right" data-db="st_josephs" data-clicked="false">
    <img src="Images/3.jpg" alt="Dignity Health" class="icon right" data-db="dignity_health" data-clicked="false">
    <img src="Images/2.jpg" alt="Dignity - Westgate" class="icon right" data-db="dignity_westgate" data-clicked="false"></p>
    </div>





<script type="text/javascript" src="Scripts/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
	}
}

$(document).ready(function(){
	var mrn=GetURLParameter('mrn');
	
	$('header').addClass('hidden');
	
	$('.report-img').click(function(e) {
        //var target=$(this).attr('href');
		$(this).toggleClass('zoom-center');
		//alert($(target).hasClass('afflink'));
    });
	var clicks=$('img[data-clicked=true]').length;
	if(clicks==0)
	alert('none');
	else
	$('header').removeClass('hidden');
	
	$(".icon").click(function(){
		//alert(this.dataset.db);
		$('header').removeClass('hidden').addClass('visible');
		var tb=this.dataset.db;
		if(this.dataset.clicked==="true")
		return false;
		
		this.dataset.clicked=true;
		$.ajax({
			'url':'query_db.php',
			'data':{
				'tname':tb,
				'mrn':mrn 
				}
			
		}).done(function(res){
			//alert(res);
			
			$(".reports").append('<div class="caption">'+tb+'</div>');
			var x=$("<table>"+res+"</table>");
			$(".reports").append(x);
			$(".reports").append("<hr/>");
			$(x).hide();
			$(x).show(1500);
			
			
			});
		});
	
	
	
	});
    </script>
</body>
</html>
<?php
session_destroy();
?>