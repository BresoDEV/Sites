<?php
include("inc/php/header.php");
if (!($user -> isAdmin($odb)))//if user is not admin return to main
{
	header('location: index');
	die();
}
?>

<?php



function RANDOM_KEY($length = 7) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321';
    $string = '';

    for ($i = 0; $i < $length; $i++)
    {
    	$string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $string;
}

//$gkey = RANDOM_KEY();
//var gkey = RANDOM_KEY();
//print_r($gkey);

//if ($user == checkout)

/*after payament run a script php on your website a 
check to check on the web if the payement is done enter email , 
auto generated key  auto inserted on the db  
(using the script addlicense.php, and auto send message )
Im gonna start to create the script
i need help with testing if can i generate random keys on php*/


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
				$Name = $_POST['name'];
    $email = $_POST['email'];   
  if (empty($Name) || empty($email)) {
	      echo error('Fill all the fields');
   }
  else
  {
    $SQLinsert = $odb -> prepare("INSERT INTO `users` VALUES(:license, :name, `dateline`, NULL, 0, 0, 0, 0, 0, :email, 0, 0)");
  $emptyString = '';

  $gkey = RANDOM_KEY();

  $SQLinsert -> execute(array(':license' => $gkey, ':email' => $email, ':name' => $Name));//intupspaces


              //Send Email
              $to      = $email;
              $subject = 'Predator SPRX License Key';
              $message = 'Hey! Thanks For Your Purchase! '.htmlspecialchars($Name).' Your License Key Is: '.htmlspecialchars($gkey).' Please Dont Reply This Message';
              $headers = 'From: admin@example.com' . "\r\n" .'Reply-To: admin@example.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
              mail($to, $subject, $message, $headers);
              ++$n;
              if(0 == $n)
            {
             echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>ERROR:</strong> Email is invalid</div>';
            }//end send

session_start();
unset($_SESSION['license']);
unset($_SESSION['email']);
unset($_SESSION['name']);
session_destroy();
header('location: login');

  echo success('License key has been Sent');
  }
			}
            ?>
		        <h2 class="form-login-heading">Your Email To Send your Key</h2>
            <form method="post">
		        <div class="login-wrap">
						<div class="form-group">
					<input class="form-control" name="name" placeholder="Your Name or ID" type="text" />
						</div>
						<div class="form-group">
					<input class="form-control" name="email" placeholder="Your Email.." type="email" />
						</div>
						<div class="form-group">
						<input class="form-control" name="email" placeholder="Your Email Confirm" type="email" />
						</div>
            <hr>
						<div class="form-group">
		            <button class="btn btn-theme btn-block" name="addBtn" type="submit"><i class="fa fa-plus">&nbsp;</i>Send Your Information Purchase</button>
						</div>
		            <hr>

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