<?php

function confirm($result) {

	global $connection;

	if (!$result) {
		die('Query failed .' . mysqli_error($connection));
	}
}

function insert_categories() {

	global $connection;

	if (isset($_POST['submit'])) {

		$category_title = $_POST['category_title'];

		if ($category_title == '' || empty($category_title)) {
			echo 'This field should not be empty';
		} else {

			$query = "INSERT INTO category(category_title) ";
			$query .= "VALUE('{$category_title}') ";

			$create_category_query = mysqli_query($connection, $query);

			confirm($create_category_query);
		}
	}
}

function find_all_categories() {

	global $connection;

	$query = 'SELECT * FROM category';
	$select_categories = mysqli_query($connection, $query);

	while ($row = mysqli_fetch_assoc($select_categories)) {
		$category_id = $row['category_id'];
		$category_title = $row['category_title'];

		echo "<tr>";
		echo "<td>{$category_id}</td>";
		echo "<td>{$category_title}</td>";
		echo "<td><a href='categories.php?delete={$category_id}'>Delete</a></td>";
		echo "<td><a href='categories.php?edit={$category_id}'>Edit</a></td>";
		echo "</tr>";
	}
}

function delete_categories() {

	global $connection;

	if (isset($_GET['delete'])) {
		$the_category_id = $_GET['delete'];

		$query = "DELETE FROM category WHERE category_id = '{$the_category_id}'";
		$delete_query = mysqli_query($connection, $query);
		header('Location: categories.php');

		confirm($delete_query);
	}
}
