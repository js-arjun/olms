<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{?>

<!DOCTYPE html>
<html xmlns="">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | User Dash Board</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />







    

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->



    <script>
        // enable this if you want to make only one call and not repeated calls automatically
        // pushNotify();

        // following makes an AJAX call to PHP to get notification every 10 secs
        setInterval(function() { pushNotify(); }, 10000);

        function pushNotify() {
        	if (!("Notification" in window)) {
        		// checking if the user's browser supports web push Notification
        		alert("Web browser does not support desktop notification");
        	}
        	if (Notification.permission !== "granted")
        		Notification.requestPermission();
        	else {
        		$.ajax({
        			url: "push-notify.php",
        			type: "POST",
        			success: function(data, textStatus, jqXHR) {
        				// if PHP call returns data process it and show notification
        				// if nothing returns then it means no notification available for now
        				if ($.trim(data)) {
        					var data = jQuery.parseJSON(data);
        					console.log(data);
        					notification = createNotification(data.title, data.icon, data.body, data.url);

        					// closes the web browser notification automatically after 5 secs
        					setTimeout(function() {
        						notification.close();
        					}, 5000);
        				}
        			},
        			error: function(jqXHR, textStatus, errorThrown) { }
        		});
        	}
        };

        function createNotification(title, icon, body, url) {
        	var notification = new Notification(title, {
        		icon: icon,
        		body: body,
        	});
        	// url that needs to be opened on clicking the notification
        	// finally everything boils down to click and visits right
        	notification.onclick = function() {
        		window.open(url);
        	};
        	return notification;
        }
    </script>




    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">User DASHBOARD</h4>
                
                            </div>

        </div>
             
             <div class="row">


<a href="listed-books.php">
<div class="col-md-4 col-sm-4 col-xs-6">
 <div class="alert alert-success back-widget-set text-center">
 <i class="fa fa-book fa-5x"></i>
<?php 
$sql ="SELECT id from tblbooks ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$listdbooks=$query->rowCount();
?>
<h3><?php echo htmlentities($listdbooks);?></h3>
Books Listed
</div></div></a>
             
               <div class="col-md-4 col-sm-4 col-xs-6">
                      <div class="alert alert-warning back-widget-set text-center">
                            <i class="fa fa-recycle fa-5x"></i>
<?php 
$rsts=0;
 $sid=$_SESSION['stdid'];
$sql2 ="SELECT id from tblissuedbookdetails where StudentID=:sid and (RetrunStatus=:rsts || RetrunStatus is null || RetrunStatus='')";
$query2 = $dbh -> prepare($sql2);
$query2->bindParam(':sid',$sid,PDO::PARAM_STR);
$query2->bindParam(':rsts',$rsts,PDO::PARAM_STR);
$query2->execute();
$results2=$query2->fetchAll(PDO::FETCH_OBJ);
$returnedbooks=$query2->rowCount();
?>

                            <h3><?php echo htmlentities($returnedbooks);?></h3>
                          Books Not Returned Yet
                        </div>
                    </div>

<a href="issued-books.php">
<div class="col-md-4 col-sm-4 col-xs-6">
 <div class="alert alert-success back-widget-set text-center">
 <i class="fa fa-book fa-5x"></i>
      <h3>&nbsp;</h3>
Issued Books
</div></div></a>
<a href="reserved-books.php">
<div class="col-md-4 col-sm-4 col-xs-6">
 <div class="alert alert-success back-widget-set text-center">
 <i class="fa fa-book fa-5x"></i>
      <h3>&nbsp;</h3>
Reserved Books
</div></div></a>




        </div>    
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
