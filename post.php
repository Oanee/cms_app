<?php include 'includes/db.php' ?>

<?php include 'includes/header.php' ?>
<!-- Navigation -->

<?php include 'includes/navigation.php' ?>

<?php

if (isset($_POST['liked'])) {

	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];

	$searchPost = "SELECT * FROM posts WHERE post_id='$post_id'";
	$postResult = mysqli_query($connection, $searchPost);
	$post = mysqli_fetch_array($postResult);
	$likes = $post['likes'];

	if (mysqli_num_rows($postResult) >= 1) {
		echo $post['post_id'];
	}

	mysqli_query($connection, "UPDATE posts SET likes='$likes' + 1 WHERE post_id = '$post_id'");

	mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
}

if (isset($_POST['unliked'])) {

	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];

	$searchPost = "SELECT * FROM posts WHERE post_id='$post_id'";
	$postResult = mysqli_query($connection, $searchPost);
	$post = mysqli_fetch_array($postResult);
	$likes = $post['likes'];

	if (mysqli_num_rows($postResult) >= 1) {
		echo $post['post_id'];
	}

	mysqli_query($connection, "UPDATE posts SET likes='$likes' - 1 WHERE post_id = '$post_id'");

	mysqli_query($connection, "DELETE FROM likes WHERE user_id = $user_id AND post_id = $post_id");
}

?>

<!-- Page Content -->
<div class="container">

	<div class="row">

		<!-- Blog Entries Column -->
		<div class="col-md-8">

			<?php
			if (isset($_GET['p_id'])) {
				$the_post_id = escape($_GET['p_id']);

				$view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id";
				$send_query = mysqli_query($connection, $view_query);

				confirm($send_query);

				if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
					$query = "SELECT * FROM posts WHERE post_id = $the_post_id";

				} else {
					$query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published'";

				}
				$select_all_posts_query = mysqli_query($connection, $query);


				if (mysqli_num_rows($select_all_posts_query) < 1) {
					echo "<h1 class='text-center'>No posts available</h1>";
				} else {

					$select_all_posts_query = mysqli_query($connection, $query);

					while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
						$post_id = $row['post_id'];
						$post_title = $row['post_title'];
						$post_user = $row['post_user'];
						$post_date = $row['post_date'];
						$post_image = $row['post_image'];
						$post_content = substr($row['post_content'], 0, 100);

						?>

						<h1 class="page-header">
							Posts
						</h1>

						<!-- First Blog Post -->
						<h2>
							<a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title ?></a>
						</h2>

						<p class="lead">
							by <a href="index.php"><?php echo $post_user ?></a>
						</p>

						<p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>

						<hr>

						<img class="img-responsive" src="/cms/images/<?php echo imagePlaceholder($post_image) ?>" alt="">

						<hr>

						<p><?php echo $post_content ?></p>

						<hr>

						<div class='row'>
							<p class='pull-right'><a class='<?php echo userLikedThisPost($post_id) ? 'unlike' : 'like' ?> glyphicon glyphicon-thumbs-up' href=''><?php echo userLikedThisPost($post_id) ? ' Unlike' : ' Like' ?></a></p>
						</div>

						<div class='row'>
							<p class='pull-right'><a>Likes: <?php echo getPostLikes($post_id) ?></a></p>
						</div>

						<div class='clearfix'></div>

					<?php }
					?>

					<?php

					if (isset($_POST['create_comment'])) {

						$the_post_id = escape($_GET['p_id']);

						$comment_author = escape($_POST['comment_author']);
						$comment_email = escape($_POST['comment_email']);
						$comment_content = escape($_POST['comment_content']);

						if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {
							$query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status)";

							$query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved')";

							$create_comment_query = mysqli_query($connection, $query);

							confirm($create_comment_query);

							$query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
							$query .= "WHERE post_id = $the_post_id ";
							$update_comments_query = mysqli_query($connection, $query);
							confirm($update_comments_query);
						} else {
							echo "<script>alert('Fields cannot be empty')</script>";
						}

						redirect("/cms/post.php?p_id=$the_post_id");

					}

					?>

					<!-- Comments Form -->
					<div class="well">
						<h4>Leave a Comment:</h4>
						<form role="form" method='post' action=''>
							<div class="form-group">
								<label for='comment_author'>Author</label>
								<input type='text' class='form-control' name='comment_author' id='comment_author'>
							</div>

							<div class="form-group">
								<label for='comment_email'>Email</label>
								<input type='email' class='form-control' name='comment_email' id='comment_email'>
							</div>

							<div class="form-group">
								<label for='comment_email'>Comment</label>
								<textarea
									name='comment_content' class="form-control" rows="3" id='comment_email'></textarea>
							</div>
							<button type="submit" name='create_comment' class="btn btn-primary">Submit</button>
						</form>
					</div>

					<hr>

					<!-- Posted Comments -->

					<?php

					$query = "SELECT * FROM comments WHERE comment_post_id = $the_post_id ";
					$query .= "AND comment_status = 'approved' ";
					$query .= "ORDER BY comment_id DESC ";
					$select_comment_query = mysqli_query($connection, $query);

					confirm($select_comment_query);

					while ($row = mysqli_fetch_assoc($select_comment_query)) {
						$comment_date = $row['comment_date'];
						$comment_content = $row['comment_content'];
						$comment_author = $row['comment_author'];

						?>

						<!-- Comment -->
						<div class="media">
							<a class="pull-left" href="#">
								<img class="media-object" src="http://placehold.it/64x64" alt="">
							</a>
							<div class="media-body">
								<h4 class="media-heading"><?php echo $comment_author ?>
									<small><?php echo $comment_date ?></small>
								</h4>
								<?php echo $comment_content ?>
							</div>
						</div>

					<?php }
				}
			} else {
				redirect('index.php');
			} ?>

			<!-- Pager -->
<!--			<ul class="pager">-->
<!--				<li class="previous">-->
<!--					<a href="#">&larr; Older</a>-->
<!--				</li>-->
<!--				<li class="next">-->
<!--					<a href="#">Newer &rarr;</a>-->
<!--				</li>-->
<!--			</ul>-->
		</div>


		<!-- Blog Sidebar Widgets Column -->
		<?php include 'includes/sidebar.php' ?>

	</div>
	<!-- /.row -->

	<hr>

	<?php include 'includes/footer.php' ?>

	<script>

		$(document).ready(function() {

			var post_id = <?php echo $the_post_id ?>;
				var user_id = <?php echo loggedInUserId() ?>;


			$('.like').click(function () {
				$.ajax({
					url: '/cms/post.php?p_id=<?php echo $the_post_id ?>',
					type: 'post',
					data: {
						liked: 1,
						post_id: post_id,
						user_id: user_id
					}
				})
			})

			$('.unlike').click(function () {
				$.ajax({
					url: '/cms/post.php?p_id=<?php echo $the_post_id ?>',
					type: 'post',
					data: {
						unliked: 1,
						post_id: post_id,
						user_id: user_id
					}
				})
			})
		})

	</script>
