<?php 
require('../db-connect.php');
//header has the security check!
$page = 'profile';

//parse the form if it was submitted
if( $_POST['did_upload'] ){
	//file uploading stuff begins
	
	$target_path = "../uploads/";
	
	//list of image sizes to generate. (these are widths)
	$sizes = array(
		'thumb_img' => 150,
		'medium_img' => 300,
		'large_img' => 1200
	);		
		
	// This is the temporary file created by PHP
	$uploadedfile = $_FILES['uploadedfile']['tmp_name'];
	// Capture the original size of the uploaded image
	list($width,$height) = getimagesize($uploadedfile);
	
	//make sure the width and height exist, otherwise, this is not a valid image
	if($width > 0 AND $height > 0){
	
	//what kind of image is it
	$filetype = $_FILES['uploadedfile']['type'];
	
	switch($filetype){
		case 'image/gif':
			// Create an Image from it so we can do the resize
			$src = imagecreatefromgif($uploadedfile);
		break;
		
		case 'image/pjpeg':
		case 'image/jpg':
		case 'image/jpeg': 
			// Create an Image from it so we can do the resize
			$src = imagecreatefromjpeg($uploadedfile);
		break;
	
		case 'image/png':
			// Create an Image from it so we can do the resize
			$required_memory = Round($width * $height * $size['bits']);
			$new_limit=memory_get_usage() + $required_memory;
			ini_set("memory_limit", $new_limit);
			$src = imagecreatefrompng($uploadedfile);
			ini_restore ("memory_limit");
		break;
			
	}
	//for filename
	$randomsha = sha1(microtime());
	//for DB update
	$user_id = $_SESSION['user_id'];
	
	//do it!  resize images
	foreach($sizes as $size_name => $size_width){
		//preserve aspect ratio
		if($width >=  $size_width){
			$newwidth = $size_width;
			$newheight=($height/$width) * $newwidth;
		}else{
			$newwidth=$width;
			$newheight=$height;
		}
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		
		$filename = $target_path.$randomsha.'_'.$size_name.'.jpg';
		$didcreate = imagejpeg($tmp,$filename,70);
		imagedestroy($tmp);	

		//DELETE OLD FILE
		if($didcreate){			
			//get filepath of old file (needs to be file path, not URL)
            $old_file = show_userpic($user_id, $size_name, true);
             //Delete the file from the directory with unlink()
            unlink($old_file);
		}
		//END DELETE OLD FILE
		
	}	
	imagedestroy($src);	
		
	}else{//width and height not greater than 0
		$didcreate = false;
	}	
	
	if($didcreate) {
		
		//image was uploaded. Update the DB with its path
		
		$query = "UPDATE users
				SET userpic = '$randomsha'
				WHERE user_id = $user_id
				LIMIT 1";
		$result = $db->query($query);

		if($db->affected_rows == 1){
			$statusmsg .= 'DB query worked';
		}else{
			$statusmsg .= 'DB query failed';
		}

		$statusmsg .=  "The file ".  basename( $_FILES['uploadedfile']['name']). 
		" has been uploaded <br />";
	} else{
		$statusmsg .= "There was an error uploading the file, please try again!<br />";
	}		

}//end parser


include('admin-header.php');
 ?>

	<main>
		<h2>Edit Your Profile</h2>
		<?php 
		show_userpic($_SESSION['user_id'], 'medium_img');


		if(isset($statusmsg)){
			echo $statusmsg;
		} 
		?>

		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
			<label for="the_file">Upload your Profile Picture:</label>
			<input type="file" name="uploadedfile" id="the_file">

			<input type="submit" value="Update Profile">
			<input type="hidden" name="did_upload" value="1">

		</form>
		
	</main>

<?php include('admin-footer.php'); ?>