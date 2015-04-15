<?php 
//connect to DB
require('db-connect.php');
include( INCLUDES_PATH . 'header.php'); 

//what did the user search for?
$phrase = $_GET['phrase'];

//configuration for pagination
$per_page = 3; 		//how many posts per page
$page_number = 1; 	//the default page to start on
?>

<main>

	<?php //get ALL the published posts that match the search phrase
	$query = "SELECT post_id, title, body
				FROM posts
				WHERE is_published = 1
				AND ( title LIKE '%$phrase%'
				OR body LIKE '%$phrase%'
				) ";
	//run it
	$result = $db->query($query);
	//check to make sure at least one post is found
	$total = $result->num_rows;
	if( $total >= 1 ){ 
		//calculations for pagination
		//how many pages do we need?
		$total_pages = ceil( $total / $per_page );

		//what page are we on?
		//path looks like search.php?phrase=bla&page=3
		if( $_GET['page'] ){
			$page_number = $_GET['page'];
		}
		//make sure we are viewing a valid page
		if( $page_number <= $total_pages ){
			//modify the original query with a LIMIT for pagination
			//figure out the offset
			$offset = ( $page_number - 1 ) * $per_page;

			$query_modified = $query . " LIMIT $offset, $per_page";

			//run the modified query
			$result_modified = $db->query($query_modified);
		?>
	<h1>Search Results:</h1>
	<p class="info"><?php echo $total; ?> results found for <b><?php echo $phrase; ?></b>. 
		Showing page <?php echo $page_number ?> of <?php echo $total_pages ?>
	</p>
	<?php //loop through the results
	while( $row = $result_modified->fetch_assoc() ){ ?>
	<article>
		<h2>
			<a href="<?php echo SITE_URL ?>single.php?post_id=<?php 
					echo $row['post_id']; ?>">
				<?php echo $row['title']; ?>
			</a>
		</h2>
		<p><?php echo substr($row['body'], 0, 100); ?>&hellip;</p>
	</article>
	<?php } //end while ?>

	<?php //set up pagination
	$prev_page = $page_number - 1;
	$next_page = $page_number + 1;
	?>
	<section class="pagination">
		<?php //show the previous button if we are not on page 1
		if( $page_number > 1 ){ ?>
			<a class="prev button" href="?phrase=<?php echo $phrase ?>&amp;page=<?php echo $prev_page 
					?>">&larr; Previous</a>
		<?php }  ?>
		
		<?php //show next button if we are not on the last page
		if( $page_number < $total_pages ){ ?>
			<a class="next button"href="?phrase=<?php echo $phrase ?>&amp;page=<?php echo $next_page 
					?>">Next &rarr;</a>
		<?php } ?>
	</section>
	<?php 
		}//end if valid page
		else{
			echo 'invalid page';
		}
	} //end if posts found
	else{
		echo 'Sorry, no posts match your search terms.';
	} ?>
</main>


<?php include( INCLUDES_PATH . 'sidebar.php' ); ?>
<?php include( INCLUDES_PATH . 'footer.php' );	?>