<?php //security check!
session_start();
if($_SESSION['loggedin'] != true){
	//redirect to login
	header('Location:login.php');

	//stop this file from loading
	die('You do not have permission to access this page. ');
} 

include_once(INCLUDES_PATH . 'functions.php'); 
?>
<!doctype html>
<html>
<head>
	<title>Admin Panel</title>
	<link rel="stylesheet" type="text/css" href="../css/admin-style.css">
</head>
<body class="<?php echo $page; ?>">
	<header>
		<h1>Blog Admin Panel</h1>
		<nav>
			<ul>
				<li class="dashboard"><a href="<?php echo SITE_URL ?>admin/admin.php">Dashboard</a></li>
				<li class="write"><a href="<?php echo SITE_URL ?>admin/write-post.php">Write Post</a></li>
				<li class="manage"><a href="<?php echo SITE_URL ?>admin/manage-posts.php">Manage Posts</a></li>
				<li class="comments"><a href="#">Manage Comments</a></li>
				<li class="profile"><a href="#">Edit Profile</a></li>
			</ul>
		</nav>
		<ul class="utilities">
			<li><a href="login.php?action=logout" class="warn">Log Out!</a></li>
		</ul>
	</header>