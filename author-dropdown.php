<?php require('db-connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Simple AJAX dropdown with JQuery</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.min.js"></script>

	<style type="text/css">
	.loading{
		background-color: #CDCDCD;
		opacity:.5;
	}
	</style>
</head>
<body>

	<h1>Read Posts by Author:</h1>

	<?php 
	//get all the users from the DB
	$query = "SELECT username, user_id 
				FROM users ";
	$result = $db->query($query);
	if( $result->num_rows >= 1 ){
	 ?>
	<select class="picker" >
		<option>Choose One</option>

		<?php while( $row = $result->fetch_assoc() ){ ?>
		<option value="<?php echo $row['user_id'] ?>">
			<?php echo $row['username'] ?>
		</option>
		<?php } //end while ?>

	</select>
	<?php }//end if users ?>

	<div id="display-area">
		Choose an author to see their posts here
	</div>

	<script type="text/javascript">
		//when the user changes the dropdown option, trigger an AJAX request
		$(".picker").change(function(){
		// 	//which user did they choose? ('this' refers to select.picker)
			var user_id = this.value;
			//create the ajax request. 
			$.ajax({
				type	: 	"GET",
				url		: 	"display.php", //the file that handles the DB query 
											//and results
				data 	: 	{ "user_id" : user_id }, //this data will be sent to display.php
				dataType: 	"html",  //format of data that will be returned
				success	: 	function(response){
					$("#display-area").html(response); //show the result in the div
				}
			});	
			//short version of the $.ajax() function:
			//$( "#display-area" ).load( "display.php", { "user_id" : user_id } );
		});
		//do stuff while the AJAX request is working (user feedback)
		$(document).on({
			ajaxStart: function(){ $("body").addClass("loading"); },
			ajaxStop: function(){ $("body").removeClass("loading"); }
		});

	</script>
</body>
</html>