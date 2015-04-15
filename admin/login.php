<?php 
//open or resume session
session_start();
//connect to db
require( '../db-connect.php' );
include_once( INCLUDES_PATH . 'functions.php' );

//parse the form if it was submitted
if( $_POST['did_login'] ){
	
	//extract what the user typed in
	//clean the 'dirty' data
	$input_username = clean_input($_POST['username']);
	$input_password = clean_input($_POST['password']);

	$sha1_pass = sha1($input_password);

	//validate - check to see if username/password are correct lengths
	if( strlen( $input_username ) >= 5 
		AND strlen( $input_username ) <= 50  
		AND strlen($input_password) >= 5 ){
	
		//Look for this user in the DB. if they match, log the user in
		$query = "SELECT user_id, is_admin
				FROM users
				WHERE username = '$input_username'
				AND password = '$sha1_pass'
				LIMIT 1";

		$result = $db->query($query);
		//if one row found, log them in!
		if( $result->num_rows == 1 ){
			//success! remember the user for 1 week
			setcookie( 'loggedin', true, time() + 60 * 60 * 24 * 7 );
			$_SESSION['loggedin'] = true;

			//WHO is logged in?
			$row = $result->fetch_assoc();
			setcookie( 'user_id', $row['user_id'], time() + 60 * 60 * 24 * 7 );
			$_SESSION['user_id'] =  $row['user_id'];

			$message = 'You are now logged in.';
		}else{
			//error. not a match. 
			$message = 'Sorry, Your username and password combo is not correct. Try again.';
		} //end credential check

	} //end length check
	else{
		$message = 'Sorry, Your username and password combo is not correct. Try again.';
	}
} //end login parser

//LOG OUT. check to see if the action is set to logout
if( $_GET['action'] == 'logout' ){
	//delete all session and cookie vars, close the open session
	setcookie( 'loggedin', '', time() - 3600 );
	unset( $_SESSION['loggedin'] );

	setcookie( 'user_id', '', time() - 3600);
	unset($_SESSION['user_id']);

	//end the session
	session_destroy();
}
//if the user's session is invalid, but they still have a valid cookie, re-build the session
elseif($_COOKIE['loggedin']){
	//re-make session vars
	$_SESSION['loggedin'] = true;
	$_SESSION['user_id'] = $_COOKIE['user_id'];
}

//if someone visits this page and they are logged in, redirect them to the profile page
if( $_SESSION['loggedin'] ){
	//redirect
	header('Location:admin.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Simple Login Form</title>
</head>
<body>
	<h1>Log in to your account</h1>

	<?php //success/error message
	if( isset($message) ){
		echo $message;
	} 
	?>

	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username">

		<label for="password">Password:</label>
		<input type="password" name="password" id="password">
		
		<input type="submit" value="Log In">

		<input type="hidden" name="did_login" value="true">

	</form>

</body>
</html>