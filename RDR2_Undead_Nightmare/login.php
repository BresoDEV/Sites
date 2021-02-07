<?php
require 'inc/php/php.php';
if ($user -> LoggedIn())
{
header('Location: index');
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

    <title>Predator SPRX Users</title>

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
            if(isset($_POST['logBtn'])) {
            $license = $_POST['license'];
            //Is it empty?
            if (empty($license) || strlen($license) < 7 || strlen($license) > 9) {
            die(error('Please fill in all fields.'));
            }

            //Check key
            $SQLCheckLogin = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE `license` = :license");
            $SQLCheckLogin -> execute(array(':license' => $license));
            $countLogin = $SQLCheckLogin -> fetchColumn(0);
            if (!($countLogin == 1)) {
            die(error('License key is invalid.'));
            }

            //Check if the key is banned
            $SQL = $odb -> prepare("SELECT `status` FROM `users` WHERE `username` = :username");
            $SQL -> execute(array(':username' => $username));
            $status = $SQL -> fetchColumn(0);
            if ($status == 1) {
            die(error('You are banned'));
            }

            //Success, log in
            $SQL = $odb -> prepare("SELECT * FROM `users` WHERE `license` = :license");
            $SQL -> execute(array(':license' => $license));
            $keyInfo = $SQL -> fetch();
            $_SESSION['license'] = $keyInfo['license'];
            $_SESSION['id'] = $keyInfo['id'];
            header("Location: index");
            }

            //Start forgot license

            if (isset($_POST['doBtn'])) {
            $SQLGetInfo = $odb -> prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
            $n = 0;
            $SQLGetInfo -> execute(array(':email' => $_POST['email']));
            while($row = $SQLGetInfo ->fetch())
            {
              $showLicense = $row['license'];
              $showEmail = $row['email'];
              $currentIP = $_SERVER['REMOTE_ADDR'];
              $showID = $row['id'];
              //Send Email
              $to      = $showEmail;
              $subject = 'License Key';
              $message = 'Hello, you have requested a license key reset.<br>Requested IP: '.htmlspecialchars($currentIP).'<br>Your License Key: '.htmlspecialchars($showLicense).'';
              $headers = 'From: admin@example.com' . "\r\n" .'Reply-To: admin@example.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
              mail($to, $subject, $message, $headers);
              echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>SUCCESS:</strong> Email has been sent!</div>';
              ++$n;
            }
            if(0 == $n)
            {
             echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>ERROR:</strong> Email is invalid</div>';
            }
          }
            ?>
		        <h2 class="form-login-heading">Log In With Your Key</h2>
            <form method="post">
		        <div class="login-wrap">
					<input class="form-control" name="license" placeholder="KEY-KEY" type="text" />
		            <label class="checkbox">
		                <span class="pull-right">
		                    <a data-toggle="modal" href="#forgotKey"> Forgot your key?</a>

		                </span>
		            </label>
		            <button class="btn btn-theme btn-block" name="logBtn" type="submit"><i class="fa fa-lock">&nbsp;</i> LOGIN</button>
		            <hr>

		            <div class="registration">
		                Don't have a key yet?<br/>
		                <a class="" href="#">
		                    Purchase a key
		                </a>
		            </div>

		        </div> </form>
		          <div aria-hidden="true" aria-labelledby="forgotKey" role="dialog" tabindex="-1" id="forgotKey" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Forgot your key ?</h4>
		                      </div>
                          <form method="post">
		                      <div class="modal-body">
		                          <p>Enter your e-mail address below to receive your key - This process is instant.</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" name="doBtn" type="submit">Submit</button>
		                      </div>
                         </form>
		                  </div>
		              </div>
		          </div>
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
