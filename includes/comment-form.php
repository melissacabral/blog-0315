<form method="post" action="#leavecomment" id="leavecomment">

	<?php 
	//show feedback
	if( isset($message) ){
		echo $message;
	} ?>

	<h3><label for="the_body">Leave a Comment:</label></h3>
	<textarea name="body" id="the_body"></textarea>
	<input type="submit" value="Submit Comment">
	<input type="hidden" name="did_comment" value="1">
</form>