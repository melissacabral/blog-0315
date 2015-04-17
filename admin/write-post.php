<?php 
require('../db-connect.php');
//header has the security check!
$page = 'write';
include('admin-header.php');
 ?>

<main>
	<h2>Write a Post</h2>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<label for="the_title">Title:</label>
		<input type="text" name="title" id="the_title">
		

	</form>
	
</main>

<?php include('admin-footer.php'); ?>