<?php 
//connect to the database (contains DB info and constants)
require('db-connect.php'); 
?>
<?php include( INCLUDES_PATH . 'header.php' ); 
$last_msg_id=$_GET['last_msg_id'];
$action=$_GET['action'];

if($action <> "get")
{
	?>

	<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		function last_msg_funtion() 
		{ 
			var ID=$(".message_box:last").attr("id");
			$('div#last_msg_loader').html('<img src="bigLoader.gif">');
			$.post("load_second.php?action=get&last_msg_id="+ID,

				function(data){
					if (data != "") {
						$(".message_box:last").after(data); 
					}
					$('div#last_msg_loader').empty();
				});
		}; 

		$(window).scroll(function(){
			if ($(window).scrollTop() == $(document).height() - $(window).height()){
				last_msg_funtion();
			}
		}); 
	});
	</script>
</head>
<body>
	<?php 
include('load_first.php'); //Include load_first.php 
?>
<div id="last_msg_loader"></div>
</body>
</html>
<?php
}

else
{
include('load_second.php'); //include load_second.php
}
?>


<?php include( INCLUDES_PATH . 'footer.php' ); ?>
