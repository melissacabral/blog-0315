<?php 
require('../db-connect.php');
//header has the security check!
$page = 'write';
include('admin-header.php');


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

	//if valid, add new post to DB!
	if($valid){
		$user_id = $_SESSION['user_id'];
		$query = "INSERT INTO posts
				(title, body, is_published, allow_comments, date, user_id)
				VALUES
				('$title', '$body', $is_published, $allow_comments, now(), $user_id)";
		$result = $db->query($query);
		//did it work?
		if($db->affected_rows == 1){
			//get the post_id that was just added
			$post_id = $db->insert_id;

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

			$message = 'Post successfully saved';
		}//end if insert worked
		else{
			$message = 'Sorry, something went wrong when saving yout post. Try again.';
		}
	}//end if valid
}//end parser
 ?>

<main>
	<h2>Write a Post</h2>

	<?php if(isset($message)){
		echo $message;
	} ?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<div class="panel threequarters noborder">
			<label for="the_title">Title:</label>
			<input type="text" name="title" id="the_title">

			<label for="the_body">Body:</label>
			<textarea name="body" id="the_body"></textarea>
		</div>
		<div class="panel onequarter">
			<h3>Post settings</h3>
			<label>
				<input type="checkbox" name="is_published" value="1">
				Make this post public?
			</label>

			<label>
				<input type="checkbox" name="allow_comments" value="1">
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
				<input type="checkbox" name="categories[<?php echo $row['category_id'] ?>]" value="<?php echo $row['category_id'] ?>">

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
	
</main>

<?php include('admin-footer.php'); ?>