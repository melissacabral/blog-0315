<?php //security check!
session_start();
if($_SESSION['loggedin'] != true){
	//redirect to login
	header('Location:login.php');

	//stop this file from loading
	die('You do not have permission to access this page. ');
} ?>
<!doctype html>
<html>
<head>
	<title>Admin Panel</title>
	<link rel="stylesheet" type="text/css" href="../css/admin-style.css">
</head>
<body>
	<header>
		<h1>Blog Admin Panel</h1>
		<nav>
			<ul>
				<li><a href="#">Dashboard</a></li>
				<li><a href="#">Write Post</a></li>
				<li><a href="#">Manage Posts</a></li>
				<li><a href="#">Manage Comments</a></li>
				<li><a href="#">Edit Profile</a></li>
			</ul>
		</nav>
		<ul class="utilities">
			<li><a href="login.php?action=logout" class="warn">Log Out!</a></li>
		</ul>
	</header>

	<main>
		put content here!
	</main>

	<footer>
		&copy; 2014 Your Name Here!
	</footer>
</body>
</html>