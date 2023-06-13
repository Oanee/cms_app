<?php

if (isset($_POST['create_post'])) {

	$post_title = $_POST['post_title'];
	$post_author = $_POST['post_author'];
	$post_category_id = $_POST['post_category'];
	$post_status = $_POST['post_status'];

	$post_image = $_FILES['image']['name'];
	$post_image_temp = $_FILES['image']['tmp_name'];

	$post_tags = $_POST['post_tags'];
	$post_content = $_POST['post_content'];
//	$post_date = date('d-m-y');
//	$post_comment_count = 4;

	move_uploaded_file($post_image_temp, "../images/$post_image");

	$query = "INSERT INTO posts(post_category_id, post_title, post_author, post_image, post_content, post_tags, post_status) ";

	$query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_image}' ,'{$post_content}', '{$post_tags}', '{$post_status}') ";

	$create_post_query = mysqli_query($connection, $query);

	confirm($create_post_query);

}

?>



<form action='' method='post' enctype='multipart/form-data'>
	<div class='form-group'>
		<label for='title'>Post title</label>
		<input type='text' class='form-control' name='post_title' id='title'>
	</div>

	<div class='form-group'>
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

	<div class='form-group'>
		<label for='post_author'>Post author</label>
		<input type='text' class='form-control' name='post_author' id='post_author'>
	</div>

	<div class='form-group'>
		<label for='post_status'>Post status</label>
		<input type='text' class='form-control' name='post_status' id='post_status'>
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
