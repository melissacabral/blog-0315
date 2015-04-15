<?php 
session_start();
//connect to database
require('../db-connect.php');
include_once( INCLUDES_PATH . 'functions.php' );

//parse the form if it was submitted
if( $_POST['did_register'] ){
	//sanitize
	$username 	= clean_input($_POST['username']);
	$email 		= clean_input($_POST['email']);
	$password 	= clean_input($_POST['password']);
	$policy 	= clean_input($_POST['policy']); 
	
	//validate
	$valid = true;

	//check username length for minimum & maximum
	if( strlen($username) >= 5 && strlen($username) <= 50 ){
		//check to see if it already exists
		$query 	 = "SELECT username 
					FROM users 
					WHERE '$username' = username
					LIMIT 1";
		$result = $db->query($query);
		if($result->num_rows == 1){
			$valid = false;
			$errors['username'] = 'Sorry, your username is already taken.';
		}
	}else{
		$valid = false;
		$errors['username'] = 'Your username must be between 5 and 50 characters long.';
	} //end username check

	//check for valid email
	if( filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
		//check if the email is already taken
		$query = "SELECT email 
					FROM users
					WHERE '$email' = email
					LIMIT 1";
		$result = $db->query($query);
		if($result->num_rows == 1){
			$valid = false;
			$errors['email'] = 'Your email address is already registered. Try logging in.';
		}
	}else{
		$valid = false;
		$errors['email'] = 'Please provide a valid email address.';
	}

	//check for password too short
	if(strlen($password) <= 5){
		$valid = false;
		$errors['password'] = 'Choose a password that is at least 5 characters long';
	}

	//did not check the policy box
	if( $policy != 1 ){
		$valid = false;
		$errors['policy'] = 'You must agree to the terms before creating an account';
	}	
	//if valid, add user to DB
	if($valid){
		$query = "INSERT INTO users
				(username, email, password, is_admin, date_joined)
				VALUES
				('$username', '$email', sha1('$password'), 0, now())";
		$result = $db->query($query);
		//check to make sure the user was added
		if( $db->affected_rows == 1 ){
			//success. log them in and redirect to admin panel
			$_SESSION['loggedin'] = true;
			setcookie( 'loggedin', true, time() + 60 * 60 * 24 * 7 );

			//WHO is logged in?
			$user_id = $db->insert_id;
			$_SESSION['user_id'] = $user_id;
			setcookie('user_id', $user_id,time() + 60 * 60 * 24 * 7 );
			
			//redirect
			header('Location:admin.php');
		}else{
			$errors['db'] = 'Something went wrong during account creation.';
		}
	}//end if valid
} //end parser
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sign up for an account</title>
	<link rel="stylesheet" type="text/css" href="../css/admin-style.css">
</head>
<body class="register">
	<main>
	<h1>Create an account</h1>

	<?php mmc_show_array($errors); ?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<label for="the_username">Create a username:</label>
		<input type="text" name="username" id="the_username">

		<label for="the_email">Email Address:</label>
		<input type="email" name="email" id="the_email">

		<label for="the_password">Create your password:</label>
		<input type="password" name="password" id="the_password">

		<label>
			<input type="checkbox" name="policy" value="1">
			I agree to the <a href="#" target="_blank">Terms of Service and Privacy Policy</a>
		</label>

		<input type="submit" value="Sign Up!">
		<input type="hidden" name="did_register" value="1">
	</form>
</main>
</body>
</html>