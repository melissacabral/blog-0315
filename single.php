<?php 
//connect to the database (contains DB info and constants)
require('db-connect.php'); 

//figure out which post the user clicked on.
//URL looks like /single.php?post_id=X
$post_id = $_GET['post_id'];
?>
<?php 
include( INCLUDES_PATH . 'header.php' );

include( INCLUDES_PATH . 'parse-comment.php'); ?>

	<main>
		<?php 
		//set up query to get the post the user is viewing 
	
		$query = "SELECT posts.title, posts.body, posts.date, users.username, 
					posts.post_id, posts.allow_comments
					FROM posts, users
					WHERE posts.is_published = 1
					AND posts.user_id = users.user_id
					AND posts.post_id = $post_id
					LIMIT 1";
		//run the query
		$result = $db->query($query); 

		//check to make sure that the result contains data
		if( $result->num_rows >= 1  ){
			//loop through each row in the results
			while($row = $result->fetch_assoc()){
		?>
		<article>
			<h2><?php echo $row['title']; ?></h2>
			<p><?php echo $row['body'] ?></p>

			<div class="post-info">
				Posted by
				<?php echo $row['username'] ?>
				on
			<time datetime="<?php echo $row['date']; ?>">
				<?php echo convert_date($row['date']); ?>
			</time>

				<?php count_comments( $row['post_id'], true ); ?>

			</div>

		</article>

		<?php //only show the form if comments are allowed on this post
		if( $row['allow_comments'] ){ 
			include(INCLUDES_PATH . 'comment-form.php');
		}  //end if allow comments
		?>
		
		<?php 
			} //end while loop

		//we are done with the results, so free the memory/resources on the server
		$result->free();		
		?>

		<?php		
		}else{
			echo 'Sorry, no posts found';
		} 
		
		?>

	</main>
	<?php include( INCLUDES_PATH . 'sidebar.php' ); ?>

	<?php include( INCLUDES_PATH . 'footer.php' );	?>