<?php
//parse the comment if the form was submitted
if( $_REQUEST['did_comment'] ){
	//extract and clean the data
	$body = mysqli_real_escape_string($db, strip_tags($_POST['body']));

	//validate
	$valid = true;

	//check for empty body
	if($body == ''){
		$valid = false;
		$message = 'Please fill in the comment body';
	}	
	//add to the DB if valid
	if( $valid ){
		//set up query
		//TODO: change the user_id to the logged in person
		//TODO:  Add better "is_approved" handling
		$query 	 = "INSERT INTO comments
					(body, user_id, date, is_approved, post_id)
					VALUES  
					('$body', 1, now(), 1, $post_id )";
		//run it
		$result = $db->query($query);
		//check to see if it worked
		if( $db->affected_rows == 1 ){
			$message = 'Thank you for your comment.';
		}
		else{
			$message = 'Your comment was NOT added. Try again.';
		}//end if query worked

	}//end if valid
} //end parser