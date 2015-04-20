<?php 
require('../db-connect.php');
//header has the security check!
$page = 'manage';
include('admin-header.php');
 ?>

	<main>
		<h2>Manage Your Posts</h2>
		
		<?php //get all the posts written by the logged in user, newest first
		$user_id = $_SESSION['user_id'];
		$query = "SELECT post_id, title
				 FROM posts
				 WHERE user_id = $user_id
				 ORDER BY date DESC";
		//run it
		$result = $db->query($query); 
		//check it 
		if( $result->num_rows >= 1 ){
		?>
		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>
			<li>
				<a href="edit-post.php?post_id=<?php echo $row['post_id'] ?>" >
				<?php echo $row['title'] ?>
				</a>
			</li>
			<?php 
			} //end while
			$result->free();
			?>
		</ul>
		<?php }//end if posts found 
		else{
			echo 'You have not written any posts yet.';
		}?>
	</main>

<?php include('admin-footer.php'); ?>