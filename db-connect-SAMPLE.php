<?php 
/*
Database connection credentials
This represents the whole site connecting to the database on the server
it is different from the users who log in to write posts, etc
*/

					//host 		//username 			password 		    database
$db = new mysqli( 'localhost', 'mmc_bloguser0315', 'HzNJWY8X5234DXBC', 
	'melissa_blog_0315' );

//handle any errors by stopping the page
if( $db->connect_errno > 0 ){
	die('Unable to connect to the Database.');
}


// Define constants - these are pieces of data that cannot be changed
//use this for links (<a>) or src attributes
define( 'INCLUDES_URL', 'http://localhost/melissa-php-0315/blog-0315/includes/' );

//use this one for include() functions
define( 'INCLUDES_PATH', 'C:/xampp/htdocs/melissa-php-0315/blog-0315/includes/');

//use in links and src attributes
define( 'SITE_URL', 'http://localhost/melissa-php-0315/blog-0315/' );


//no close php

