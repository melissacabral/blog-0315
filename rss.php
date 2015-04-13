<?php 
require('db-connect.php'); 
include( INCLUDES_PATH . 'functions.php');
//this has to be echoed because the <? characters confuse the PHP parser
echo '<?xml version="1.0" encoding="utf-8" ?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>Melissa&#8217;s Blog Feed</title>
		<link><?php echo SITE_URL; ?></link>
		<description>A blog about nothing in particular</description>
		<language>en-us</language>
		<atom:link href="<?php echo SITE_URL; ?>rss.php" rel="self" type="application/rss+xml" />

		<?php //get newest 10 published posts
		$query = 	"SELECT posts.*, users.username, users.email
					FROM posts, users
					WHERE is_published = 1
					AND posts.user_id = users.user_id
					ORDER BY date DESC
					LIMIT 10";
		$result = $db->query($query);
		if( $result->num_rows >= 1 ){
			while( $row = $result->fetch_assoc() ){
		?>
		<item>
			<title><?php echo $row['title'] ?></title>
			<link><?php echo SITE_URL; ?>single.php?post_id=<?php 
				echo $row['post_id'] ?></link>
			<guid><?php echo SITE_URL; ?>single.php?post_id=<?php 
				echo $row['post_id'] ?></guid>
			<author><?php echo $row['email'] ?> (<?php 
				echo $row['username'] ?>)</author>
			<description><![CDATA[ <?php 
				echo htmlspecialchars($row['body'], ENT_COMPAT, 'utf-8') ?> ]]></description>
			<pubDate><?php echo convTimestamp($row['date']) ?></pubDate>
		</item>
		<?php 
			}//end while
		}//end if posts found 
		?>
	</channel>
</rss>