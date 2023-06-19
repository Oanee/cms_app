<?php

function escape($string = '')
{

	global $connection;

	return mysqli_real_escape_string($connection, trim($string));
}

function users_online()
{

	if (isset($_GET['onlineusers'])) {

		global $connection;

		if (!$connection) {
			session_start();

			include("../includes/db.php");

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

function confirm($result)
{

	global $connection;

	if (!$result) {
		die('Query failed .' . mysqli_error($connection));
	}
}

function insert_categories()
{

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

function find_all_categories()
{

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

function delete_categories()
{

	global $connection;

	if (isset($_GET['delete'])) {
		$the_category_id = escape($_GET['delete']);

		$query = "DELETE FROM category WHERE category_id = '{$the_category_id}'";
		$delete_query = mysqli_query($connection, $query);
		header('Location: categories.php');

		confirm($delete_query);
	}
}

function redirect($location)
{
	header("Location:" . $location);
	exit;
}

function ifItIsMethod($method = null)
{
	if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
		return true;
	}

	return false;
}

function isLoggedIn()
{
	if (isset($_SESSION['user_role'])) {
		return true;
	}

	return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation)
{
	if (isLoggedIn()) {
		redirect($redirectLocation);
	}
}

function recordCount($table)
{

	global $connection;

	$query = "SELECT * FROM $table";
	$select_all_post = mysqli_query($connection, $query);
	$result = mysqli_num_rows($select_all_post);
	confirm($result);

	return $result;
}

function recordCountLimit($table)
{
	$result = query("SELECT * FROM $table WHERE user_id='" . loggedInUserId() . "'");
	return mysqli_num_rows($result);
}

function checkStatus($table, $column, $status)
{

	global $connection;

	$query = "SELECT * FROM $table WHERE $column = '$status' ";
	$result = mysqli_query($connection, $query);
	confirm($result);

	return mysqli_num_rows($result);
}

function checkUserRole($table, $column, $role)
{

	global $connection;

	$query = "SELECT * FROM $table WHERE $column = '$role' ";
	$result = mysqli_query($connection, $query);
	confirm($result);

	return mysqli_num_rows($result);
}

function isAdmin()
{
	global $connection;

	if (isLoggedIn()) {
		$result = query("SELECT user_role FROM users WHERE user_id = '" . $_SESSION['user_id'] . "'");
		confirm($result);

		$row = mysqli_fetch_array($result);

		if ($row['user_role'] == 'admin') {
			return true;
		} else {
			return false;
		}
	}
}

function username_exists($username)
{
	global $connection;

	$query = "SELECT username FROM users WHERE username = '$username' ";
	$result = mysqli_query($connection, $query);
	confirm($result);

	if (mysqli_num_rows($result) > 0) {
		return true;
	} else {
		return false;
	}
}

function email_exists($email)
{
	global $connection;

	$query = "SELECT user_email FROM users WHERE user_email = '$email' ";
	$result = mysqli_query($connection, $query);
	confirm($result);

	if (mysqli_num_rows($result) > 0) {
		return true;
	} else {
		return false;
	}
}

function register_user($username, $email, $password)
{
	global $connection;

	$username = mysqli_real_escape_string($connection, $username);
	$email = mysqli_real_escape_string($connection, $email);
	$password = mysqli_real_escape_string($connection, $password);

	$password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

	$query = "INSERT INTO users (username, user_email, user_password, user_role)";
	$query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber')";

	$register_user_query = mysqli_query($connection, $query);

	confirm($register_user_query);
}

function login_user($username, $password)
{
	global $connection;

	$username = mysqli_real_escape_string($connection, $username);
	$password = mysqli_real_escape_string($connection, $password);

	$query = "SELECT * FROM users WHERE username = '$username'";
	$select_user_query = mysqli_query($connection, $query);

	confirm($select_user_query);

	while ($row = mysqli_fetch_assoc($select_user_query)) {
		$db_user_id = $row['user_id'];
		$db_username = $row['username'];
		$db_user_password = $row['user_password'];
		$db_user_firstname = $row['user_firstname'];
		$db_user_lastname = $row['user_lastname'];
		$db_user_role = $row['user_role'];

		if (password_verify($password, $db_user_password)) {
			$_SESSION['user_id'] = $db_user_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['firstname'] = $db_user_firstname;
			$_SESSION['lastname'] = $db_user_lastname;
			$_SESSION['user_role'] = $db_user_role;
			redirect('/cms/admin/dashboard.php');

		} else {
			return false;
		}

	}
	return true;

}

function currentUser()
{
	if (isset($_SESSION['username'])) {
		return $_SESSION['username'];
	}

	return false;
}

function imagePlaceholder($image)
{
	if (!$image) {
		return 'image_4.jpg';
	} else {
		return $image;
	}
}

function loggedInUserId()
{
	if (isLoggedIn()) {
		$result = query("Select* FROM users WHERE username ='" . $_SESSION['username'] . "'");
		confirm($result);
		$users = mysqli_fetch_array($result);

		if (mysqli_num_rows($result) >= 1) {
			return $users['user_id'];
		}
	}

	return false;
}

function query($query)
{
	global $connection;

	return mysqli_query($connection, $query);
}

function userLikedThisPost($post_id)
{
	$result = query("SELECT * FROM likes WHERE user_id=" . loggedInUserId() . " AND post_id = '$post_id'");
	return mysqli_num_rows($result) >= 1 ? true : false;
}

function getPostLikes($post_id)
{
	$result = query("SELECT * FROM likes WHERE post_id = '$post_id'");
	confirm($result);
	return mysqli_num_rows($result);
}

function get_user_name()
{
	return isset($_SESSION['username']) ? strtoupper($_SESSION['username']) : null;
}
