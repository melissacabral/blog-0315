<aside class="sidebar">

	<form method="get" action="<?php echo SITE_URL ?>search.php">
		<label for="the_phrase" class="screen-reader-text">Search:</label>
		<input type="search" name="phrase" id="the_phrase" >
		<input type="submit" value="Search">
	</form>

	<?php 
	//get the title and post_id of the latest 10 published posts
	$query = 	"SELECT title, post_id
				FROM posts
				WHERE is_published = 1
				ORDER BY date DESC
				LIMIT 5"; 
	//run it
	$result = $db->query($query);
	//check to see if there are posts in the result
	if( $result->num_rows >= 1 ){
	?>
	<section>
		<h2>Latest Posts</h2>
		<ul>
			<?php //output each post as a list item
			while( $row = $result->fetch_assoc() ){ ?>

<li>
<a href="<?php echo SITE_URL . 'single.php?post_id=' . $row['post_id']; ?>">
				<?php echo $row['title']; ?>
			</a>
			<?php count_comments($row['post_id']); ?>
		</li>

			<?php }//end while 
			$result->free(); ?>
		</ul>
	</section>
	<?php } //end if posts found ?>



	<?php //get the names and IDs of all categories in random order
	$query = 	"SELECT name, category_id
				FROM categories
				ORDER BY RAND()";
	//run it
	$result = $db->query($query);

	//check for rows in the result
	if( $result->num_rows >= 1 ){
	 ?>
	<section>
		<h2>Post Categories</h2>
		<ul>
			<?php //output each cat as list item
			while( $row = $result->fetch_assoc() ){ ?>

			<li>
<a href="<?php echo SITE_URL . 'category.php?cat_id=' . $row['category_id'] ?>">
	<?php echo $row['name'] ?></a>

			<?php count_posts_in_category($row['category_id']); ?>
			</li>

			<?php }//end while
			$result->free(); ?>
		</ul>
	</section>
	<?php } //end if categories ?>



<?php 
//get the titles and URLs of up to 10 links  
$query	 = "SELECT title, url 
			FROM links 
			LIMIT 10";
$result = $db->query($query);
if( $result->num_rows >= 1 ){ ?>

	<section>
		<h2>Links</h2>
		<ul>
			<?php while( $row = $result->fetch_assoc() ) { ?>
			<li>
				<a href="<?php echo $row['url']; ?>">
					<?php echo $row['title'] ?>
				</a>
			</li>
			<?php } //end while
			$result->free(); ?>
		</ul>
	</section>

<?php } //end if links ?>

</aside>