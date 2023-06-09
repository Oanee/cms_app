<?php

if (isset($_POST['checkBoxArray'])) {

	foreach ($_POST['checkBoxArray'] as $postValueId) {
		$bulk_options = escape($_POST['bulk_options']);

		switch ($bulk_options) {
			case 'published':
				$query = "UPDATE posts SET post_status = '$bulk_options' WHERE post_id = $postValueId";
				$update_to_published_status = mysqli_query($connection, $query);
				confirm($update_to_published_status);
				break;

			case 'draft':
				$query = "UPDATE posts SET post_status = '$bulk_options' WHERE post_id = $postValueId";
				$update_to_draft_status = mysqli_query($connection, $query);
				confirm($update_to_draft_status);
				break;

			case 'delete':
				$query = "DELETE FROM posts WHERE post_id = $postValueId";
				$update_to_delete_status = mysqli_query($connection, $query);
				confirm($update_to_delete_status);
				break;

			case 'clone':
				$query = "SELECT * FROM posts WHERE post_id = '$postValueId'";
				$select_post_query = mysqli_query($connection, $query);

				while ($row = mysqli_fetch_assoc($select_post_query)) {
					$post_id = $row['post_id'];
					$post_user = $row['post_user'];
					$post_title = $row['post_title'];
					$post_category_id = $row['post_category_id'];
					$post_status = $row['post_status'];
					$post_image = $row['post_image'];
					$post_tags = $row['post_tags'];
					$post_comment_count = $row['post_comment_count'];
					$post_date = $row['post_date'];
					$post_content = $row['post_content'];
				}

				$query = "INSERT INTO posts(post_category_id, post_title, post_user, post_image, post_content, post_tags, post_status) ";

				$query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_user}', '{$post_image}' ,'{$post_content}', '{$post_tags}', '{$post_status}') ";

				$copy_query = mysqli_query($connection, $query);

				confirm($copy_query);

				break;
		}
	}
}

?>

	<form action='' method='post'>

		<table class='table table-bordered table-hover'>

			<div id='bulkOptionContainer' class='col-xs-4'>

				<select class='form-control' name='bulk_options' id=''>
					<option value=''>Select options</option>
					<option value='published'>Publish</option>
					<option value='draft'>Draft</option>
					<option value='delete'>Delete</option>
					<option value='clone'>Clone</option>
				</select>

			</div>

			<div class='col-xs-4'>
				<input type='submit' name='submit' value='Apply' class='btn btn-success'>
				<a href='./posts.php?source=add_post' class='btn btn-primary'>Add New</a>
			</div>

			<thead>
			<tr>
				<th><input type='checkbox' id='selectAllButtons'></th>
				<th>Id</th>
				<th>Users</th>
				<th>Title</th>
				<th>Category</th>
				<th>Status</th>
				<th>Image</th>
				<th>Tags</th>
				<th>Comments</th>
				<th>Date</th>
				<th>View post</th>
				<th>Edit</th>
				<th>Delete</th>
				<th>Views</th>
			</tr>
			</thead>

			<tbody>


			<?php
			$user = currentUser();

			$query = "SELECT * FROM posts WHERE post_user = '$user' ORDER BY post_id";
			$select_posts = mysqli_query($connection, $query);

			while ($row = mysqli_fetch_array($select_posts)) {
				$post_id = $row['post_id'];
				$post_author = $row['post_author'];
				$post_user = $row['post_user'];
				$post_title = $row['post_title'];
				$post_category_id = $row['post_category_id'];
				$post_status = $row['post_status'];
				$post_image = $row['post_image'];
				$post_tags = $row['post_tags'];
				$post_comment_count = $row['post_comment_count'];
				$post_date = $row['post_date'];
				$post_views_count = $row['post_views_count'];

				echo "<tr>";
				echo "<td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='$post_id'></td>";
				echo "<td>$post_id</td>";

				if (!empty($post_user)) {
					echo "<td>$post_user</td>";
				} elseif (!empty($post_author)) {
					echo "<td>$post_author</td>";
				}

				echo "<td>$post_title</td>";

				$query = "SELECT * FROM category WHERE category_id = '{$post_category_id}'";
				$select_categories_id = mysqli_query($connection, $query);

				while ($row = mysqli_fetch_assoc($select_categories_id)) {
					$category_id = $row['category_id'];
					$category_title = $row['category_title'];
				}

				echo "<td>$category_title</td>";
				echo "<td>$post_status</td>";
				echo "<td><img width='100' src='../images/$post_image' alt='image'></td>";
				echo "<td>$post_tags</td>";

				echo "<td><a href='post_comments.php?id=$post_id'>$post_comment_count</a></td>";

				echo "<td>$post_date</td>";
				echo "<td><a class='btn btn-primary' href='../post.php?p_id=$post_id''>View post</a></td>";
				echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id=$post_id'>Edit</a></td>";

				?>

				<form method='post'>
					<input type='hidden' name='post_id' value='<?php echo $post_id ?>'>

					<?php

					echo "<td><input class='btn btn-danger' type='submit' name='delete' value='Delete'></td>";

					?>

				</form>

				<?php

				echo "<td><a href='posts.php?reset=$post_id'>$post_views_count</a></td>";
				echo "</tr>";
			}

			?>

			</tbody>
		</table>

	</form>

<?php

if (isset($_POST['delete'])) {

	$the_post_id = $_POST['post_id'];

	echo $the_post_id;

	$query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
	$delete_query = mysqli_query($connection, $query);
	redirect('posts.php');

	confirm($delete_query);
}

if (isset($_GET['reset'])) {

	$the_post_id = escape($_GET['reset']);

	$query = "UPDATE posts SET post_views_count = 0 WHERE post_id = " . escape($the_post_id);
	$reset_query = mysqli_query($connection, $query);
	redirect('posts.php');

	confirm($reset_query);
}
