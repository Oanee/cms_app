<?php

function escape($string) {

	global $connection;

	return mysqli_real_escape_string($connection, trim($string));
}

function users_online() {

		if(isset($_GET['onlineusers'])) {

		global $connection;

		if (!$connection) {
			session_start();

			include ("../includes/db.php");

			$session = session_id();
			$time = time();
			$time_out_in_seconds = 10;
			$time_out = $time - $time_out_in_seconds;

			$query = "SELECT * FROM users_online WHERE session = '$session'";
			$send_query = mysqli_query($connection, $query);
			$count = mysqli_num_rows($send_query);

			if ($count == NULL) {
				mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
			} else {
				mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
			}

			$users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
			echo $count_user = mysqli_num_rows($users_online_query);
		}
	}
}

users_online();

function confirm($result) {

	global $connection;

	if (!$result) {
		die('Query failed .' . mysqli_error($connection));
	}
}

function insert_categories() {

	global $connection;

	if (isset($_POST['submit'])) {

		$category_title = escape($_POST['category_title']);

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
		$the_category_id = escape($_GET['delete']);

		$query = "DELETE FROM category WHERE category_id = '{$the_category_id}'";
		$delete_query = mysqli_query($connection, $query);
		header('Location: categories.php');

		confirm($delete_query);
	}
}

function redirect($location) {
	header("Location:" . $location);
}
