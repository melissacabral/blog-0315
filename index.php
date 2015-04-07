<?php 
//connect to the database
require('db-connect.php'); 
include_once('functions.php');
?>

<?php //Include the header here ?>

	<main>
		<?php 
		//set up query to get all the published posts - title & body only. 
		// newest first
		$query = "SELECT title, body, date
					FROM posts
					WHERE is_published = 1
					ORDER BY date DESC";
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

			<time datetime="<?php echo $row['date']; ?>">
				<?php echo convert_date($row['date']); ?>
			</time>

		</article>
		
		<?php 
			} //end while loop			
		
		}else{
			echo 'Sorry, no posts found';
		} 
		//we are done with the results, so free the memory/resources on the server
		$result->free();
		?>

	</main>
	<aside>
		sidebar stuff goes here
	</aside>
	<footer>
		<small>&copy; 2015 Melissa C!</small>
	</footer>
</body>
</html>