<?php
include("inc/php/header.php");
if (!($user -> isAdmin($odb)))
{
	header('location: index');
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="EERZZ">
    <meta name="keyword" content="">

    <title>EERZZ | Add Key</title>

    <!-- CSS -->
    <link href="inc/assets/css/bootstrap.css" rel="stylesheet">
    <link href="inc/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom -->
    <link href="inc/assets/css/style.css" rel="stylesheet">
    <link href="inc/assets/css/style-responsive.css" rel="stylesheet">

    <style>
        .input-error { border: 1px solid red; }
    </style>

</head>

  <body>

      <!-- Main -->

	  <div id="login-page">
	  	<div class="container">
		      <form class="form-login" action="" method="post">
				  				            <?php
            if(isset($_POST['addBtn'])) {
				$license = $_POST['license'];
    $email = $_POST['email'];
    $ipaddr = $_POST['ipaddr'];
  if (empty($license) || empty($email) || empty($ipaddr)) {
	      echo error('Please fill in all the fields');
   }
  else
  {
	  //$SQLinsert = $odb -> prepare("INSERT INTO `users` VALUES(NULL, :license, :email, 0, 0, :ip)");
    $SQLinsert = $odb -> prepare("INSERT INTO `users` VALUES(:license, :ip, `dateline`, NULL, 0, 0, 0, 0, 0, :email, 0, 0)");
  $emptyString = '';
  $SQLinsert -> execute(array(':license' => $license, ':email' => $email, ':ip' => $ipaddr));//intupspaces
  echo success('License key has been added');
  }
			}
            ?>
		        <h2 class="form-login-heading">ADD LICENSE</h2>
            <form method="post">
		        <div class="login-wrap">
						<div class="form-group">
					<input class="form-control" name="license" placeholder="KEY-KEY" type="text" />
						</div>
						<div class="form-group">
					<input class="form-control" name="email" placeholder="Email.." type="email" />
						</div>
						<div class="form-group">
					<input class="form-control" name="ipaddr" placeholder="IP.." type="text" />
						</div>
						<div class="form-group">
		            <button class="btn btn-theme btn-block" name="addBtn" type="submit"><i class="fa fa-plus">&nbsp;</i> ADD KEY</button>
						</div>
		            <hr>

		            <div class="registration">
		                Don't have a key yet?<br/>
		                <a class="" href="#">
		                    Purchase a key
		                </a>
		            </div>

		        </div> </form>
		      </form>

	  	</div>
	  </div>

    <script src="inc/assets/js/jquery.js"></script>
    <script src="inc/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="inc/assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("inc/assets/img/gta-background-blur.jpg", {speed: 500});
    </script>
  </body>
</html>
