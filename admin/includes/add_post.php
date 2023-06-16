<?php

if (isset($_POST['create_post'])) {

	$post_title = escape($_POST['post_title']);
	$post_user = escape($_POST['post_user']);
	$post_category_id = escape($_POST['post_category']);
	$post_status = escape($_POST['post_status']);

	$post_image = escape($_FILES['image']['name']);
	$post_image_temp = escape($_FILES['image']['tmp_name']);

	$post_tags = escape($_POST['post_tags']);
	$post_content = escape($_POST['post_content']);

	move_uploaded_file($post_image_temp, "../images/$post_image");

	$query = "INSERT INTO posts(post_category_id, post_title, post_user, post_image, post_content, post_tags, post_status) ";

	$query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_user}', '{$post_image}' ,'{$post_content}', '{$post_tags}', '{$post_status}') ";

	$create_post_query = mysqli_query($connection, $query);

	confirm($create_post_query);

	$the_post_id = mysqli_insert_id($connection);

	echo "
		<p class='bg-success'>Post created. 
			<a href='../post.php?p_id=$the_post_id'>View Post</a> 
			or
			<a href='posts.php'>Edit More Posts</a>
		</p>";
}

?>



<form action='' method='post' enctype='multipart/form-data'>
	<div class='form-group'>
		<label for='title'>Post title</label>
		<input type='text' class='form-control' name='post_title' id='title'>
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

				echo "<option value='{$category_id}'>{$category_title}</option>";
			}

			?>

		</select>
	</div>

<!--	<div class='form-group'>-->
<!--		<label for='post_user'>Post author</label>-->
<!--		<input type='text' class='form-control' name='post_user' id='post_user'>-->
<!--	</div>-->

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

				echo "<option value='{$username}'>{$username}</option>";
			}

			?>

		</select>
	</div>

	<div class='form-group'>
		<select name='post_status' id='post_status'>
			<option value='draft'>Select status</option>
			<option value='published'>Published</option>
			<option value='draft'>Draft</option>
		</select>
	</div>

	<div class='form-group'>
		<label for='post_image'>Post image</label>
		<input type='file' class='form-control' name='image' id='post_image'>
	</div>

	<div class='form-group'>
		<label for='post_tags'>Post tags</label>
		<input type='text' class='form-control' name='post_tags' id='post_tags'>
	</div>

	<div class='form-group'>
		<label for='post_content'>Post content</label>
		<textarea class='form-control' name='post_content' id='summernote' cols='30' rows='10'></textarea>
	</div>

	<div class='from_group'>
		<input class='btn btn-primary' type='submit' name='create_post' value='Publish Post'>
	</div>
</form>
