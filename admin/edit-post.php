<?php 
require('../db-connect.php');
//header has the security check!
$page = 'edit';
include('admin-header.php');

//which post are we editing?
$post_id = $_GET['post_id'];

//who is logged in?
$user_id = $_SESSION['user_id'];


//Parse the form!
if( $_POST['did_post'] ){
	//sanitize
	$title = clean_input($_POST['title']);
	$body = clean_input($_POST['body']);
	$is_published = clean_input($_POST['is_published']);
	$allow_comments = clean_input($_POST['allow_comments']);
	$categories = $_POST['categories'];

	//BOOLEAN checkboxes - if null or "not 1", change value to 0
	if($allow_comments != 1){
		$allow_comments = 0;
	}
	if($is_published != 1){
		$is_published = 0;
	}
	
	//validate
	$valid = true;

	//if body or title is blank = NOT VALID.
	if( $title == '' OR $body == '' ){
		$valid = false;
		$errors[] = 'Please fill in all fields';
	}

	//if valid, update the old post in the DB!
	if($valid){
		$user_id = $_SESSION['user_id'];
		$query = "UPDATE posts
				  SET
				  title = '$title',
				  body = '$body',
				  is_published = $is_published,
				  allow_comments = $allow_comments
				  WHERE post_id = $post_id
				  LIMIT 1";
		$result = $db->query($query);	

		//re-set all the categories for this post (delete them all!)
		$query_delete = "DELETE FROM posts_categories
						WHERE post_id = $post_id";
		$result_delete = $db->query($query_delete);
			

		//check to see if any categories were selected
		if(isset($categories)){
			//go through the list of checked categories, adding one row to posts_categories for each category
			foreach( $categories as $cat_id ){
				$query_pc = "INSERT INTO posts_categories
							(post_id, category_id)
							VALUES
							($post_id, $cat_id)";
				$result_pc = $db->query($query_pc);
				
			}//end foreach
		}//end if categories

		//did it work?
		if($db->affected_rows == 1){	
			$message = 'Post successfully saved';
		}//end if insert worked
		else{
			$message = 'Sorry, something went wrong when saving yout post. Try again.';
		}
	}//end if valid
}//end parser
 ?>

<main>

<?php 
//Get all the content for this post, and make sure the logged in user wrote it.
$query = "SELECT * 
			FROM posts
			WHERE post_id = $post_id
			AND user_id = $user_id
			LIMIT 1";
$result = $db->query($query);
if( $result->num_rows == 1 ){
	$row = $result->fetch_assoc();

	//figure out what categories this post is in. get an array
	$query_cats = "SELECT category_id 
					FROM posts_categories
					WHERE post_id = $post_id";
	$result_cats = $db->query($query_cats);

	//make an empty list to hold the categories
	$cat_list = array();

	//check to see if this post is in any categories	
	if($result_cats->num_rows >= 1){
		while($row_cats = $result_cats->fetch_assoc() ){
			$cat_list[] = $row_cats['category_id'];

		}
	}
	//debug with print_r
	// print_r( $cat_list );
?>

	<h2>Edit Post</h2>

	<?php if(isset($message)){
		echo $message;
	} ?>
	<form method="post" 
		action="<?php echo $_SERVER['PHP_SELF'] ?>?post_id=<?php echo $post_id ?>">
		<div class="panel threequarters noborder">
			<label for="the_title">Title:</label>
			<input type="text" name="title" id="the_title" 
				value="<?php echo $row['title'] ?>">

			<label for="the_body">Body:</label>
			<textarea name="body" id="the_body"><?php echo $row['body'] ?></textarea>
		</div>
		<div class="panel onequarter">
			<h3>Post settings</h3>
			<label>
				<input type="checkbox" name="is_published" value="1" <?php 
					if($row['is_published']){ 
						echo 'checked'; 
					} ?>>
				Make this post public?
			</label>

			<label>
				<input type="checkbox" name="allow_comments" value="1" <?php 
					if($row['allow_comments']){
						echo 'checked';
					}
				 ?>>
				Allow Comments?
			</label>

			<?php //get all the categories
			$query = "SELECT * FROM categories";
			$result = $db->query($query);
			if($result->num_rows >= 1){ 
			?>

			<h3>Categories:</h3>

			<?php while( $row = $result->fetch_assoc() ){ ?>
			<label>
				<input type="checkbox" name="categories[<?php echo $row['category_id'] ?>]" value="<?php echo $row['category_id'] ?>" <?php 
					// check to see if the category we are showing is one of the cats that this post is in
					if( in_array($row['category_id'], $cat_list) ){
						echo 'checked';
					}
				 ?>>

				<?php echo $row['name'] ?>

			</label>

			<?php 
				} //end while
				$result->free();
			}//end if categories ?>
			

			<input type="submit" value="Save Post">		

		</div>
		<input type="hidden" name="did_post" value="1">

	</form>
<?php 
}//end if post found
else{
	echo 'You do not have permission to edit this post';
} ?>	
</main>

<?php include('admin-footer.php'); ?>