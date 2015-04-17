<?php 
require('../db-connect.php');
//header has the security check!
$page = 'dashboard';
include('admin-header.php');
 ?>

	<main>
		<section>
			<h2>Stats</h2>

			<ul>
				<li>You have <?php mmc_count_posts($_SESSION['user_id']); ?> published posts</li>
				<li>You have <?php mmc_count_posts($_SESSION['user_id'], 0); ?> Post Drafts</li>
				<li>Your posts have <?php mmc_count_comments($_SESSION['user_id']) ?> comments</li>
			</ul>

		</section>

		<section>
			<h2>Most Popular Content</h2>
			<ul>
				<li>Post with the most comments: 
					<?php mmc_most_popular_post($_SESSION['user_id']) ?>
				</li>
				<li>Most commonly used category: NAME</li>
			</ul>
		</section>
	</main>

<?php include('admin-footer.php'); ?>