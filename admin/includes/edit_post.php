<?php

if (isset($_GET['p_id'])) {

	$the_post_id = $_GET['p_id'];

	$query = "SELECT * FROM posts WHERE post_id = {$the_post_id}";
	$select_post_by_id = mysqli_query($connection, $query);

	while ($row = mysqli_fetch_assoc($select_post_by_id)) {
		$post_id = $row['post_id'];
		$post_user = $row['post_user'];
		$post_title = $row['post_title'];
		$post_category_id = $row['post_category_id'];
		$post_status = $row['post_status'];
		$post_image = $row['post_image'];
		$post_tags = $row['post_tags'];
		$post_content = $row['post_content'];
	}
}

if (isset($_POST['update_post'])) {
	$post_user = escape($_POST['post_user']);
	$post_title = escape($_POST['post_title']);
	$post_category_id = escape($_POST['post_category']);
	$post_status = escape($_POST['post_status']);

	$post_image = escape($_FILES['image']['name']);
	$post_image_temp = escape($_FILES['image']['tmp_name']);

	$post_content = escape($_POST['post_content']);
	$post_tags = escape($_POST['post_tags']);

	move_uploaded_file($post_image_temp, "../images/$post_image");

	if (empty($post_image)) {
		$query = "SELECT * FROM posts WHERE post_id = $the_post_id";

		$select_image = mysqli_query($connection, $query);

		while ($row = mysqli_fetch_assoc($select_image)) {
			$post_image = $row['post_image'];
		}
	}

	$query = "
	UPDATE posts SET 
		post_category_id = '{$post_category_id}', 
		post_title = '{$post_title}', 
		post_user = '{$post_user}', 
		post_image = '{$post_image}', 
		post_content = '{$post_content}', 
		post_tags = '{$post_tags}', 
		post_status = '{$post_status}'
	WHERE post_id = '{$the_post_id}'";

	$update_post = mysqli_query($connection, $query);

	confirm($update_post);

	echo "
		<p class='bg-success'>Post updated. 
			<a href='../post.php?p_id=$the_post_id'>View Post</a> 
			or
			<a href='posts.php'>Edit More Posts</a>
		</p>";
}

?>


<form action='' method='post' enctype='multipart/form-data'>
	<div class='form-group'>
		<label for='title'>Post title</label>
		<input value='<?php echo $post_title ?>' type='text' class='form-control' name='post_title' id='title'>
	</div>

	<div class='form-group'>
		<label for='post_category'>Category</label>
		<select name='post_category' id='post_category'>

			<?php

			$query = "SELECT * FROM category";
			$select_categories = mysqli_query($connection, $query);

			confirm($select_categories);

			while ($row = mysqli_fetch_assoc($select_categories)) {
				$category_id = $row['category_id'];
				$category_title = $row['category_title'];

				if ($category_id == $post_category_id) {
					echo "<option selected value='{$category_id}'>{$category_title}</option>";
				} else {
					echo "<option value='{$category_id}'>{$category_title}</option>";
				}
			}

			?>

		</select>
	</div>

	<div class='form-group'>
		<label for='users'>Users</label>
		<select name='post_user' id='post_category'>

			<?php


			$users_query = "SELECT * FROM users";
			$select_users = mysqli_query($connection, $users_query);

			confirm($select_users);

			while ($row = mysqli_fetch_assoc($select_users)) {
				$user_id = $row['user_id'];
				$username = $row['username'];

				if ($post_user == $username) {
					echo "<option selected value='{$username}'>{$username}</option>";
				} else {
					echo "<option value='{$username}'>{$username}</option>";

				}
			}


			?>

		</select>
	</div>

	<div class='form-group'>
		<select name='post_status' id='post_status'>

			<option value='<?php echo $post_status ?>'><?php echo ucfirst($post_status) ?></option>

			<?php

			if ($post_status == 'published') {
				echo "<option value='draft'>Draft</option>";
			} else {
				echo "<option value='published'>Published</option>";
			}

			?>

		</select>
	</div>
	<!--	<div class='form-group'>-->
	<!--		<label for='post_status'>Post status</label>-->
	<!--		<input value='-->
	<?php //echo $post_status ?><!--' type='text' class='form-control' name='post_status' id='post_status'>-->
	<!--	</div>-->

	<div class='form-group'>
		<img width='100' src='../images/<?php echo $post_image ?>' alt='image'>
	</div>

	<div class='form-group'>
		<label for='post_image'>Post image</label>
		<input value='<?php echo $post_image ?>' type='file' class='form-control' name='image' id='post_image'>
	</div>

	<div class='form-group'>
		<label for='post_tags'>Post tags</label>
		<input value='<?php echo $post_tags ?>' type='text' class='form-control' name='post_tags' id='post_tags'>
	</div>

	<div class='form-group'>
		<label for='summernote'>Post content</label>
		<textarea
			class='form-control' name='post_content' id='summernote' cols='30'
			rows='10'><?php echo str_replace('\r\n', '</br>', $post_content) ?></textarea>
	</div>

	<div class='from_group'>
		<input class='btn btn-primary' type='submit' name='update_post' value='Update Post'>
	</div>
</form>
