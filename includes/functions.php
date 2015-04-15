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
/**
 * Convert DATETIME into RSS friendly pubDate format
 * @param  string $date datetime data
 * @return string       nice pubDate for RSS
 */
function convTimestamp($date){
	$year   = substr($date,0,4);
	$month  = substr($date,5,2);
	$day    = substr($date,8,2);
	$hour   = substr($date,11,2);
	$minute = substr($date,14,2);
	$second = substr($date,17,2);
	$stamp =  date('D, d M Y H:i:s O', mktime($hour, $min, $sec, $month, $day, $year));
	return $stamp;
}

/**
 * Clean strings to prepare them for the DB
 * @param string $input - dirty data submitted by the user
 * @return string - clean data, ready for DB
 */
function clean_input( $input ){
	global $db;
	return mysqli_real_escape_string($db, strip_tags( $input ));
}



/**
 * Displays one item in an array of errors. 
 * Use next to a form input.
 * @param  	array 	$error A list of inline errors
 * @return  string 	HTML formatted inline error
 * @author  melissa <mcabral@platt.edu>
 * @since  	0.1
 */
function mmc_show_inline_error( $error ){
	//if error exists, show it
	if( $error ){
		$output = '<span class="inline-error">';
		$output .= $error;
		$ouptut .= '</span>';

		echo $output;
	}else{
		return;  //return nothing. no error to show. 
	}
}
/**
 * Display an array as an unordered list
 * @param   array 	$array list to display
 * @return  string 	HTML formatted list
 */
function mmc_show_array( $array ){
	if( !empty( $array ) ){
		$output = '<ul>';
		foreach( $array as $item ){
			$output .= '<li>' . $item . '</li>';
		}
		$output .= '</ul>';

		echo $output;
	}else{
		return;
	}
}

//no close PHP