<?php
require('db-connect.php');
echo $last_msg_id=$_GET['last_msg_id'];
$query="SELECT * FROM posts WHERE post_id < '$last_msg_id' ORDER BY post_id DESC LIMIT 5";
$result = $db->query($query);
$last_msg_id="";
while($row=$result->fetch_assoc())
{
$msgID= $row['post_id'];
$msg= $row['body']; 
?>
<div id="<?php echo $msgID; ?>" class="message_box" > 
<?php echo $msg; 
?>
</div>
<?php
} 
?>