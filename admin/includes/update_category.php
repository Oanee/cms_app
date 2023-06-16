<form action='' method='post'>
	<div class='form-group'>
		<label for='cat'>Edit Category</label>

		<?php

		if (isset($_GET['edit'])) {
			$category_id = escape($_GET['edit']);

			$query = "SELECT * FROM category WHERE category_id = '{$category_id}'";
			$select_categories_id = mysqli_query($connection, $query);

			while ($row = mysqli_fetch_assoc($select_categories_id)) {
				$category_id = $row['category_id'];
				$category_title = $row['category_title'];

				?>

				<input
					value='<?php if (isset($category_title)) {
						echo $category_title; } ?>' class='form-control' type='text'
					name='category_title' id='cat'>

			<?php }
		} ?>

		<?php

		if (isset($_POST['update_category'])) {
			$the_category_title = escape($_POST['category_title']);

			$query = "UPDATE category SET category_title = '{$the_category_title}' WHERE category_id = '{$category_id}'";
			$update_query = mysqli_query($connection, $query);

			if(!$update_query) {
				die('Query failed'. mysqli_error($connection));
			}
		}

		?>

	</div>
	<div class='form-group'>
		<input class='btn btn-primary' type='submit' name='update_category' value='Edit Category'>
	</div>

</form><?php
