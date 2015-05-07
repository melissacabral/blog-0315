<?php 
/**
 * DISPLAY OUTPUT
 * This file gets loaded by the ajax request
 * It is the same whether using jQuery or pure JS
 * Note that it has no doctype and is not intended to be a standalone file
 */

require('db-connect.php');

//read the data that came in the AJAX request (which user did they choose?)
$user_id = $_REQUEST['user_id'];

//get the published posts authored by that user, newest first
$query = "SELECT title, body
			FROM posts
			WHERE is_published = 1
			AND user_id = $user_id
			ORDER BY date DESC";
//run it
$result = $db->query($query);
//check it
if($result->num_rows >= 1){
?>

<h1><?php echo $result->num_rows; ?> posts found</h1>

	<?php while( $row = $result->fetch_assoc() ){ ?>
	<article>
		<h2><?php echo $row['title'] ?></h2>
		<p><?php echo $row['body'] ?></p>
	</article>
	<?php } //end while ?>

<?php 
}else{
	echo '<h1>This author has not written any posts.</h1> ';
} ?>