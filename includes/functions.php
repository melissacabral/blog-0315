<?php 
/**
 * Convert MySQL datetime format into a human-friendly display date
 * @param  string $dateR the datetime string like 2015-04-02 09:58:43
 * @return string        a nice date like April 2, 2015
 */
function convert_date($dateR){
	$engMon=array('January','February','March','April','May','June','July','August','September','October','November','December',' ');
	$l_months='January:February:March:April:May:June:July:August:September:October:November:December';
	$dateFormat='F j, Y';
	$months=explode (':', $l_months);
	$months[]='&nbsp;';
	$dfval=strtotime($dateR);
	$dateR=date($dateFormat,$dfval);
	$dateR=str_replace($engMon,$months,$dateR);
	return $dateR;
}

/**
 * Count the number of comments on any post
 * @param int $post_id any valid post_id to count the comments for
 */
function count_comments( $post_id, $show_text = false ){
	//access the $db connection from outside this function
	global $db;

	//count all the published comments on the post_id
	$query 	 = "SELECT COUNT(*) AS total
				FROM comments 
				WHERE is_approved = 1
				AND post_id = $post_id";
	$result = $db->query( $query );
	//check to make sure it worked. COUNT should return exactly one row. 
	if( $result->num_rows == 1 ){
		//while loop can be skipped since we only have one row
		while($row = $result->fetch_assoc()){
			if($show_text){
				//display comment count with good grammar!
				if( $row['total'] == 1 ){
					echo 'One comment';
				}elseif( $row['total'] == 0 ){
					echo 'No comments yet';
				}else{
					echo $row['total'] . ' comments';
				}//end of grammar
			}else{
				echo '<span class="comment-number">' . $row['total'] . '</span>';
			}//end if show_text
		}//end while
		$result->free();
	}//end if comments
}//end count_comments function

/**
 * Count the number of published posts in any category
 * @param int $cat_id any valid category_id
 */
function count_posts_in_category($cat_id){
	global $db;
	$query = "SELECT COUNT(*) AS total
			FROM posts_categories AS pc, posts
			WHERE posts.is_published = 1
			AND pc.post_id = posts.post_id
			AND pc.category_id = $cat_id";
	$result = $db->query($query);
	//check it
	if( $result->num_rows == 1 ){
		//only one result possible, so while() can be omitted
		$row = $result->fetch_assoc();

		echo '(' . $row['total'] . ')';
	}
}

//no close PHP