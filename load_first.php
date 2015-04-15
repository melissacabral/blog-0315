<?php
$query="SELECT * FROM posts ORDER BY post_id DESC LIMIT 20";
$result = $db->query($query);
while($row=$result->fetch_assoc())
{
	$msgID= $row['post_id'];
	$msg= $row['body'];
	?>
	<div id="<?php echo $msgID; ?>" class="message_box" > 
		<?php echo $msg; ?>
	</div> 
	<?php
} 
?>