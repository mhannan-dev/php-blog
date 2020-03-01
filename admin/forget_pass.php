<?php 
	include '../lib/Session.php'; 
	Session::checkLogin();
?>
<?php include '../config/config.php'; ?>
<?php include '../lib/Database.php'; ?>
<?php include '../helpers/Format.php'; ?>
<?php
    $db = new Database();
    $fm = new Format();
?> 
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Password Recovery</title>
    <link rel="stylesheet" type="text/css" href="css/stylelogin.css" media="screen" />
</head>
<body>
<div class="container">
	<section id="content">
		<?php 
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				$email = $fm->validation($_POST['email']);
				$email = mysqli_real_escape_string ($db->link, $email);
				

				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo "<span style='color:red'>In valid email</span>";		
                    } else{

							
							$mail_query 		= "SELECT * FROM tbl_user WHERE email='$email' LIMIT 1";
                            $mailCheck 			= $db->select($mail_query);
							
							if ($mailCheck 		!= false) {
								while ($value 	= $mailCheck->fetch_assoc()) {
									$userid 	= $value['id'];
									$username 	= $value['username'];
								}
								$text 			= substr($email, 0, 3);
								$rand_digit 	= rand(10000, 99999);
								$new_pass 		= "$text$rand_digit";
								$md5_password 	= md5($new_pass);

								$query  		= "UPDATE tbl_user SET password='$md5_password' WHERE id='$userid'";
                            	$pass_update 	= $db->update($query);

                            	$to  			= "$email";
                            	$from  			= "hannan@arobil.com";
                            	$headers  		= "FROM : $from\n";
                            	// To send HTML mail, the Content-type header must be set
								$headers[] 		.= 'MIME-Version: 1.0';
								$headers[] 		.= 'Content-type: text/html; charset=iso-8859-1';
								$subject 		= "Your password";
								$message 		= "Your username is".$username."and password is".$new_pass."Please visit web site to login";
                            	$sendMail 		= mail($to, $subject, $message, $headers);
                            		
                            	if ($sendMail) {
									 
									echo "<span style='color:green'>Please check your email for new password</span>";		

                            	} else{

                            		echo "<span style='color:red; font-size:18px;'>Mail not sent</span>";		
                            	}
                                

						}  else {
								echo "<span style='color:red; font-size:18px;'>No mail found..!!</span>";		
                    }
                }
			}
		?>
		<form action="" method="post">
			<h1>Password Recovery</h1>
			<div>
				<input type="text" placeholder="Email email" required="" name="email"/>
			</div>
			
			<div>
				<input type="submit" name="login" value="Send mail" />
			</div>
		</form><!-- form -->

			<div class="button">
				<a href="login.php">Login</a>
			</div>
		
	</section><!-- content -->
</div><!-- container -->
</body>
</html>