<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button
				type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/cms/index">Home</a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php

				$query = 'SELECT * FROM category';
				$select_all_categories_query = mysqli_query($connection, $query);

				while ($row = mysqli_fetch_assoc($select_all_categories_query)) {
					$category_title = $row['category_title'];
					$category_id = $row['category_id'];

					$category_class = '';

					$registration_class = '';

					$page_name = basename($_SERVER['PHP_SELF']);

					echo $page_name;

					$registration_name = 'registration.php';

					if (isset($_GET['category']) && $_GET['category'] == $category_id) {
						$category_class = 'active';
					} else if ($page_name == $registration_name) {
						$registration_class = 'active';
					}

					echo "<li class='$category_class'><a href='/cms/category/{$category_id}'>{$category_title}</a></li>";
				}

				?>

				<?php if (isLoggedIn()): ?>

					<li>
						<a href='/cms/admin'>Admin</a>
					</li>

					<li>
						<a href='/cms/includes/logout.php'>Logout</a>
					</li>

				<?php else: ?>

					<li>
						<a href='/cms/login.php'>Login</a>
					</li>

				<?php endif; ?>

				<li class='<?php echo $registration_class ?>'>
					<a href='/cms/registration.php'>Registration</a>
				</li>

				<li>
					<a href='/cms/contact.php'>Contact</a>
				</li>

				<?php

				if (isset($_SESSION['user_role'])) {
					if (isset($_GET['p_id'])) {
						$the_post_id = escape($_GET['p_id']);
						echo "<li><a href='/cms/admin/posts.php?source=edit_post&p_id=$the_post_id'>Edit post</a></li>";
					}
				}

				?>

			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container -->
</nav>
